<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Service;

use Cpsit\T3faq\Domain\Model\Category;
use Cpsit\T3faq\Domain\Model\Question;

final class QuestionToCategoryTreeService
{
    /** @var iterable<Category> */
    private iterable $categories;

    /** @var iterable<Question> */
    private iterable $questions;

    /**
     * @param iterable<Question> $questions
     * @param iterable<Category> $categories
     */
    public function __construct(iterable $questions, iterable $categories)
    {
        $this->categories = $categories;
        $this->questions = $questions;
    }

    /**
     * @return array<int|string, array<string, mixed>>
     */
    public function getQuestionCategoryTree(): array
    {
        $categories = $this->categoriesToArray();
        $categories = $this->questionsToCategories($categories);
        $categories = $this->buildTree($categories);
        return $this->buildTree($categories);
    }

    /**
     * @param array<int|string, array<string, mixed>> $categories
     * @return array<int|string, array<string, mixed>>
     */
    private function questionsToCategories(array $categories): array
    {
        /** @var Question $question */
        foreach ($this->questions as $question) {
            $category = $question->getFirstCategory();
            if ($category === null || !isset($categories[$category->getUid()])) {
                continue;
            }
            $categories[$category->getUid()]['faqs'][] = $question;
            $this->updateHasFaqsInCategories($category, $categories);
        }

        return $categories;
    }

    /**
     * @param array<int|string, array<string, mixed>> $categories
     */
    private function updateHasFaqsInCategories(Category $category, array &$categories): void
    {
        $categories[$category->getUid()]['hasFaqs'] = true;
        $parentCategory = $category->getParent();
        if ($parentCategory !== null
            && $categories[$parentCategory->getUid()]['hasFaqs'] === false) {
            $categories[$parentCategory->getUid()]['hasFaqs'] = true;
            $this->updateHasFaqsInCategories($parentCategory, $categories);
        }
    }

    /**
     * @return array<int|string, array<string, mixed>>
     */
    private function categoriesToArray(): array
    {
        $categories = [];
        $allCategories = $this->categories;
        foreach ($allCategories as $category) {
            $categoryArray = $this->categoryToArray($category);
            $categoryArray['faqs'] = [];
            $categoryArray['hasFaqs'] = false;
            $categories[$categoryArray['uid']] = $categoryArray;
        }
        return $categories;
    }

    /**
     * @return array<string, mixed>
     */
    private function categoryToArray(?Category $category = null): array
    {
        if ($category === null) {
            return [];
        }

        $data = [
            Category::FIELD_UID => $category->getUid(),
            Category::FIELD_TITLE => $category->getTitle(),
            Category::FIELD_DESCRIPTION => $category->getDescription(),
            Category::FIELD_FRAME_CLASS => $category->getFrameClass(),
            Category::FIELD_PARENT => $category->getParent(),
        ];

        if ($data[Category::FIELD_PARENT] === null) {
            $data[Category::FIELD_PARENT] = 0;
        }

        if ($data[Category::FIELD_PARENT] instanceof Category) {
            $data[Category::FIELD_PARENT] = $data[Category::FIELD_PARENT]->getUid();
        }

        return $data;
    }

    /**
     * @param array<int|string, array<string, mixed>> $data
     * @return array<int|string, array<string, mixed>>
     */
    private function buildTree(
        array $data,
        string $parentKey = 'parent',
        string $idKey = 'uid',
        string $childrenKey = 'children'
    ): array {
        $grouped = [];
        foreach ($data as $node) {
            $grouped[$node[$parentKey]][$node[$idKey]] = $node;
        }
        $fnBuilder = function ($siblings) use (&$fnBuilder, $grouped, $idKey, $childrenKey) {
            foreach ($siblings as $k => $sibling) {
                $id = $sibling[$idKey] ?? null;
                if (isset($grouped[$id])) {
                    $sibling[$childrenKey] = $fnBuilder($grouped[$id]);
                }
                $siblings[$k] = $sibling;
            }
            return $siblings;
        };
        return $fnBuilder($grouped[0]);
    }
}
