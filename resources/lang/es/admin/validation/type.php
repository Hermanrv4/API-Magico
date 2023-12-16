<?php

return[
    'register'=>[
        'type_group_id'=>[
            'required'=>'Debe seleccionar un grupo vàlido.',
            'exists'=>'El grupo seleccionado no existe.',
        ],
        'code'=>[
            'required'=>'Debe ingresar un código de tipo.',
            'between'=>'El código debe tener entre :min y :max carácteres.',
            'unique'=>'El código del tipo ya existe.',
        ],
        'name'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'between'=>'El tipo debe tener entre :min y :max carácteres.',
            'unique'=>'El nombre ya existe.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Tipo registrado exitosamente.",
                'error'=>"No hemos podido registrar el tipo. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar un ID de tipo a eliminar.',
            'exists'=>'El ID a eliminar no existe.',
        ],
        'forms'=>[
            'result'=>[
                'success'=>'Tipo eliminado exitosamente.',
                'error'=>'No hemos podido eliminar el tipo. Por favor, revise los siguientes mensajes.',
            ],
        ],
    ],
];