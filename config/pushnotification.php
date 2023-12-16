<?php
/**
 * @see https://github.com/Edujugon/PushNotification
 */

return [
    'gcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AIzaSyB3owSLcgeFLYnkb5uxknCf0roIyh5mUpA',
    ],
    'fcm' => [
        'priority' => 'normal',
        'dry_run' => false,
        'apiKey' => 'AIzaSyB3owSLcgeFLYnkb5uxknCf0roIyh5mUpA',
    ],
    'apn' => [
        'certificate' => __DIR__ . '/iosCertificates/pushcert.pem',
        'passPhrase' => 'secret', //Optional
        'passFile' => __DIR__ . '/iosCertificates/pushcert.pem', //Optional
        'dry_run' => true,
    ],
];
