<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Unit\Domain\Model\Dto\DemandFromSettingsDecorator;

use Cpsit\CpsUtility\Utility\PageUtility;
use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\PageIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\QuestionIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\SortingFromSettingDecorator;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageIdsFromSettingsDecoratorTest extends TestCase
{
    protected function tearDown(): void
    {
        GeneralUtility::purgeInstances();
        parent::tearDown();
    }

    public function testDecorate(): void
    {
        $settings = [
            PageIdsFromSettingsDecorator::SETTING_KEY_STORAGE_ID => '3,5',
            PageIdsFromSettingsDecorator::SETTING_KEY_RECURSIVE => 0
        ];
        $pageUtility = $this->createMock(PageUtility::class);
        $pageUtility->method('resolveStoragePages')->willReturn([3, 5]);
        GeneralUtility::addInstance(PageUtility::class, $pageUtility);
        $component = new QuestionDemand();
        $decorator = new PageIdsFromSettingsDecorator($component, $settings);
        self::assertEquals(
            explode(',', $settings[PageIdsFromSettingsDecorator::SETTING_KEY_STORAGE_ID]),
            $decorator->decorate()->getPageIds()
        );
    }
}
