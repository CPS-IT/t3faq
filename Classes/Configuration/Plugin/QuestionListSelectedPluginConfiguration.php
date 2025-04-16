<?php

namespace Cpsit\T3faq\Configuration\Plugin;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Cpsit\T3faq\Controller\QuestionController;
use DWenzel\T3extensionTools\Configuration\PluginConfigurationInterface;
use DWenzel\T3extensionTools\Configuration\PluginConfigurationTrait;
use Cpsit\T3faq\Configuration\Extension;
use DWenzel\T3extensionTools\Configuration\PluginRegistrationTrait;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

/**
 * Class QuestionListPluginConfiguration
 * Provides configuration for the FAQ
 */
#[AutoconfigureTag('t3extensionTools.pluginConfiguration')]
#[AutoconfigureTag('t3extensionTools.pluginRegistration')]
class QuestionListSelectedPluginConfiguration implements PluginConfigurationInterface
{
    use PluginConfigurationTrait;
    use PluginRegistrationTrait;

    protected string $extensionName = Extension::KEY;
    protected string $pluginName = 'ListSelected';
    protected string $pluginSignature = 't3faq_listselected';
    protected string $pluginTitle = 'LLL:EXT:t3faq/Resources/Private/Language/locallang_be.xlf:plugin.faq.list_selected.title';
    protected string $pluginDescription = 'Plugin for List of Selected Questions';
    protected string $pluginGroup = 'plugins';
    protected string $pluginType = ExtensionUtility::PLUGIN_TYPE_PLUGIN;
    protected string $flexForm = 'FILE:EXT:t3faq/Configuration/FlexForms/QuestionListSelectedPlugin.xml';
    protected array $controllerActions = [
        QuestionController::class => 'listSelected'
    ];
    protected array $nonCacheableControllerActions = [];
}
