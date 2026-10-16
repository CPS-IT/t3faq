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

use Cpsit\T3faq\Upgrade\ListTypeToCTypeUpdate;
use PHPUnit\Framework\Attributes\DataProvider;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\TestingFramework\Core\Functional\FunctionalTestCase;

class ListTypeToCTypeUpdateTest extends FunctionalTestCase
{
    protected array $testExtensionsToLoad = [
        'cpsit/cps-utility',
        'cpsit/t3faq',
    ];

    private ListTypeToCTypeUpdate $subject;

    protected function setUp(): void
    {
        parent::setUp();
        $this->subject = new ListTypeToCTypeUpdate($this->get(ConnectionPool::class));
    }

    public function testMappingIsOneToOne(): void
    {
        $mapping = (new \ReflectionMethod($this->subject, 'getListTypeToCTypeMapping'))->invoke($this->subject);

        self::assertSame(
            [
                't3faq_list' => 't3faq_list',
                't3faq_listselected' => 't3faq_listselected',
            ],
            $mapping
        );
    }

    /**
     * @return array<string, array{int, string}>
     */
    public static function migratedContentProvider(): array
    {
        return [
            'list' => [1, 't3faq_list'],
            'selected' => [2, 't3faq_listselected'],
            'hidden list' => [3, 't3faq_list'],
            'deleted selected' => [4, 't3faq_listselected'],
        ];
    }

    #[DataProvider('migratedContentProvider')]
    public function testContentRecordsAreMigrated(int $uid, string $expectedCType): void
    {
        $this->importContentFixture();

        self::assertTrue($this->subject->updateNecessary());
        self::assertTrue($this->subject->executeUpdate());

        $row = $this->fetchRow('tt_content', $uid);
        self::assertSame($expectedCType, $row['CType']);
        self::assertSame('', $row['list_type']);
    }

    public function testOtherContentRecordsStayUntouched(): void
    {
        $this->importContentFixture();

        $this->subject->executeUpdate();

        $other = $this->fetchRow('tt_content', 5);
        self::assertSame('list', $other['CType']);
        self::assertSame('other_plugin', $other['list_type']);
        $otherExtension = $this->fetchRow('tt_content', 6);
        self::assertSame('list', $otherExtension['CType']);
        self::assertSame('cpsauthor_list', $otherExtension['list_type']);
        self::assertSame('t3faq_list', $this->fetchRow('tt_content', 7)['CType']);
        self::assertSame('text', $this->fetchRow('tt_content', 8)['CType']);
    }

    public function testBackendGroupPermissionsAreMigrated(): void
    {
        $this->importContentFixture();

        $this->subject->executeUpdate();

        $permissions = explode(',', $this->fetchRow('be_groups', 1)['explicit_allowdeny']);
        sort($permissions);
        self::assertSame(
            ['tt_content:CType:t3faq_list', 'tt_content:CType:text', 'tt_content:list_type:other_plugin'],
            $permissions
        );
        self::assertSame('tt_content:CType:t3faq_listselected', $this->fetchRow('be_groups', 2)['explicit_allowdeny']);
        self::assertSame('tt_content:CType:t3faq_list', $this->fetchRow('be_groups', 3)['explicit_allowdeny']);
    }

    public function testSecondRunChangesNothing(): void
    {
        $this->importContentFixture();
        $this->subject->executeUpdate();
        $contentAfterFirstRun = $this->fetchAll('tt_content');
        $groupsAfterFirstRun = $this->fetchAll('be_groups');

        self::assertFalse($this->subject->updateNecessary());
        self::assertTrue($this->subject->executeUpdate());
        self::assertSame($contentAfterFirstRun, $this->fetchAll('tt_content'));
        self::assertSame($groupsAfterFirstRun, $this->fetchAll('be_groups'));
    }

    public function testLegacyGroupFormatSkipsPermissionsButMigratesContent(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/ListTypeToCTypeLegacyGroup.csv');

        $this->subject->executeUpdate();

        self::assertSame('t3faq_list', $this->fetchRow('tt_content', 1)['CType']);
        self::assertSame('tt_content:list_type:t3faq_list:ALLOW', $this->fetchRow('be_groups', 1)['explicit_allowdeny']);
        self::assertSame('tt_content:list_type:t3faq_listselected', $this->fetchRow('be_groups', 2)['explicit_allowdeny']);
    }

    /**
     * Hidden and deleted rows are inserted directly, the CSV import does not keep them
     */
    private function importContentFixture(): void
    {
        $this->importCSVDataSet(__DIR__ . '/Fixtures/ListTypeToCType.csv');
        $connection = $this->getConnectionPool()->getConnectionForTable('tt_content');
        $connection->insert('tt_content', ['uid' => 3, 'pid' => 1, 'CType' => 'list', 'list_type' => 't3faq_list', 'header' => 'hidden faq list', 'hidden' => 1]);
        $connection->insert('tt_content', ['uid' => 4, 'pid' => 1, 'CType' => 'list', 'list_type' => 't3faq_listselected', 'header' => 'deleted faq selected', 'deleted' => 1]);
    }

    /**
     * @return array<string, mixed>
     */
    private function fetchRow(string $table, int $uid): array
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();
        $row = $queryBuilder
            ->select('*')
            ->from($table)
            ->where($queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($uid, Connection::PARAM_INT)))
            ->executeQuery()
            ->fetchAssociative();
        self::assertIsArray($row);

        return $row;
    }

    /**
     * @return list<array<string, mixed>>
     */
    private function fetchAll(string $table): array
    {
        $queryBuilder = $this->getConnectionPool()->getQueryBuilderForTable($table);
        $queryBuilder->getRestrictions()->removeAll();

        return $queryBuilder->select('*')->from($table)->orderBy('uid')->executeQuery()->fetchAllAssociative();
    }
}
