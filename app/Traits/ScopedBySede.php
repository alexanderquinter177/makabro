<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait ScopedBySede
{
    /**
     * Boot del trait para registrar el Global Scope de Sede.
     */
    protected static function bootScopedBySede(): void
    {
        // 1. Filtrar todas las consultas por la sede activa del usuario/sesión
        static::addGlobalScope('sede', function (Builder $builder) {
            $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id;

            if (!empty($sedeId)) {
                $table = $builder->getModel()->getTable();
                $builder->where("{$table}.sede_id", $sedeId);
            }
        });

        // 2. Inyectar automáticamente el sede_id al crear nuevos registros
        static::creating(function ($model) {
            $sedeId = session('sede_id') ?? auth()->user()?->sede_id_actual ?? auth()->user()?->sede_id;
            
            if (empty($model->sede_id) && !empty($sedeId)) {
                $model->sede_id = $sedeId;
            }
        });
    }

    /**
     * Scope para omitir el filtro por sede (ej: SuperAdmins o Reportes Globales).
     */
    public function scopeWithoutSedeScope(Builder $query): Builder
    {
        return $query->withoutGlobalScope('sede');
    }

    /**
     * Scope para consultar los productos de una sede en específico.
     */
    public function scopeForSede(Builder $query, int $sedeId): Builder
    {
        return $query->withoutGlobalScope('sede')->where($this->getTable() . '.sede_id', $sedeId);
    }
}
