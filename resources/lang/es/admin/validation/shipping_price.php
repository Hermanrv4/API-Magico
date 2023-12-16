<?php

return[
    'register'=>[
        'ubication_id'=>[
            'required'=>'Debe ingresar un código válido.',
            'exists'=>'La ubicación seleccionada no existe.',
        ],
        'currency_id'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'exists'=>'La moneda seleccionada no existe.',
            'unique'=>'Ya se encuentra registrado la ubicación con la moneda seleccionada.',
        ],
        'price'=>[
            'required'=>'El precio es requirido.',
            'numeric'=>'El precio debe ser numérico.',
        ],
        'min_days'=>[
            'required'=>'Debe ingresar un mínimo de días válido.',
        ],
        'is_static'=>[
            'required'=>'El campo es requerido.',
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
        ],
        'form'=>[
            
            'result'=>[
                'success'=>"Precio de envío registrado exitosamente.",
                'error'=>"No hemos podido registrar el precio de envío. Por favor, revise los siguientes mensajes."
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
                'success'=>'Precio de envío eliminado exitosamente.',
                'error'=>'No hemos podido eliminar el precio de envío. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];