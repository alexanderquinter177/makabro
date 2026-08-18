<?php

namespace App\Http\Controllers;

use App\Models\Inventory\CargaHistorial;
use Illuminate\Http\Request;

class HistorialCargaPrintController extends Controller
{
    public function __invoke(Request $request, CargaHistorial $cargaHistorial)
    {
        $cargaHistorial->load([
            'productos',
            'sede',
        ]);

        return view('historial-cargas.imprimir', compact('cargaHistorial'));
    }
}
