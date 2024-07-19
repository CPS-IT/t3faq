<?php

use TYPO3\CMS\Core\Utility\ExtensionManagementUtility;

defined('TYPO3') or die();


$GLOBALS['TCA']['sys_category']['columns']['items']['config']['MM_oppositeUsage']['tx_t3faq_domain_model_question']
    = ['categories'];

$extensionKey = \Cpsit\T3faq\Configuration\Extension::KEY;
$ll = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_db.xlf:';

// Bidirectional relation to FAQs
$faqsSysCategoryColumns = [
    'frame_class' => [
        'exclude' => true,
        'label' => $ll . 'sys_category.frame_class',
        'config' => [
            'type' => 'select',
            'renderType' => 'selectSingle',
            'items' => [
                ['label' => '', 'value' => ''],
                ['label' => $ll . 'sys_category.frame_class.blue', 'value' => 'c-faq-category--pri1'],
                ['label' => $ll . 'sys_category.frame_class.red', 'value' => 'c-faq-category--pri2'],
                ['label' => $ll . 'sys_category.frame_class.turquoise', 'value' => 'c-faq-category--pri3'],
                ['label' => $ll . 'sys_category.frame_class.yellow', 'value' => 'c-faq-category--pri4'],
            ],
            'fieldWizard' => [
                'selectIcons' => [
                    'disabled' => false,
                ],
            ],
            'default' => '',
            'size' => 1,
            'maxitems' => 1,
        ]
    ],


    'faqs' => [
        'exclude' => true,
        'label' => $ll . 'sys_category.tx_t3faqs',
        'config' => [
            'type' => 'group',
            'size' => 5,
            'allowed' => 'tx_t3faq_domain_model_question',
            'foreign_table' => 'tx_t3faq_domain_model_question',
            'MM' => 'sys_category_record_mm',
            'MM_match_fields' => [
                'fieldname' => 'categories',
                'tablenames' => 'tx_t3faq_domain_model_question',
            ],
            'maxitems' => 1000
        ],
    ],
];

ExtensionManagementUtility::addTCAcolumns('sys_category', $faqsSysCategoryColumns);


// Reinsert position and company after last name
ExtensionManagementUtility::addToAllTCAtypes(
    'sys_category',
    'frame_class',
    '',
    'after:title'
);
