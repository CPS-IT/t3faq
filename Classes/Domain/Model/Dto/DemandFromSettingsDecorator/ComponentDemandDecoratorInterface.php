<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Fr\T3faq\Domain\Model\Dto\DemandFromSettingsDecorator;

use Fr\T3faq\Domain\Model\Dto\DemandInterface;

interface ComponentDemandDecoratorInterface
{
    public function decorate(): DemandInterface;
}
