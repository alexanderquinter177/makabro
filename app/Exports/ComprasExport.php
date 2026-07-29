<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Database\Eloquent\Builder;

class ComprasExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    protected Builder $query;

    public function __construct(Builder $query)
    {
        $this->query = clone $query;
    }

    public function collection()
    {
        // Traer 1 fila por cada compra filtrada con sus relaciones generales
        return $this->query
            ->with(['sede', 'proveedor', 'usuario'])
            ->get();
    }

    public function headings(): array
    {
        return [
            'Factura #',
            'Fecha Factura',
            'Fecha Registro',
            'Sede',
            'Proveedor',
            'NIT Proveedor',
            'Tipo Compra',
            'Estado',
            'Forma de Pago',
            'Recibido Por',
            'Registrado Por',
            'Total ($)',
        ];
    }

    public function map($compra): array
    {
        /** @var \App\Models\Purchase\Compra $compra */
        $estadoLabel = match ($compra->status) {
            'borrador'  => 'Borrador',
            'pendiente' => 'Pendiente de Aprobación',
            'aprobado'  => 'Aprobado',
            'rechazado' => 'Rechazado',
            default     => $compra->status ?? '---',
        };

        $tipoCompraLabel = match ($compra->tipo_compra) {
            'materia_prima' => 'Materia Prima',
            'aseo'          => 'Aseo / Limpieza',
            'desechables'   => 'Desechables',
            'bebidas'       => 'Bebidas',
            'utensilios'    => 'Utensilios',
            'equipos'       => 'Equipos',
            'otros'         => 'Otros',
            default         => $compra->tipo_compra ?? '---',
        };

        return [
            $compra->numero_factura ?? '---',
            $compra->fecha_factura ? $compra->fecha_factura->format('d/m/Y') : '---',
            $compra->fecha_registro ? $compra->fecha_registro->format('d/m/Y') : '---',
            $compra->sede?->nombre ?? '---',
            $compra->proveedor?->nombre ?? '---',
            $compra->proveedor?->nit ?? '---',
            $tipoCompraLabel,
            $estadoLabel,
            ucfirst($compra->forma_pago ?? '---'),
            $compra->recibido_por ?? '---',
            $compra->usuario?->name ?? '---',
            floatval($compra->total ?? 0),
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E3A8A'],
                ],
            ],
        ];
    }
}
