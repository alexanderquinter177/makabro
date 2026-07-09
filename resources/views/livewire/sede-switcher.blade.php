<div x-data="{ open: false }" style="position:relative;display:flex;align-items:center;margin-right:8px;" @keydown.escape.window="open = false">

    {{-- ===================== BOTÓN ===================== --}}
    <button
        @click="open = !open"
        @click.outside="open = false"
        type="button"
        title="Cambiar sede"
        style="display:flex;align-items:center;gap:6px;padding:0 14px;
               border-radius:9999px;
               background:rgba(245,158,11,.15);
               border:1px solid rgba(180,83,9,.35);
               cursor:pointer;transition:all 150ms;white-space:nowrap;
               height:34px; 
               align-self:center;"
    >
        {{-- Ícono edificio 14×14 --}}
        <svg style="width:14px;height:14px;min-width:14px;color:#b45309;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
            <path stroke-linecap="round" stroke-linejoin="round"
                  d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5
                     M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
        </svg>

        {{-- Nombre de la sede (Sin límites de ancho) --}}
        <span style="font-size:12px;font-weight:700;color:#92400e !important;
                     letter-spacing:.01em;
                     white-space:nowrap;
                     -webkit-text-fill-color:#92400e;">
            {{ $sedeActual?->nombre ?? 'Sin sede' }}
        </span>

        {{-- Chevron 11×11 animado --}}
        <svg style="width:11px;height:11px;min-width:11px;stroke:#d97706;transition:transform 200ms;"
             :style="open ? 'transform:rotate(180deg)' : 'transform:rotate(0deg)'"
             fill="none" viewBox="0 0 24 24" stroke-width="3">
            <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
        </svg>
    </button>

    {{-- ===================== DROPDOWN ===================== --}}
    <div
        x-show="open"
        x-cloak
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 translateY(-4px)"
        x-transition:enter-end="opacity-100 translateY(0)"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        @click.outside="open = false"
        style="position:absolute;top:calc(100% + 8px);right:0;z-index:9999;
               background:#fff;
               border:1px solid #e7e5e4;
               border-radius:16px;
               box-shadow:0 16px 48px rgba(0,0,0,.14),0 2px 8px rgba(0,0,0,.06);
               min-width:220px;overflow:hidden;"
    >
        {{-- Encabezado del menú --}}
        <div style="padding:10px 16px 8px;border-bottom:1px solid #f5f5f4;">
            <p style="font-size:10px;font-weight:700;color:#a8a29e;
                      text-transform:uppercase;letter-spacing:.1em;margin:0;">
                Cambiar sede
            </p>
        </div>

        {{-- Lista de sedes --}}
        <div style="padding:4px 0;max-height:280px;overflow-y:auto;">
            @forelse ($sedes as $sede)
                @php $esActiva = session('sede_id') == $sede->id; @endphp
                <button
                    wire:click="switchSede({{ $sede->id }})"
                    @click="open = false"
                    type="button"
                    style="width:100%;display:flex;align-items:center;gap:10px;
                           padding:9px 16px;font-size:13px;text-align:left;
                           border:none;cursor:pointer;transition:background 100ms;
                           background:{{ $esActiva ? '#fffbeb' : 'transparent' }};
                           color:{{ $esActiva ? '#92400e' : '#292524' }};"
                >
                    {{-- Check ✓ o punto --}}
                    <span style="flex-shrink:0;width:16px;height:16px;display:flex;align-items:center;justify-content:center;">
                        @if ($esActiva)
                            <svg style="width:14px;height:14px;color:#f59e0b;" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                      d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                      clip-rule="evenodd"/>
                            </svg>
                        @else
                            <span style="width:6px;height:6px;border-radius:50%;background:#d6d3d1;display:block;"></span>
                        @endif
                    </span>

                    {{-- Nombre de la sede --}}
                    <span style="font-weight:{{ $esActiva ? '700' : '400' }};
                                 flex:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;
                                 color:{{ $esActiva ? '#92400e' : '#292524' }};">
                        {{ $sede->nombre }}
                    </span>

                    {{-- Cargo (si existe) --}}
                    @if ($sede->pivot?->cargo_sede)
                        <span style="font-size:10px;color:#a8a29e;flex-shrink:0;">
                            {{ $sede->pivot->cargo_sede }}
                        </span>
                    @endif
                </button>
            @empty
                <p style="padding:12px 16px;font-size:13px;color:#a8a29e;font-style:italic;margin:0;">
                    Sin sedes asignadas.
                </p>
            @endforelse
        </div>

        {{-- Footer --}}
        @if ($sedes->count() > 1)
            <div style="padding:8px 16px;border-top:1px solid #f5f5f4;">
                <p style="font-size:10px;color:#c0bbb5;margin:0;">
                    {{ $sedes->count() }} sede{{ $sedes->count() > 1 ? 's' : '' }} disponible{{ $sedes->count() > 1 ? 's' : '' }}
                </p>
            </div>
        @endif
    </div>
</div>