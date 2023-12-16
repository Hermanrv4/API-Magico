<?php
return [
    'result' => [
        'message' => [
            'key' => 'message',
            'value' => [
                'default_success' => 'default_success',
                'default_error' => 'default_error',
            ],
        ],
        'response' => [
            'key' => 'response',
            'value' => [
                'default' => array()
            ],
        ],
        'status' => [
            'key' => 'status',
            'value' => [
                'success' => 'success',
                'error' => 'error',
            ],
        ],
    ],
    'db' => [
        'type_group' => [
            'states'                => 'STATES',
            'parameter'             => 'PARAMETERS',
        ],
        'type' => [
            'states' => [
                'approved'          => 'APRO',
                'pending'           => 'PEND',
                'rejected'          => 'REJE',
                'cancelled'         => 'CANC',
            ],
            'parameter' => [
                'unlocalized'       => 'UNLOCALIZED',
                'localized'         => 'LOCALIZED',
            ],
        ],
    ],
];
