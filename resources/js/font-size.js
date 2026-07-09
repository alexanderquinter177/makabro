/**
 * Makabro — Font Size Switcher
 * Persiste la preferencia en localStorage y la aplica al <html>.
 * Se inicializa automáticamente al cargar la página.
 */
(function () {
    const STORAGE_KEY = 'mk_font_size';
    const SIZES = ['sm', 'md', 'lg'];
    const DEFAULT = 'md';

    /** Aplica la clase al <html> y actualiza los botones */
    function applySize(size) {
        if (!SIZES.includes(size)) size = DEFAULT;

        // Quitar clases anteriores
        SIZES.forEach(s => document.documentElement.classList.remove('mk-font-' + s));

        // Aplicar nueva clase
        document.documentElement.classList.add('mk-font-' + size);

        // Guardar preferencia
        localStorage.setItem(STORAGE_KEY, size);

        // Actualizar estado visual de los botones
        document.querySelectorAll('.mk-font-btn').forEach(btn => {
            btn.classList.toggle('mk-active', btn.dataset.size === size);
        });
    }

    /** Carga la preferencia guardada (o la por defecto) */
    function loadSaved() {
        return localStorage.getItem(STORAGE_KEY) || DEFAULT;
    }

    /** Inicialización: aplica el tamaño guardado ANTES de que se pinte la UI */
    applySize(loadSaved());

    /** Exponer función global para que los botones en el HTML puedan llamarla */
    window.mkSetFontSize = applySize;

    /**
     * Cuando el DOM esté listo, asignar listeners a los botones del switcher.
     * (También cubre navegación Livewire que re-renderiza el topbar)
     */
    function bindButtons() {
        document.querySelectorAll('.mk-font-btn').forEach(btn => {
            // Evitar doble binding
            if (btn.dataset.bound) return;
            btn.dataset.bound = '1';
            btn.addEventListener('click', () => applySize(btn.dataset.size));
        });
        // Sincronizar estado visual con el valor guardado
        const saved = loadSaved();
        document.querySelectorAll('.mk-font-btn').forEach(btn => {
            btn.classList.toggle('mk-active', btn.dataset.size === saved);
        });
    }

    document.addEventListener('DOMContentLoaded', bindButtons);

    // Re-bind después de cada navegación de Livewire (Filament usa Livewire navigate)
    document.addEventListener('livewire:navigated', bindButtons);
    document.addEventListener('livewire:initialized', bindButtons);
})();
