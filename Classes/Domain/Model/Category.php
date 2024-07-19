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
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\Annotation as Extbase;


class Category extends AbstractEntity
{
    public const FIELD_SORTING = 'sorting';
    public const FIELD_UID = 'uid';
    public const FIELD_TITLE = 'title';
    public const FIELD_DESCRIPTION = 'description';
    public const FIELD_FRAME_CLASS = 'frameClass';
    public const FIELD_PARENT = 'parent';

    /**
     * @var string
     */
    #[Extbase\Validate(['validator' => 'NotEmpty'])]
    protected string $title = '';

    /**
     * @var string
     */
    protected string $description = '';

    /**
     * Questions
     *
     * @var ObjectStorage<\Cpsit\T3faq\Domain\Model\Question>
     */
    #[Lazy]
    protected $faqs;

    /**
     * @var string
     */
    protected string $frameClass = '';

    /**
     * @var Category|null
     */
    #[Lazy]
    protected $parent = null;

    public function __construct()
    {
        $this->initStorageObjects();
    }

    /**
     * Initializes all object storage properties
     */
    protected function initStorageObjects(): void
    {
        $this->faqs = new ObjectStorage();
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription($description): void
    {
        $this->description = $description;
    }

    public function getFaqs(): ObjectStorage
    {
        return $this->faqs;
    }


    public function setFaqs(ObjectStorage $faqs): void
    {
        $this->faqs = $faqs;
    }

    /**
     * Gets the parent category.
     *
     * @return Category|null the parent category
     */
    public function getParent()
    {
        if ($this->parent instanceof LazyLoadingProxy) {
            $this->parent->_loadRealInstance();
        }
        return $this->parent;
    }

    public function setParent(Category $parent): void
    {
        $this->parent = $parent;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function setUid(int $uid): void
    {
        $this->uid = $uid;
    }

    public function getFrameClass(): string
    {
        return $this->frameClass;
    }

    public function setFrameClass(string $frameClass): void
    {
        $this->frameClass = $frameClass;
    }
}
