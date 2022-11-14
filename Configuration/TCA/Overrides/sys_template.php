<?php
defined('TYPO3') || die();

call_user_func(function () {
    /**
     * Extension key
     */
    $extensionKey = 'booking';

   /**
    * Add default TypoScript (constants and setup)
    */
   \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addStaticFile(
       $extensionKey,
       'Configuration/TypoScript',
       'Reservation'
   );
});