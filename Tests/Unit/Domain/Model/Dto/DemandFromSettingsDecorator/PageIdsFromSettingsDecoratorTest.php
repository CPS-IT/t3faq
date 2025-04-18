<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Unit\Domain\Model\Dto\DemandFromSettingsDecorator;

use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\PageIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\QuestionIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\SortingFromSettingDecorator;
use PHPUnit\Framework\TestCase;

class PageIdsFromSettingsDecoratorTest extends TestCase
{
    public function testDecorate(): void
    {
        $settings = [
            PageIdsFromSettingsDecorator::SETTING_KEY_STORAGE_ID => '3,5',
            PageIdsFromSettingsDecorator::SETTING_KEY_RECURSIVE => 0
        ];
        $component = new QuestionDemand();
        $decorator = new PageIdsFromSettingsDecorator($component, $settings);
        self::assertEquals(
            explode(',', $settings[PageIdsFromSettingsDecorator::SETTING_KEY_STORAGE_ID]),
            $decorator->decorate()->getPageIds()
        );
    }
}
