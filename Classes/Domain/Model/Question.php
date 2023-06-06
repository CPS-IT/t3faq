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

use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;

class Question extends AbstractEntity
{
    public const TABLE_NAME = 'tx_t3faq_domain_model_question';
    public const FIELD_UID = 'uid';
    public const FIELD_CATEGORIES = 'categories';
    public const FIELD_WEIGHT = 'weight';
    public const FIELD_ANSWER = 'answer';
    public const FIELD_QUESTION = 'question';
    public const FIELD_SORTING = 'sorting';
    public const FIELD_LIKES = 'likes';
    public const FIELD_DISLIKES = 'dislikes';
    public const FIELD_INFORMATION_DATE = 'information_date';

    protected string $question = '';
    protected string $answer = '';
    protected int $weight = 0;
    protected int $likes = 0;
    protected int $dislikes = 0;
    protected int $informationDate = 0;

    /**
     * Categories
     *
     * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\Cpsit\T3faq\Domain\Model\Category>
     * @Lazy
     */
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

    public function setQuestion(string $question): void
    {
        $this->question = $question;
    }

    public function getAnswer(): string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): void
    {
        $this->answer = $answer;
    }

    public function weight(): int
    {
        return $this->weight;
    }

    public function setWeight(int $weight): void
    {
        $this->weight = $weight;
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
     * Sets categories
     *
     * @param ObjectStorage<Category> $categories
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }

    public function addCategory(Category $category): Question
    {
        $this->categories->attach($category);
        return $this;
    }

    public function removeCategory(Category $category): Question
    {
        $this->categories->detach($category);
        return $this;
    }

    /**
     * Get first category
     */
    public function getFirstCategory(): ?Category
    {
        $categories = $this->getCategories();
        if (!is_null($categories)) {
            $categories->rewind();
            return $categories->current();
        } else {
            return null;
        }
    }
}
