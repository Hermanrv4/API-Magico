<?php

return[
    'register'=>[
        'category_id'=>[
            'required'=>'Debe indicar una categoría válida.',
            'exists'=>'La categoría seleccionada no existe.',
        ],
        'code'=>[
            'required'=>'Debe ingresar un código de válido.',
            'between'=>'El código de la categoría debe tener entre :min y :max',
            'unique'=>'El código ingresado ya existe.',
        ],
        'name'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'unique'=>'El nombre ingresao ya existe.',
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
            'required'=>'Debe indicar un ID de categoría a eliminar.',
            'exists'=>'El ID a eliminar no existe.',
        ],
        'form'=>[            
            'exist_product'=>'El grupo por productos a eliminar esta asignado a productos.',
            'result'=>[
                'success'=>'Categoría eliminada exitosamente.',
                'error'=>'No hemos podido eliminar la categoría. Por favor, revise los siguientes mensajes.',
            ]
        ]
    ],
];