<?php
return[
    'register'=>[
        'category_code'=>[
            'required'=>'El codigo de categoria debe ser requerido.',
            'exists'=>'El codigo de categoria no existe.',
        ],
        'group_id'=>[
            'required'=>'El grupo del producto es requerido.',
            'exists'=>'El grupo de producto no existe.',
        ],
        'especifications'=>[
            'required'=>'Debe ingresar un nombre válido.',
        ],
        'sku'=>[
            'unique'=>'El sku generado existe.',
        ],
        'product_group'=>[
            'required'=>'El grupo de producto debe ser requerido.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Archivo cargado de manera exitosa",
                'error'=>"No se concreto la carga de datos de manera exitosa, revisar la informacion enviada por favor."
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