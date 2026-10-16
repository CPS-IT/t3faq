<?php

declare(strict_types=1);

use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider;

return [
    SI::ICON_IDENTIFIER_QUESTION => [
        'provider' => SvgIconProvider::class,
        'source' => 'EXT:t3faq/Resources/Public/Icons/faq-question.svg',
    ],
];
