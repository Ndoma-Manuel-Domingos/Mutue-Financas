<?php

namespace App\Http\Controllers;

use App\Models\TipoBolsaInsitituicao;
use Illuminate\Http\Request;

class AssociarBolsaInstituicaoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'tipo_bolsa' => 'required',
        ], []);
        
        try {
            
            $verificar = TipoBolsaInsitituicao::where('tipo_bolsa', $request->tipo_bolsa)->where('instituicao', $request->instituicao)->first();
            
            if(!$verificar){
                $request = TipoBolsaInsitituicao::create([
                    'tipo_bolsa' => $request->tipo_bolsa,
                    'instituicao' => $request->instituicao,
                ]);
            }
                        
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Ocorreu um erro {$th}"], 200);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $delete = TipoBolsaInsitituicao::findOrFail($id);
        $delete->delete();
        
        return redirect()->back();
    }
}
