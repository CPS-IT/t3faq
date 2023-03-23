<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Unit\Domain\Model\Dto;

use Fr\T3faq\Configuration\SettingsInterface as SI;
use Fr\T3faq\Domain\Model\Dto\CategoryDemand;
use Fr\T3faq\Domain\Model\Dto\DemandInterface;
use Fr\T3faq\Domain\Model\Dto\Factory\CategoryDemandFromSettings;
use PHPUnit\Framework\TestCase;

class CategoryDemandFromSettingsTest extends TestCase
{

    public function testGetReturnsDemandInterface()
    {
        $categoryDemandFromSettingsProvider = new CategoryDemandFromSettings([]);

        self::assertInstanceOf(DemandInterface::class, $categoryDemandFromSettingsProvider->get());
    }

    public function testGetReturnsDecoratedDemandInterface()
    {
        $settings = [
            SI::SETTING_CATEGORY_STORAGE_ID => '41',
            SI::SETTING_CATEGORY_SORTING => 'sorting asc',
            SI::SETTING_CATEGORIES_LIST => '116,19,97,100',
        ];

        $questionDemandFromSettingsProvider = new CategoryDemandFromSettings();
        /** @var CategoryDemand $demand */
        $demand = $questionDemandFromSettingsProvider->get($settings);

        self::assertInstanceOf(
            DemandInterface::class,
            $demand,
            'Demand is instance of ' . DemandInterface::class
        );
        self::assertEquals($settings[SI::SETTING_CATEGORY_SORTING], $demand->getSorting());
        self::assertContains($settings[SI::SETTING_CATEGORY_STORAGE_ID], $demand->getPageIds());
        self::assertEquals(explode(',', $settings[SI::SETTING_CATEGORIES_LIST]), $demand->getCategoryIds());
    }
}
