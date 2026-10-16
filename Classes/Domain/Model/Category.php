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

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Annotation\ORM\Lazy;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\Generic\LazyLoadingProxy;

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
     * @var string
     */
    protected string $frameClass = '';

    /**
     * @var Category|LazyLoadingProxy|null
     */
    #[Lazy]
    protected $parent;

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * Gets the parent category.
     *
     * @return Category|LazyLoadingProxy|null the parent category
     */
    public function getParent()
    {
        if ($this->parent instanceof LazyLoadingProxy) {
            $this->parent->_loadRealInstance();
        }
        return $this->parent;
    }

    public function getUid(): int
    {
        return $this->uid;
    }

    public function getFrameClass(): string
    {
        return $this->frameClass;
    }
}
