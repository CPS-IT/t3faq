<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Unit\Controller;

use Cpsit\T3faq\Controller\QuestionController;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Core\Http\ServerRequest;
use TYPO3\CMS\Core\View\ViewInterface;
use TYPO3\CMS\Extbase\Mvc\ExtbaseRequestParameters;
use TYPO3\CMS\Extbase\Mvc\Request;
use TYPO3\CMS\Frontend\Page\PageInformation;

class QuestionControllerTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($GLOBALS['TSFE']);
        parent::tearDown();
    }

    public function testPrepareViewAssignsPageRecordFromRequestAttribute(): void
    {
        $pageInformation = new PageInformation();
        $pageInformation->setPageRecord(['uid' => 7, 'title' => 'FAQ']);
        $GLOBALS['TSFE'] = (object)['page' => ['uid' => 99]];

        $view = $this->prepareView(
            $this->createRequest()->withAttribute('frontend.page.information', $pageInformation)
        );

        self::assertSame(['uid' => 7, 'title' => 'FAQ'], $view->assigned['pageData']);
        self::assertSame(['uid' => 1], $view->assigned['contentObjectData']);
    }

    public function testPrepareViewAssignsNoPageDataWithoutRequestAttribute(): void
    {
        $GLOBALS['TSFE'] = (object)['page' => ['uid' => 99]];

        $view = $this->prepareView($this->createRequest());

        self::assertArrayNotHasKey('pageData', $view->assigned);
        self::assertSame(['uid' => 1], $view->assigned['contentObjectData']);
    }

    private function createRequest(): ServerRequestInterface
    {
        $contentObject = new \stdClass();
        $contentObject->data = ['uid' => 1];

        return (new ServerRequest())
            ->withAttribute('extbase', new ExtbaseRequestParameters())
            ->withAttribute('currentContentObject', $contentObject);
    }

    private function prepareView(ServerRequestInterface $serverRequest): object
    {
        $view = new class implements ViewInterface {
            /** @var array<string, mixed> */
            public array $assigned = [];

            public function assign(string $key, mixed $value): self
            {
                $this->assigned[$key] = $value;

                return $this;
            }

            public function assignMultiple(array $values): self
            {
                $this->assigned = array_merge($this->assigned, $values);

                return $this;
            }

            public function render(string $templateFileName = ''): string
            {
                return '';
            }
        };

        // The repositories are final classes, the constructor is not needed for prepareView()
        $reflection = new \ReflectionClass(QuestionController::class);
        $controller = $reflection->newInstanceWithoutConstructor();
        $reflection->getProperty('request')->setValue($controller, new Request($serverRequest));
        $reflection->getProperty('view')->setValue($controller, $view);
        $reflection->getMethod('prepareView')->invoke($controller);

        return $view;
    }
}
