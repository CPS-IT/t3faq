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
use TYPO3\CMS\Core\Configuration\FlexForm\FlexFormTools;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class PluginRegistrationTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'cpsit/cps-utility',
        'cpsit/t3faq',
    ];

    /**
     * @return array<string, array{string, string}>
     */
    public static function pluginProvider(): array
    {
        return [
            'list' => ['List', 't3faq_list'],
            'list selected' => ['ListSelected', 't3faq_listselected'],
        ];
    }

    /**
     * @return array<string, array{string, string}>
     */
    public static function flexFormFieldProvider(): array
    {
        return [
            'list' => ['t3faq_list', 'settings.categoriesList'],
            'list selected' => ['t3faq_listselected', 'settings.listSelectedQuestions'],
        ];
    }

    #[DataProvider('pluginProvider')]
    public function testPluginIsSelectableContentType(string $pluginName, string $signature): void
    {
        $item = $this->findCTypeItem($signature);

        self::assertNotNull($item);
        self::assertSame('plugins', $item['group']);
        self::assertNotEmpty($item['description']);
    }

    #[DataProvider('pluginProvider')]
    public function testPluginIsConfiguredAsContentElement(string $pluginName, string $signature): void
    {
        $plugin = $GLOBALS['TYPO3_CONF_VARS']['EXTCONF']['extbase']['extensions']['T3faq']['plugins'][$pluginName] ?? null;

        self::assertIsArray($plugin);
        self::assertSame(ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT, $plugin['pluginType']);
        self::assertNotEmpty($plugin['controllers']);
    }

    public function testNoT3faqListTypeIsRegistered(): void
    {
        $values = array_column($GLOBALS['TCA']['tt_content']['columns']['list_type']['config']['items'] ?? [], 'value');

        self::assertSame([], array_filter($values, static fn($value): bool => str_starts_with((string)$value, 't3faq_')));
    }

    #[DataProvider('flexFormFieldProvider')]
    public function testPluginHasFlexFormDataStructure(string $signature, string $field): void
    {
        $flexFormTools = $this->get(FlexFormTools::class);
        $identifier = $flexFormTools->getDataStructureIdentifier(
            $GLOBALS['TCA']['tt_content']['columns']['pi_flexform'],
            'tt_content',
            'pi_flexform',
            ['CType' => $signature, 'list_type' => '']
        );
        $dataStructure = $flexFormTools->parseDataStructureByIdentifier($identifier);

        self::assertArrayHasKey($field, $dataStructure['sheets']['sDEF']['ROOT']['el']);
    }

    public function testConfigurationTabLabelIsTranslatable(): void
    {
        $showItem = $GLOBALS['TCA']['tt_content']['types']['t3faq_list']['showitem'] ?? '';

        self::assertStringContainsString(
            '--div--;LLL:EXT:t3faq/Resources/Private/Language/locallang_be.xlf:tab.configuration,pi_flexform',
            $showItem
        );
    }

    /**
     * @return array<string, array{string}>
     */
    public static function languageFileProvider(): array
    {
        return [
            'english' => ['locallang_be.xlf'],
            'german' => ['de.locallang_be.xlf'],
        ];
    }

    #[DataProvider('languageFileProvider')]
    public function testBackendLabelsOfPluginsExistInLanguageFile(string $fileName): void
    {
        $xml = simplexml_load_file(dirname(__DIR__, 3) . '/Resources/Private/Language/' . $fileName);
        self::assertInstanceOf(\SimpleXMLElement::class, $xml);
        $ids = [];
        foreach ($xml->xpath('//trans-unit') ?: [] as $unit) {
            $ids[] = (string)$unit['id'];
        }

        foreach (['list', 'list_selected'] as $plugin) {
            self::assertContains('plugin.faq.' . $plugin . '.title', $ids, $fileName);
            self::assertContains('plugin.faq.' . $plugin . '.description', $ids, $fileName);
        }
        self::assertContains('tab.configuration', $ids, $fileName);
    }

    /**
     * @return array<string, mixed>|null
     */
    private function findCTypeItem(string $signature): ?array
    {
        foreach ($GLOBALS['TCA']['tt_content']['columns']['CType']['config']['items'] as $item) {
            if (($item['value'] ?? null) === $signature) {
                return $item;
            }
        }

        return null;
    }
}
