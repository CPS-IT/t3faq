<?php
declare(strict_types=1);

namespace Fr\T3faq\Configuration;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use DWenzel\T3extensionTools\Configuration\ExtensionConfiguration;
use Fr\T3faq\Configuration\Plugin\QuestionListPluginConfiguration;
use Fr\T3faq\Configuration\Plugin\QuestionListSelectedPluginConfiguration;
use Fr\T3faq\Domain\Model\Question;
use Fr\T3faq\Configuration\SettingsInterface as SI;
use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

/**
 * Extension
 *
 * provides configuration for the extension t3faq
 */
final class Extension extends ExtensionConfiguration
{

    public const NAME = 'T3faq';
    public const KEY = 't3faq';
    public const VENDOR_NAME = 'Fr';

    public const TABLES_ALLOWED_ON_STANDARD_PAGES = [
        Question::TABLE_NAME
    ];

    /**
     * SVG icons to register
     */
    protected const SVG_ICONS_TO_REGISTER = [
        SI::ICON_IDENTIFIER_QUESTION => 'EXT:t3faq/Resources/Public/Icons/faq-question.svg',
    ];

    protected const PLUGINS_TO_REGISTER = [
        QuestionListPluginConfiguration::class,
        QuestionListSelectedPluginConfiguration::class
    ];

    /**
     * Array of strings to add as TSconfig content.
     * @var string[]
     */
    protected const ADD_PAGE_TSCONFIG = [
        "@import 'EXT:t3faq/Configuration/TSconfig/ContentElementWizard.tsconfig'"
    ];

    /**
     * Add page TSconfig content
     */
    public static function addPageTSconfig(): void
    {
        foreach (self::ADD_PAGE_TSCONFIG as $TSconfig) {
            ExtensionManagementUtility::addPageTSConfig($TSconfig);
        }
    }
}
