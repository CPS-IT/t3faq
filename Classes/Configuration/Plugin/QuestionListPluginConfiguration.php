<?php

namespace Cpsit\T3faq\Configuration\Plugin;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use Cpsit\T3faq\Controller\QuestionController;
use DWenzel\T3extensionTools\Configuration\PluginConfigurationInterface;
use DWenzel\T3extensionTools\Configuration\PluginConfigurationTrait;
use Cpsit\T3faq\Configuration\Extension;
use DWenzel\T3extensionTools\Configuration\PluginRegistrationInterface;
use DWenzel\T3extensionTools\Configuration\PluginRegistrationTrait;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * Class QuestionListPluginConfiguration
 * Provides configuration for the FAQ
 * Plugin Signature: t3faq_list
 */
#[AutoconfigureTag('t3extensionTools.pluginConfiguration')]
#[AutoconfigureTag('t3extensionTools.pluginRegistration')]
class QuestionListPluginConfiguration implements PluginConfigurationInterface, PluginRegistrationInterface
{
    use PluginConfigurationTrait;
    use PluginRegistrationTrait;

    protected string $extensionName = Extension::KEY;
    protected string $pluginName = 'List';
    protected string $pluginTitle = 'LLL:EXT:t3faq/Resources/Private/Language/locallang_be.xlf:plugin.faq.list.title';
    protected string $pluginDescription = 'Plugin for a list of questions.';
    protected string $pluginGroup = 'plugins';
    protected string $pluginType = ExtensionUtility::PLUGIN_TYPE_PLUGIN;
    protected string $pluginIcon = SI::ICON_IDENTIFIER_QUESTION;
    protected string $flexForm = 'FILE:EXT:t3faq/Configuration/FlexForms/QuestionListPlugin.xml';
    protected array $controllerActions = [
        QuestionController::class => 'list'
    ];
    protected array $nonCacheableControllerActions = [];
}
