<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator;

use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class QuestionIdsFromSettingsDecorator extends AbstractQuestionDemandFromSettingsDecorator
{
    public const SETTING_KEY_QUESTION_IDS = 'questionIds';

    public function decorate(): DemandInterface
    {
        if (!empty($this->settings[self::SETTING_KEY_QUESTION_IDS])) {
            $this->component->setQuestionIds(GeneralUtility::intExplode(',',
                $this->settings[self::SETTING_KEY_QUESTION_IDS]));
        }
        return $this->component;
    }
}
