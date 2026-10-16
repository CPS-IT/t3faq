<?php

declare(strict_types=1);

namespace Cpsit\T3faq\Tests\Unit\Domain\Model;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Cpsit\T3faq\Domain\Model\Question;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

class QuestionTest extends TestCase
{
    /**
     * @var Question
     */
    protected $subject;

    public function setUp(): void
    {
        $this->subject = new Question();
    }

    public function testGetQuestionInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->subject->getQuestion()
        );
    }

    public function testGetAnswerInitiallyReturnsEmptyString(): void
    {
        self::assertSame(
            '',
            $this->subject->getAnswer()
        );
    }

    public function testGetCategoriesInitiallyReturnsEmptyObjectStorage(): void
    {
        $categories = $this->subject->getCategories();
        /** @noinspection UnnecessaryAssertionInspection */
        self::assertInstanceOf(
            ObjectStorage::class,
            $categories
        );

        self::assertEmpty($categories);
    }

}
