<?php

return[
    'register'=>[
        'event_id'=>[
            'required'=>'Debe seleccionar un evento válido',
            'exists'=>'El evento seleccionado no existe.',
        ],
        'email'=>[
            'required'=>'Debe ingresar un correo electrónico válido',
            'email'=>'Debe ingresar un correo electrónico válido',
            'between'=>'El correo electrónico debe tener entre :min y :max carácteres.',
            'unique'=>'El símbolo ya existe.',
        ],
        'full_name'=>[
            'between'=>'El nombre debe tener entre :min y :max carácteres.',
        ],
        'is_original'=>[
            'boolean'=>'El campo debe ser verdadero o falso.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Invitación registrada exitosamente.",
                'error'=>"No hemos podido registrar la invitación. Por favor, revise los siguientes mensajes."
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
                'success'=>'Invitación eliminada exitosamente.',
                'error'=>'No hemos podido eliminar la invitación. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];