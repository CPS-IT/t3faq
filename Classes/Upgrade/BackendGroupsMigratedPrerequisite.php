<?php

declare(strict_types=1);

namespace Cpsit\T3faq\Upgrade;

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

use Symfony\Component\Console\Output\OutputInterface;
use TYPO3\CMS\Install\Updates\BackendGroupsExplicitAllowDenyMigration;
use TYPO3\CMS\Install\Updates\ChattyInterface;
use TYPO3\CMS\Install\Updates\PrerequisiteInterface;

final class BackendGroupsMigratedPrerequisite implements PrerequisiteInterface, ChattyInterface
{
    private readonly BackendGroupsExplicitAllowDenyMigration $migration;

    public function __construct()
    {
        $this->migration = new BackendGroupsExplicitAllowDenyMigration();
    }

    public function getTitle(): string
    {
        return 'Backend groups "explicit_allowdeny" in simplified format';
    }

    public function isFulfilled(): bool
    {
        return !$this->migration->updateNecessary();
    }

    public function ensure(): bool
    {
        return $this->migration->executeUpdate();
    }

    public function setOutput(OutputInterface $output): void
    {
        $this->migration->setOutput($output);
    }
}
