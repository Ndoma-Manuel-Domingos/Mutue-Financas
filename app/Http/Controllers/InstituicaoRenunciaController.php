<?php

namespace App\Http\Controllers;

use App\Models\Bolsa;
use App\Models\Instituicacao;
use App\Models\InstituicaoRenuncia;
use App\Models\TipoBolsa;
use App\Models\TipoBolsaInsitituicao;
use App\Models\TipoInstituicao;
use Illuminate\Http\Request;
use Inertia\Inertia;

class InstituicaoRenunciaController extends Controller
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

        $data['items'] = InstituicaoRenuncia::with('bolsas.bolsa')->when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
        ->when($request->search, function ($query, $value) {
            $query->where('Instituicao', 'LIKE', '%' . $value . '%');
            $query->orWhere("nif", "like", "%{$value}%");
            $query->orWhere("sigla", "like", "%{$value}%");
        })
        ->where('tipo_instituicao', 2)
        ->orderBy('codigo', 'desc')
        ->paginate(5)
        ->withQueryString();

        $data['listae_tipos_bolsas'] = TipoBolsa::select('tb_tipo_bolsas.codigo AS id', 'tb_tipo_bolsas.designacao AS text')->get();

        $data['tipo_instituicao'] = TipoInstituicao::get();

        $data['totalinstituicao'] = InstituicaoRenuncia::where('tipo_instituicao', 2)->count();
        $data['filtros'] = $request->all("instituicao");

        return Inertia::render('GestaoCreditoInstituicional/Renuncia/Index.vue', $data);
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
            'tipos_bolsas' => 'required',
        ], [

        ]);

        $create = InstituicaoRenuncia::create([
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

        return redirect()->back();

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\InstituicaoRenuncia  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function show(InstituicaoRenuncia $instituicaoRenuncia)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\InstituicaoRenuncia  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function edit(InstituicaoRenuncia $instituicaoRenuncia)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\InstituicaoRenuncia  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'instituicao' => 'required',
            'nif' => 'required',
        ], [

        ]);

        $update = InstituicaoRenuncia::findOrFail($id);
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
        // return response()->json(['data' => $data], 200);
        // return response()->json($update);
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\InstituicaoRenuncia  $instituicaoRenuncia
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $update = InstituicaoRenuncia::findOrFail($id);
        $update->delete();

        return redirect()->back();
    }

}
