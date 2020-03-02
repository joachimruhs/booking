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
 * The repository for Books
 */
class BookRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
	/*
	 *	get counts of bookings for a date and bookobject
	 *
	 *	@param int $pid
	 *	@param int $objectUid
	 *	@param int $date
	 *
	 *	@return int
	 */	
	function getBookingCounts($pid, $objectUid, $day) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_book');
		$queryBuilder->from('tx_booking_domain_model_book');
		$queryBuilder->count('uid')->from('tx_booking_domain_model_book')

		->where(
			$queryBuilder->expr()->eq(
				'pid',
				$queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT)
			)
		)			
		->andWhere($queryBuilder->expr()->andX(
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->eq('objectuid', $queryBuilder->createNamedParameter($objectUid, \PDO::PARAM_INT))
				),
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->gte('startdate', $queryBuilder->createNamedParameter($day, \PDO::PARAM_INT))
				),
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->lte('enddate', $queryBuilder->createNamedParameter(($day + 3600 * 23), \PDO::PARAM_INT))
				)
			)
		);
		$count = $queryBuilder->execute()->fetchColumn(0);
		return $count;		
	}


	/*
	 *	get bookings for a date
	 *
	 *	@param int $pid
	 *	@param int $day
	 *
	 *	@return array
	 */	
	function getBookingsOfDate($pid, $day, $bookobjectUid) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_book');
		$queryBuilder->from('tx_booking_domain_model_book', 'a');
		$queryBuilder->select('a.*', 'users.username', 'first_name', 'last_name');

		$queryBuilder->join(
			'a',
			'fe_users',
			'users',
			 $queryBuilder->expr()->eq('users.uid', $queryBuilder->quoteIdentifier('a.feuseruid'))
		)
		->where(
			$queryBuilder->expr()->eq(
				'a.pid',
				$queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT)
			)
		)			
		->andWhere($queryBuilder->expr()->andX(
			$queryBuilder->expr()->eq(
				'objectuid', $queryBuilder->createNamedParameter($bookobjectUid, \PDO::PARAM_INT)
			),
			$queryBuilder->expr()->gte(
				'startdate', $queryBuilder->createNamedParameter($day, \PDO::PARAM_INT)
			),
			$queryBuilder->expr()->lte(
				'enddate', $queryBuilder->createNamedParameter(($day + 86400), \PDO::PARAM_INT)
			)
			
		)
		);



		$result = $queryBuilder->execute()->fetchAll();
//print_r($result);		
		return $result;

	}

	/*
	 *	delete a booking
	 *
	 *	@param int $bookUid
	 *	@param int $feUserUid
	 *
	 *	@return array
	 */	
	function deleteBooking($bookUid, $feUserUid) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_book');
//		$queryBuilder->from('tx_booking_domain_model_book');
		$queryBuilder->update('tx_booking_domain_model_book')
		->andWhere($queryBuilder->expr()->andX(
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->eq('uid', $queryBuilder->createNamedParameter($bookUid, \PDO::PARAM_INT))
				),
				$queryBuilder->expr()->andX(
					$queryBuilder->expr()->eq('feuseruid', $queryBuilder->createNamedParameter($feUserUid, \PDO::PARAM_INT))
				)
			)
		)
		->set('hidden', 1)
		->execute();

//echo $bookUid;
//echo $queryBuilder->getSql();
//print_r ($queryBuilder->getParameters());
		exit;
	}
	

}
