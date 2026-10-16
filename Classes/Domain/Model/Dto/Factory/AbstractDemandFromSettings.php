<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Domain\Model\Dto\Factory;

use Cpsit\CpsUtility\Utility\PageUtility;
use Cpsit\T3faq\Domain\Model\Dto\CategoryDemand;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\CategoryIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\ComponentDemandDecoratorInterface;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\PageIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Service\CategoryService;

abstract class AbstractDemandFromSettings implements DemandFromSettingsFactoryInterface
{
    /** @var array<string, array{decorator: class-string<ComponentDemandDecoratorInterface>, settings: array<string, string>}> */
    protected const PROPERTY_DECORATOR_MAP = [];

    public function __construct(
        protected readonly PageUtility $pageUtility,
        protected readonly CategoryService $categoryService
    ) {}

    /**
     * @param array<string, mixed> $settings
     */
    protected function decorate(array $settings, QuestionDemand|CategoryDemand $demand): void
    {
        if (!empty($settings) && !empty(static::PROPERTY_DECORATOR_MAP)) {
            foreach (static::PROPERTY_DECORATOR_MAP as $decoratorMap) {
                if (class_exists($decoratorMap['decorator'])) {

                    $decorator = $this->createDecorator(
                        $decoratorMap['decorator'],
                        $demand,
                        $this->resolveSettingValueMap($decoratorMap['settings'], $settings)
                    );
                    $decorator->decorate();
                }
            }
        }
    }

    /**
     * @param class-string<ComponentDemandDecoratorInterface> $decoratorClass
     * @param array<string, mixed> $settings
     */
    private function createDecorator(
        string $decoratorClass,
        QuestionDemand|CategoryDemand $demand,
        array $settings
    ): ComponentDemandDecoratorInterface {
        return match ($decoratorClass) {
            PageIdsFromSettingsDecorator::class => new PageIdsFromSettingsDecorator($demand, $settings, $this->pageUtility),
            CategoryIdsFromSettingsDecorator::class => new CategoryIdsFromSettingsDecorator($demand, $settings, $this->categoryService),
            default => new $decoratorClass($demand, $settings),
        };
    }

    /**
     * @param array<string, string> $settingsMap
     * @param array<string, mixed> $settings
     * @return array<string, mixed>
     */
    protected function resolveSettingValueMap(array $settingsMap, array $settings): array
    {
        $resolvedSettings = [];
        foreach ($settingsMap as $key => $value) {
            if (isset($settings[$value])) {
                $resolvedSettings[$key] = $settings[$value];
            } else {
                $resolvedSettings[$key] = null;
            }
        }
        return $resolvedSettings;
    }
}
