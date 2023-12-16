<?php

return[
    'register'=>[
        'user_id'=>[
            'exists'=>'El usuario no existe.',
        ],
        'name'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'max'=>'El nombre debe tener como máximo :max carácteres.',
        ],
        'description'=>[
            'required'=>'Debe ingresar una descripción válida.',
            'between'=>'El nombre debe tener entre :min y :max carácteres.',
            'unique'=>'El nombre ya esxiste.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Evento registrado exitosamente.",
                'error'=>"No hemos podido registrar el evento. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar ID de moneda a eliminar',
            'exists'=>'El ID a eliminar no existe',
        ],
        'form'=>[
            'result'=>[
                'success'=>'Evento eliminado exitosamente.',
                'error'=>'No hemos podido eliminar el evento. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];