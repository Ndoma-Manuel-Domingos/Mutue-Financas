<?php

namespace App\Http\Controllers;

use App\Models\Bolsa;
use App\Models\Instituicacao;
use App\Models\TipoBolsa;
use App\Models\TipoBolsaInsitituicao;
use App\Models\TipoInstituicao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TipoBolsaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }
        
        $data['items'] = TipoBolsa::with('instituicoes.instituicao')->when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
        ->when($request->search, function ($query, $value) {
            $query->where('designacao', 'LIKE', '%' . $value . '%');
        })
        ->orderBy('codigo', 'desc')
        ->paginate(5)
        ->withQueryString();
        
        $data['totalTipoBolsa'] = TipoBolsa::count();
        $data['tipo_instituicao'] = TipoInstituicao::get();
        $data['instituicoes'] = Instituicacao::when($request->tipo_instituicao_id, function($query, $value){
            $query->where('tipo_instituicao', $value);
        })->get();
        // 
        $data['filtros'] = $request->all("designacao", "tipo_instituicao");
        
        return Inertia::render('GestaoCreditoInstituicional/Tipobolsa/Index', $data);
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
        $request->validate([
            'designacao' => 'required',
        ], [
        
        ]);
        
        $data = TipoBolsa::create([
            'designacao' => $request->designacao,
        ]);
        
        $request = TipoBolsaInsitituicao::create([
            'tipo_bolsa' => $data->codigo,
            'instituicao' => $request->instituicao_id,
        ]);
        
        // return response()->json(['data' => $data], 200);
        return redirect()->back();
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
        $request->validate([
            'designacao' => 'required',
        ], [
        
        ]);
        
        $update = TipoBolsa::findOrFail($id);
        
        $update->designacao = $request->designacao;
        
        $update->update();
        
        // return response()->json(['data' => $data], 200);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $update = TipoBolsa::findOrFail($id);
        $update->delete();
        return redirect()->back();
    }
    
    
    public function listar_bolsas()
    {
        $data['listae_tipos_bolsas'] = Bolsa::select('tb_tipo_bolsas.codigo AS id', 'tb_tipo_bolsas.designacao AS text')->get();
       return  response()->json(['data' => $data], 200);
    }
}
