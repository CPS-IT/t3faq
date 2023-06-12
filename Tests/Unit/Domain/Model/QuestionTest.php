<?php
declare(strict_types=1);

namespace Cpsit\T3faq\Tests\Unit\Configuration;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Cpsit\T3faq\Domain\Model\Question;
use PHPUnit\Framework\TestCase;
use Cpsit\T3faq\Domain\Model\Category;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;

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

    public function testWeightInitiallyReturnsZero(): void
    {
        self::assertSame(
            0,
            $this->subject->weight()
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

    public function testQuestionCanBeSet(): void
    {
        $question = 'foo';
        $this->subject->setQuestion($question);
        self::assertSame(
            $question,
            $this->subject->getQuestion()
        );
    }

    public function testAnswerCanBeSet(): void
    {
        $answer = '42';
        $this->subject->setAnswer($answer);
        self::assertSame(
            $answer,
            $this->subject->getAnswer()
        );
    }

    public function testWeightCanBeSet(): void
    {
        $weight = 4;
        $this->subject->setWeight($weight);
        self::assertSame(
            $weight,
            $this->subject->weight()
        );
    }

    public function testCategoriesCanBeSet(): void
    {
        $category = new Category();
        $storage = new ObjectStorage();
        $storage->attach($category);

        $this->subject->setCategories($storage);

        self::assertSame(
            $storage,
            $this->subject->getCategories()
        );
    }

    public function testCategoryCanBeAdded(): void
    {
        $category = new Category();
        $this->subject->addCategory($category);
        $storage = $this->subject->getCategories();

        self::assertContains(
            $category,
            $storage
        );
    }

    public function testCategoryCanBeRemoved(): void
    {
        $category = new Category();
        $storage = new ObjectStorage();
        $storage->attach($category);

        $this->subject->setCategories($storage);
        $this->subject->removeCategory($category);

        self::assertNotContains(
            $category,
            $this->subject->getCategories()
        );
    }
}
