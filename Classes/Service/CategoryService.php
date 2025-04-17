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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use Cpsit\T3faq\Domain\Model\Category;
use Cpsit\T3faq\Domain\Repository\CategoryRepository;

final class CategoryService
{
    /**
     * @var CategoryRepository
     */
    private CategoryRepository $categoryRepository;

    /**
     * CategoryUidToHierarchy constructor.
     *
     * @param CategoryRepository|null $categoryRepository
     */
    public function __construct(CategoryRepository $categoryRepository = null)
    {
        $this->categoryRepository = $categoryRepository ?? GeneralUtility::makeInstance(CategoryRepository::class);
    }

    /**
     * Builds a category's root line for a category
     *
     * @param int $uid The category ID to build the rootline for
     * @return array Category rootline as array
     */
    public function getCategoryRootLine(int $uid): array
    {
        $rootlineIds = [];
        $parentCategory = $this->categoryRepository->findOneBy(['uid' => $uid]);

        while ($parentCategory !== null) {
            $rootlineIds[] = $parentCategory;
            /** @var Category $childCategory */
            $childCategory = $this->categoryRepository->findOneBy(['uid' => $parentCategory]);
            if ($childCategory === null) {
                $parentCategory = 0;
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
     * @return array
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
