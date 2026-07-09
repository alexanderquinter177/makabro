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
                $this->getSedeFormComponent(),
                $this->getRememberFormComponent(),
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
     * Genera el componente de selección de Sede.
     */
    protected function getSedeFormComponent(): \Filament\Schemas\Components\Component
    {
        return Select::make('sede_id')
            ->label('Sede de Conexión')
            ->options(Sede::activas()->pluck('nombre', 'id'))
            ->required()
            ->searchable()
            ->placeholder('Seleccione la sede a conectarse');
    }

    /**
     * Sobrescribe el proceso de autenticación.
     * Autentica por cédula + contraseña y valida acceso a la sede.
     */
    public function authenticate(): ?LoginResponse
    {
        try {
            $data = $this->form->getState();

            $cedula        = $data['cedula']   ?? null;
            $password      = $data['password'] ?? null;
            $selectedSedeId = $data['sede_id'] ?? null;

            // 1. Validar que se haya seleccionado sede
            if (empty($selectedSedeId)) {
                throw ValidationException::withMessages([
                    'data.sede_id' => 'Debes seleccionar una sede para continuar.',
                ]);
            }

            // 2. Buscar usuario por cédula
            $user = User::where('cedula', $cedula)->first();

            if (!$user || !\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
                throw ValidationException::withMessages([
                    'data.cedula' => 'La cédula o contraseña son incorrectas.',
                ]);
            }

            // 3. Verificar que el usuario esté activo
            if (!$user->activo) {
                throw ValidationException::withMessages([
                    'data.cedula' => 'El usuario se encuentra inactivo. Contacta al administrador.',
                ]);
            }

            // 4. Verificar que el usuario tiene acceso a la sede seleccionada
            $tieneAcceso = $user->sedes()
                ->where('sedes.id', $selectedSedeId)
                ->wherePivot('activo', true)
                ->exists();

            if (!$tieneAcceso) {
                throw ValidationException::withMessages([
                    'data.sede_id' => 'No tienes permiso para acceder a esta sede.',
                ]);
            }

            // 5. Hacer login manualmente
            \Filament\Facades\Filament::auth()->login($user, $data['remember'] ?? false);

            // 6. Guardar la sede en sesión
            session(['sede_id' => $selectedSedeId]);
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