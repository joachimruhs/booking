<?php
defined('TYPO3') or die();


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Booking',
    'Reservation',
    'Booking (Reservation)'
);

$GLOBALS['TCA']['tt_content']['types']['booking_reservation']['showitem'] = 'pi_flexform';


\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    '*',
    'FILE:EXT:booking/Configuration/FlexForms/Booking.xml',
    'booking_reservation',
);

/*
$GLOBALS['TCA']['tt_content']['types']['booking_reservation']['columnsOverrides']
    ['pi_flexform']['config']['ds'] =
    'FILE:EXT:my_extension/Configuration/FlexForms/Booking.xml';
*/
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addToAllTCAtypes(
    'tt_content',
    '--div--;Configuration,pi_flexform,pages,recursive,',
    'booking_reservation',
    'after:subheader',
);

