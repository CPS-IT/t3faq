<?php

namespace Unit\Domain\Model\Dto;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Fr\T3faq\Domain\Model\Dto\QuestionDemand;

class QuestionDemandTest extends \PHPUnit\Framework\TestCase
{

    /**
     * @var QuestionDemand
     */
    protected $subject;

    public function setUp()
    {
        $this->subject = new QuestionDemand();
    }

    public function testGetPagIdsInitiallyReturnsEmptyArray(): void
    {
        $expected = [];
        self::assertSame(
            $expected,
            $this->subject->getPageIds()
        );
    }

    public function testPagIdsCanBeSet(): void
    {
        $pages = [1,2];
        $this->subject->setPageIds($pages);

        self::assertSame(
            $pages,
            $this->subject->getPageIds()
        );
    }

    public function testGetLimitInitiallyReturnsDefaultLimit(): void
    {
        self::assertSame(
            QuestionDemand::DEFAULT_LIMIT,
            $this->subject->getLimit()
        );
    }

    public function testLimitCanBeSet(): void
    {
        $limit = 1000;
        $this->subject->setLimit($limit);

        self::assertSame(
            $limit,
            $this->subject->getLimit()
        );
    }
}
