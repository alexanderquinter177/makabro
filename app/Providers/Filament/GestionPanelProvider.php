<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class GestionPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('gestion')
            ->path('')
            ->brandName(fn () => session('sede_id') ? \App\Models\Catalog\Sede::find(session('sede_id'))?->nombre : 'Makabro')
            ->login(\App\Filament\Pages\Auth\CustomLogin::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->renderHook(
                \Filament\View\PanelsRenderHook::USER_MENU_BEFORE,
                fn(): string => auth()->check()
                    ? \Illuminate\Support\Facades\Blade::render('<livewire:sede-switcher />') .
                      <<<'HTML'
                      <div class="mk-font-switcher" title="Tamaño de letra">
                          <button class="mk-font-btn mk-font-btn-sm" data-size="sm" title="Letra pequeña">A</button>
                          <button class="mk-font-btn mk-font-btn-md" data-size="md" title="Letra mediana">A</button>
                          <button class="mk-font-btn mk-font-btn-lg" data-size="lg" title="Letra grande">A</button>
                      </div>
                      HTML
                    : ''
            )
            ->renderHook(
                \Filament\View\PanelsRenderHook::HEAD_END,
                fn (): string => <<<'HTML'
                <style id="mk-login-css">
                    /* ---- Fondo del panel de formulario ---- */
                    #mk-form-panel { background: #f5f5f4 !important; }

                    /* ---- Forzar modo claro: TODOS los elementos dentro del card ---- */
                    /* (Filament usa spans anidados para labels en modo oscuro) */
                    #mk-card * {
                        color: #1c1917 !important;
                    }
                    /* Asterisco de campo requerido → rojo */
                    #mk-card sup,
                    #mk-card .fi-required-mark,
                    #mk-card [class*="required"] {
                        color: #ef4444 !important;
                    }
                    /* Fondos de inputs → blanco */
                    #mk-card,
                    #mk-card input,
                    #mk-card select,
                    #mk-card textarea {
                        background-color: #fff !important;
                        border-color: #e7e5e4 !important;
                    }
                    /* Placeholder → gris suave */
                    #mk-card input::placeholder { color: #a8a29e !important; }
                    /* Focus → ámbar */
                    #mk-card input:focus,
                    #mk-card select:focus {
                        border-color: #f59e0b !important;
                        box-shadow: 0 0 0 3px rgba(245,158,11,.15) !important;
                        outline: none !important;
                    }
                    /* Botón Sign in */
                    #mk-card button[type="submit"],
                    #mk-card .fi-btn {
                        background: #f59e0b !important;
                        color: #1c1917 !important;
                        font-weight: 700 !important;
                        border-radius: 10px !important;
                        box-shadow: 0 4px 14px rgba(245,158,11,.35) !important;
                        border: none !important;
                    }
                    #mk-card button[type="submit"] *,
                    #mk-card .fi-btn * {
                        color: #1c1917 !important;
                    }
                    #mk-card button[type="submit"]:hover,
                    #mk-card .fi-btn:hover {
                        background: #d97706 !important;
                    }
                    /* Select dropdown fondo */
                    #mk-card select option { background: #fff; color: #1c1917; }
                    /* ---- Resto de estilos del layout ---- */


                    #mk-login-root {
                        display: flex;
                        flex-direction: column;
                        min-height: 100dvh;
                        width: 100%;
                    }
                    #mk-banner {
                        position: relative;
                        height: 240px;
                        overflow: hidden;
                        flex-shrink: 0;
                        background: #0c0a09;
                    }
                    #mk-banner img {
                        position: absolute; inset: 0;
                        width: 100%; height: 100%;
                        object-fit: cover; object-position: center;
                        opacity: .55;
                    }
                    #mk-overlay {
                        position: absolute; inset: 0;
                        background: linear-gradient(to bottom, rgba(12,10,9,.2) 0%, rgba(12,10,9,.88) 100%);
                    }
                    #mk-banner-content {
                        position: absolute; inset: 0;
                        display: flex; flex-direction: column;
                        align-items: center; justify-content: flex-end;
                        padding: 20px 24px;
                        gap: 6px;
                    }
                    .mk-logo {
                        width: 52px; height: 52px; border-radius: 14px;
                        background: #f59e0b; display: flex; align-items: center;
                        justify-content: center; font-size: 24px; font-weight: 900;
                        color: #1c1917; box-shadow: 0 8px 28px rgba(245,158,11,.5);
                    }
                    .mk-name {
                        font-size: 19px; font-weight: 900;
                        letter-spacing: .18em; text-transform: uppercase;
                        color: #fff; text-shadow: 0 2px 10px rgba(0,0,0,.5);
                    }
                    .mk-sub {
                        font-size: 11.5px; color: #d6d3d1;
                        font-weight: 300; letter-spacing: .06em;
                    }
                    #mk-form-panel {
                        flex: 1;
                        display: flex;
                        align-items: flex-start;
                        justify-content: center;
                        background: #f5f5f4;
                        padding: 28px 16px 48px;
                    }
                    #mk-form-inner {
                        width: 100%;
                        max-width: 420px;
                    }
                    .mk-heading { margin-bottom: 18px; }
                    .mk-heading h2 {
                        font-size: 21px; font-weight: 800;
                        color: #1c1917; margin: 0 0 6px;
                        letter-spacing: -.02em;
                    }
                    .mk-heading p {
                        font-size: 13px; color: #78716c;
                        margin: 0; line-height: 1.5;
                    }
                    .mk-bar { width: 30px; height: 3px; background: #f59e0b; border-radius: 4px; margin-bottom: 20px; }
                    #mk-card {
                        background: #fff;
                        border-radius: 20px;
                        padding: 26px 22px;
                        box-shadow: 0 20px 60px rgba(0,0,0,.13), 0 4px 16px rgba(0,0,0,.07);
                        border: 1px solid rgba(0,0,0,.05);
                    }
                    .mk-foot { text-align: center; font-size: 11px; color: #a8a29e; margin-top: 18px; }
                    #mk-desktop-tag { display: none; }
                    #mk-dt-copy { display: none; }

                    @media (min-width: 1024px) {
                        #mk-login-root {
                            flex-direction: row !important;
                        }
                        #mk-banner {
                            width: 45%;
                            height: 100dvh;
                            position: sticky;
                            top: 0;
                            flex-shrink: 0;
                            align-self: flex-start;
                            display: flex;
                            flex-direction: column;
                            justify-content: flex-end;
                        }
                        #mk-banner-content {
                            align-items: flex-start;
                            justify-content: flex-end;
                            padding: 48px 52px;
                            flex-direction: column;
                            gap: 0;
                        }
                        .mk-logo { width: 58px; height: 58px; font-size: 26px; margin-bottom: 14px; border-radius: 16px; }
                        .mk-name { font-size: 26px; letter-spacing: .14em; margin-bottom: 28px; display: block; }
                        .mk-sub { display: none !important; }
                        #mk-desktop-tag {
                            display: block;
                            max-width: 340px;
                        }
                        #mk-desktop-tag h1 {
                            font-size: 1.9rem; font-weight: 800;
                            color: #fff; line-height: 1.25;
                            margin: 0 0 12px;
                        }
                        #mk-desktop-tag p {
                            font-size: 14px; color: #a8a29e;
                            line-height: 1.7; margin: 0;
                        }
                        #mk-desktop-tag .mk-dots { display: flex; gap: 8px; margin-top: 28px; }
                        #mk-desktop-tag .mk-da { height: 3px; width: 36px; background: #f59e0b; border-radius: 4px; }
                        #mk-desktop-tag .mk-db { height: 3px; width: 12px; background: #57534e; border-radius: 4px; }
                        #mk-dt-copy { display: block; position: absolute; bottom: 18px; left: 52px; font-size: 11px; color: #57534e; }
                        #mk-form-panel {
                            align-items: center;
                            padding: 48px 64px;
                            background: #fafaf9;
                        }
                        #mk-form-inner { max-width: 440px; }
                        .mk-heading h2 { font-size: 24px; }
                        #mk-card { padding: 32px 28px; }
                        .mk-foot { display: none !important; }
                    }
                </style>

                <!-- Makabro: Tamaño de letra -->
                <style id="mk-font-size-css">
                    html { font-size: 16px; }
                    html.mk-font-sm { font-size: 13px; }
                    html.mk-font-md { font-size: 16px; }
                    html.mk-font-lg { font-size: 19px; }

                    .mk-font-switcher {
                        display: flex; align-items: center; gap: 3px;
                        background: rgba(0,0,0,.06); border-radius: 9999px;
                        padding: 3px 6px; margin-right: 4px;
                    }
                    .dark .mk-font-switcher { background: rgba(255,255,255,.08); }

                    .mk-font-btn {
                        display: flex; align-items: center; justify-content: center;
                        width: 26px; height: 26px; border-radius: 9999px;
                        border: none; background: transparent; cursor: pointer;
                        font-weight: 800; color: #78716c;
                        transition: background .15s, color .15s, transform .1s;
                        line-height: 1; font-family: inherit;
                    }
                    .dark .mk-font-btn { color: #a8a29e; }
                    .mk-font-btn:hover { background: rgba(245,158,11,.18); color: #d97706; transform: scale(1.12); }
                    .mk-font-btn.mk-active { background: #f59e0b; color: #1c1917; box-shadow: 0 2px 8px rgba(245,158,11,.4); }
                    .mk-font-btn-sm { font-size: 10px; }
                    .mk-font-btn-md { font-size: 13px; }
                    .mk-font-btn-lg { font-size: 16px; }
                </style>

                <script id="mk-font-size-js">
                (function(){
                    var KEY='mk_font_size', SIZES=['sm','md','lg'], DEF='md';
                    function apply(s){
                        if(!SIZES.includes(s)) s=DEF;
                        SIZES.forEach(function(x){ document.documentElement.classList.remove('mk-font-'+x); });
                        document.documentElement.classList.add('mk-font-'+s);
                        localStorage.setItem(KEY,s);
                        document.querySelectorAll('.mk-font-btn').forEach(function(b){
                            b.classList.toggle('mk-active', b.dataset.size===s);
                        });
                    }
                    apply(localStorage.getItem(KEY)||DEF);
                    window.mkSetFontSize=apply;
                    function bind(){
                        document.querySelectorAll('.mk-font-btn').forEach(function(b){
                            if(b.dataset.bound) return;
                            b.dataset.bound='1';
                            b.addEventListener('click',function(){ apply(b.dataset.size); });
                        });
                        apply(localStorage.getItem(KEY)||DEF);
                    }
                    document.addEventListener('DOMContentLoaded', bind);
                    document.addEventListener('livewire:navigated', bind);
                    document.addEventListener('livewire:initialized', bind);
                })();
                </script>
                HTML
            )
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\Filament\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\Filament\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\Filament\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
