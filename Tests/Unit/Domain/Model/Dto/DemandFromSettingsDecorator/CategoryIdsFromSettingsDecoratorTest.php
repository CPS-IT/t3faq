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
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\CategoryIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\PageIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\QuestionIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\SortingFromSettingDecorator;
use PHPUnit\Framework\TestCase;

class CategoryIdsFromSettingsDecoratorTest extends TestCase
{
    public function testDecorate()
    {
        $settings = [
            CategoryIdsFromSettingsDecorator::SETTING_KEY_CATEGORY_LIST => '3,5',
            CategoryIdsFromSettingsDecorator::SETTING_KEY_INCLUDE_PARENT_CATEGORY => 0

        ];
        $component = new QuestionDemand();
        $decorator = new CategoryIdsFromSettingsDecorator($component, $settings);
        self::assertEquals(
            explode(',', $settings[CategoryIdsFromSettingsDecorator::SETTING_KEY_CATEGORY_LIST]),
            $decorator->decorate()->getCategoryIds()
        );
    }
}
