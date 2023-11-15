<?php

//namespace App\Http\Controllers;
namespace App\Http\Controllers\Generales;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use \PDF;



class ProtocoloController extends Controller
{
    public function protocolo()
    {
        $pathResolucion= public_path()."\storage\pdf\\resolucion.pdf";
        $pathFormulario= public_path()."\storage\pdf\\formulario_denuncia.pdf";

        return view('Generales.protocolo', compact('pathResolucion', 'pathFormulario'));
    }
}
