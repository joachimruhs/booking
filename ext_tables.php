<?php
defined('TYPO3') or die();

call_user_func(
    function()
    {
		/**
		 * Register icons
		 */

		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
		$iconRegistry->registerIcon(
			'extension-booking-reservation',
			\TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
//			['source' => 'EXT:myleaflet/Resources/Public/Icons/contentElementIcon.png']
			['source' => 'EXT:booking/Resources/Public/Icons/ext_booking_icon.png']
		);
    }
);




