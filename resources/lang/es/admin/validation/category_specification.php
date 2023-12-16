<?php

return[
    'register'=>[
        'category_id'=>[
            'required'=>'Debe ingresar una categoría válida.',
            'exists'=>'La categoría seleccionada no existe.',
        ],
        'specification_id'=>[
            'exists'=>'La especificación seleccionada no existe.',
        ],
        'is_filter'=>[
            'boolean'=>'El campo debe tener un valor verdadero o falso.',
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
            'required'=>'Debe indicar ID válido a eliminar',
            'exists'=>'El ID a eliminar no existe',
        ],
        'form'=>[
            'result'=>[
                'success'=>'Eliminado exitosamente.',
                'error'=>'No hemos podido eliminar. Por favor, revise los siguiente mensajes.',
            ]
        ],
    ],
];