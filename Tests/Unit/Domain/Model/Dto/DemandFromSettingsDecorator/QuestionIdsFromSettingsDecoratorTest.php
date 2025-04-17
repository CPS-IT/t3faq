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
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\QuestionIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\SortingFromSettingDecorator;
use PHPUnit\Framework\TestCase;

class QuestionIdsFromSettingsDecoratorTest extends TestCase
{
    public function testDecorate(): void
    {
        $settings = [
            QuestionIdsFromSettingsDecorator::SETTING_KEY_QUESTION_IDS => '116,19,97,100',
        ];
        $component = new QuestionDemand();
        $decorator = new QuestionIdsFromSettingsDecorator($component, $settings);
        self::assertEquals(
            explode(',', $settings[QuestionIdsFromSettingsDecorator::SETTING_KEY_QUESTION_IDS]),
            $decorator->decorate()->getQuestionIds());
    }
}
