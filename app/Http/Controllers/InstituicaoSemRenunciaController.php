<?php

namespace App\Http\Controllers;

use App\Models\Bolsa;
use App\Models\InstituicaoSemRenuncia;
use App\Models\TipoBolsaInsitituicao;
use App\Models\TipoInstituicao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstituicaoSemRenunciaController extends Controller
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

        $data['items'] = InstituicaoSemRenuncia::with('bolsas.bolsa')->when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
        ->when($request->search_instituicao_sem_renuncia, function ($query, $value) {
            $query->where('tipo_instituicao', 'sem renuncia');
            $query->where('Instituicao', 'LIKE', '%' . $value . '%');
            $query->orWhere("nif", "like", "%{$value}%");
            $query->orWhere("sigla", "like", "%{$value}%");
        })
        ->when($request->nome_instituicao_search, function($query, $value){
            $query->where('Instituicao',"like", "%{$value}%");
        })
        ->when($request->sigla_instituicao_search, function($query, $value){
            $query->where('sigla', "like","%{$value}%");
        })
        ->when($request->nif_instituicao_search, function($query, $value){
            $query->where('nif', "like","%{$value}%");
        })
        ->where('tipo_instituicao', 1)
        ->orderBy('Instituicao', 'asc')
        ->paginate(20)
        ->withQueryString();
        
        $data['tipo_bolsa'] = Bolsa::get();
        $data['tipo_instituicao'] = TipoInstituicao::get();
        
        $data['totalinstituicao'] = InstituicaoSemRenuncia::where('tipo_instituicao', 'sem renuncia')->count();
        $data['filtros'] = $request->all("instituicao");
        
        return Inertia::render('GestaoCreditoInstituicional/Sem-Renuncia/Index', $data);
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
            'instituicao' => 'required',
            'nif' => 'required',
        ], []);
        
        try {
            
            $create = InstituicaoSemRenuncia::create([
                'Instituicao' => $request->instituicao,
                'nif' => $request->nif,
                'contacto' => $request->contacto,
                'Endereco' =>$request->endereco, 
                'tipo_instituicao' => $request->tipo,
                'sigla' => $request->sigla,
            ]);
            
            foreach($request->tipos_bolsas as $item){
                $request = TipoBolsaInsitituicao::create([
                    'tipo_bolsa' => $item,
                    'instituicao' => $create->codigo,
                ]);
            }
            
            return response()->json(['message' => "Dados salvos com sucesso!"]);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Ocorreu um erro! {$th}"]);
        }

        // return redirect()->back();
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InstituicaoSemRenuncia  $instituicaoSemRenuncia
     * @return \Illuminate\Http\Response
     */
    public function show(InstituicaoSemRenuncia $instituicaoSemRenuncia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InstituicaoSemRenuncia  $instituicaoSemRenuncia
     * @return \Illuminate\Http\Response
     */
    public function edit(InstituicaoSemRenuncia $instituicaoSemRenuncia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InstituicaoSemRenuncia  $instituicaoSemRenuncia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'instituicao' => 'required',
            'nif' => 'required',
        ], [
        
        ]);
        
        try {
            
            $update = InstituicaoSemRenuncia::findOrFail($id);
            
            $update->Instituicao = $request->instituicao;
            $update->nif = $request->nif;
            $update->contacto = $request->contacto;
            $update->Endereco = $request->endereco; 
            $update->tipo_instituicao = $request->tipo;
            $update->sigla = $request->sigla;
            
            $update->update();
            
            foreach($request->tipos_bolsas as $item){
                $request = TipoBolsaInsitituicao::create([
                    'tipo_bolsa' => $item,
                    'instituicao' => $update->codigo,
                ]);
            }
            
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
            
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Ocorreu um erro {$th}"], 200);
        }
        
        // return response()->json(['data' => $data], 200);
        // return response()->json($update);
        // return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InstituicaoSemRenuncia  $instituicaoSemRenuncia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $update = InstituicaoSemRenuncia::findOrFail($id);
            $update->delete();
            
            return response()->json(['message' => "Dados salvos com sucesso!"], 200);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['error' => "Ocorreu um erro {$th}"], 200);
        }
        
        return redirect()->back();
    }
}
