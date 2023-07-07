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

use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Domain\Model\Dto\Factory\QuestionDemandFromSettings;
use PHPUnit\Framework\TestCase;

class QuestionDemandFromSettingsTest extends TestCase
{

    public function testGetReturnsDemandInterface()
    {
        $questionDemandFromSettingsProvider = new QuestionDemandFromSettings([]);

        self::assertInstanceOf(DemandInterface::class, $questionDemandFromSettingsProvider->get());
    }

    public function testGetReturnsDecoratedDemandInterface()
    {
        $settings = [
            SI::SETTING_FAQ_STORAGE_ID => '41',
            SI::SETTING_FAQ_SORTING => 'sorting asc',
            SI::SETTING_LIST_SELECTED_QUESTIONS => '116,19,97,100',

        ];

        $questionDemandFromSettingsProvider = new QuestionDemandFromSettings();
        /** @var QuestionDemand $demand */
        $demand = $questionDemandFromSettingsProvider->get($settings);

        self::assertInstanceOf(
            DemandInterface::class,
            $demand,
            'Demand is instance of ' . DemandInterface::class
        );
        self::assertEquals($settings[SI::SETTING_FAQ_SORTING], $demand->getSorting());
        self::assertContains((int)$settings[SI::SETTING_FAQ_STORAGE_ID], $demand->getPageIds());
        self::assertEquals(explode(',', $settings[SI::SETTING_LIST_SELECTED_QUESTIONS]), $demand->getQuestionIds());
    }
}
