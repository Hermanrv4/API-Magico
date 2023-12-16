<?php

return[
    'register'=>[
        'root_category_id'=>[
            'required'=>'Debe seleccionar una categoría válida.',
            'exists'=>'La categoría no existe.',
            'not_in'=>'La categoría no puede ser la misma que se edita.',
        ],
        'code'=>[
            'required'=>'Debe ingresar un código de válido.',
            'between'=>'El código de la categoría debe tener entre :min y :max',
            'unique'=>'El código ingresado ya existe.',
        ],
        'name'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'between'=>'El nombre de la categoría debe tener entre :min y :max',
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
            'exists_root_categories'=>'La categoría tiene otras categorías a cargo.',
            'exist_categoryspecification'=>'La categoría a eliminar esta asignado a especificaciones.',
            'exist_productgroup'=>'La categoría a eliminar esta asignado a un grupo de productos.',
            'result'=>[
                'success'=>'Categoría eliminada exitosamente.',
                'error'=>'No hemos podido eliminar la categoría. Por favor, revise los siguientes mensajes.',
            ]
        ]
    ],
];