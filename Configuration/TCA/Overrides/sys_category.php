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
                ['', ''],
                [$ll . 'sys_category.frame_class.blue', 'c-faq-category--pri1'],
                [$ll . 'sys_category.frame_class.red', 'c-faq-category--pri2'],
                [$ll . 'sys_category.frame_class.turquoise', 'c-faq-category--pri3'],
                [$ll . 'sys_category.frame_class.yellow', 'c-faq-category--pri4'],
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
            'internal_type' => 'db',
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
