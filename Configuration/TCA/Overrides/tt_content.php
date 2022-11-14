<?php
defined('TYPO3') or die();


\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'Booking',
    'Reservation',
    'Booking (Reservation)'
);



$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_excludelist']['booking_reservation'] = 'recursive,select_key,pages';


$GLOBALS['TCA']['tt_content']['types']['list']['subtypes_addlist']['booking_reservation'] = 'pi_flexform';
\TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPiFlexFormValue(
    'booking_reservation',
    'FILE:EXT:booking/Configuration/FlexForms/Booking.xml'
);