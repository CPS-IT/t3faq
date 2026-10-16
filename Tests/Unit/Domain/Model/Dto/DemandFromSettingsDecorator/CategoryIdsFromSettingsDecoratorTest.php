<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Unit\Domain\Model\Dto\DemandFromSettingsDecorator;

use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\CategoryIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Service\CategoryService;
use PHPUnit\Framework\TestCase;

class CategoryIdsFromSettingsDecoratorTest extends TestCase
{
    public function testDecorate(): void
    {
        $settings = [
            CategoryIdsFromSettingsDecorator::SETTING_KEY_CATEGORY_LIST => '3,5',
            CategoryIdsFromSettingsDecorator::SETTING_KEY_INCLUDE_PARENT_CATEGORY => 0,

        ];
        $component = new QuestionDemand();
        // CategoryService is final and not used without the parent category setting
        $categoryService = (new \ReflectionClass(CategoryService::class))->newInstanceWithoutConstructor();
        $decorator = new CategoryIdsFromSettingsDecorator($component, $settings, $categoryService);
        self::assertEquals(
            explode(',', $settings[CategoryIdsFromSettingsDecorator::SETTING_KEY_CATEGORY_LIST]),
            $decorator->decorate()->getCategoryIds()
        );
    }

    public function testConstructorRequiresCategoryService(): void
    {
        $parameters = (new \ReflectionClass(CategoryIdsFromSettingsDecorator::class))->getConstructor()?->getParameters() ?? [];

        self::assertSame(
            ['component', 'settings', 'categoryService'],
            array_map(static fn(\ReflectionParameter $parameter): string => $parameter->getName(), $parameters)
        );
        self::assertSame(CategoryService::class, (string)$parameters[2]->getType());
    }
}
