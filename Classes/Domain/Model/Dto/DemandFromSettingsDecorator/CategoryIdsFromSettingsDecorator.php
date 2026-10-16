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

use Cpsit\T3faq\Domain\Model\Dto\CategoryDemand;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Service\CategoryService;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class CategoryIdsFromSettingsDecorator extends AbstractQuestionDemandFromSettingsDecorator
{
    public const SETTING_KEY_CATEGORY_LIST = 'categoryList';
    public const SETTING_KEY_INCLUDE_PARENT_CATEGORY = 'includeParentCategory';

    /**
     * @param QuestionDemand|CategoryDemand $component
     * @param array<string, mixed> $settings
     */
    public function __construct(
        DemandInterface $component,
        array $settings,
        private readonly CategoryService $categoryService
    ) {
        parent::__construct($component, $settings);
    }

    public function decorate(): DemandInterface
    {
        if (!empty($this->settings[self::SETTING_KEY_CATEGORY_LIST])) {
            $categoriesList = GeneralUtility::intExplode(',', $this->settings[self::SETTING_KEY_CATEGORY_LIST]);
            $this->component->setCategoryIds($categoriesList);
        }

        if (!empty($this->settings[self::SETTING_KEY_INCLUDE_PARENT_CATEGORY])
            && !empty($this->component->getCategoryIds())) {
            $categoriesList = $this->categoryService->getExtendedCategoryListIncludeParents($this->component->getCategoryIds());
            $this->component->setCategoryIds($categoriesList);
        }
        return $this->component;
    }
}
