<?php

return[
    'change_status'=>[
        'id'=>[
            'required'=>'Debe indicar una orden válida.',
            'exists'=>'La orden seleccionada no existe.',
        ],
        'status_type_id'=>[
            'required'=>'Debe indicar un producto válido.',
            'exists'=>'El producto seleccionado no existe.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Categoría registrada exitosamente.",
                'error'=>"No hemos podido registrar la categoría. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
];