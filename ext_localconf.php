<?php
defined('TYPO3') or die();

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'Booking',
            'Reservation',
            [
				\WSR\Booking\Controller\BookobjectController::class => 'calendarBase, showMonth, showWeek, showBookingForm, insertBooking, deleteBooking'
            ],
            // non-cacheable actions
            [
				\WSR\Booking\Controller\BookobjectController::class => 'calendarBase, showMonth, showWeek, showBookingForm, insertBooking, deleteBooking'
            ]
        );

/*
        $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
		
			$iconRegistry->registerIcon(
				'booking-plugin-reservation',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:booking/Resources/Public/Icons/user_plugin_reservation.svg']
			);
*/		
    }

    );
