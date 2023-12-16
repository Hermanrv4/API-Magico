<?php

return[
    'register'=>[
        'product_id'=>[
            'required'=>'Debe ingresar un producto válido.',
            'exists'=>'El producto seleccionado no existe.',
        ],
        'specification_id'=>[
            'required'=>'Debe ingresar una especificación válida.',
            'exists'=>'La especificación seleccionada no existe.',
            'unique'=>'Ya esta especificación para este producto.',
        ],
        'value'=>[
            'required'=>'El valor es requirido.',
            'max'=>'El valor debe tener un máximo de :max carácteres.',
        ],
        'form'=>[
            
            'result'=>[
                'success'=>"Especificación asignado a producto registrado exitosamente.",
                'error'=>"No hemos podido registrar la asignación de la especificación al producto. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar ID de precio de envío a eliminar',
            'exists'=>'El ID a eliminar no existe',
        ],
        'form'=>[
            'result'=>[
                'success'=>'Especificación asignado a producto, eliminado exitosamente.',
                'error'=>'No hemos podido eliminar la especificación del producto. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];