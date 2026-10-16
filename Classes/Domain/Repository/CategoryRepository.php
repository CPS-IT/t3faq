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

use Cpsit\T3faq\Domain\Model\Category;
use Cpsit\T3faq\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Qom\ConstraintInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/**
 * @extends AbstractRepository<Category>
 */
final class CategoryRepository extends AbstractRepository
{
    protected $defaultOrderings = [
        Category::FIELD_SORTING => QueryInterface::ORDER_ASCENDING,
    ];

    /**
     * @param QueryInterface<Category> $query
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
            $constraints[] = $query->in('uid', $categories);
        }

        return $constraints;
    }

    /**
     * @param int[] $pidList
     * @return QueryResultInterface<int, Category>
     */
    public function findByPidList(array $pidList, string $sorting): QueryResultInterface
    {
        /** @var Query<Category> $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraint = $query->in('pid', $pidList);

        if (!empty($orderings = $this->getOrderings($sorting))) {
            $query->setOrderings($orderings);
        }

        $query->matching($query->logicalAnd($constraint));

        return $query->execute();
    }
}
