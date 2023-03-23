<?php
declare(strict_types=1);

namespace Fr\T3faq\Controller;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Fr\FrUtility\Traits\FeCacheTagsTrait;
use Fr\T3faq\Configuration\SettingsInterface as SI;
use Fr\T3faq\Domain\Model\Dto\Factory\CategoryDemandFromSettings;
use Fr\T3faq\Domain\Model\Dto\Factory\QuestionDemandFromSettings;
use Fr\T3faq\Domain\Model\Dto\QuestionDemand;
use Fr\T3faq\Domain\Repository\CategoryRepository;
use Fr\T3faq\Domain\Repository\QuestionRepository;
use Fr\T3faq\Service\QuestionToCategoryTreeService;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use TYPO3\CMS\Extbase\Mvc\View\ViewInterface;
use Fr\FrUtility\Traits\ExtBaseTypoScriptStdWrapParserTrait;


/**
 * Class QuestionController
 */
class QuestionController extends ActionController
{
    use ExtBaseTypoScriptStdWrapParserTrait;
    use FeCacheTagsTrait;

    /**
     * @var QuestionRepository
     */
    private $questionRepository;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(QuestionRepository $questionRepository, CategoryRepository $categoryRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->categoryRepository = $categoryRepository;
    }

    public function initializeAction()
    {
        $this->addCacheTags([SI::FE_CACHE_TAG_FAQ]);
        $this->settings = $this->parseTypoScriptStdWrap($this->settings);

    }

    /**
     * {@inheritdoc}
     */
    protected function initializeView(ViewInterface $view)
    {
        $view->assign('contentObjectData', $this->configurationManager->getContentObject()->data);
        if (is_object($GLOBALS['TSFE'])) {
            $view->assign('pageData', $GLOBALS['TSFE']->page);
        }
        parent::initializeView($view);
    }


    public function listAction(): void
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

        $variables = [
            SI::VIEW_VAR_CATEGORY_TREE => $questions
        ];

        $this->view->assignMultiple(
            $variables
        );
    }


    public function listSelectedAction(): void
    {
        /** @var QuestionDemand $demand */
        $demand = GeneralUtility::makeInstance(QuestionDemandFromSettings::class)->get($this->settings);

        $questions = $this->questionRepository->findByUidList($demand->getQuestionIds());

        $variables = [
            SI::VIEW_VAR_QUESTIONS => $questions,
        ];

        $this->view->assignMultiple(
            $variables
        );
    }
}
