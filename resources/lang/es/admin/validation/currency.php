<?php

return[
    'register'=>[
        'code'=>[
            'required'=>'Debe ingresar un código válido.',
            'between'=>'El código debe tener entre :min y :max carácteres.',
            'unique'=>'El código ya existe.',
        ],
        'symbol'=>[
            'required'=>'Debe ingresar un símbolo válido.',
            'between'=>'El símbolo debe tener entre :min y :max carácteres.',
            'unique'=>'El símbolo ya existe.',
        ],
        'name'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'between'=>'El nombre debe tener entre :min y :max carácteres.',
            'unique'=>'El nombre ya esxiste.',
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
            'required'=>'Debe indicar ID de moneda a eliminar',
            'exists'=>'El ID a eliminar no existe',
        ],
        'form'=>[
            'result'=>[
                'success'=>'Moneda eliminado exitosamente.',
                'error'=>'No hemos podido eliminar la moneda. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];