<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Tests\Functional\Upgrade;

use Cpsit\T3faq\Upgrade\BackendGroupsMigratedPrerequisite;
use Cpsit\T3faq\Upgrade\ListTypeToCTypeUpdate;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Install\Updates\DatabaseUpdatedPrerequisite;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class BackendGroupsMigratedPrerequisiteTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'cpsit/cps-utility',
        'cpsit/t3faq',
    ];

    public function testWizardRequiresDatabaseAndMigratedBackendGroups(): void
    {
        $wizard = new ListTypeToCTypeUpdate($this->get(ConnectionPool::class));

        self::assertSame(
            [DatabaseUpdatedPrerequisite::class, BackendGroupsMigratedPrerequisite::class],
            $wizard->getPrerequisites()
        );
    }

    public function testIsFulfilledWithoutLegacyGroups(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/ListTypeToCType.csv');

        self::assertTrue((new BackendGroupsMigratedPrerequisite())->isFulfilled());
    }

    public function testEnsureMigratesLegacyGroupsSoThatPermissionsCanFollow(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/ListTypeToCTypeLegacyGroup.csv');
        $prerequisite = new BackendGroupsMigratedPrerequisite();

        self::assertFalse($prerequisite->isFulfilled());
        self::assertTrue($prerequisite->ensure());
        self::assertTrue($prerequisite->isFulfilled());

        $wizard = new ListTypeToCTypeUpdate($this->get(ConnectionPool::class));
        $wizard->executeUpdate();

        $permissions = $this->getConnectionPool()
            ->getConnectionForTable('be_groups')
            ->select(['uid', 'explicit_allowdeny'], 'be_groups', [], [], ['uid' => 'ASC'])
            ->fetchAllKeyValue();
        self::assertSame(
            [
                1 => 'tt_content:CType:t3faq_list',
                2 => 'tt_content:CType:t3faq_listselected',
            ],
            $permissions
        );
    }
}
