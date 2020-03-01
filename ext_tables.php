<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
            'WSR.Booking',
            'Reservation',
            'Booking (Reservation)'
        );

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile('booking', 'Configuration/TypoScript', 'Booking');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_booking_domain_model_bookobject', 'EXT:booking/Resources/Private/Language/locallang_csh_tx_booking_domain_model_bookobject.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_booking_domain_model_bookobject');

        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addLLrefForTCAdescr('tx_booking_domain_model_book', 'EXT:booking/Resources/Private/Language/locallang_csh_tx_booking_domain_model_book.xlf');
        \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::allowTableOnStandardPages('tx_booking_domain_model_book');

    }
);
