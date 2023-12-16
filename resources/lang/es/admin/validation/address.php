<?php

return[
    'register'=>[
        'user_id'=>[
            'required'=>'Debe ingresar un usuario válido.',
            'exists'=>'El usuario seleccionado no existe.',
        ],
        'wish_list_id'=>[
            'exists'=>'El lista de deseos seleccionada no existe.',
        ],
        'ubications_id'=>[
            'required'=>'Debe ingresar una ubicación válida.',
            'exists'=>'La ubicación seleccionada no esxiste.',
        ],
        'address'=>[
            'required'=>'Debe ingresar una dirección válida.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Dirección registrada exitosamente.",
                'error'=>"No hemos podido registrar la dirección. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar ID de dirección a eliminar',
            'exists'=>'El ID a eliminar no existe',
        ],
        'form'=>[
            'exist_billing_address'=>'La dirección se encuentra registro en ordenes de compra.',
            'exist_shipping_address'=>'La dirección se encuentra registro en ordenes de compra.',
            'result'=>[
                'success'=>'Dirección eliminada exitosamente.',
                'error'=>'No hemos podido eliminar la dirección. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];