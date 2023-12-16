<?php

return[
    'register'=>[
        'order_id'=>[
            'required'=>'Debe indicar una orden válida.',
            'exists'=>'La orden seleccionada no existe.',
        ],
        'product_id'=>[
            'required'=>'Debe indicar un producto válido.',
            'exists'=>'El producto seleccionado no existe.',
        ],
        'quantity'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'unique'=>'El nombre ingresao ya existe.',
        ],
        'price'=>[
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