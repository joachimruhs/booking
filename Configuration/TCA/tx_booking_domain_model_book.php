<?php
return [
    'ctrl' => [
        'title' => 'LLL:EXT:booking/Resources/Private/Language/locallang_db.xlf:tx_booking_domain_model_book',
        'label' => 'objectuid',
        'tstamp' => 'tstamp',
        'crdate' => 'crdate',
        'cruser_id' => 'cruser_id',
        'delete' => 'deleted',
        'enablecolumns' => [
            'disabled' => 'hidden',
            'starttime' => 'starttime',
            'endtime' => 'endtime',
        ],
        'searchFields' => 'feuseruid,memo',
        'iconfile' => 'EXT:booking/Resources/Public/Icons/tx_booking_domain_model_book.gif'
    ],
    'interface' => [
        'showRecordFieldList' => 'hidden, objectuid, startdate, enddate, feuseruid, memo',
    ],
    'types' => [
        '1' => ['showitem' => 'hidden, objectuid, startdate, enddate, feuseruid, memo, --div--;LLL:EXT:frontend/Resources/Private/Language/locallang_ttc.xlf:tabs.access, starttime, endtime'],
    ],
    'columns' => [
        'hidden' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.visible',
            'config' => [
                'type' => 'check',
                'renderType' => 'checkboxToggle',
                'items' => [
                    [
                        0 => '',
                        1 => '',
                        'invertStateDisplay' => true
                    ]
                ],
            ],
        ],
        'starttime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.starttime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],
        'endtime' => [
            'exclude' => true,
            'label' => 'LLL:EXT:core/Resources/Private/Language/locallang_general.xlf:LGL.endtime',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime,int',
                'default' => 0,
                'range' => [
                    'upper' => mktime(0, 0, 0, 1, 1, 2038)
                ],
                'behaviour' => [
                    'allowLanguageSynchronization' => true
                ]
            ],
        ],

        'objectuid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang_db.xlf:tx_booking_domain_model_book.objectuid',
            'config' => [
                'type' => 'select',
                'renderType' => 'selectSingle',
                'default' => 0,
                'items' => [
                    ['', 0],
                ],
                'foreign_table' => 'tx_booking_domain_model_bookobject',
                'foreign_table_where' => 'AND tx_booking_domain_model_bookobject.pid=###CURRENT_PID###',
            ],
        ],
        'startdate' => [
            'exclude' => true,
            'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang_db.xlf:tx_booking_domain_model_book.startdate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ]
        ],
        'enddate' => [
            'exclude' => true,
            'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang_db.xlf:tx_booking_domain_model_book.enddate',
            'config' => [
                'type' => 'input',
                'renderType' => 'inputDateTime',
                'eval' => 'datetime',
            ]
        ],
        'feuseruid' => [
            'exclude' => true,
            'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang_db.xlf:tx_booking_domain_model_book.feuseruid',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
        'memo' => [
            'exclude' => true,
            'label' => 'LLL:EXT:booking/Resources/Private/Language/locallang_db.xlf:tx_booking_domain_model_book.memo',
            'config' => [
                'type' => 'input',
                'size' => 30,
                'eval' => 'trim'
            ],
        ],
    
    ],
];
