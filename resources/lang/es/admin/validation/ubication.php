<?php

return[
    'register'=>[
        'root_ubication_id'=>[
            'required'=>'Debe seleccionar un nivel válido.',
            'exists'=>'El nivel seleccionado no existe.',
            'not_in'=>'El nivel no puede ser la misma que se edita.',
        ],
        'code'=>[
            'required'=>'Debe ingresar un código de válido.',
            'between'=>'El código debe tener entre :min y :max',
            'unique'=>'El código ingresado ya existe.',
        ],
        'name'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'between'=>'El nombre debe tener entre :min y :max',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Nivel registrado exitosamente.",
                'error'=>"No hemos podido registrar el nivel. Por favor, revise los siguientes mensajes.",
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar un ID de categoría a eliminar.',
            'exists'=>'El ID a eliminar no existe.'
        ],
        'form'=>[
            'exist_ubications'=>'El nivel tiene otros niveles a cargo.',
            'exist_address'=>'El nivel esta asignado en direcciones.',
            'exist_shippingprice'=>'El nivel esta asignado en precio de envíos.',
            'result'=>[
                'success'=>'Categoría eliminada exitosamente.',
                'error'=>'No hemos podido eliminar la categoría. Por favor, revise los siguientes mensajes.',
            ]
        ]
    ],
];