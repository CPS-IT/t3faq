<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Domain\Model\Dto;

class QuestionDemand implements DemandInterface
{
    public const DEFAULT_LIMIT = 10;

    /**
     * Page Ids to search for
     * @var int[]
     */
    protected array $pageIds = [];

    /**
     * Question Ids to search for
     * @var int[]
     */
    protected array $questionIds = [];

    /**
     * Category Ids to search for
     * @var int[]
     */
    protected array $categoryIds = [];

    protected int $limit = self::DEFAULT_LIMIT;

    protected string $sorting = '';

    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * @param int $limit
     * @return QuestionDemand
     */
    public function setLimit(int $limit): QuestionDemand
    {
        $this->limit = $limit;
        return $this;
    }

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
    public function setPageIds(array $pages): QuestionDemand
    {
        $this->pageIds = $pages;
        return $this;
    }

    /**
     * @return int[]
     */
    public function getQuestionIds(): array
    {
        return $this->questionIds;
    }

    /**
     * @param int[] $questionIds
     */
    public function setQuestionIds(array $questionIds): QuestionDemand
    {
        $this->questionIds = $questionIds;
        return $this;
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
    public function setCategoryIds(array $categoryIds): QuestionDemand
    {
        $this->categoryIds = $categoryIds;
        return $this;
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
