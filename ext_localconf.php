<?php

defined('TYPO3') or die();

use Cpsit\T3faq\Configuration\Extension;
use Cpsit\T3faq\Controller\QuestionController;
use TYPO3\CMS\Extbase\Utility\ExtensionUtility;

ExtensionUtility::configurePlugin(
    Extension::NAME,
    'List',
    [QuestionController::class => 'list'],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);

ExtensionUtility::configurePlugin(
    Extension::NAME,
    'ListSelected',
    [QuestionController::class => 'listSelected'],
    [],
    ExtensionUtility::PLUGIN_TYPE_CONTENT_ELEMENT
);
