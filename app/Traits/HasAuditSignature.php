<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasAuditSignature
{
    /**
     * Boot the trait to hook into Eloquent events.
     */
    public static function bootHasAuditSignature(): void
    {
        static::creating(function ($model) {
            if (Auth::check()) {
                if (!$model->created_by) {
                    $model->created_by = Auth::id();
                }
                if (!$model->updated_by) {
                    $model->updated_by = Auth::id();
                }
            }
        });

        static::updating(function ($model) {
            // Solo asigna updated_by si:
            // 1. Hay un usuario autenticado
            // 2. El campo updated_by no fue modificado manualmente
            // 3. Auth::id() es un entero válido (no null, no una cédula string)
            // 4. No es el propio usuario actualizándose durante el login (remember_token)
            $authId = Auth::id();
            if (
                Auth::check()
                && !$model->isDirty('updated_by')
                && is_int($authId)
                && $authId > 0
            ) {
                $model->updated_by = $authId;
            }
        });

        static::deleting(function ($model) {
            // Check if model has SoftDeletes enabled and is not force deleting
            if (Auth::check() && method_exists($model, 'isForceDeleting') && !$model->isForceDeleting()) {
                $model->deleted_by = Auth::id();
                $model->save();
            }
        });
    }

    /**
     * Usuario que creó el registro.
     */
    public function creador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuario que realizó la última actualización.
     */
    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Usuario que realizó el borrado lógico.
     */
    public function eliminador(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }
}
