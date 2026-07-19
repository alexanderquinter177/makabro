<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Líneas de Lenguaje para Restablecimiento de Contraseñas
    |--------------------------------------------------------------------------
    |
    | Las siguientes líneas de lenguaje son los mensajes predeterminados que
    | coinciden con los motivos proporcionados por el intermediario de contraseñas
    | para un intento de actualización de contraseña, como fallos debido a un
    | token no válido o una nueva contraseña no válida.
    |
    */

    'reset' => '¡Tu contraseña ha sido restablecida correctamente!',
    'sent' => 'Te hemos enviado por correo electrónico el enlace para restablecer tu contraseña.',
    'throttled' => 'Por favor, espera unos momentos antes de intentarlo de nuevo.',
    'token' => 'El token de restablecimiento de contraseña no es válido o ha expirado.',
    'user' => 'No podemos encontrar un usuario con esa dirección de correo electrónico.',
    
    /*
    |--------------------------------------------------------------------------
    | Mensajes Personalizados para el Sistema de Compras
    |--------------------------------------------------------------------------
    |
    | Estos mensajes adicionales se pueden usar en el contexto específico
    | del módulo de compras para una mejor experiencia de usuario.
    |
    */
    
    'expired' => 'El enlace de restablecimiento ha expirado. Por favor, solicita uno nuevo.',
    'invalid_user' => 'El usuario no está activo o ha sido desactivado.',
    'success' => 'Contraseña actualizada exitosamente.',
    'error' => 'Ocurrió un error al restablecer la contraseña. Por favor, intenta nuevamente.',
];