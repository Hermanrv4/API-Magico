<?php
return [
    'register' => [
        'form' => [
            'result' => [
                'success' => "Orden registrada existosamente.",
                'error' => "No hemos podido registrar la orden, por favor revise los datos ingresados.",
            ],
            'error'=>[
                'invalid_shipping_price' => "Costo de envío no disponible para la ubicación seleccionada.",
                'product_id' => "El carrito cuenta con códigos de producto inválidos.",
                'product_qty' => "El carrito cuenta con cantidades productos inválidos.",
                'invalid_cart' => "El carrito de compras no es válido.",
            ],
        ],
        'user_id' => [
            'required' => 'Usuario inválido.',
            'exists' => 'El usuario no existe.',
        ],
        'receiver_first_name' => [
            'required' => 'Debe ingresar sus nombres.',
            'between' => 'Debe ingresar sus nombre de entre :min y :max caracteres.',
        ],
        'receiver_last_name' => [
            'required' => 'Debe ingresar sus apellidos.',
            'between' => 'Debe ingresar sus apellidos de entre :min y :max caracteres.',
        ],
        'receiver_phone' => [
            'required' => 'Debe ingresar su teléfono.',
            'digits_between' => 'Debe ingresar un teléfono de entre :min y :max números.',
            'unique' => 'El teléfono ingresado ya existe.',
        ],
        'receiver_email' => [
            'required' => 'Debe ingresar su correo.',
            'email' => 'El correo ingresado es inválido.',
            'unique' => 'El correo ingresado ya existe.',
        ],
        'receiver_dni' => [
            'required' => 'Debe ingresar su DNI.',
            'digits_between' => 'Debe ingresar un DNI/CE de entre :min y :max números.',
        ],
        'ubication_id' => [
            'required' => 'Debe seleccionar una ubicación.',
            'exists' => 'La ubicación seleccionada es inválida.',
        ],
        'address' => [
            'required' => 'Debe ingresar su dirección.',
            'between' => 'Debe ingresar una dirección de entre :min y :max caracteres.',
        ],
        'token' => [
            'required' => 'Token de orden inválida.',
            'unique' => 'El token ya ha generado otra orden.',
        ],
        'currency_code' => [
            'required' => 'Código de moneda inválido.',
            'exists' => 'El código de moneda es inválido.',
        ],
    ],
];
