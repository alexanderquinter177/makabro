{{-- resources/views/auth/login.blade.php --}}
{{-- CSS is injected into <head> via PanelsRenderHook::HEAD_END in GestionPanelProvider --}}
<div id="mk-login-root">

    {{-- PANEL IMAGEN (banner móvil / panel izquierdo desktop) --}}
    <div id="mk-banner">
        <img src="{{ asset('images/restaurant_bg.png') }}" alt="Restaurante Makabro">
        <div id="mk-overlay"></div>
        <div id="mk-banner-content">
            <div class="mk-logo">M</div>
            <div class="mk-name">Makabro</div>
            <div class="mk-sub">Sistema de Gestión</div>
            <div id="mk-desktop-tag">
                <h1>Sabor artesanal,<br>gestión profesional.</h1>
                <p>Administra tu sede, controla tus inventarios y optimiza tus recetas desde una sola plataforma.</p>
                <div class="mk-dots">
                    <div class="mk-da"></div>
                    <div class="mk-db"></div>
                    <div class="mk-db"></div>
                </div>
            </div>
        </div>
        <div id="mk-dt-copy">© {{ date('Y') }} Makabro · Todos los derechos reservados.</div>
    </div>

    {{-- PANEL FORMULARIO --}}
    <div id="mk-form-panel">
        <div id="mk-form-inner">
            <div class="mk-heading">
                <h2>¡Hola, de nuevo! 👋</h2>
                <p>Selecciona tu sede e ingresa tus credenciales para continuar.</p>
            </div>
            <div class="mk-bar"></div>
            <div id="mk-card">
                {{ $this->content }}
            </div>
            <p class="mk-foot">© {{ date('Y') }} Makabro App · Todos los derechos reservados.</p>
        </div>
    </div>

</div>