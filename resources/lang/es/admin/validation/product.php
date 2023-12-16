<?php

return[
    'register'=>[
        'product_group_id'=>[
            'required'=>'Debe indicar una grupo válida.',
            'exists'=>'El grupo seleccionado no existe.',
        ],
        'sku'=>[
            'required'=>'Debe ingresar código SKU válido.',
            'unique'=>'El código SKU ya existe.',
        ],
        'url_code'=>[
            'required'=>'Debe ingresar un URL de válido.',
            'unique'=>'La URL ingresado ya existe.',
        ],
        'name'=>[
            'required'=>'Debe ingresar un nombre válido.',
        ],
        'is_for_catalogue'=>[
            'required'=>'El campo es requerido.',
            'boolean'=>'El campo debe tener un valor verdadero o falso.'
        ],
        'is_active'=>[
            'required'=>'El campo es requerido.',
            'boolean'=>'El campo debe tener un valor verdadero o falso.'
        ],
        'stock'=>[
            'required'=>'Debe ingresar un stock válido.',
            'numeric'=>'El stock debe ser numérico.'
        ],
        'shipping_size'=>[
            'required'=>'El campo es requerido.',
            'numeric'=>'El campo debe ser numérico.'
        ],
        'form'=>[
            'result'=>[
                'success'=>"Categoría registrada exitosamente.",
                'error'=>"No hemos podido registrar la categoría. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'change_status'=>[
        'id'=>[
            'required'=>'Debe indicar un ID de producto a editar.',
            'exists'=>'El ID a editar no existe.',
        ],
        'is_active'=>[
            'boolean'=>'El estado del producto debe tener valor de verdadero o falso.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Producto editado exitosamente.",
                'error'=>"No hemos podido editar el producto. Por favor, revise los siguientes mensajes."
            ],
        ],
    ],
    'delete'=>[
        'id'=>[
            'required'=>'Debe indicar un ID de categoría a eliminar.',
            'exists'=>'El ID a eliminar no existe.',
        ],
        'form'=>[            
            'exist_cart'=>'El producto se encuentra en un carrito.',
            'exist_orderdetail'=>'El producto se encuentra en una orden.',
            'exist_productspecification'=>'El producto cuenta con especificaciones asignadas.',
            'exist_wish_list_product'=>'El producto se encuentra en una lista de deseos.',
            'result'=>[
                'success'=>'Categoría eliminada exitosamente.',
                'error'=>'No hemos podido eliminar la categoría. Por favor, revise los siguientes mensajes.',
            ]
        ]
    ],
];