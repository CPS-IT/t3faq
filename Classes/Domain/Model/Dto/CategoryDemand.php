<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Fr\T3faq\Domain\Model\Dto;

class CategoryDemand implements DemandInterface
{
    /**
     * Page Ids to search for
     * @var int[]
     */
    protected array $pageIds = [];

    /**
     * Category Ids to search for
     * @var int[]
     */
    protected array $categoryIds = [];

    protected string $sorting = '';

    /**
     * @return int[]
     */
    public function getPageIds(): array
    {
        return $this->pageIds;
    }

    /**
     * @param int[] $pages
     * @return QuestionDemand
     */
    public function setPageIds(array $pages)
    {
        $this->pageIds = $pages;
    }

    /**
     * @return int[]
     */
    public function getCategoryIds(): array
    {
        return $this->categoryIds;
    }

    /**
     * @param int[] $categoryIds
     */
    public function setCategoryIds(array $categoryIds): void
    {
        $this->categoryIds = $categoryIds;
    }

    public function getSorting(): string
    {
        return $this->sorting;
    }

    public function setSorting(string $sorting): void
    {
        $this->sorting = $sorting;
    }
}
