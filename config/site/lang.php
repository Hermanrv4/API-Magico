<?php
$type = "site";
return [
    'validation' => [
        'user' => $type.'/validation/user.',
        'order' => $type.'/validation/order.',
        'contact'=>$type.'/validation/contact.',
    ],
];
