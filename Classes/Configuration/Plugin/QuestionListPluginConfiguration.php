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

/**
 * Class QuestionListPluginConfiguration
 * Provides configuration for the FAQ
 */
class QuestionListPluginConfiguration implements PluginConfigurationInterface
{
    use PluginConfigurationTrait;

    static protected $pluginName = 'List';
    static protected $pluginSignature = 't3faq_list';
    static protected $pluginTitle = 'LLL:EXT:t3faq/Resources/Private/Language/locallang_be.xlf:plugin.faq.list.title';

    static protected $flexForm = 'FILE:EXT:t3faq/Configuration/FlexForms/QuestionListPlugin.xml';
    static protected $controllerActions = [
        QuestionController::class => 'list'
    ];

    static protected $nonCacheableControllerActions = [];
    static protected $vendorExtensionName = Extension::VENDOR_NAME . '.' . Extension::KEY;
}
