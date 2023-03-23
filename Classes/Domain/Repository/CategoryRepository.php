<?php
declare(strict_types=1);

/*
 * This file is part of the t3faq project.
 *
 * It is free software; you can redistribute it and/or modify it under
 * the terms of the GNU General Public License, either version 2
 * of the License, or any later version.
 */

namespace Fr\T3faq\Domain\Repository;

use Fr\T3faq\Domain\Model\Category;
use Fr\T3faq\Domain\Model\Dto\DemandInterface;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class CategoryRepository extends AbstractRepository
{
    protected $defaultOrderings = [
        Category::FIELD_SORTING => QueryInterface::ORDER_ASCENDING
    ];

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

    public function findByPidList(array $pidList, string $sorting): QueryResultInterface
    {
        /** @var Query $query */
        $query = $this->createQuery();
        $query->getQuerySettings()->setRespectStoragePage(false);

        $constraints[] = $query->in('pid', $pidList);

        if (!empty($orderings = $this->getOrderings($sorting))) {
            $query->setOrderings($orderings);
        }

        if (!empty($constraints)) {
            $query->matching(
                $query->logicalAnd($constraints)
            );
        }
        return $query->execute();
    }
}
