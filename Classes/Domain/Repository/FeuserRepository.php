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
 *  (c) 2020 - 2022 Joachim Ruhs <postmaster@joachim-ruhs.de>, Web Services Ruhs
 *
 ***/
/**
 * The repository for Books
 */
class FeuserRepository extends \TYPO3\CMS\Extbase\Persistence\Repository
{
	/*
	 *	get FE user
	 *
	 *	@param int $uid
	 *
	 *	@return array
	 */	
	function findByUidOverride($uid) {
		$queryBuilder = GeneralUtility::makeInstance(\TYPO3\CMS\Core\Database\ConnectionPool::class)
			->getQueryBuilderForTable('fe_users');

        $queryBuilder->select('*')
		->from('fe_users')
		->where(
			$queryBuilder->expr()->eq(
				'uid',
				$queryBuilder->createNamedParameter($uid, \PDO::PARAM_INT)
			)
		);			
		$result = $queryBuilder->execute()->fetchAll();
    	return $result[0];		
	}


	

}
