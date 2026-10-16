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
use Cpsit\T3faq\Domain\Repository\CategoryRepository;

final class CategoryService
{
    public function __construct(private readonly CategoryRepository $categoryRepository) {}

    /**
     * Builds a category's root line for a category
     *
     * @param int $uid The category ID to build the rootline for
     * @return Category[] Category rootline as array
     */
    public function getCategoryRootLine(int $uid): array
    {
        $rootlineIds = [];
        $parentCategory = $this->categoryRepository->findOneBy(['uid' => $uid]);

        while ($parentCategory !== null) {
            $rootlineIds[] = $parentCategory;
            /** @var Category|null $childCategory */
            $childCategory = $this->categoryRepository->findOneBy(['uid' => $parentCategory]);
            if ($childCategory === null) {
                $parentCategory = null;
            } else {
                $parentCategory = $childCategory->getParent();
            }
        }
        krsort($rootlineIds);

        return array_values($rootlineIds);
    }

    /**
     * Extends a category list to include parent categories
     *
     * @param int[] $categoryList
     * @return int[]
     */
    public function getExtendedCategoryListIncludeParents(array $categoryList): array
    {
        $categoryListWithParents = $categoryList;
        foreach ($categoryList as $categoryUid) {
            $categoryRootLine = $this->getCategoryRootLine($categoryUid);
            foreach ($categoryRootLine as $category) {
                if (!in_array($category->getUid(), $categoryListWithParents)) {
                    $categoryListWithParents[] = $category->getUid();
                }
            }
        }
        return $categoryListWithParents;
    }

}
