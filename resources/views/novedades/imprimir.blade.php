<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Novedad y Merma #MER-{{ str_pad($novedad->id, 5, '0', STR_PAD_LEFT) }}</title>
    <style>
        /* Baseline Styles */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            background-color: #f8fafc;
            font-size: 13px;
            line-height: 1.4;
        }

        /* Action Toolbar */
        .no-print-bar {
            background-color: #0f172a;
            color: #ffffff;
            padding: 12px 24px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }
        .no-print-bar h1 {
            font-size: 16px;
            font-weight: 600;
        }
        .btn-print {
            background-color: #f59e0b;
            color: #0f172a;
            border: none;
            padding: 8px 18px;
            font-size: 13px;
            font-weight: 700;
            border-radius: 6px;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s ease;
        }
        .btn-print:hover {
            background-color: #d97706;
            color: #ffffff;
        }

        /* Page Container - Tamaño Carta */
        .carta-page {
            width: 215.9mm;
            min-height: 279.4mm;
            margin: 20px auto;
            background: #ffffff;
            padding: 16mm 18mm;
            border-radius: 4px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.08);
            position: relative;
        }

        /* Header Section */
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 2px solid #e2e8f0;
            padding-bottom: 14px;
            margin-bottom: 16px;
        }
        .brand-section h2 {
            font-size: 24px;
            font-weight: 800;
            color: #0f172a;
            letter-spacing: -0.5px;
            text-transform: uppercase;
        }
        .brand-section p {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }
        .doc-title-box {
            text-align: right;
        }
        .doc-title {
            font-size: 16px;
            font-weight: 800;
            color: #b45309;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .doc-number {
            font-size: 14px;
            font-weight: 700;
            color: #334155;
            margin-top: 2px;
        }
        .doc-date {
            font-size: 11px;
            color: #64748b;
            margin-top: 2px;
        }

        /* Grid Information */
        .info-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 12px;
            margin-bottom: 18px;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 12px 14px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            color: #64748b;
            margin-bottom: 2px;
        }
        .info-value {
            font-size: 12px;
            font-weight: 600;
            color: #0f172a;
        }

        /* Badges */
        .badge {
            display: inline-block;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            width: fit-content;
        }
        .badge-warning { background-color: #fef3c7; color: #92400e; }
        .badge-danger  { background-color: #fee2e2; color: #991b1b; }
        .badge-info    { background-color: #e0f2fe; color: #075985; }
        .badge-success { background-color: #dcfce7; color: #166534; }
        .badge-gray    { background-color: #f1f5f9; color: #475569; }

        /* Items Table */
        .table-section-title {
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 6px;
            letter-spacing: 0.5px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 18px;
            border: 1px solid #cbd5e1;
        }
        .items-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 8px 10px;
            text-align: left;
        }
        .items-table td {
            padding: 8px 10px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 12px;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }

        /* Description Box */
        .box-section {
            margin-bottom: 18px;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            overflow: hidden;
        }
        .box-header {
            background-color: #f1f5f9;
            padding: 6px 12px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #334155;
            border-bottom: 1px solid #cbd5e1;
        }
        .box-content {
            padding: 10px 12px;
            font-size: 12px;
            color: #334155;
            min-height: 48px;
            white-space: pre-wrap;
        }

        /* Evidence Image Section */
        .evidence-container {
            margin-bottom: 18px;
            text-align: center;
            border: 1px solid #cbd5e1;
            border-radius: 6px;
            padding: 10px;
            background-color: #fafafa;
        }
        .evidence-title {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            color: #475569;
            margin-bottom: 8px;
        }
        .evidence-img {
            max-width: 100%;
            max-height: 180mm;
            object-fit: contain;
            border-radius: 4px;
            border: 1px solid #e2e8f0;
        }

        /* Signatures Section */
        .signatures-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 40px;
            padding-top: 10px;
        }
        .signature-box {
            text-align: center;
        }
        .signature-line {
            border-top: 1.5px dashed #475569;
            margin-bottom: 6px;
        }
        .signature-role {
            font-size: 11px;
            font-weight: 700;
            color: #0f172a;
            text-transform: uppercase;
        }
        .signature-name {
            font-size: 10px;
            color: #64748b;
        }

        /* Footer */
        .doc-footer {
            margin-top: 30px;
            border-top: 1px solid #e2e8f0;
            padding-top: 8px;
            font-size: 9px;
            color: #94a3b8;
            display: flex;
            justify-content: space-between;
        }

        /* Print Media Styles */
        @media print {
            html, body {
                background: #ffffff !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            .no-print-bar {
                display: none !important;
            }
            .carta-page {
                width: 100% !important;
                max-width: 100% !important;
                min-height: auto !important;
                margin: 0 !important;
                padding: 12mm 15mm !important;
                box-shadow: none !important;
                border-radius: 0 !important;
                border: none !important;
            }
            @page {
                size: letter portrait;
                margin: 0;
            }
        }
    </style>
</head>
<body>

    <!-- Bar de Control (Se oculta al imprimir) -->
    <div class="no-print-bar">
        <h1>Vista Previa de Impresión - Tamaño Carta</h1>
        <button class="btn-print" onclick="window.print()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-4 0v4H10v-4"></path>
            </svg>
            Imprimir Documento / Guardar PDF
        </button>
    </div>

    <!-- Hoja Tamaño Carta -->
    <div class="carta-page">

        <!-- Encabezado Principal -->
        <div class="header">
            <div class="brand-section">
                <h2>MAKABRO</h2>
                <p>Sistema de Control de Calidad e Inventarios</p>
                <p>Sede: <strong>{{ $novedad->sede->nombre ?? 'N/A' }}</strong></p>
            </div>
            <div class="doc-title-box">
                <div class="doc-title">Comprobante de Novedad / Merma</div>
                <div class="doc-number">Acta N° #MER-{{ str_pad($novedad->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="doc-date">Fecha Registro: {{ $novedad->created_at ? $novedad->created_at->format('d/m/Y h:i A') : 'N/A' }}</div>
            </div>
        </div>

        <!-- Rejilla de Información General -->
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Sede / Ubicación</span>
                <span class="info-value">{{ $novedad->sede->nombre ?? 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Área Afectada</span>
                <span class="info-value">{{ strtoupper($novedad->area ?? 'N/A') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tipo de Novedad</span>
                <span class="info-value">
                    @php
                        $badgeClass = match($novedad->tipo) {
                            'caída/quiebre' => 'badge-warning',
                            'quemado' => 'badge-danger',
                            'vencimiento' => 'badge-info',
                            'daño' => 'badge-danger',
                            'devolución' => 'badge-gray',
                            'pérdida/robo' => 'badge-danger',
                            default => 'badge-gray'
                        };
                    @endphp
                    <span class="badge {{ $badgeClass }}">{{ strtoupper($novedad->tipo ?? 'N/A') }}</span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Registrado Por</span>
                <span class="info-value">{{ $novedad->usuario->name ?? 'Usuario Sistema' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Empleado Responsable</span>
                <span class="info-value">{{ $novedad->responsable_nombre ?? $novedad->responsable->name ?? 'No Asignado' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Aplica Cobro a Empleado</span>
                <span class="info-value">
                    @if($novedad->estado_cobro === 'si')
                        <span class="badge badge-danger">SÍ APLICA</span>
                    @elseif($novedad->estado_cobro === 'pendiente')
                        <span class="badge badge-warning">PENDIENTE</span>
                    @else
                        <span class="badge badge-success">NO APLICA</span>
                    @endif
                </span>
            </div>
        </div>

        <!-- Tabla de Ítems Afectados -->
        <div class="table-section-title">Detalle del Ítem / Producto Afectado</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th>Tipo Elemento</th>
                    <th>Código / SKU</th>
                    <th>Descripción / Nombre</th>
                    <th class="text-center">U.M.</th>
                    <th class="text-right">Cantidad</th>
                    <th class="text-right">Costo Total ($)</th>
                    <th class="text-right">Cobro Responsable ($)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-bold">{{ strtoupper($novedad->tipo_afectado ?? 'General') }}</td>
                    <td>
                        @if($novedad->tipo_afectado === 'producto' && $novedad->producto)
                            {{ $novedad->producto->codigo ?? 'N/A' }}
                        @elseif($novedad->tipo_afectado === 'plato' && $novedad->plato)
                            PLT-{{ str_pad($novedad->plato->id, 4, '0', STR_PAD_LEFT) }}
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="font-bold">
                        @if($novedad->tipo_afectado === 'producto' && $novedad->producto)
                            {{ $novedad->producto->nombre }}
                        @elseif($novedad->tipo_afectado === 'plato' && $novedad->plato)
                            {{ $novedad->plato->nombre }}
                        @else
                            {{ $novedad->descripcion ? Str::limit($novedad->descripcion, 40) : 'Novedad General' }}
                        @endif
                    </td>
                    <td class="text-center">
                        @if($novedad->tipo_afectado === 'producto' && $novedad->producto)
                            {{ $novedad->producto->unidadCompra->nombre ?? 'UND' }}
                        @else
                            UND
                        @endif
                    </td>
                    <td class="text-right font-bold">{{ number_format($novedad->cantidad, 2) }}</td>
                    <td class="text-right font-bold">${{ number_format($novedad->valor_costo, 2) }}</td>
                    <td class="text-right font-bold" style="color: {{ $novedad->valor_cobro > 0 ? '#b91c1c' : '#475569' }};">
                        ${{ number_format($novedad->valor_cobro, 2) }}
                    </td>
                </tr>
            </tbody>
        </table>

        <!-- Caja de Observaciones / Descripción -->
        <div class="box-section">
            <div class="box-header">Motivo / Descripción Detallada de la Novedad</div>
            <div class="box-content">{{ $novedad->descripcion ?: 'Sin observaciones adicionales registradas.' }}</div>
        </div>

        <!-- Evidencia Fotográfica (Si aplica) -->
        @if(!empty($novedad->evidencia_imagen))
            <div class="evidence-container">
                <div class="evidence-title">Evidencia Fotográfica Adjunta</div>
                <img class="evidence-img" src="{{ asset('storage/' . $novedad->evidencia_imagen) }}" alt="Evidencia de Novedad">
            </div>
        @endif

        <!-- Firmas de Conformidad -->
        <div class="signatures-grid">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-role">Empleado Responsable</div>
                <div class="signature-name">{{ $novedad->responsable_nombre ?? $novedad->responsable->name ?? 'Nombre y Firma' }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-role">Registrado Por / Testigo</div>
                <div class="signature-name">{{ $novedad->usuario->name ?? 'Usuario Sistema' }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-role">Gerencia / Auditoría Sede</div>
                <div class="signature-name">Aprobado / Verificado</div>
            </div>
        </div>

        <!-- Pie de página -->
        <div class="doc-footer">
            <span>Generado automáticamente por Makabro ERP - Módulo de Novedades y Mermas</span>
            <span>Página 1 de 1 - Documento Oficial de Inventario</span>
        </div>

    </div>

    <script>
        // Disparar la impresión automáticamente si la ventana se abre directamente
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                window.print();
            }, 600);
        });
    </script>
</body>
</html>
