<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acta de Carga y Entrega #CARGA-{{ str_pad($cargaHistorial->id, 5, '0', STR_PAD_LEFT) }}</title>
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
            font-size: 12px;
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
            background-color: #10b981;
            color: #ffffff;
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
            background-color: #059669;
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
            font-size: 15px;
            font-weight: 800;
            color: #047857;
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
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            padding: 8px 8px;
            text-align: left;
        }
        .items-table td {
            padding: 7px 8px;
            border-bottom: 1px solid #e2e8f0;
            font-size: 11px;
        }
        .items-table tr:nth-child(even) {
            background-color: #f8fafc;
        }
        .items-table tfoot tr {
            background-color: #f1f5f9;
            font-weight: 700;
            border-top: 2px solid #0f172a;
        }
        .items-table tfoot td {
            font-size: 11px;
            padding: 8px 8px;
        }

        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .font-bold { font-weight: 700; }
        .text-success { color: #059669; }

        /* Signatures Section */
        .signatures-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-top: 45px;
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
            margin-top: 2px;
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
        <h1>Vista Previa de Impresión - Acta de Carga de Productos</h1>
        <button class="btn-print" onclick="window.print()">
            <svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                <path d="M6 9V2h12v7M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2m-4 0v4H10v-4"></path>
            </svg>
            Imprimir Acta / Guardar PDF
        </button>
    </div>

    <!-- Hoja Tamaño Carta -->
    <div class="carta-page">

        <!-- Encabezado Principal -->
        <div class="header">
            <div class="brand-section">
                <h2>MAKABRO</h2>
                <p>Sistema de Control de Calidad e Inventarios</p>
                <p>Módulo: <strong>Historial de Entregas</strong></p>
            </div>
            <div class="doc-title-box">
                <div class="doc-title">Acta de Carga de Productos</div>
                <div class="doc-number">Acta N° #CARGA-{{ str_pad($cargaHistorial->id, 5, '0', STR_PAD_LEFT) }}</div>
                <div class="doc-date">Fecha Carga: {{ $cargaHistorial->fecha ? $cargaHistorial->fecha->format('d/m/Y') : 'N/A' }}</div>
            </div>
        </div>

        <!-- Rejilla de Información General -->
        <div class="info-grid">
            <div class="info-item">
                <span class="info-label">Fecha de Entrega</span>
                <span class="info-value">{{ $cargaHistorial->fecha ? $cargaHistorial->fecha->format('d/m/Y') : 'N/A' }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Tipo de Entrega</span>
                <span class="info-value">
                    <span class="badge {{ $cargaHistorial->tipo === 'Entrega de barra' ? 'badge-info' : 'badge-warning' }}">
                        {{ strtoupper($cargaHistorial->tipo ?? 'N/A') }}
                    </span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Cargo Quien Recibe</span>
                <span class="info-value">
                    <span class="badge badge-warning">{{ strtoupper($cargaHistorial->cargo_recibe ?? 'N/A') }}</span>
                </span>
            </div>
            <div class="info-item">
                <span class="info-label">Nombre Quien Recibe</span>
                <span class="info-value">{{ strtoupper($cargaHistorial->nombre_recibe ?? 'N/A') }}</span>
            </div>
            <div class="info-item">
                <span class="info-label">Total Ítems Cargados</span>
                <span class="info-value">{{ $cargaHistorial->productos->count() }} Productos</span>
            </div>
            <div class="info-item">
                <span class="info-label">Valor Total Carga</span>
                <span class="info-value font-bold text-success" style="font-size: 13px;">
                    ${{ number_format($cargaHistorial->valor_total, 2, ',', '.') }}
                </span>
            </div>
        </div>

        <!-- Tabla de Ítems Cargados -->
        <div class="table-section-title">Detalle de Productos Ingresados</div>
        <table class="items-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 30px;">#</th>
                    <th style="width: 125px; white-space: nowrap;">Código</th>
                    <th>Producto / Descripción</th>   
                    <th class="text-center" style="width: 55px;">U.M.</th>
                    <th class="text-right" style="width: 80px;">Cantidad</th>
                    <th class="text-right" style="width: 85px;">Precio ($)</th>
                    <th class="text-right" style="width: 95px;">Total Línea ($)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalPrecio = 0;
                @endphp
                @forelse($cargaHistorial->productos as $index => $item)
                    @php
                        $totalPrecio += $item->precio;
                    @endphp
                    <tr>
                        <td class="text-center font-bold" style="color: #64748b;">{{ $index + 1 }}</td>
                        <td class="font-bold" style="white-space: nowrap; font-family: monospace; font-size: 10px;">{{ $item->codigo }}</td>
                        <td class="font-bold">{{ $item->nombre_producto }}</td>
                        <td class="text-center"><span class="badge badge-gray">{{ $item->unidad_medida }}</span></td>
                        <td class="text-right font-bold">{{ number_format($item->cantidad, 2, ',', '.') }}</td>
                        <td class="text-right">${{ number_format($item->precio, 2, ',', '.') }}</td>
                        <td class="text-right font-bold text-success">${{ number_format($item->total_linea, 2, ',', '.') }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center" style="padding:20px; color: #94a3b8;">Sin productos registrados en esta carga.</td>
                    </tr>
                @endforelse
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="5" class="text-right">TOTALES ACUMULADOS DE LA CARGA:</td>
                    <td class="text-right font-bold">${{ number_format($totalPrecio, 2, ',', '.') }}</td>
                    <td class="text-right font-bold text-success" style="font-size: 11px;">
                        ${{ number_format($cargaHistorial->valor_total, 2, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>

        <!-- Firmas de Conformidad -->
        <div class="signatures-grid">
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-role">Entregado Por</div>
                <div class="signature-name">Nombre y Firma Entregador</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-role">Recibido Por ({{ strtoupper($cargaHistorial->cargo_recibe ?? 'Responsable') }})</div>
                <div class="signature-name">{{ strtoupper($cargaHistorial->nombre_recibe ?? 'Nombre y Firma Recibe') }}</div>
            </div>
            <div class="signature-box">
                <div class="signature-line"></div>
                <div class="signature-role">Administración / Verificación Sede</div>
                <div class="signature-name">Aprobado / Verificado</div>
            </div>
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
