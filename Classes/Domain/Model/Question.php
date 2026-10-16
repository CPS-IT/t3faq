<?php

declare(strict_types=1);

namespace Cpsit\T3faq\Domain\Model;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class Question extends AbstractEntity
{
    public const TABLE_NAME = 'tx_t3faq_domain_model_question';
    public const FIELD_UID = 'uid';
    public const FIELD_WEIGHT = 'weight';
    public const FIELD_SORTING = 'sorting';

    protected string $question = '';
    protected string $answer = '';
    // Not read in PHP, but a mapped property for the default ordering
    protected int $weight = 0;
    protected int $informationDate = 0;

    /**
     * Categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Cpsit\T3faq\Domain\Model\Category>
     */
    #[Lazy]
    protected $categories;

    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * Initializes all object storage properties
     */
    protected function initStorageObjects(): void
    {
        $this->categories = new ObjectStorage();
    }

    public function getQuestion(): string
    {
        return $this->question;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function getInformationDate(): int
    {
        return $this->informationDate;
    }

    /**
     * Returns categories
     *
     * @return ObjectStorage<Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * Get first category
     */
    public function getFirstCategory(): ?Category
    {
        $categories = $this->getCategories();
        $categories->rewind();

        return $categories->current();
    }
}
