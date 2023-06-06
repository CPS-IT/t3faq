<?php
declare(strict_types=1);

namespace Cpsit\T3faq\Controller;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Cpsit\CpsUtility\Traits\FeCacheTagsTrait;
use Cpsit\CpsUtility\Traits\ExtBaseTypoScriptStdWrapParserTrait;
use Cpsit\T3faq\Configuration\SettingsInterface as SI;
use Cpsit\T3faq\Domain\Model\Dto\Factory\CategoryDemandFromSettings;
use Cpsit\T3faq\Domain\Model\Dto\Factory\QuestionDemandFromSettings;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;
use Cpsit\T3faq\Domain\Repository\CategoryRepository;
use Cpsit\T3faq\Domain\Repository\QuestionRepository;
use Cpsit\T3faq\Service\QuestionToCategoryTreeService;
use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;

/**
 * Class QuestionController
 */
class QuestionController extends ActionController
{
    use ExtBaseTypoScriptStdWrapParserTrait;
    use FeCacheTagsTrait;

    private QuestionRepository $questionRepository;

    private CategoryRepository $categoryRepository;

    public function __construct(QuestionRepository $questionRepository, CategoryRepository $categoryRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function initializeAction(): void
    {
        $this->addCacheTags([SI::FE_CACHE_TAG_FAQ]);
        $this->settings = $this->parseTypoScriptStdWrap($this->settings);
        parent::initializeAction();
    }

    protected function prepareView(): void
    {
        $this->view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
        if (is_object($GLOBALS['TSFE'])) {
            $this->view->assign('pageData', $GLOBALS['TSFE']->page);
        }
    }

    public function listAction(): ResponseInterface
    {
        /** @var QuestionDemand $questionsDemand */
        $questionsDemand = GeneralUtility::makeInstance(QuestionDemandFromSettings::class)->get($this->settings);
        $questions = $this->questionRepository->findDemanded($questionsDemand);

        /**
         * Prepare questions for view to meet client grouping and sorting requirements
         */

        $categoryDemand = GeneralUtility::makeInstance(CategoryDemandFromSettings::class)->get($this->settings);
        $categories = $this->categoryRepository->findByPidList(
            $categoryDemand->getPageIds(),
            $categoryDemand->getSorting() ?? ''
        );

        $questionToCategoryTreeService = new QuestionToCategoryTreeService($questions, $categories);
        $questions = $questionToCategoryTreeService->getQuestionCategoryTree();
        // Remove Root category from tree
        if ($this->settings[SI::SETTING_IGNORE_ROOT_CATEGORY]) {
            if (isset($questions[$this->settings[SI::SETTING_CATEGORY_ROOT]]['children'])) {
                $questions = $questions[$this->settings[SI::SETTING_CATEGORY_ROOT]]['children'];
            }
        }
        $this->prepareView();
        $variables = [
            SI::VIEW_VAR_CATEGORY_TREE => $questions
        ];

        $this->view->assignMultiple(
            $variables
        );
        return $this->htmlResponse();
    }

    public function listSelectedAction(): ResponseInterface
    {
        /** @var QuestionDemand $demand */
        $demand = GeneralUtility::makeInstance(QuestionDemandFromSettings::class)->get($this->settings);

        $questions = $this->questionRepository->findByUidList($demand->getQuestionIds());
        $this->prepareView();
        $variables = [
            SI::VIEW_VAR_QUESTIONS => $questions,
        ];

        $this->view->assignMultiple(
            $variables
        );
        return $this->htmlResponse();
    }
}
