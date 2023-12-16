<?php

return[
    'register'=>[
        'code'=>[
            'required'=>'Debe ingresar un código válido.',
            'between'=>'El código debe tener entre :min y :max carácteres.',
            'unique'=>'El código ya existe.',
        ],
        'name'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'unique'=>'El nombre ya existe.',
        ],
        'is_preview'=>[
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
        ],
        'is_color'=>[
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
        ],
        'is_html'=>[
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
        ],
        'is_image'=>[
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
        ],
        'is_globalfilter'=>[
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
        ],
        'needs_user_info'=>[
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Especificación registrada exitosamente.",
                'error'=>"No hemos podido registrar la especificación. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar ID de especificación a eliminar',
            'exists'=>'El ID a eliminar no existe',
        ],
        'form'=>[
            'exists_categoryspecification'=>'La especificación a eliminar se encuentra asigandos en categorias.',
            'exists_productspecification'=>'La especificación a eliminar se encuentra asignados en productos.',
            'result'=>[
                'success'=>'Especificación eliminado exitosamente.',
                'error'=>'No hemos podido eliminar la especificación. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];