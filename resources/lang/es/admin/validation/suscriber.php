<?php

return[
    'register'=>[
        'email'=>[
            'required'=>'Debe ingresar un código válido.',
            'email'=>'El correo ingresado no es válido',
            'unique'=>'El correo ingresado ya esta suscrito.',
        ],
        'info_suscriber'=>[
            'max'=>'La información de suscripción no debe ser mayor de :max carácteres.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Categoría registrada exitosamente.",
                'error'=>"No hemos podido registrar la categoría. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar ID de suscripción a eliminar',
            'exists'=>'El ID a eliminar no existe',
        ],
        'form'=>[
            'result'=>[
                'success'=>'Suscripción eliminada exitosamente.',
                'error'=>'No hemos podido eliminar la suscripción. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];