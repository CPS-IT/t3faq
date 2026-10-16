<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Functional\Configuration;

use PHPUnit\Framework\Attributes\DataProvider;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class FlexFormTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'cpsit/cps-utility',
        'cpsit/t3faq',
    ];

    /**
     * @return array<string, array{string}>
     */
    public static function flexFormFileProvider(): array
    {
        return [
            'list' => ['QuestionListPlugin.xml'],
            'list selected' => ['QuestionListSelectedPlugin.xml'],
        ];
    }

    #[DataProvider('flexFormFileProvider')]
    public function testDataStructureHasNoTceformsWrapper(string $file): void
    {
        $dataStructure = GeneralUtility::xml2array((string)file_get_contents(__DIR__ . '/../../../Configuration/FlexForms/' . $file));

        self::assertIsArray($dataStructure);
        self::assertNotEmpty($dataStructure['sheets']);
        self::assertFalse($this->containsKey($dataStructure, 'TCEforms'), 'Wrapper TCEforms is removed in TYPO3 13');
        foreach ($dataStructure['sheets'] as $sheet) {
            self::assertArrayHasKey('sheetTitle', $sheet['ROOT']);
        }
    }

    /**
     * @param array<mixed> $data
     */
    private function containsKey(array $data, string $key): bool
    {
        foreach ($data as $name => $value) {
            if ($name === $key || (is_array($value) && $this->containsKey($value, $key))) {
                return true;
            }
        }

        return false;
    }
}
