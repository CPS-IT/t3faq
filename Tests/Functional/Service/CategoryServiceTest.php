<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Functional\Service;

use Cpsit\T3faq\Service\CategoryService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class CategoryServiceTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'cpsit/cps-utility',
        'cpsit/t3faq',
    ];

    private CategoryService $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = $this->get(CategoryService::class);
        $this->importCSVDataSet(__DIR__ . '/../Fixtures/Database/sys_category_rootline.csv');
    }

    public function testGetCategoryRootLineReturnsChainFromRootToCategory(): void
    {
        $uids = array_map(static fn($category) => $category->getUid(), $this->subject->getCategoryRootLine(3));

        self::assertSame([1, 2, 3], $uids);
    }

    public function testGetCategoryRootLineStopsWhenParentCannotBeFound(): void
    {
        // The parent exists but is not found by the repository (other language); abort after 10 seconds instead of looping forever
        set_time_limit(10);

        $uids = array_map(static fn($category) => $category->getUid(), $this->subject->getCategoryRootLine(5));

        self::assertSame([4, 5], $uids);
    }
}
