<?php


return [
    'frontend' => [
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

