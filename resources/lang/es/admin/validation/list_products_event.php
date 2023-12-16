<?php

return[
    'register'=>[
        'event_id'=>[
            'required'=>'Debe seleccionar un evento válido',
            'exists'=>'El evento seleccionado no existe.',
        ],
        'product_id'=>[
            'required'=>'Debe seleccionar un producto válido',
            'exists'=>'El producto seleccionado no existe.',
        ],
        'quantity'=>[
            'numeric'=>'La cantidad debe ser numérica.',
        ],
        'quantity_acumulated'=>[
            'numeric'=>'La cantidad acumulada debe ser numérica.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Producto registrado exitosamente.",
                'error'=>"No hemos podido registrar el producto. Por favor, revise los siguientes mensajes."
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
                'success'=>'Producto eliminado exitosamente.',
                'error'=>'No hemos podido eliminar el producto. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];