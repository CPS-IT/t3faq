<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Unit\Domain\Model\Dto;

use Cpsit\CpsUtility\Utility\PageUtility;
use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Dto\Factory\QuestionDemandFromSettings;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Service\CategoryService;
use PHPUnit\Framework\TestCase;

class QuestionDemandFromSettingsTest extends TestCase
{
    /**
     * @param int[] $pageIds
     */
    private function createSubject(array $pageIds): QuestionDemandFromSettings
    {
        $pageUtility = self::createStub(PageUtility::class);
        $pageUtility->method('resolveStoragePages')->willReturn($pageIds);
        // CategoryService is final and not used by these settings
        $categoryService = (new \ReflectionClass(CategoryService::class))->newInstanceWithoutConstructor();

        return new QuestionDemandFromSettings($pageUtility, $categoryService);
    }

    public function testGetReturnsDemandInterface(): void
    {
        $provider = $this->createSubject([0]);

        self::assertInstanceOf(DemandInterface::class, $provider->get());
    }

    public function testGetReturnsDecoratedDemandInterface(): void
    {
        $settings = [
            SI::SETTING_FAQ_STORAGE_ID => '41',
            SI::SETTING_FAQ_SORTING => 'sorting asc',
            SI::SETTING_LIST_SELECTED_QUESTIONS => '116,19,97,100',

        ];

        $provider = $this->createSubject([41]);
        /** @var QuestionDemand $demand */
        $demand = $provider->get($settings);

        self::assertInstanceOf(
            DemandInterface::class,
            $demand,
            'Demand is instance of ' . DemandInterface::class
        );
        self::assertEquals($settings[SI::SETTING_FAQ_SORTING], $demand->getSorting());
        self::assertContains((int)$settings[SI::SETTING_FAQ_STORAGE_ID], $demand->getPageIds());
        self::assertEquals(explode(',', $settings[SI::SETTING_LIST_SELECTED_QUESTIONS]), $demand->getQuestionIds());
    }

    public function testConstructorRequiresInjectedServices(): void
    {
        $parameters = (new \ReflectionClass(QuestionDemandFromSettings::class))->getConstructor()?->getParameters() ?? [];

        self::assertSame(
            [PageUtility::class, CategoryService::class],
            array_map(static fn(\ReflectionParameter $parameter): string => (string)$parameter->getType(), $parameters)
        );
    }
}
