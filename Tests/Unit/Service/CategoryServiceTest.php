<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Unit\Service;

use Cpsit\T3faq\Domain\Repository\CategoryRepository;
use Cpsit\T3faq\Service\CategoryService;
use PHPUnit\Framework\TestCase;

class CategoryServiceTest extends TestCase
{
    public function testConstructorRequiresCategoryRepository(): void
    {
        $parameters = (new \ReflectionClass(CategoryService::class))->getConstructor()?->getParameters() ?? [];

        self::assertCount(1, $parameters);
        self::assertSame(CategoryRepository::class, (string)$parameters[0]->getType());
        self::assertFalse($parameters[0]->isOptional(), 'The repository must be injected, no default fallback');
    }
}
