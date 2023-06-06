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
use Iterator;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Url;

final class QuestionToCategoryTreeService
{
    private iterable $categories;
    private iterable $questions;

    public function __construct(iterable $questions, iterable $categories)
    {
        $this->categories = $categories;
        $this->questions = $questions;
    }

    public function getQuestionCategoryTree(): array
    {
        $categories = $this->categoriesToArray();
        $categories = $this->questionsToCategories($categories);
        $categories = $this->buildTree($categories);
        return $this->buildTree($categories);
    }

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

    private function updateHasFaqsInCategories($category, &$categories): void
    {
        $categories[$category->getUid()]['hasFaqs'] = true;
        $parentCategory = $category->getParent();
        if ($parentCategory !== null
            && $categories[$parentCategory->getUid()]['hasFaqs'] === false) {
            $categories[$parentCategory->getUid()]['hasFaqs'] = true;
            $this->updateHasFaqsInCategories($parentCategory, $categories);
        }
    }


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

    private function categoryToArray(?Category $category = null): array
    {
        $data = [];
        if (!is_object($category)) {
            return $data;
        }
        // Properties to include in array
        $properties = [
            Category::FIELD_UID,
            Category::FIELD_TITLE,
            Category::FIELD_DESCRIPTION,
            Category::FIELD_FRAME_CLASS,
            Category::FIELD_PARENT,
        ];

        foreach ($properties as $property) {
            $method = 'get' . ucfirst($property);
            if (is_callable([get_class($category), $method])) {
                $data[$property] = $category->$method();
            }
        }

        if ($data[Category::FIELD_PARENT] === null) {
            $data[Category::FIELD_PARENT] = 0;
        }

        if ($data[Category::FIELD_PARENT] instanceof Category) {
            $data[Category::FIELD_PARENT] = $data[Category::FIELD_PARENT]->getUid();
        }

        return $data;
    }

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
                    $sibling[$childrenKey] = $fnBuilder($grouped[$id], $idKey, $childrenKey);
                }
                $siblings[$k] = $sibling;
            }
            return $siblings;
        };
        return $fnBuilder($grouped[0]);
    }
}
