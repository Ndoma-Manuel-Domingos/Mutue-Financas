<?php

namespace App\Http\Controllers;

use App\Models\MesTemp;
use Facade\FlareClient\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class AjudaSistemaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Ajuda/Index');
    }


    public function baixarManualUtilizador()
    {
        $file = public_path('manualUtilizador');

        return response()->download($file, 'manualUtilizador.pdf');
    }    
    
    public function getMeses(Request $request)
    {
       $meses = MesTemp::when($request->ano_isencao, function($q, $v){
            $q->where('ano_lectivo', $v);
       })->where('activo', '1')->get();
 
    }
}
