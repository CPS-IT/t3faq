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
use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;
use TYPO3\CMS\Extbase\Persistence\Repository;

abstract class AbstractRepository extends Repository
{
    /**
     * @param DemandInterface $demand
     * @return QueryResultInterface
     * @throws \TYPO3\CMS\Extbase\Persistence\Exception\InvalidQueryException
     */
    public function findDemanded(DemandInterface $demand): QueryResultInterface
    {
        /** @var Query $query */
        $query = $this->createQuery();

        if (!empty($constraints = $this->getDemandConstraints($query, $demand))) {
            $query->matching(
                $query->logicalAnd(...$constraints)
            );
        }

        if (!empty($orderings = $this->getDemandOrderings($query, $demand))) {
            $query->setOrderings($orderings);
        }

        return $query->execute();
    }

    /**
     * @param QueryInterface $query
     * @param DemandInterface $demand
     * @return array
     */
    public function getDemandOrderings(QueryInterface $query, DemandInterface $demand): array
    {
        return $this->getOrderings($demand->getSorting() ?? '');
    }

    protected function getOrderings(string $sorting): array
    {
        $orderings = [];
        $sorting = GeneralUtility::trimExplode(',', $sorting, true);

        if (empty($sorting)) {
            return $orderings;
        }

        foreach ($sorting as $orderItem) {
            [$orderField, $ascDesc] = GeneralUtility::trimExplode(' ', $orderItem, true);
            // count == 1 means that no direction is given
            if ($ascDesc) {
                $orderings[$orderField] = ((strtolower($ascDesc) === 'desc') ?
                    QueryInterface::ORDER_DESCENDING :
                    QueryInterface::ORDER_ASCENDING);
            } else {
                $orderings[$orderField] = QueryInterface::ORDER_ASCENDING;
            }
        }
        return $orderings;
    }
}
