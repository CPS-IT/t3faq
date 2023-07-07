<?php
defined('TYPO3') or die();

$tableName = \Cpsit\T3faq\Domain\Model\Question::TABLE_NAME;
$extensionKey = \Cpsit\T3faq\Configuration\Extension::KEY;
$ll = 'LLL:EXT:' . $extensionKey . '/Resources/Private/Language/locallang_db.xlf:';

return [
    'ctrl' => [
        'title' => $ll . 'question.title',
        'label' => 'question',
        'sortby' => 'sorting',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'transOrigPointerField' => 'l10n_parent',
        'translationSource' => 'l10n_source',
        'transOrigDiffSourceField' => 'l10n_diffsource',
        'languageField' => 'sys_language_uid',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'uid,question,answer',
        'typeicon_classes' => [
            'default' => \Cpsit\T3faq\Configuration\SettingsInterface::ICON_IDENTIFIER_QUESTION,
        ],

    ],
    'interface' => [],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true,
                    ],
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038),
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'l10n_parent' => [
            'displayCond' => 'FIELD:sys_language_uid:>:0',
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.l18n_parent',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'items' => [
                    [
                        '',
                        0,
                    ],
                ],
                'foreign_table' => $tableName,
                'foreign_table_where' => 'AND ' . $tableName . '.uid=###CURRENT_PID### ' .
                    'AND ' . $tableName . '.sys_language_uid = 0',
                'default' => 0,
            ],
        ],
        'sys_language_uid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.language',
            'config' => [
                'type' => 'language',
            ],
        ],
        'l10n_diffsource' => [
            'config' => [
                'type' => 'passthrough',
                'default' => '',
            ],
        ],
        'l10n_source' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],
        'tstamp' => [
            'config' => [
                'type' => 'passthrough',
            ],
        ],

        'question' => [
            'exclude' => false,
            'l10n_mode' => 'prefixLangTitle',
            'label' => $ll . 'question.question',
            'config' => [
                'type' => 'input',
                'cols' => 80,
                'eval' => 'trim,required',
                'max' => 300,
            ],
        ],
        'answer' => [
            'l10n_mode' => 'prefixLangTitle',
            'label' => $ll . 'question.answer',
            'config' => [
                'type' => 'text',
                'enableRichtext' => true,
                'richtextConfiguration' => 'default',
                'cols' => 80,
                'rows' => 15,
                'eval' => 'trim',
            ],
        ],
        'weight' => [
            'exclude' => false,
            'label' => $ll . 'question.weight',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim,int',
                'range' => [
                    'lower' => 0,
                    'upper' => 100,
                ],
                'slider' => [
                    'step' => 1,
                    'width' => 100,
                ],
                'default' => 0,
            ],
        ],
        'information_date' => [
            'exclude' => false,
            'label' => $ll . 'question.information_date',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'date,int',
                'required' => false,
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ],
        ],
        'likes' => [
            'exclude' => false,
            'label' => $ll . 'question.likes',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim,int',
                'default' => 0,
                'readOnly' => true,
            ],
        ],
        'dislikes' => [
            'exclude' => false,
            'label' => $ll . 'question.dislikes',
            'config' => [
                'type' => 'input',
                'size' => 5,
                'eval' => 'trim,int',
                'default' => 0,
                'readOnly' => true,
            ],
        ],
        'categories' => [
            'exclude' => false,
            'l10n_mode' => 'exclude',
            'label' => $ll . 'question.categories',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectTree',
                'minitems' => 0,
                'maxitems' => 99,
                'foreign_table' => 'sys_category',
                'foreign_table_where' => ' AND {#sys_category}.{#sys_language_uid} IN (-1, 0)
                    ORDER BY sys_category.sorting',
                'MM' => 'sys_category_record_mm',
                'MM_opposite_field' => 'items',
                'MM_match_fields' => [
                    'fieldname' => 'categories',
                    'tablenames' => \Cpsit\T3faq\Domain\Model\Question::TABLE_NAME,
                ],
                'treeConfig' => [
                    'parentField' => 'parent',
                    'appearance' => [
                        'maxLevels' => 2,
                        'expandAll' => false
                    ]
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true,
                ],
            ]
        ],
    ],
    'types' => [
        '0' => [
            'showitem' => '
                --div--;' . $ll . 'tab.general, question, weight, answer, information_date, --palette--;;likes,
                --div--;' . $ll . 'tab.categories, categories,
                --div--;' . $ll . 'tab.language, --palette--;;language,
                --div--;' . $ll . 'tab.access' . ', hidden, --palette--;;access
            ',
        ],
    ],
    'palettes' => [
        'language' => [
            'showitem' => 'sys_language_uid, l10n_parent',
        ],
        'access' => [
            'showitem' => 'starttime, endtime',
        ],
        'likes' => [
            'label' => $ll . 'palette.likes',
            'showitem' => 'likes, dislikes',
        ],
    ],
];
