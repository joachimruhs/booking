<?php
defined('TYPO3_MODE') || die('Access denied.');

call_user_func(
    function()
    {

        \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
            'WSR.Booking',
            'Reservation',
            [
                'Bookobject' => 'calendarBase, showMonth, showWeek, showBookingForm, insertBooking, deleteBooking'
            ],
            // non-cacheable actions
            [
                'Bookobject' => 'calendarBase, showMonth, showWeek, showBookingForm, insertBooking, deleteBooking'
            ]
        );

    // wizards
    \TYPO3\CMS\Core\Utility\ExtensionManagementUtility::addPageTSConfig(
        'mod {
            wizards.newContentElement.wizardItems.plugins {
                elements {
                    reservation {
                        iconIdentifier = booking-plugin-reservation
                        title = LLL:EXT:booking/Resources/Private/Language/locallang_db.xlf:tx_booking_reservation.name
                        description = LLL:EXT:booking/Resources/Private/Language/locallang_db.xlf:tx_booking_reservation.description
                        tt_content_defValues {
                            CType = list
                            list_type = booking_reservation
                        }
                    }
                }
                show = *
            }
       }'
    );
		$iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
		
			$iconRegistry->registerIcon(
				'booking-plugin-reservation',
				\TYPO3\CMS\Core\Imaging\IconProvider\SvgIconProvider::class,
				['source' => 'EXT:booking/Resources/Public/Icons/user_plugin_reservation.svg']
			);
		
    }
);
