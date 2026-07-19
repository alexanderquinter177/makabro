<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Líneas de Lenguaje de Autenticación
    |--------------------------------------------------------------------------
    |
    | Las siguientes líneas de lenguaje se utilizan durante la autenticación
    | para varios mensajes que debemos mostrar al usuario. Eres libre de
    | modificar estas líneas de lenguaje según los requisitos de tu aplicación.
    |
    */

    'failed' => 'Las credenciales ingresadas no coinciden con nuestros registros. Verifica tu usuario y contraseña.',
    'password' => 'La contraseña ingresada es incorrecta. Por favor, inténtalo nuevamente.',
    'throttle' => 'Has realizado demasiados intentos de inicio de sesión. Por favor, espera :seconds segundos antes de intentar nuevamente.',

    /*
    |--------------------------------------------------------------------------
    | Mensajes Personalizados para el Sistema de Compras
    |--------------------------------------------------------------------------
    |
    | Estos mensajes son específicos para el contexto del sistema de compras
    | y mejoran la experiencia del usuario al proporcionar información clara.
    |
    */

    'login' => [
        'success' => '¡Bienvenido al Sistema de Compras! Has iniciado sesión correctamente.',
        'failed' => 'Usuario o contraseña incorrectos. Inténtalo nuevamente.',
        'required' => 'Debes iniciar sesión para acceder al módulo de compras.',
        'locked' => 'Tu cuenta ha sido bloqueada por múltiples intentos fallidos. Contacta al administrador.',
        'inactive' => 'Tu cuenta está desactivada. Por favor, contacta al administrador para activarla.',
        'expired' => 'Tu sesión ha expirado por inactividad. Inicia sesión nuevamente para continuar.',
    ],

    'logout' => [
        'success' => 'Has cerrado sesión exitosamente. ¡Hasta luego!',
        'failed' => 'Ocurrió un error al cerrar sesión. Por favor, intenta nuevamente.',
    ],

    'register' => [
        'success' => '¡Registro completado! Revisa tu correo para verificar tu cuenta.',
        'failed' => 'Ocurrió un error durante el registro. Por favor, intenta nuevamente.',
    ],

    'verify' => [
        'required' => 'Necesitas verificar tu correo electrónico para acceder al sistema de compras.',
        'success' => '¡Correo verificado! Ahora puedes acceder al sistema.',
        'sent' => 'Te hemos enviado un enlace de verificación a tu correo electrónico.',
        'resend' => 'Reenviar correo de verificación',
    ],

    'permissions' => [
        'denied' => 'No tienes permisos para acceder a este módulo de compras.',
        'restricted' => 'Esta acción requiere permisos de administrador.',
        'role_required' => 'No tienes el rol necesario para realizar esta operación.',
    ],

    'security' => [
        'two_factor_required' => 'Se requiere autenticación de dos factores para esta acción.',
        'ip_blocked' => 'Tu dirección IP ha sido bloqueada por seguridad.',
        'session_required' => 'Necesitas una sesión activa para continuar.',
    ],
];