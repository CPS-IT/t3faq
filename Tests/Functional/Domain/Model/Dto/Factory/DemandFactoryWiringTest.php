<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Functional\Domain\Model\Dto\Factory;

use Cpsit\T3faq\Domain\Model\Dto\Factory\CategoryDemandFromSettings;
use Cpsit\T3faq\Domain\Model\Dto\Factory\QuestionDemandFromSettings;
use Cpsit\T3faq\Service\CategoryService;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class DemandFactoryWiringTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'cpsit/cps-utility',
        'cpsit/t3faq',
    ];

    public function testDemandFactoriesAndCategoryServiceAreAutowired(): void
    {
        self::assertInstanceOf(QuestionDemandFromSettings::class, $this->get(QuestionDemandFromSettings::class));
        self::assertInstanceOf(CategoryDemandFromSettings::class, $this->get(CategoryDemandFromSettings::class));
        self::assertInstanceOf(CategoryService::class, $this->get(CategoryService::class));
    }
}
