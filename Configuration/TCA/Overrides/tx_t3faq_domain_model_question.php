<?php
defined('TYPO3') or die();

$extensionKey = \Cpsit\T3faq\Configuration\Extension::KEY;
$ll = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_db.xlf:';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::makeCategorizable(
    \Cpsit\T3faq\Configuration\Extension::KEY,
    \Cpsit\T3faq\Domain\Model\Question::TABLE_NAME,
    'categories',
    [
        'exclude' => false,
        'label' => $ll . 'question.categories',
        'fieldConfiguration' => [
            'behaviour' => [
                'allowLanguageSynchronization' => true,
            ],
            'treeConfig' => [
                'appearance' => [
                    'maxLevels' => 2
                ]
            ]
        ],
        'position' => 'replace:categories'
    ]
);
