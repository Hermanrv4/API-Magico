<?php

return[
    'register'=>[
        'parameter_type_id'=>[
            'required'=>'Debe indicar una tipo de parámetro válida.',
            'exists'=>'El tipo de parámetro seleccionada no existe.',
        ],
        'code'=>[
            'required'=>'Debe indicar un código válido.',
            'exists'=>'El código no existe.',
        ],
        'value'=>[
            'required'=>'Debe ingresar un valor válido.',
            'unique'=>'El valor ingresado ya existe.',
        ],
        'is_json'=>[
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Parámetro registrado exitosamente.",
                'error'=>"No hemos podido registrar el parametro. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar un ID de parámetro  a eliminar.',
            'exists'=>'El ID a eliminar no existe.',
        ],
        'form'=>[            
            'result'=>[
                'success'=>'Parámetro eliminado exitosamente.',
                'error'=>'No hemos podido eliminar el parámetro. Por favor, revise los siguientes mensajes.',
            ]
        ]
    ],
];