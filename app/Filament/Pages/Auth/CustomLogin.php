<?php

namespace App\Filament\Pages\Auth;

use Filament\Auth\Pages\Login as BaseLogin;
use Filament\Schemas\Schema;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use App\Models\Catalog\Sede;
use App\Models\Auth\User;
use Filament\Auth\Http\Responses\Contracts\LoginResponse;
use Illuminate\Validation\ValidationException;

class CustomLogin extends BaseLogin
{
    protected static string $layout = 'filament-panels::components.layout.base';

    protected string $view = 'auth.login';

    public function getHeading(): string
    {
        return 'Iniciar Sesión';
    }

    public function getSubheading(): ?string
    {
        return 'Selecciona tu sede y accede al sistema';
    }

    /**
     * Define la estructura del formulario de Login.
     * Reemplaza el campo email por cédula.
     */
    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                $this->getCedulaFormComponent(),
                $this->getPasswordFormComponent(),
            ]);
    }

    /**
     * Campo de cédula (reemplaza el email original de Filament).
     */
    protected function getCedulaFormComponent(): \Filament\Schemas\Components\Component
    {
        return TextInput::make('cedula')
            ->label('Cédula')
            ->placeholder('Ingresa tu número de cédula')
            ->required()
            ->autocomplete('username')
            ->autofocus()
            ->extraInputAttributes(['tabindex' => 1]);
    }

    /**
     * Sobrescribe el proceso de autenticación.
     * Autentica por cédula + contraseña y valida acceso a alguna sede activa.
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            $data = $this->form->getState();

            $cedula   = $data['cedula']   ?? null;
            $password = $data['password'] ?? null;

            // 1. Buscar usuario por cédula
            $user = User::where('cedula', $cedula)->first();

            if (!$user || !\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                throw ValidationException::withMessages([
                    'data.cedula' => 'La cédula o contraseña son incorrectas.',
                ]);
            }

            // 2. Verificar que el usuario esté activo
            if (!$user->activo) {
                throw ValidationException::withMessages([
                    'data.cedula' => 'El usuario se encuentra inactivo. Contacta al administrador.',
                ]);
            }

            // 3. Verificar que el usuario tiene al menos una sede activa asignada
            $sedesActivas = $user->sedesActivas()->get();

            if ($sedesActivas->isEmpty()) {
                throw ValidationException::withMessages([
                    'data.cedula' => 'No tienes ninguna sede activa asignada. Contacta al administrador.',
                ]);
            }

            // 4. Hacer login manualmente
            \Filament\Facades\Filament::auth()->login($user, false);

            // 5. Si tiene exactamente una sede, guardarla en sesión inmediatamente
            if ($sedesActivas->count() === 1) {
                session(['sede_id' => $sedesActivas->first()->id]);
            }

            session()->regenerate();

            return app(LoginResponse::class);

        } catch (ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            throw ValidationException::withMessages([
                'data.cedula' => 'Error durante el inicio de sesión: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Personalizar la redirección después del login.
     */
    protected function getRedirectUrl(): string
    {
        return '/';
    }
}