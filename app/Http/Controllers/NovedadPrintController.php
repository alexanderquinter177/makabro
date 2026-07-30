<?php

namespace App\Http\Controllers;

use App\Models\Inventory\Novedad;
use Illuminate\Http\Request;

class NovedadPrintController extends Controller
{
    public function __invoke(Request $request, Novedad $novedad)
    {
        $novedad->load([
            'sede',
            'usuario',
            'responsable',
            'producto.unidadCompra',
        ]);

        return view('novedades.imprimir', compact('novedad'));
    }
}
