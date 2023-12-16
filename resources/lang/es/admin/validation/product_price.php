<?php

return[
    'register'=>[
        'product_id'=>[
            'required'=>'Debe indicar un producto válido.',
            'exists'=>'El producto seleccionado no existe.',
        ],
        'currency_id'=>[
            'required'=>'Debe ingresar una moneda de válida.',
            'exists'=>'La moneda seleccionada no existe.',
            'unique'=>'Ya se asignado precio al producto con esta moneda.',
        ],
        'regular_price'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'numeric'=>'El Precio Regular ingresado tiene que ser numérico.',
        ],
        'online_price'=>[
            'required'=>'Debe ingresar un precio válido.',
            'numeric'=>'El Precio Online ingresado tiene que ser numérico.',
        ],
        'form'=>[            
            'result'=>[
                'success'=>"Precios asignado a producto registrado exitosamente.",
                'error'=>"No hemos podido registrar los precios a los productos. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar un ID de categoría a eliminar.',
            'exists'=>'El ID a eliminar no existe.',
        ],
        'form'=>[
            'result'=>[
                'success'=>'Precios asignados a producto eliminado exitosamente.',
                'error'=>'No hemos podido eliminar los precios. Por favor, revise los siguientes mensajes.',
            ]
        ]
    ],
];