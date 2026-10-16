<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator;

use Cpsit\T3faq\Domain\Model\Dto\CategoryDemand;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Dto\QuestionDemand;

abstract class AbstractQuestionDemandFromSettingsDecorator implements ComponentDemandDecoratorInterface
{
    /** @var QuestionDemand|CategoryDemand */
    protected DemandInterface $component;

    /** @var array<string, mixed> */
    protected array $settings;

    /**
     * @param QuestionDemand|CategoryDemand $component
     * @param array<string, mixed> $settings
     */
    public function __construct(DemandInterface $component, array $settings)
    {
        $this->component = $component;
        $this->settings = $settings;
    }
}
