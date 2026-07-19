<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Líneas de Lenguaje de Validación
    |--------------------------------------------------------------------------
    |
    | Las siguientes líneas de lenguaje contienen los mensajes de error predeterminados
    | utilizados por la clase validadora. Algunas de estas reglas tienen múltiples
    | versiones, como las reglas de tamaño. Siéntete libre de ajustar cada uno de
    | estos mensajes aquí.
    |
    */

    'accepted' => 'El campo :attribute debe ser aceptado.',
    'accepted_if' => 'El campo :attribute debe ser aceptado cuando :other sea :value.',
    'active_url' => 'El campo :attribute no es una URL válida.',
    'after' => 'El campo :attribute debe ser una fecha posterior a :date.',
    'after_or_equal' => 'El campo :attribute debe ser una fecha posterior o igual a :date.',
    'alpha' => 'El campo :attribute sólo debe contener letras.',
    'alpha_dash' => 'El campo :attribute sólo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num' => 'El campo :attribute sólo debe contener letras y números.',
    'array' => 'El campo :attribute debe ser un conjunto.',
    'before' => 'El campo :attribute debe ser una fecha anterior a :date.',
    'before_or_equal' => 'El campo :attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'numeric' => 'El campo :attribute debe estar entre :min y :max.',
        'file' => 'El campo :attribute debe pesar entre :min y :max kilobytes.',
        'string' => 'El campo :attribute debe tener entre :min y :max caracteres.',
        'array' => 'El campo :attribute debe tener entre :min y :max elementos.',
    ],
    'boolean' => 'El campo :attribute debe ser verdadero o falso.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'date' => 'El campo :attribute no es una fecha válida.',
    'date_equals' => 'El campo :attribute debe ser una fecha igual a :date.',
    'date_format' => 'El campo :attribute no corresponde al formato :format.',
    'declined' => 'El campo :attribute debe ser rechazado.',
    'declined_if' => 'El campo :attribute debe ser rechazado cuando :other sea :value.',
    'different' => 'Los campos :attribute y :other deben ser diferentes.',
    'digits' => 'El campo :attribute debe tener :digits dígitos.',
    'digits_between' => 'El campo :attribute debe tener entre :min y :max dígitos.',
    'dimensions' => 'Las dimensiones de la imagen :attribute no son válidas.',
    'distinct' => 'El campo :attribute contiene un valor duplicado.',
    'doesnt_start_with' => 'El campo :attribute no debe comenzar con uno de los siguientes valores: :values.',
    'email' => 'El campo :attribute debe ser una dirección de correo válida.',
    'ends_with' => 'El campo :attribute debe finalizar con uno de los siguientes valores: :values.',
    'enum' => 'El campo :attribute seleccionado no es válido.',
    'exists' => 'El campo :attribute seleccionado no es válido.',
    'filled' => 'El campo :attribute es obligatorio.',
    'gt' => [
        'numeric' => 'El campo :attribute debe ser mayor que :value.',
        'file' => 'El campo :attribute debe tener más de :value kilobytes.',
        'string' => 'El campo :attribute debe tener más de :value caracteres.',
        'array' => 'El campo :attribute debe tener más de :value elementos.',
    ],
    'gte' => [
        'numeric' => 'El campo :attribute debe ser como mínimo :value.',
        'file' => 'El campo :attribute debe tener como mínimo :value kilobytes.',
        'string' => 'El campo :attribute debe tener como mínimo :value caracteres.',
        'array' => 'El campo :attribute debe tener como mínimo :value elementos.',
    ],
    'image' => 'El campo :attribute debe ser una imagen.',
    'in' => 'El campo :attribute seleccionado no es válido.',
    'in_array' => 'El campo :attribute no existe en :other.',
    'integer' => 'El campo :attribute debe ser un número entero.',
    'ip' => 'El campo :attribute debe ser una dirección IP válida.',
    'ipv4' => 'El campo :attribute debe ser una dirección IPv4 válida.',
    'ipv6' => 'El campo :attribute debe ser una dirección IPv6 válida.',
    'json' => 'El campo :attribute debe ser una cadena JSON válida.',
    'lt' => [
        'numeric' => 'El campo :attribute debe ser menor que :value.',
        'file' => 'El campo :attribute debe tener menos de :value kilobytes.',
        'string' => 'El campo :attribute debe tener menos de :value caracteres.',
        'array' => 'El campo :attribute debe tener menos de :value elementos.',
    ],
    'lte' => [
        'numeric' => 'El campo :attribute debe ser como máximo :value.',
        'file' => 'El campo :attribute debe tener como máximo :value kilobytes.',
        'string' => 'El campo :attribute debe tener como máximo :value caracteres.',
        'array' => 'El campo :attribute debe tener como máximo :value elementos.',
    ],
    'mac_address' => 'El campo :attribute debe ser una dirección MAC válida.',
    'max' => [
        'numeric' => 'El campo :attribute no debe ser mayor que :max.',
        'file' => 'El campo :attribute no debe pesar más de :max kilobytes.',
        'string' => 'El campo :attribute no debe tener más de :max caracteres.',
        'array' => 'El campo :attribute no debe tener más de :max elementos.',
    ],
    'max_digits' => 'El campo :attribute no debe tener más de :max dígitos.',
    'mimes' => 'El campo :attribute debe ser un archivo con formato: :values.',
    'mimetypes' => 'El campo :attribute debe ser un archivo con formato: :values.',
    'min' => [
        'numeric' => 'El campo :attribute debe ser de al menos :min.',
        'file' => 'El campo :attribute debe pesar al menos :min kilobytes.',
        'string' => 'El campo :attribute debe tener al menos :min caracteres.',
        'array' => 'El campo :attribute debe tener al menos :min elementos.',
    ],
    'min_digits' => 'El campo :attribute debe tener al menos :min dígitos.',
    'missing' => 'El campo :attribute no debe estar presente.',
    'missing_if' => 'El campo :attribute no debe estar presente cuando :other sea :value.',
    'missing_unless' => 'El campo :attribute no debe estar presente a menos que :other sea :value.',
    'missing_with' => 'El campo :attribute no debe estar presente cuando :values esté presente.',
    'missing_with_all' => 'El campo :attribute no debe estar presente cuando :values estén presentes.',
    'multiple_of' => 'El campo :attribute debe ser un múltiplo de :value.',
    'not_in' => 'El campo :attribute seleccionado no es válido.',
    'not_regex' => 'El formato del campo :attribute no es válido.',
    'numeric' => 'El campo :attribute debe ser un número.',
    'password' => [
        'letters' => 'La contraseña debe contener al menos una letra.',
        'mixed' => 'La contraseña debe contener al menos una letra mayúscula y una minúscula.',
        'numbers' => 'La contraseña debe contener al menos un número.',
        'symbols' => 'La contraseña debe contener al menos un símbolo.',
        'uncompromised' => 'La contraseña proporcionada se ha filtrado en una filtración de datos. Seleccione otra.',
    ],
    'present' => 'El campo :attribute debe estar presente.',
    'prohibited' => 'El campo :attribute está prohibido.',
    'prohibited_if' => 'El campo :attribute está prohibido cuando :other es :value.',
    'prohibited_unless' => 'El campo :attribute está prohibido a menos que :other sea :value.',
    'prohibits' => 'El campo :attribute prohibe que :other esté presente.',
    'regex' => 'El formato del campo :attribute no es válido.',
    'required' => 'El campo :attribute es obligatorio.',
    'required_array_keys' => 'El campo :attribute debe contener entradas para: :values.',
    'required_if' => 'El campo :attribute es obligatorio cuando :other es :value.',
    'required_if_accepted' => 'El campo :attribute es obligatorio cuando :other es aceptado.',
    'required_unless' => 'El campo :attribute es obligatorio a menos que :other esté en :values.',
    'required_with' => 'El campo :attribute es obligatorio cuando :values está presente.',
    'required_with_all' => 'El campo :attribute es obligatorio cuando :values están presentes.',
    'required_without' => 'El campo :attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => 'El campo :attribute es obligatorio cuando ninguno de los campos :values están presentes.',
    'same' => 'Los campos :attribute y :other deben coincidir.',
    'size' => [
        'numeric' => 'El campo :attribute debe ser :size.',
        'file' => 'El campo :attribute debe pesar :size kilobytes.',
        'string' => 'El campo :attribute debe tener :size caracteres.',
        'array' => 'El campo :attribute debe contener :size elementos.',
    ],
    'starts_with' => 'El campo :attribute debe comenzar con uno de los siguientes valores: :values.',
    'string' => 'El campo :attribute debe ser una cadena de caracteres.',
    'timezone' => 'El campo :attribute debe ser una zona horaria válida.',
    'unique' => 'El valor del campo :attribute ya ha sido registrado.',
    'uploaded' => 'El campo :attribute no se pudo subir.',
    'uppercase' => 'El campo :attribute debe estar en mayúsculas.',
    'url' => 'El campo :attribute debe ser una URL válida.',
    'ulid' => 'El campo :attribute debe ser un ULID válido.',
    'uuid' => 'El campo :attribute debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Mensajes de Validación Personalizados
    |--------------------------------------------------------------------------
    |
    | Aquí puedes especificar mensajes de validación personalizados para atributos
    | usando la convención "atributo.regla" para nombrar las líneas. Esto hace
    | que sea rápido especificar un mensaje de lenguaje personalizado para una
    | regla de atributo determinada.
    |
    */

    'custom' => [
        // ============================================================
        // Módulo de Compras
        // ============================================================
        
        // Campos de la tabla Compras
        'numero_factura' => [
            'required' => 'El número de factura es obligatorio.',
            'unique' => 'Esta factura ya está registrada en el sistema. Verifica el número.',
            'max' => 'El número de factura no debe exceder los :max caracteres.',
            'min' => 'El número de factura debe tener al menos :min caracteres.',
        ],
        'sede_id' => [
            'required' => 'Debes seleccionar una sede para registrar la compra.',
            'exists' => 'La sede seleccionada no es válida o ha sido desactivada.',
        ],
        'proveedor_id' => [
            'required' => 'Debes seleccionar un proveedor para la compra.',
            'exists' => 'El proveedor seleccionado no es válido o ha sido desactivado.',
        ],
        'fecha_factura' => [
            'required' => 'La fecha de la factura es obligatoria.',
            'date' => 'La fecha de la factura no es válida.',
            'before_or_equal' => 'La fecha de la factura no puede ser futura.',
        ],
        'fecha_registro' => [
            'required' => 'La fecha de registro es obligatoria.',
            'date' => 'La fecha de registro no es válida.',
        ],
        'subtotal' => [
            'required' => 'El subtotal de la compra es obligatorio.',
            'numeric' => 'El subtotal debe ser un valor numérico válido.',
            'min' => 'El subtotal debe ser mayor a 0.',
        ],
        'iva' => [
            'required' => 'El IVA de la compra es obligatorio.',
            'numeric' => 'El IVA debe ser un valor numérico válido.',
            'min' => 'El IVA debe ser mayor o igual a 0.',
        ],
        'total' => [
            'required' => 'El total de la compra es obligatorio.',
            'numeric' => 'El total debe ser un valor numérico válido.',
            'min' => 'El total debe ser mayor a 0.',
        ],
        'status' => [
            'in' => 'El estado seleccionado no es válido. Estados permitidos: borrador, pendiente, aprobado.',
        ],
        'forma_pago' => [
            'required' => 'La forma de pago es obligatoria.',
            'max' => 'La forma de pago no debe exceder los :max caracteres.',
        ],
        'tipo_compra' => [
            'required' => 'El tipo de compra es obligatorio.',
            'max' => 'El tipo de compra no debe exceder los :max caracteres.',
        ],
        'recibido_por' => [
            'max' => 'El nombre de quien recibe no debe exceder los :max caracteres.',
        ],
        'imagen_factura' => [
            'image' => 'El archivo debe ser una imagen válida.',
            'mimes' => 'El archivo debe ser de tipo: :values.',
            'max' => 'El archivo no debe pesar más de :max kilobytes.',
        ],
        'registro_tardio' => [
            'boolean' => 'El campo registro tardío debe ser verdadero o falso.',
        ],
        'recibido' => [
            'boolean' => 'El campo recibido debe ser verdadero o falso.',
        ],
        'pagado' => [
            'boolean' => 'El campo pagado debe ser verdadero o falso.',
        ],
        'notas' => [
            'max' => 'Las notas no deben exceder los :max caracteres.',
        ],

        // Campos de la tabla CompraItems
        'items' => [
            'required' => 'Debes agregar al menos un producto a la compra.',
            'array' => 'El formato de los items no es válido.',
            'min' => 'Debes agregar al menos un producto a la compra.',
        ],
        'items.*.producto_id' => [
            'required' => 'El producto #:position es obligatorio.',
            'exists' => 'El producto #:position no es válido o ha sido desactivado.',
        ],
        'items.*.cantidad' => [
            'required' => 'La cantidad del producto #:position es obligatoria.',
            'integer' => 'La cantidad del producto #:position debe ser un número entero.',
            'min' => 'La cantidad del producto #:position debe ser al menos 1.',
        ],
        'items.*.precio_unitario' => [
            'required' => 'El precio unitario del producto #:position es obligatorio.',
            'numeric' => 'El precio unitario del producto #:position debe ser un valor numérico.',
            'min' => 'El precio unitario del producto #:position debe ser mayor a 0.',
        ],
        'items.*.descuento' => [
            'numeric' => 'El descuento del producto #:position debe ser un valor numérico.',
            'min' => 'El descuento del producto #:position debe ser mayor o igual a 0.',
            'max' => 'El descuento del producto #:position no puede ser mayor al precio unitario.',
        ],
        'items.*.total' => [
            'required' => 'El total del producto #:position es obligatorio.',
            'numeric' => 'El total del producto #:position debe ser un valor numérico.',
            'min' => 'El total del producto #:position debe ser mayor a 0.',
        ],

        // ============================================================
        // Módulo de Proveedores
        // ============================================================
        'nombre' => [
            'required' => 'El nombre es obligatorio.',
            'max' => 'El nombre no debe exceder los :max caracteres.',
            'min' => 'El nombre debe tener al menos :min caracteres.',
            'unique' => 'Este nombre ya está registrado en el sistema.',
        ],
        'nit' => [
            'required' => 'El NIT es obligatorio.',
            'max' => 'El NIT no debe exceder los :max caracteres.',
            'unique' => 'Este NIT ya está registrado en el sistema.',
        ],
        'telefono' => [
            'max' => 'El teléfono no debe exceder los :max caracteres.',
        ],
        'email' => [
            'email' => 'El correo electrónico debe ser una dirección válida.',
            'max' => 'El correo electrónico no debe exceder los :max caracteres.',
            'unique' => 'Este correo electrónico ya está registrado en el sistema.',
        ],
        'direccion' => [
            'max' => 'La dirección no debe exceder los :max caracteres.',
        ],

        // ============================================================
        // Módulo de Sede
        // ============================================================
        'sede.nombre' => [
            'required' => 'El nombre de la sede es obligatorio.',
            'max' => 'El nombre de la sede no debe exceder los :max caracteres.',
            'unique' => 'Este nombre de sede ya está registrado.',
        ],
        'sede.codigo' => [
            'required' => 'El código de la sede es obligatorio.',
            'max' => 'El código de la sede no debe exceder los :max caracteres.',
            'unique' => 'Este código de sede ya está registrado.',
        ],

        // ============================================================
        // Módulo de Productos / Inventario
        // ============================================================
        'producto_id' => [
            'required' => 'Debes seleccionar un producto.',
            'exists' => 'El producto seleccionado no es válido o ha sido desactivado.',
        ],
        'stock' => [
            'integer' => 'El stock debe ser un número entero.',
            'min' => 'El stock debe ser mayor o igual a 0.',
        ],
        'stock_minimo' => [
            'integer' => 'El stock mínimo debe ser un número entero.',
            'min' => 'El stock mínimo debe ser mayor o igual a 0.',
        ],
        'stock_maximo' => [
            'integer' => 'El stock máximo debe ser un número entero.',
            'min' => 'El stock máximo debe ser mayor a 0.',
        ],
        'precio_compra' => [
            'required' => 'El precio de compra es obligatorio.',
            'numeric' => 'El precio de compra debe ser un valor numérico.',
            'min' => 'El precio de compra debe ser mayor a 0.',
        ],
        'precio_venta' => [
            'required' => 'El precio de venta es obligatorio.',
            'numeric' => 'El precio de venta debe ser un valor numérico.',
            'min' => 'El precio de venta debe ser mayor a 0.',
        ],

        // ============================================================
        // Módulo de Usuarios / Autenticación
        // ============================================================
        'password' => [
            'required' => 'La contraseña es obligatoria.',
            'min' => 'La contraseña debe tener al menos :min caracteres.',
            'confirmed' => 'La confirmación de la contraseña no coincide.',
        ],
        'current_password' => [
            'required' => 'La contraseña actual es obligatoria.',
            'current_password' => 'La contraseña actual es incorrecta.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Atributos de Validación
    |--------------------------------------------------------------------------
    |
    | Las siguientes líneas de lenguaje se utilizan para intercambiar los
    | marcadores de posición de atributo con algo más amigable como
    | "Dirección de Correo Electrónico" en lugar de "email".
    | Esto simplemente nos ayuda a hacer los mensajes un poco más limpios.
    |
    */

    'attributes' => [
        // ============================================================
        // Atributos Generales del Sistema
        // ============================================================
        'id' => 'ID',
        'created_at' => 'fecha de creación',
        'updated_at' => 'fecha de actualización',
        'deleted_at' => 'fecha de eliminación',
        'created_by' => 'creado por',
        'updated_by' => 'actualizado por',
        'deleted_by' => 'eliminado por',

        // ============================================================
        // Módulo de Compras
        // ============================================================
        'numero_factura' => 'número de factura',
        'fecha_factura' => 'fecha de factura',
        'fecha_registro' => 'fecha de registro',
        'forma_pago' => 'forma de pago',
        'tipo_compra' => 'tipo de compra',
        'recibido_por' => 'recibido por',
        'subtotal' => 'subtotal',
        'iva' => 'IVA',
        'total' => 'total',
        'imagen_factura' => 'imagen de la factura',
        'status' => 'estado',
        'notas' => 'notas',
        'registro_tardio' => 'registro tardío',
        'recibido' => 'recibido',
        'pagado' => 'pagado',
        'fecha_aprobacion' => 'fecha de aprobación',
        'aprobado_por' => 'aprobado por',

        // Relaciones de Compra
        'sede_id' => 'sede',
        'proveedor_id' => 'proveedor',
        'usuario_id' => 'usuario',

        // ============================================================
        // Compra Items
        // ============================================================
        'items' => 'productos',
        'items.*.producto_id' => 'producto #:position',
        'items.*.cantidad' => 'cantidad #:position',
        'items.*.precio_unitario' => 'precio unitario #:position',
        'items.*.descuento' => 'descuento #:position',
        'items.*.iva' => 'IVA del producto #:position',
        'items.*.total' => 'total del producto #:position',

        // ============================================================
        // Módulo de Proveedores
        // ============================================================
        'nombre' => 'nombre',
        'nit' => 'NIT',
        'telefono' => 'teléfono',
        'email' => 'correo electrónico',
        'direccion' => 'dirección',
        'ciudad' => 'ciudad',
        'departamento' => 'departamento',
        'pais' => 'país',
        'codigo_postal' => 'código postal',
        'tipo_documento' => 'tipo de documento',
        'numero_documento' => 'número de documento',
        'razon_social' => 'razón social',
        'nombre_comercial' => 'nombre comercial',
        'contacto' => 'contacto',
        'website' => 'sitio web',

        // ============================================================
        // Módulo de Sede
        // ============================================================
        'sede' => 'sede',
        'sede.nombre' => 'nombre de la sede',
        'sede.codigo' => 'código de la sede',
        'sede.direccion' => 'dirección de la sede',
        'sede.telefono' => 'teléfono de la sede',

        // ============================================================
        // Módulo de Productos
        // ============================================================
        'producto' => 'producto',
        'producto_id' => 'producto',
        'codigo' => 'código',
        'codigo_barra' => 'código de barras',
        'descripcion' => 'descripción',
        'categoria_id' => 'categoría',
        'unidad_medida' => 'unidad de medida',
        'stock' => 'stock',
        'stock_minimo' => 'stock mínimo',
        'stock_maximo' => 'stock máximo',
        'precio_compra' => 'precio de compra',
        'precio_venta' => 'precio de venta',
        'utilidad' => 'utilidad',
        'peso' => 'peso',
        'dimensiones' => 'dimensiones',
        'fecha_vencimiento' => 'fecha de vencimiento',
        'lote' => 'lote',

        // ============================================================
        // Módulo de Usuarios / Autenticación
        // ============================================================
        'name' => 'nombre',
        'username' => 'nombre de usuario',
        'password' => 'contraseña',
        'password_confirmation' => 'confirmación de contraseña',
        'current_password' => 'contraseña actual',
        'remember' => 'recordarme',
        'role' => 'rol',
        'permission' => 'permiso',

        // ============================================================
        // Módulo de Kardex / Inventario
        // ============================================================
        'tipo_movimiento' => 'tipo de movimiento',
        'cantidad_movimiento' => 'cantidad',
        'saldo_anterior' => 'saldo anterior',
        'saldo_nuevo' => 'saldo nuevo',
        'costo_unitario' => 'costo unitario',
        'costo_total' => 'costo total',
        'documento_origen' => 'documento de origen',
        'motivo' => 'motivo',

        // ============================================================
        // Módulo de Configuración
        // ============================================================
        'configuracion' => 'configuración',
        'clave' => 'clave',
        'valor' => 'valor',
        'tipo' => 'tipo',

        // ============================================================
        // Campos Comunes
        // ============================================================
        'fecha' => 'fecha',
        'hora' => 'hora',
        'activo' => 'activo',
        'estado' => 'estado',
        'observaciones' => 'observaciones',
        'comentarios' => 'comentarios',
        'prioridad' => 'prioridad',
    ],
];