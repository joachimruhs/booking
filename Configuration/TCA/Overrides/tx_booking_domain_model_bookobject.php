<?php
defined('TYPO3') or die();

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('booking', 'Configuration/TypoScript', 'Booking');

\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_booking_domain_model_bookobject', 'EXT:booking/Resources/Private/Language/locallang_csh_tx_booking_domain_model_bookobject.xlf');
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_booking_domain_model_bookobject');

