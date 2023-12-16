<?php

return[
    'register'=>[
        'email'=>[
            'required'=>'Debe ingresar un código válido.',
            'email'=>'El correo ingresado es inválido',
        ],
        'first_name' => [
            'required' => 'Debe ingresar unos nombres válido.',
            'string' => 'Los nombres debe ser una cadena de caracteres.',
            'between' => 'Los nombres deben tener entre :min y :max caracteres.',
        ],
        'last_name' => [
            'required' => 'Debe ingresar unos apellidos válido.',
            'string' => 'Los apellidos debe ser una cadena de caracteres.',
            'between' => 'Los apellidos deben tener entre :min y :max caracteres.',
        ],
        'phone' => [
            'required' => 'Debe ingresar un teléfono válido.',
            'digits_between' => 'El teléfono debe tener entre :min y :max caracteres.',
        ],
        'message'=>[
            'required'=>'Debe ingresar un mensaje válido',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Contacto registrado exitosamente.",
                'error'=>"No hemos podido registrar el contacto. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar ID de contacto a eliminar',
            'exists'=>'El ID a eliminar no existe',
        ],
        'form'=>[
            'result'=>[
                'success'=>'Contacto eliminado exitosamente.',
                'error'=>'No hemos podido eliminar el contacto. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];