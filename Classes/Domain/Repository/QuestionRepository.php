<?php

declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Cpsit\T3faq\Domain\Repository;

use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Question;
use Doctrine\DBAL\ArrayParameterType;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * @extends AbstractRepository<Question>
 */
final class QuestionRepository extends AbstractRepository
{
    protected $defaultOrderings = [
        Question::FIELD_WEIGHT => Query::ORDER_DESCENDING,
        Question::FIELD_SORTING => Query::ORDER_ASCENDING,
    ];

    /**
     * Datamaper
     *
     * @var DataMapper
     */
    protected $dataMapper;

    public function __construct(
        DataMapper $dataMapper,
        private readonly ConnectionPool $connectionPool
    ) {
        parent::__construct();
        $this->dataMapper = $dataMapper;
    }

    /**
     * @param QueryInterface<Question> $query
     * @return array<int, ConstraintInterface>
     */
    protected function getDemandConstraints(QueryInterface $query, DemandInterface $demand): array
    {
        $constraints = [];
        if (!empty($pages = $demand->getPageIds())) {
            $query->getQuerySettings()
                ->setRespectStoragePage(true)
                ->setStoragePageIds($pages);
        }

        if (!empty($categories = $demand->getCategoryIds())) {
            $categoryConstraint = [];
            foreach ($categories as $category) {
                $categoryConstraint[] = $query->contains('categories', $category);
            }
            $constraints[] = $query->logicalOr(...$categoryConstraint);
        }

        return $constraints;
    }

    /**
     * @return QueryResultInterface<int, Question>
     */
    public function findByCategory(int $category): QueryResultInterface
    {
        /** @var Query<Question> $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching($query->equals('categories.uid', $category));
        return $query->execute();
    }

    /**
     * Returns all matching records for the given list of uids and applies the uidList sorting for the result
     *
     * @param int[] $uidList
     * @return array<int, object>
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByUidList(array $uidList): array
    {
        if (empty($uidList)) {
            return [];
        }

        $queryBuilder = $this->connectionPool
            ->getQueryBuilderForTable(Question::TABLE_NAME);

        $rows = $queryBuilder
            ->select('*')
            ->from(Question::TABLE_NAME)
            ->where($queryBuilder->expr()->in(
                Question::FIELD_UID,
                $queryBuilder->createNamedParameter($uidList, ArrayParameterType::INTEGER)
            ))
            ->executeQuery()
            ->fetchAllAssociative();

        $position = array_flip(array_map('intval', $uidList));
        usort(
            $rows,
            static fn(array $a, array $b): int => $position[(int)$a[Question::FIELD_UID]] <=> $position[(int)$b[Question::FIELD_UID]]
        );

        return $this->dataMapper->map(Question::class, $rows);
    }
}
