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

use Doctrine\DBAL\Query\QueryBuilder;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use Cpsit\T3faq\Domain\Model\Question;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Mapper\DataMapper;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

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

    /**
     * @param DataMapper $dataMapper
     */
    public function injectDataMapper(DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    protected function getDemandConstraints(QueryInterface $query, DemandInterface $demand): array
    {
        $constraints = [];
        if (!empty($pages = $demand->getPageIds())) {
            $query->getQuerySettings()
                ->setRespectStoragePage(true)
                ->setStoragePageIds($pages);
        }

        if (!empty($categories = $demand->getCategoryIds())) {
            foreach ($categories as $category) {
                $categoryConstraint[] = $query->contains('categories', $category);
            }
            if (!empty($categoryConstraint)) {
                $constraints[] = $query->logicalOr($categoryConstraint);
            }
        }

        return $constraints;
    }

    public function findByCategory(int $category): QueryResultInterface
    {
        /** @var Query $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $query->matching($query->equals('categories.uid', $category));
        return $query->execute();
    }

    /**
     * Returns all matching records for the given list of uids and applies the uidList sorting for the result
     *
     * @param int[] $uidList
     * @return array|\TYPO3\CMS\Extbase\Persistence\QueryResultInterface
     * @throws \Doctrine\DBAL\Driver\Exception
     * @throws \Doctrine\DBAL\Exception
     */
    public function findByUidList(array $uidList): array
    {
        if (empty($uidList)) {
            return [];
        }

        /** @var QueryBuilder $queryBuilder */
        $queryBuilder = GeneralUtility::makeInstance(ConnectionPool::class)
            ->getQueryBuilderForTable(Question::TABLE_NAME);

        $rows = $queryBuilder
            ->select('*')
            ->from(Question::TABLE_NAME)
            ->where($queryBuilder->expr()->in(Question::FIELD_UID, $uidList))
            ->add('orderBy',
                'FIELD(' . Question::TABLE_NAME . '.' . Question::FIELD_UID . ',' . implode(',', $uidList) . ')')
            ->execute()
            ->fetchAllAssociative();

        return $this->dataMapper->map(Question::class, $rows);
    }
}
