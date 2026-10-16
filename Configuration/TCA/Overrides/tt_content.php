<?php

defined('TYPO3') or die();

use Cpsit\T3faq\Configuration\Extension;
use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

$languageFile = 'LLL:EXT:t3faq/Resources/Private/Language/locallang_be.xlf:';

$plugins = [
    'List' => ['languageKey' => 'list', 'flexForm' => 'QuestionListPlugin.xml'],
    'ListSelected' => ['languageKey' => 'list_selected', 'flexForm' => 'QuestionListSelectedPlugin.xml'],
];

foreach ($plugins as $pluginName => $plugin) {
    $signature = ExtensionUtility::registerPlugin(
        Extension::NAME,
        $pluginName,
        $languageFile . 'plugin.faq.' . $plugin['languageKey'] . '.title',
        SI::ICON_IDENTIFIER_QUESTION,
        'plugins',
        $languageFile . 'plugin.faq.' . $plugin['languageKey'] . '.description'
    );

    ExtensionManagementUtility::addToAllTCAtypes('tt_content', '--div--;' . $languageFile . 'tab.configuration,pi_flexform,', $signature, 'after:subheader');
    ExtensionManagementUtility::addPiFlexFormValue('*', 'FILE:EXT:t3faq/Configuration/FlexForms/' . $plugin['flexForm'], $signature);
}
