<?php
namespace WSR\Booking\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

/***
 *
 * This file is part of the "Booking" Extension for TYPO3 CMS.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2020 Joachim Ruhs <postmaster@joachim-ruhs.de>, Web Services Ruhs
 *
 ***/
/**
 * The repository for Bookobjects
 */
class BookobjectRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{

    /**
     * @var array
     */
    protected $defaultOrderings = ['sorting' => \TYPO3\CMS\Extbase\Persistence\QueryInterface::ORDER_ASCENDING];
	
	/*
	 * find all booking objects stored in $pid
	 *
	 * @param int $pid
	 *
	 * @return array
	 */
	public function findAllNew($pid) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_bookobject');
		$queryBuilder->from('tx_booking_domain_model_bookobject', 'a');
		$queryBuilder->select('*');

		$queryBuilder->where(
			$queryBuilder->expr()->eq(
				'a.pid',
				$queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT)
			)
		);
		
		$queryBuilder->andWhere(
			$queryBuilder->expr()->andX(
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->eq('hidden', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
				)
			),
			$queryBuilder->expr()->andX(
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->eq('deleted', $queryBuilder->createNamedParameter(0, \PDO::PARAM_INT))
				)
			)
		);
		$result =  $queryBuilder->execute()->fetchAll();
		return $result;		
	}


	
}
