<?php

namespace App\Http\Controllers;

use App\Exports\PropinasExport;
use App\Exports\ServicoExport;
use App\Models\AnoLectivo;
use App\Models\TipoServico;
use App\Models\Polo;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ServicoEmonumentoController extends Controller
{
    use TraitHelpers;

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    
    public function index(Request $request)
    {
        $data["filters"] = "";

        return Inertia::render('AreaFinanceira/ServicoEmonumento/Index', $data);
    }
    
    
    // estudantes com propinas pagas
    public function propinas(Request $request)
    {
        
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $data['servicos'] = TipoServico::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'desc'));
        })
        ->when($request->anolectivo, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
        })
        ->when($request->polo, function ($query, $value) {
            $query->where('polo_id', '=', $value);
        })
        ->when($request->search_descricao, function ($query, $value) {
            $query->where('descricao', 'like', "Propina %".$value."%" );
        })
        ->whereNull('codigo_grade_currilular')
        ->where('descricao', 'like', 'Propina %')
        ->with('polo')
        ->orderBy('Codigo', 'desc')
        ->paginate($request->page_size ?? 5)
        ->withQueryString();

        $data['totalServico'] = TipoServico::count();
        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();        $data['polos'] = Polo::get();

        return Inertia::render('ServicoEmonumentos/Propinas', $data);
    }

    public function updatePropinas(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required',
            'preco' => 'required',
            'polo_id' => 'required',
        ], [
            'descricao.required' => "Campo de caracter obrigatório.",
            'preco.required' => "Campo de caracter obrigatório.",
            'polo_id.required' => "Campo de caracter obrigatório.",
        ]);

        $servico = TipoServico::findOrFail($id);
        $servico->Preco = $request->preco;
        $servico->Descricao = $request->descricao;
        $servico->polo_id = $request->polo_id;
        $servico->update();

        return redirect()->back();

    }

    // pagamento por validar
    public function servicos(Request $request)
    {
        $porPagina = $request->porPagina;
        $search = $request->search;
        
        $ids = TipoServico::where('Descricao', 'like', 'Propina %')->where('codigo_ano_lectivo', $this->anoLectivoActivo())->pluck('Codigo');

        $data['servicos'] = TipoServico::when($search, function($query) use($search){
            $query->where("Descricao", "like", "%{$search}%");
            $query->orWhere("Preco", "=", $search);
        })
        ->whereNull('codigo_grade_currilular')
        ->whereNotIn('Codigo', $ids)
        ->orderBy('Codigo', 'desc')
        ->paginate($porPagina ?? 5)
        ->withQueryString();

        return Inertia::render('ServicoEmonumentos/Servicos', $data);
    }

    public function propinasPdfImprimir(Request $request)
    {   
        $data['servicos'] = TipoServico::when($request->a, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
        })
        ->when($request->p, function ($query, $value) {
            $query->where('polo_id', '=', $value);
        })
        ->with('polo')
        ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.servicos.propinas', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();

    }

    public function propinasExcelImprimir(Request $request)
    {
        if($request->a == "null"){ $request->a = ""; }
        if($request->p == "null"){ $request->p = ""; }

        return Excel::download(new PropinasExport, 'servicos.xlsx');
    }  
    
    
    public function servicosPdfImprimir(Request $request)
    {  
    
        $ids = TipoServico::where('Descricao', 'like', 'Propina %')->where('codigo_ano_lectivo', $this->anoLectivoActivo())->pluck('Codigo');
    
        $data['servicos'] = TipoServico::with('polo')->where('codigo_ano_lectivo', $this->anoLectivoActivo())->whereNull('codigo_grade_currilular')->whereNotIn('Codigo', $ids)->get();
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.servicos.servicos', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();

    }

    public function servicosExcelImprimir(Request $request)
    {
        return Excel::download(new ServicoExport, 'servicos.xlsx');
    } 

    public function servicosCreate(Request $request)
    {
    
        $ano_lectivo = AnoLectivo::where('estado', 'Activo')->first();
        
        $request->validate([
            'descricao' => 'required',
            'preco' => 'required',
            'tipoServico' => 'required',
        ], [
            'descricao.required' => "Campo de caracter obrigatório.",
            'preco.required' => "Campo de caracter obrigatório.",
            'tipoServico.required' => "Campo de caracter obrigatório.",
        ]);

        $servico = TipoServico::create([
            'Preco' => $request->preco,
            'Descricao' => $request->descricao,
            'TipoServico' => $request->tipoServico,
            'codigo_ano_lectivo' => $ano_lectivo->Codigo,
            'polo_id' => 1
        ]);

        return redirect()->back();

    }

    public function editarServico($id)
    {
        $servicos = TipoServico::findOrFail($id);

        return response()->json([
            "response" => $servicos
        ], 200);
    }

    public function updateServico(Request $request, $id)
    {
        $request->validate([
            'descricao' => 'required',
            'preco' => 'required',
        ], [
            'descricao.required' => "Campo de caracter obrigatório.",
            'preco.required' => "Campo de caracter obrigatório.",
        ]);

        $servico = TipoServico::findOrFail($request->id);
        $servico->Preco = $request->preco;
        $servico->Descricao = $request->descricao;
        $servico->TipoServico = $request->tipoServico;
        
        $servico->update();

        return redirect()->back();

    }

}
