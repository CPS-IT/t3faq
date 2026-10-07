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
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Domain\Model\Dto\Factory\QuestionDemandFromSettings;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class QuestionDemandFromSettingsTest extends TestCase
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
        $questionDemandFromSettingsProvider = new QuestionDemandFromSettings([]);

        self::assertInstanceOf(DemandInterface::class, $questionDemandFromSettingsProvider->get());
    }

    public function testGetReturnsDecoratedDemandInterface(): void
    {
        $settings = [
            SI::SETTING_FAQ_STORAGE_ID => '41',
            SI::SETTING_FAQ_SORTING => 'sorting asc',
            SI::SETTING_LIST_SELECTED_QUESTIONS => '116,19,97,100',

        ];

        $this->registerPageUtility([41]);
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
