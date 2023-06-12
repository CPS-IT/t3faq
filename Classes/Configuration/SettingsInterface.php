<?php
declare(strict_types=1);

namespace Cpsit\T3faq\Configuration;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

/**
 * Interface SettingsInterface
 */
interface SettingsInterface
{
    public const ICON_IDENTIFIER_QUESTION = 't3faq-question';

    public const FE_CACHE_TAG_FAQ = 'cpsit-faqs';
    public const VIEW_VAR_QUESTIONS = 'questions';
    public const VIEW_VAR_CATEGORY_TREE = 'categoryTree';

    public const SETTING_FAQ_STORAGE_RECURSIVE = 'recursive';
    public const SETTING_FAQ_STORAGE_ID = 'listPid';
    public const SETTING_FAQ_SORTING = 'sorting';

    public const SETTING_CATEGORIES_LIST = 'categoriesList';
    public const SETTING_CATEGORIES_LIST_INCLUDE_PARENT_CATEGORIES = 'categoriesListIncludeParentCategories';
    public const SETTING_CATEGORY_STORAGE_ID = 'categoryPid';
    public const SETTING_CATEGORY_STORAGE_RECURSIVE = 'categoryPidRecursive';
    public const SETTING_CATEGORY_ROOT = 'categoryRoot';
    public const SETTING_CATEGORY_SORTING = 'categorySorting';

    public const SETTING_IGNORE_ROOT_CATEGORY = 'ignoreRootCategory';
    public const SETTING_SHOW_SEARCH_FORM = 'showSearchForm';

    public const SETTING_LIST_SELECTED_QUESTIONS = 'listSelectedQuestions';


}
