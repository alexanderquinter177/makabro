<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Makabro' }}</title>
    @livewireStyles
    <!-- Cargar Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #fafaf9;
            color: #1c1917;
        }
        svg {
            display: inline-block;
            vertical-align: middle;
        }
        button {
            background: none;
            border: none;
            padding: 0;
            margin: 0;
            font: inherit;
            color: inherit;
            cursor: pointer;
        }

        /* ---- ESTILOS RESPONSIVOS PARA PANTALLA DIVIDIDA ---- */
        @media (min-width: 1024px) {
            .mk-layout-wrapper {
                flex-direction: row !important;
                height: 100vh !important;
                max-height: 100vh !important;
                overflow: hidden !important;
            }
            .mk-left-banner {
                width: 45% !important;
                height: 100% !important;
                min-height: 100% !important;
            }
            .mk-right-content {
                width: 55% !important;
                height: 100% !important;
                overflow-y: auto !important;
            }
            .mk-hide-mobile {
                display: block !important;
            }
            .mk-hide-desktop {
                display: none !important;
            }
        }

        @media (max-width: 1023px) {
            .mk-layout-wrapper {
                flex-direction: column !important;
                min-height: 100vh !important;
            }
            .mk-left-banner {
                width: 100% !important;
                height: 200px !important;
            }
            .mk-right-content {
                width: 100% !important;
                flex: 1 !important;
            }
            .mk-hide-mobile {
                display: none !important;
            }
            .mk-hide-desktop {
                display: block !important;
            }
        }
    </style>
</head>
<body class="bg-[#f5f5f4] text-[#1c1917] antialiased">
    {{ $slot }}
    @livewireScripts
</body>
</html>
