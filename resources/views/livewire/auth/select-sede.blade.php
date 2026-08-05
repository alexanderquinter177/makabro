<div class="min-h-screen lg:h-screen w-full flex flex-col lg:flex-row bg-[#fafaf9] lg:overflow-hidden mk-layout-wrapper" style="min-height: 100vh; background-color: #fafaf9; display: flex; width: 100%;">

    <!-- PANEL IMAGEN (banner móvil / panel izquierdo desktop) -->
    <div class="relative h-[180px] sm:h-[220px] lg:h-full lg:w-[45%] flex-shrink-0 bg-[#0c0a09] overflow-hidden mk-left-banner" style="position: relative; background-color: #0c0a09; overflow: hidden; flex-shrink: 0;">
        <img src="{{ asset('images/restaurant_bg.png') }}" alt="Restaurante Makabro" class="absolute inset-0 w-full h-full object-cover opacity-55" style="position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: 0.55;">
        <div class="absolute inset-0 bg-gradient-to-b lg:bg-gradient-to-r from-transparent to-[#0c0a09]/90" style="position: absolute; inset: 0; background: linear-gradient(180deg, rgba(12,10,9,0.2) 0%, rgba(12,10,9,0.92) 100%);"></div>
        <div class="absolute inset-0 flex flex-col justify-end p-4 sm:p-6 lg:p-12" style="position: absolute; inset: 0; display: flex; flex-direction: column; justify-content: flex-end; padding: 32px;">
            <div class="w-[40px] h-[40px] sm:w-[48px] sm:h-[48px] lg:w-[58px] lg:h-[58px] rounded-2xl bg-[#f59e0b] flex items-center justify-center text-xl sm:text-2xl lg:text-3xl font-black text-[#1c1917] shadow-lg shadow-amber-500/30 mb-2 sm:mb-3 lg:mb-4" style="width: 52px; height: 52px; border-radius: 16px; background-color: #f59e0b; display: flex; align-items: center; justify-content: center; font-weight: 900; color: #1c1917; margin-bottom: 12px; font-size: 24px;">
                M
            </div>
            <div class="text-base sm:text-lg lg:text-3xl font-black tracking-widest uppercase text-white mb-0.5 lg:mb-4" style="font-weight: 900; color: #ffffff; text-transform: uppercase; letter-spacing: 2px; font-size: 22px;">
                Makabro
            </div>
            <div class="text-[9px] sm:text-[10px] lg:hidden text-stone-300 font-light tracking-wider mb-1 sm:mb-2 mk-hide-desktop" style="font-size: 11px; color: #d6d3d1;">
                Sistema de Gestión
            </div>
            <div class="hidden lg:block max-w-[360px] mk-hide-mobile">
                <h1 class="text-3xl font-extrabold text-white leading-tight mb-3" style="font-size: 24px; font-weight: 800; color: #ffffff; margin-bottom: 10px; line-height: 1.3;">Sabor artesanal,<br>gestión profesional.</h1>
                <p class="text-sm text-stone-400 leading-relaxed" style="font-size: 13px; color: #a8a29e; line-height: 1.6;">Administra tu sede, controla tus inventarios y optimiza tus recetas desde una sola plataforma.</p>
                <div class="flex gap-2 mt-7" style="display: flex; gap: 8px; margin-top: 20px;">
                    <div class="h-1 w-9 bg-[#f59e0b] rounded-full" style="height: 4px; width: 36px; background-color: #f59e0b; border-radius: 9999px;"></div>
                    <div class="h-1 w-3 bg-stone-600 rounded-full" style="height: 4px; width: 12px; background-color: #57534e; border-radius: 9999px;"></div>
                    <div class="h-1 w-3 bg-stone-600 rounded-full" style="height: 4px; width: 12px; background-color: #57534e; border-radius: 9999px;"></div>
                </div>
            </div>
        </div>
        <div class="hidden lg:block absolute bottom-5 left-12 text-[11px] text-stone-500 mk-hide-mobile" style="position: absolute; bottom: 20px; left: 32px; font-size: 11px; color: #78716c;">© {{ date('Y') }} Makabro · Todos los derechos reservados.</div>
    </div>

    <div class="flex-1 flex flex-col items-center justify-start p-6 sm:p-8 lg:p-16 bg-[#fafaf9] overflow-y-auto lg:h-full mk-right-content" style="display: flex; flex-direction: column; align-items: center; justify-content: center; padding: 32px; background-color: #fafaf9;">
        <div class="w-full max-w-[460px] my-auto py-6" style="width: 100%; max-width: 480px;">

            <!-- Mensaje de error -->
            @if (session()->has('error'))
                <div class="mb-4 sm:mb-5 p-3 sm:p-4 bg-red-50 border border-red-200 text-red-700 text-xs sm:text-sm rounded-xl" style="padding: 12px; background-color: #fef2f2; border: 1px solid #fecaca; color: #b91c1c; border-radius: 12px; margin-bottom: 16px;">
                    {{ session('error') }}
                </div>
            @endif

            <h2 style="font-size: 18px; font-weight: 800; color: #1c1917; margin-bottom: 14px;">Selecciona la Sede de Trabajo</h2>

            <!-- Grid de sedes -->
            <div class="space-y-2 sm:space-y-3" style="display: flex; flex-direction: column; gap: 10px; width: 100%;">
                @foreach ($sedes as $sede)
                    <button
                        wire:click="selectSede({{ $sede->id }})"
                        type="button"
                        class="w-full text-left p-3.5 sm:p-4 bg-white hover:bg-amber-50/30 border border-stone-200 hover:border-amber-500/50 rounded-2xl shadow-sm hover:shadow-md transition-all duration-200 group flex items-center justify-between"
                        style="display: flex; align-items: center; justify-content: space-between; width: 100%; padding: 14px 16px; background-color: #ffffff; border: 1px solid #e7e5e4; border-radius: 16px; cursor: pointer; text-align: left; transition: all 0.2s;"
                    >
                        <div class="flex items-center gap-3 sm:gap-4 min-w-0" style="display: flex; align-items: center; gap: 12px; min-width: 0;">
                            <!-- Icono edificio -->
                            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-amber-100/60 group-hover:bg-amber-100 flex items-center justify-center text-amber-700 transition-colors flex-shrink-0" style="width: 42px; height: 42px; min-width: 42px; min-height: 42px; border-radius: 12px; background-color: #fef3c7; display: flex; align-items: center; justify-content: center; color: #b45309; flex-shrink: 0;">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" style="width: 22px; height: 22px; max-width: 22px; max-height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                            </div>
                            <div class="min-w-0" style="min-width: 0;">
                                <h3 class="font-bold text-stone-800 group-hover:text-amber-900 transition-colors text-sm sm:text-base truncate" style="font-weight: 700; color: #1c1917; font-size: 15px; margin: 0;">
                                    {{ $sede->nombre }}
                                </h3>
                                @if($sede->pivot?->cargo_sede)
                                    <span class="inline-block text-[10px] sm:text-[11px] text-stone-500 font-medium bg-stone-100 px-2 py-0.5 rounded-md mt-0.5 truncate max-w-full" style="display: inline-block; font-size: 11px; color: #78716c; background-color: #f5f5f4; padding: 2px 8px; border-radius: 6px; margin-top: 4px;">
                                        {{ $sede->pivot->cargo_sede }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Flecha derecha -->
                        <div class="w-7 h-7 sm:w-8 sm:h-8 rounded-full bg-stone-50 group-hover:bg-[#f59e0b] group-hover:text-white flex items-center justify-center text-stone-400 transition-all duration-205 flex-shrink-0 ml-2" style="width: 30px; height: 30px; min-width: 30px; min-height: 30px; border-radius: 9999px; background-color: #f5f5f4; display: flex; align-items: center; justify-content: center; color: #a8a29e; flex-shrink: 0;">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transform group-hover:translate-x-0.5 transition-transform" style="width: 16px; height: 16px; max-width: 16px; max-height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7" />
                            </svg>
                        </div>
                    </button>
                @endforeach
            </div>

            <!-- Botones extras / Salida -->
            <div class="mt-6 sm:mt-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 sm:gap-0 border-t border-stone-200/80 pt-4 sm:pt-5 text-xs text-stone-500" style="margin-top: 24px; padding-top: 16px; border-top: 1px solid #e7e5e4; display: flex; align-items: center; justify-content: space-between; font-size: 12px; color: #78716c;">
                <span class="truncate max-w-full">
                    Conectado como <strong class="text-stone-700 font-semibold" style="color: #44403c; font-weight: 600;">{{ $user->name }}</strong>
                </span>
                <button
                    wire:click="logout"
                    type="button"
                    class="flex items-center gap-1.5 text-stone-500 hover:text-red-600 font-medium transition-colors w-full sm:w-auto justify-start sm:justify-end"
                    style="display: flex; align-items: center; gap: 6px; color: #78716c; font-weight: 500; background: none; border: none; cursor: pointer;"
                >
                    <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4" style="width: 16px; height: 16px;" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                    </svg>
                    Cerrar sesión
                </button>
            </div>

        </div>
    </div>
</div>