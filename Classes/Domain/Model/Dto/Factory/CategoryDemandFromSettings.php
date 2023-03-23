<?php
/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Fr\T3faq\Domain\Model\Dto\Factory;

use Fr\T3faq\Configuration\SettingsInterface as SI;
use Fr\T3faq\Domain\Model\Dto\CategoryDemand;
use Fr\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\CategoryIdsFromSettingsDecorator;
use Fr\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\PageIdsFromSettingsDecorator;
use Fr\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\SortingFromSettingDecorator;
use Fr\T3faq\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Get an instance of QuestionDemand from typoscript settings
 */
class CategoryDemandFromSettings extends AbstractDemandFromSettings
{
    protected const PROPERTY_DECORATOR_MAP = [
        'pageIds' => [
            'decorator' => PageIdsFromSettingsDecorator::class,
            'settings' => [
                PageIdsFromSettingsDecorator::SETTING_KEY_RECURSIVE => SI::SETTING_CATEGORY_STORAGE_RECURSIVE,
                PageIdsFromSettingsDecorator::SETTING_KEY_STORAGE_ID => SI::SETTING_CATEGORY_STORAGE_ID
            ]
        ],
        'categoryIds' => [
            'decorator' => CategoryIdsFromSettingsDecorator::class,
            'settings' => [
                CategoryIdsFromSettingsDecorator::SETTING_KEY_CATEGORY_LIST => SI::SETTING_CATEGORIES_LIST,
                CategoryIdsFromSettingsDecorator::SETTING_KEY_INCLUDE_PARENT_CATEGORY => SI::SETTING_CATEGORIES_LIST_INCLUDE_PARENT_CATEGORIES,
            ]
        ],
        'sorting' => [
            'decorator' => SortingFromSettingDecorator::class,
            'settings' => [
                SortingFromSettingDecorator::SETTING_KEY_SORTING => SI::SETTING_CATEGORY_SORTING
            ]
        ]
    ];

    public function get(array $settings = []): DemandInterface
    {
        $demand = GeneralUtility::makeInstance(CategoryDemand::class);
        $this->decorate($settings, $demand);
        return $demand;
    }
}
