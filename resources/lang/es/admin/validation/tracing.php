<?php

return[
    'query'=>[
        'result_column'=>[
            'full_name'=>'Nombres y Apellidos',
            'sendobj'=>'Objeto de interaccion',
            'page_title'=>'Pagin web',
            'value'=>'Lugar de interaccion',
            'id_user'=>'Id del usuario',
            'action'=>[
                'load'=>'Pagina Web Cargada',
                'click'=>'Click en una seccion de la web',
            ],
            'object'=>[
                'addCard'=>'El usuario agrego un producto al carrito de compras',
                'show'=>'El usuario visito la pagina web',
                'preview'=>'El usuario Previsualizo un producto',
            ],
            'section'=>'Seccion web',
            'created_at'=>'Fecha y hora de interaccion',
        ],
    ],
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