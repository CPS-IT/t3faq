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

use Cpsit\CpsUtility\Utility\PageUtility;
use Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator\PageIdsFromSettingsDecorator;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use PHPUnit\Framework\TestCase;

class PageIdsFromSettingsDecoratorTest extends TestCase
{
    public function testDecorate(): void
    {
        $settings = [
            PageIdsFromSettingsDecorator::SETTING_KEY_STORAGE_ID => '3,5',
            PageIdsFromSettingsDecorator::SETTING_KEY_RECURSIVE => 0,
        ];
        $pageUtility = self::createStub(PageUtility::class);
        $pageUtility->method('resolveStoragePages')->willReturn([3, 5]);
        $component = new QuestionDemand();
        $decorator = new PageIdsFromSettingsDecorator($component, $settings, $pageUtility);
        self::assertEquals(
            explode(',', $settings[PageIdsFromSettingsDecorator::SETTING_KEY_STORAGE_ID]),
            $decorator->decorate()->getPageIds()
        );
    }

    public function testConstructorRequiresPageUtility(): void
    {
        $parameters = (new \ReflectionClass(PageIdsFromSettingsDecorator::class))->getConstructor()?->getParameters() ?? [];

        self::assertSame(
            ['component', 'settings', 'pageUtility'],
            array_map(static fn(\ReflectionParameter $parameter): string => $parameter->getName(), $parameters)
        );
        self::assertSame(PageUtility::class, (string)$parameters[2]->getType());
    }
}
