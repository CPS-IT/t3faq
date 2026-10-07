<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Unit\Domain\Model\Dto;

use Cpsit\CpsUtility\Utility\PageUtility;
use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use Cpsit\T3faq\Domain\Model\Dto\CategoryDemand;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Dto\Factory\CategoryDemandFromSettings;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CategoryDemandFromSettingsTest extends TestCase
{
    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }

    private function registerPageUtility(array $pageIds): void
    {
        $pageUtility = $this->createMock(PageUtility::class);
        $pageUtility->method('resolveStoragePages')->willReturn($pageIds);
        GeneralUtility::addInstance(PageUtility::class, $pageUtility);
    }

    public function testGetReturnsDemandInterface(): void
    {
        $this->registerPageUtility([0]);
        $categoryDemandFromSettingsProvider = new CategoryDemandFromSettings([]);

        self::assertInstanceOf(DemandInterface::class, $categoryDemandFromSettingsProvider->get());
    }

    public function testGetReturnsDecoratedDemandInterface(): void
    {
        $settings = [
            SI::SETTING_CATEGORY_STORAGE_ID => '41',
            SI::SETTING_CATEGORY_SORTING => 'sorting asc',
            SI::SETTING_CATEGORIES_LIST => '116,19,97,100',
        ];

        $this->registerPageUtility([41]);
        $questionDemandFromSettingsProvider = new CategoryDemandFromSettings();
        /** @var CategoryDemand $demand */
        $demand = $questionDemandFromSettingsProvider->get($settings);

        self::assertInstanceOf(
            DemandInterface::class,
            $demand,
            'Demand is instance of ' . DemandInterface::class
        );
        self::assertEquals($settings[SI::SETTING_CATEGORY_SORTING], $demand->getSorting());
        self::assertContains((int)$settings[SI::SETTING_CATEGORY_STORAGE_ID], $demand->getPageIds());
        self::assertEquals(explode(',', $settings[SI::SETTING_CATEGORIES_LIST]), $demand->getCategoryIds());
    }
}
