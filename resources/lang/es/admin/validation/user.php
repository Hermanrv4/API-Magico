<?php
return [
    'register'=>[
        'dni' => [
            'required' => 'Debe ingresar su DNI.',
            'digits_between' => 'Debe ingresar un DNI/CE de entre :min y :max números.',
            'unique' => 'El DNI ingresado ya existe.',
        ],
        'first_name' => [
            'required' => 'Debe ingresar sus nombres.',
            'between' => 'Debe ingresar sus nombre de entre :min y :max caracteres.',
        ],
        'last_name' => [
            'required' => 'Debe ingresar sus apellidos.',
            'between' => 'Debe ingresar sus apellidos de entre :min y :max caracteres.',
        ],
        'phone' => [
            'required' => 'Debe ingresar su teléfono.',
            'digits_between' => 'Debe ingresar un teléfono de entre :min y :max números.',
            'unique' => 'El teléfono ingresado ya existe.',
        ],
        'email' => [
            'required' => 'Debe ingresar su correo.',
            'email' => 'El correo ingresado es inválido.',
            'unique' => 'El correo ingresado ya existe.',
        ],
        'password' => [
            'required' => 'Debe ingresar una clave.',
            'between' => 'Debe ingresar una clave de entre :min y :max caracteres.',
        ],
        'facebook_id' => [
            'required' => 'Se requiere un identificador de facebook para continuar.',
        ],
        'form'=>[
            'result'=>[
                'success'=>"Cliente registrado exitosamente.",
                'error'=>"No hemos podido registrar el cliente. Por favor, revise los siguientes mensajes."
            ],
        ],

    ],
    'login' => [
        'form' => [
            'credentials' => 'Las credenciales ingresadas son inválidas.',
            'admin' => 'Las credenciales ingresadas no son de administrador.',
            'result' => [
                'success' => "Bienvenido a MerliShop ADMIN.",
                'error' => "No hemos podido autenticar su usuario, por favor revise los datos ingresados.",
            ],
        ],
        'email' => [
            'required' => 'Debe ingresar su correo.',
            'email' => 'El correo ingresado es inválido.',
            'exists' => 'El correo ingresado no se encuentra registrado.',
        ],
        'password' => [
            'required' => 'Debe ingresar una clave.',
        ],
    ],
];
