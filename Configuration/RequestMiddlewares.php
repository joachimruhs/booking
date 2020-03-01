<?php


return [
    'frontend' => [
        'typo3/cms-frontend/eid' => [
            'disabled' => true,
        ],		
        'wsr/booking/booking-utilities' => [
			'disabled' => false,
            'target' => \WSR\Booking\Middleware\BookingUtilities::class,
            'before' => [
			],
            'after' => [
				'typo3/cms-frontend/tsfe'
            ],
        ],
    ]
];

