<?php

return [
    'register'=>[
        'name'=>[
            'required'=>'El nombre del contacto es requerido.',
            'string'=>'El nombre debe ser una cadena de texto.',
        ],
        'email'=>[
            'required'=>'El correo electronico es requerido.',
            'email'=>'El campo debe ser un correo electronico valido.',
            'string'=>'El correo electronico debe ser una cadena de texto valida.',
        ],
        'last_name'=>[
            'required'=>'Los apellidos son requeridos.',
            'string'=>'Los apellidos debe ser una cadena de texto.'
        ],
        'phone'=>[
            'required'=>'El telefono es requerido.',
            'numeric'=>'Debe ingresar un numero de telefono valido.'
        ],
        'message'=>[
            'string'=>'El mensaje debe ser una cadena de texto valida.'
        ],
    ],
];