<?php
namespace WSR\Booking\Domain\Repository;

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Core\Database\ConnectionPool;
use TYPO3\CMS\Core\Database\Query\QueryBuilder;
use TYPO3\CMS\Extbase\Persistence\Generic\Query;
use TYPO3\CMS\Extbase\Persistence\QueryInterface;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

use TYPO3\CMS\Core\Database\Query\Restriction\HiddenRestriction;

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
	 *	@param int $bookobjectUid | null
	 *
	 *	@return array
	 */	
	function getBookingsOfDate($pid, $day, $bookobjectUid) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_book');

		$queryBuilder->getRestrictions()
		->removeByType(HiddenRestriction::class);
			
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
		);
		if ($bookobjectUid) {
			$queryBuilder->andWhere($queryBuilder->expr()->andX(
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
		} else {
			$queryBuilder->andWhere($queryBuilder->expr()->andX(
				$queryBuilder->expr()->gte(
					'startdate', $queryBuilder->createNamedParameter($day, \PDO::PARAM_INT)
				),
				$queryBuilder->expr()->lte(
					'enddate', $queryBuilder->createNamedParameter(($day + 86400), \PDO::PARAM_INT)
				)
				
			)
			);

		}

		$result = $queryBuilder->execute()->fetchAll();
		return $result;

	}


	/*
	 *	get bookings for a date AM
	 *	
	 *  
	 *	@param int $pid
	 *	@param int $day
	 *
	 *	@return array
	 */	
	function getBookingsOfDateAM($pid, $day) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_book');

		$queryBuilder->getRestrictions()
		->removeByType(HiddenRestriction::class);

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
		);
		$queryBuilder->andWhere($queryBuilder->expr()->andX(
			$queryBuilder->expr()->gte(
					'startdate', $queryBuilder->createNamedParameter($day, \PDO::PARAM_INT)
				),

				$queryBuilder->expr()->lte(
					'enddate', $queryBuilder->createNamedParameter($day + 43200, \PDO::PARAM_INT)
				)
				
			)
		);
		$result = $queryBuilder->execute()->fetchAll();
		return $result;
	}


	/*
	 *	get bookings for a date PM
	 *	
	 *  
	 *	@param int $pid
	 *	@param int $day
	 *
	 *	@return array
	 */	
	function getBookingsOfDatePM($pid, $day) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_book');

		$queryBuilder->getRestrictions()
		->removeByType(HiddenRestriction::class);

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
		);
		$queryBuilder->andWhere($queryBuilder->expr()->andX(
			$queryBuilder->expr()->gte(
					'startdate', $queryBuilder->createNamedParameter($day + 43200, \PDO::PARAM_INT)
				),
				$queryBuilder->expr()->lte(
					'enddate', $queryBuilder->createNamedParameter(($day + 86400), \PDO::PARAM_INT)
				)
				
			)
		);
		$result = $queryBuilder->execute()->fetchAll();
		return $result;
	}



	/*
	 *	get booking for a startdate and bookobjectUid
	 *	
	 *  
	 *	@param int $pid
	 *	@param int $bookobjectUid
	 *	@param int $startdate
	 *  
	 *	@return array
	 */	
	function getBookingOfDateAndBookobject($pid, $bookobjectUid, $startdate) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_book');
		$queryBuilder->from('tx_booking_domain_model_book', 'a');
		$queryBuilder->select('a.*')

		->where(
			$queryBuilder->expr()->eq(
				'a.pid',
				$queryBuilder->createNamedParameter($pid, \PDO::PARAM_INT)
			)
		);
		$queryBuilder->andWhere($queryBuilder->expr()->andX(
			$queryBuilder->expr()->eq(
					'startdate', $queryBuilder->createNamedParameter($startdate, \PDO::PARAM_INT)
				),
			$queryBuilder->expr()->eq(
					'objectuid', $queryBuilder->createNamedParameter($bookobjectUid, \PDO::PARAM_INT)
				)
			
			)
		);
		$result = $queryBuilder->execute()->fetchAll();
		return $result;
	}






	/*
	 *	insert a booking
	 *
	 * @param int $pid
	 *	@param int $bookobjectUid
	 *	@param int $stardate
	 *	@param int $enddate
	 *	@param int $feUserUid
	 *	@param string $memo
	 *
	 *	@return array
	 */	
	function insertBooking($pid, $bookobjectUid, $startdate, $enddate, $feUserUid, $memo) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('tx_boooking_domain_model_book');
		$queryBuilder->insert('tx_booking_domain_model_book')
		->values([
			'pid' => $pid,
			'crdate' => time(),
			'tstamp' => time(),
			'cruser_id' => $feUserUid,
			'feuseruid' => $feUserUid,
			'objectuid' => $bookobjectUid,
			'startdate' => $startdate,
			'enddate' => $enddate,
			'memo' => $memo,
		
		])
		->execute();
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
		->set('deleted', 1)
		->execute();
	}
	

}
