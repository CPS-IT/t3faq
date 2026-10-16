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

use Cpsit\CpsUtility\Utility\PageUtility;
use Cpsit\T3faq\Domain\Model\Dto\CategoryDemand;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;

class PageIdsFromSettingsDecorator extends AbstractQuestionDemandFromSettingsDecorator
{
    public const SETTING_KEY_RECURSIVE = 'recursive';
    public const SETTING_KEY_STORAGE_ID = 'storageIds';

    /**
     * @param QuestionDemand|CategoryDemand $component
     * @param array<string, mixed> $settings
     */
    public function __construct(
        DemandInterface $component,
        array $settings,
        private readonly PageUtility $pageUtility
    ) {
        parent::__construct($component, $settings);
    }

    public function decorate(): DemandInterface
    {
        $recursive = (int)($this->settings[self::SETTING_KEY_RECURSIVE] ?? 0);

        $storageIds = '0';
        if (!empty($this->settings[self::SETTING_KEY_STORAGE_ID])) {
            $storageIds = $this->settings[self::SETTING_KEY_STORAGE_ID];
        }
        $this->component->setPageIds(
            $this->pageUtility->resolveStoragePages($storageIds, $recursive)
        );

        return $this->component;
    }
}
