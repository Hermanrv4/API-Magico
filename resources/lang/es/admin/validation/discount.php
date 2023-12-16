<?php
return[
    'register'=>[
        'name'=>[
            'required'=>'El nombre es requerido.',
            'exists'=>'El codigo de categoria no existe.',
        ],
        'code'=>[
            'required'=>'El codigo es requerido.',
            'unique'=>'El codigo del descuento // cupon ya existe.',
        ],
        'description'=>[
            'required'=>'La descripcion es requerido.',
        ],
        'value'=>[
            'required'=>'El valor del descuento o promocion es requerido.',
        ],
        'currency_id'=>[
            'required'=>'Debe seleccionar alguna moneda.',
        ],
        'date_start'=>[
            'required'=>'La fecha de inicio es requerida.'
        ],
        'date_end'=>[
            'required'=>'La fecha de vencimiento es requerida.'
        ],
        'id_type_discounts'=>[
            'required'=>'El tipo de descuento es requerido.',
            'exists'=>'El codigo seleccionado debe existir'
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