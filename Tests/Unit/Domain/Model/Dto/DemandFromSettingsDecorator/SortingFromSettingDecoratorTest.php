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
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\SortingFromSettingDecorator;
use PHPUnit\Framework\TestCase;

class SortingFromSettingDecoratorTest extends TestCase
{
    public function testDecorate(): void
    {
        $settings = [
            SortingFromSettingDecorator::SETTING_KEY_SORTING => 'sorting asc',
        ];
        $component = new QuestionDemand();
        $decorator = new SortingFromSettingDecorator($component, $settings);
        self::assertEquals(
            $settings[SortingFromSettingDecorator::SETTING_KEY_SORTING],
            $decorator->decorate()->getSorting()
        );
    }
}
