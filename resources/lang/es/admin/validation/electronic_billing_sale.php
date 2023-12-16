<?php

return[
    'register'=>[
        'serie'=>[
            'required'=>'Debe ingresar un código válido.',
            'between'=>'La serie debe tener entre :min y :max carácteres.',
            'unique'=>'La serie y el correlativo se repiten.',
        ],
        'correlative'=>[
            'required'=>'Debe ingresar un símbolo válido.',
            'between'=>'El símbolo debe tener entre :min y :max carácteres.',
        ],
        'order_id'=>[
            'required'=>'Debe ingresar un nombre válido.',
            'exists'=>'El nombre debe tener entre :min y :max carácteres.',            
        ],
        'status'=>[
            'size'=>'Debe campo debe ser verdadero o false ingresar un nombre válido.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Categoría registrada exitosamente.",
                'error'=>"No hemos podido registrar la categoría. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
];