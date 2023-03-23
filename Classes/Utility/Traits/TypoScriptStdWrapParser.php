<?php

namespace Fr\T3faq\Utility\Traits;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use TYPO3\CMS\Core\TypoScript\TypoScriptService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Frontend\ContentObject\ContentObjectRenderer;

trait TypoScriptStdWrapParser
{
    /**
     * Parse typoscript stdWrap options
     * Expects a valid typoscript array
     *
     * @param array $typoscript
     * @param ContentObjectRenderer $contentObject
     * @return array
     */
    public function parseTypoScriptStdWrap(array $typoscript, ContentObjectRenderer $contentObject): array
    {
        $typoScriptArray = $typoscript;
        foreach ($typoScriptArray as $key => $value) {
            if (strpos($key, '.') && is_array($value)) {
                $content = $typoScriptArray[rtrim($key, '.')] ?? '';
                $typoscript[rtrim($key, '.')] = $contentObject->stdWrap($content, $value);
                unset($typoscript[$key]);
            }
        }
        // return updated settings
        return $typoscript;
    }

    /**
     * @param $conf
     * @param $contentObject
     * @param string $content
     * @return string
     */
    protected function getContentStdWrap($conf, $contentObject, string $content = ''): string
    {
        return $contentObject->stdWrap($content, $conf);
    }

    public function convertPlainArrayToTyposcriptArray(array $plainArray): array
    {
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

        return $typoScriptService->convertPlainArrayToTypoScriptArray($plainArray);
    }

    public function convertTyposcriptArrayToPlainArray(array $plainArray): array
    {
        $typoScriptService = GeneralUtility::makeInstance(TypoScriptService::class);

        return $typoScriptService->convertTypoScriptArrayToPlainArray($plainArray);
    }

}
