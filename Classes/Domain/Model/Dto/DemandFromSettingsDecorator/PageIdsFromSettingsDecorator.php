<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Fr\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator;

use Fr\FrUtility\Utility\PageUtility;
use Fr\T3faq\Configuration\SettingsInterface as SI;
use Fr\T3faq\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class PageIdsFromSettingsDecorator extends AbstractQuestionDemandFromSettingsDecorator
{
    public const SETTING_KEY_RECURSIVE = 'recursive';
    public const SETTING_KEY_STORAGE_ID = 'storageIds';

    public function decorate(): DemandInterface
    {
        /** @var PageUtility $pageUtility */
        $pageUtility = GeneralUtility::makeInstance(PageUtility::class);
        $recursive = (int)$this->settings[self::SETTING_KEY_RECURSIVE] ?? 0;

        $storageIds = '0';
        if (!empty($this->settings[self::SETTING_KEY_STORAGE_ID])) {
            $storageIds = $this->settings[self::SETTING_KEY_STORAGE_ID];
        }
        $this->component->setPageIds(
            $pageUtility->resolveStoragePages($storageIds, $recursive)
        );

        return $this->component;
    }
}
