<?php

namespace App\Http\Controllers;

use App\Exports\InstituicaoExport;
use App\Models\AlunoAdmissao;
use App\Models\AnoLectivo;
use App\Models\Banco;
use App\Models\Bolsa;
use App\Models\Bolseiro;
use App\Models\Curso;
use App\Models\DescontoAluno;
use App\Models\estados;
use App\Models\Factura;
use App\Models\FacturaItem;
use App\Models\Faculdade;
use App\Models\FormaPagamento;
use App\Models\Instituicacao;
use App\Models\Isencao;
use App\Models\Matricula;
use App\Models\MesTemp;
use App\Models\MotivoIsencao;
use App\Models\Pagamento;
use App\Models\PagamentoItems;
use App\Models\PreInscricao;
use App\Models\Simestre;
use App\Models\tipo_Desconto;
use App\Models\TipoBolsa;
use App\Models\TipoBolsaInsitituicao;
use App\Models\TipoInstituicao;
use App\Models\TipoServico;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

use NumberFormatter;

class GestaodeBolsaeDescontoController extends Controller
{

    use TraitHelpers;
    //

    public function __construct()
    {
        $this->middleware('auth');
    }

    // estudantes com propinas pagas
    public function atribuicaoBolsa(Request $request)
    {

        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        // dd("djnglkjshdlk");

        $data['tipoBolsas'] = TipoBolsaInsitituicao::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
        ->when($request->instituicao, function ($query, $value) {
            $query->where('instituicao', '=', $value);
        })
        ->join('tb_tipo_bolsas', 'tb_tipo_bolsa_instituicao.tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
        ->select('tb_tipo_bolsas.codigo', 'tb_tipo_bolsas.designacao')
        ->get();

        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();        // $data['tipoBolsas'] = TipoBolsa::get();
        $data['tipo_instituicoes'] = TipoInstituicao::get();
        $data['instituicoes'] = Instituicacao::when($request->tipo_instituicao, function($query, $value){
            $query->where('tipo_instituicao', '=', $value);
        })->get();
        $data['semestres'] = Simestre::get();
        $data['filtros'] = $request->all('anolectivo', 'instituicao');
        $data['anolectivoactivo'] = AnoLectivo::where('estado', 'Activo')->first()->Codigo;

        if (!empty(session()->has('confirmar_estudante_bolsa_mutue_finance'))) {
            $data['numero_matricula'] = session('confirmar_estudante_bolsa_mutue_finance');
        }

        return Inertia::render('GestaoCreditoInstituicional/AtribuicaoBolsa', $data);
    }

    public function atribuicaoDesconto(Request $request)
    {
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $data['tipoBolsas'] = TipoBolsaInsitituicao::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
            ->when($request->instituicao, function ($query, $value) {
                $query->where('instituicao', '=', $value);
            })
            ->join('tb_tipo_bolsas', 'tb_tipo_bolsa_instituicao.tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
            ->select('tb_tipo_bolsas.codigo', 'tb_tipo_bolsas.designacao')
            ->get();

        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();        $data['status'] = estados::get();
        $data['tipoDesconto'] = tipo_Desconto::get();
        $data['tipo_instituicoes'] = TipoInstituicao::get();
        $data['instituicoes'] = Instituicacao::when($request->tipo_instituicao, function($query, $value){
            $query->where('tipo_instituicao', '=', $value);
        })->get();
        $data['semestres'] = Simestre::get();
        $data['filtros'] = $request->all('anolectivo', 'instituicao');
        $data['anolectivoactivo'] = AnoLectivo::where('estado', 'Activo')->first()->Codigo;

        if (!empty(session()->has('confirmar_estudante_bolsa_mutue_finance'))) {
            $data['numero_matricula'] = session('confirmar_estudante_bolsa_mutue_finance');
        }

        $data ['listarAlunoDesconto']= DB::table('tb_descontos_alunoo')
        ->join('tb_matriculas', 'tb_descontos_alunoo.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_ano_lectivo','tb_descontos_alunoo.codigo_anoLectivo','=','tb_ano_lectivo.Codigo')
        ->join('tb_Instituicao','tb_descontos_alunoo.instituicao_id','=','tb_Instituicao.codigo')
        ->join('tb_tipo_descontos','tb_descontos_alunoo.codigo_tipo_desconto','=','tb_tipo_descontos.Codigo')
        ->join('tb_semestres','tb_descontos_alunoo.semestre','=','tb_semestres.Codigo')
        ->join('tb_status','tb_descontos_alunoo.estatus_desconto_id','=','tb_status.Codigo')
        ->join('tb_admissao','tb_matriculas.Codigo_Aluno','=','tb_admissao.Codigo')
        ->join('tb_preinscricao','tb_preinscricao.Codigo','=','tb_admissao.pre_incricao')
        
        ->select('tb_matriculas.Codigo AS matricula','tb_ano_lectivo.Designacao As Ano','tb_Instituicao.Instituicao AS NomeInstituicao','tb_tipo_descontos.designacao AS tipoDesconto','tb_descontos_alunoo.estatus_desconto_id', 
        'tb_descontos_alunoo.afectacao','tb_semestres.Designacao AS semestre','tb_tipo_descontos.valor_desconto AS valor','tb_status.Designacao AS status', 'tb_preinscricao.Nome_Completo', 'tb_descontos_alunoo.codigo_anoLectivo',
        'tb_descontos_alunoo.instituicao_id', 'tb_descontos_alunoo.semestre AS semestre_id', 'tb_descontos_alunoo.codigo_tipo_desconto', 'tb_descontos_alunoo.codigo', 'tb_descontos_alunoo.codigo_matricula')
        ->paginate(50);

        return Inertia::render('GestaodeBolsaeDesconto/AtribuicaoDesconto',$data);
    }


    public function atribuicaoBolsaCarregarInstituicao(Request $request)
    {
        
        $instituicao = Instituicacao::findOrFail($request->instituicao);
    
        $resultado = TipoBolsaInsitituicao::when($request->instituicao, function ($query, $value) {
            $query->where('instituicao', '=', $value);
        })
        ->join('tb_tipo_bolsas', 'tb_tipo_bolsa_instituicao.tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
        ->select('tb_tipo_bolsas.codigo', 'tb_tipo_bolsas.designacao')
        ->get();
            
        return response()->json(['tipo_bolsas' => $resultado, 'instituicao' => $instituicao], 200);
            
    }

    // pagamento por validar
    public function  estatistica(Request $request)
    {


        $ano = AnoLectivo::where('estado', 'Activo')->first();
        if ($request->ano_lectivo == null) {
            $request->ano_lectivo = $ano->Codigo;
        }

        // $totalinstituicao = Instituicacao::count();
        // $totalBolsa = Bolseiro::count();
        // $totalBolsainativa = Bolseiro::where('status', 0)->count();
        // $totalBolsaActiva = Bolseiro::where('status', 1)->count();
        // $TipoBolsa = TipoBolsa::count();
        $anos_lectivos = AnoLectivo::orderBy('ordem', 'asc')->get();
        if (isset($request->ano_lectivo)) {

            $totalBolsa = Bolseiro::where('codigo_anoLectivo', $request->ano_lectivo)->count();
            $totalinstituicao = Instituicacao::count();
            $TipoBolsa = TipoBolsa::count();
            $totalBolsaActiva = Bolseiro::where('status', 1)->where('codigo_anoLectivo', $request->ano_lectivo)->count();
            $totalBolsainativa = Bolseiro::where('status', 0)->where('codigo_anoLectivo', $request->ano_lectivo)->count();
        } else {
            $totalBolsa = Bolseiro::where('codigo_anoLectivo', $ano->Codigo)->count();
        }




        $data['anos_lectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();        $ano = AnoLectivo::when($request->ano_lectivo, function ($query, $value) {
            $query->where('Codigo', $value);
        })->first();


        $ano_lectivo = $ano->Designacao;

        $filtros = $request->all('ano_lectivo');

        return Inertia::render('GestaodeBolsaeDesconto/Estatistica', [
            "ano_lectivo" => $ano_lectivo,
            "anos_lectivos" => $anos_lectivos,
            "totalBolsa" => $totalBolsa,
            "filtros" => $filtros,
            "totalinstituicacao" => $totalinstituicao,
            "totalBolsainativa" => $totalBolsainativa,
            "totalBolsaActiva" => $totalBolsaActiva,
            "TipoBolsa" => $TipoBolsa,


        ]);
    }

    public function instituicoes(Request $request)
    {

        $TipoBolsa = Bolsa::get();
        $instituicao = Instituicacao::when($request->instituicao, function ($query, $value) {
            $query->where("Instituicao", "like", "%{$value}%");
            $query->orWhere("nif", "like", "%{$value}%");
            $query->orWhere("sigla", "like", "%{$value}%");
        })
            ->paginate(5)
            ->withQueryString();


        $totalinstituicao = Instituicacao::count();

        return Inertia::render('GestaodeBolsaeDesconto/Instituicao', [
            "instituicacao" => $instituicao,
            "totalinstituicacao" => $totalinstituicao,
            "TipoBolsa" => $TipoBolsa,
            "filtros" => $request->all("instituicao")
        ]);
    }

    public function instituicoesPdf($ins = null)
    {
        if ($ins == "null") {
            $ins = "";
        }

        $data['instituicao'] = Instituicacao::when($ins, function ($query, $value) {
            $query->where("Instituicao", "like", "%{$value}%");
            $query->orWhere("nif", "like", "%{$value}%");
            $query->orWhere("sigla", "like", "%{$value}%");
        })
            ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.Gestao-bolsa-desconto.instituicoes', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function instituicoesExcel($ins = null)
    {
        if ($ins == "null") {
            $ins = "";
        }

        return Excel::download(new InstituicaoExport($ins), 'instituicoes.xlsx');
    }



    public function novaInstituicao(Request $request)
    {
        $request->validate([
            'Instituicao' => 'required',
            'nif' => 'required',
            'contacto' => 'required',
            'Endereco' => 'required',
            'sigla' => 'required',
        ], [
            'Instituicao.required' => "Campo de caracter obrigatório.",
            'nif.required' => "Campo de caracter obrigatório.",
            'contacto.required' => "Campo de caracter obrigatório.",
            'Endereco.required' => "Campo de caracter obrigatório.",
            'sigla.required' => "Campo de caracter obrigatório.",

        ]);


        $request = Instituicacao::create([
            'Instituicao' => $request->Instituicao,
            'nif' => $request->nif,
            'contacto' => $request->contacto,
            'Endereco' => $request->Endereco,
            'sigla' => $request->sigla,
            'tipo_instituicao' => "com renuncia",
        ]);

        return redirect()->back();
    }

    public function editarInstituicao(Request $request)
    {
        $instituicao = Instituicacao::where("codigo", $request->id)->first();
        return inertia('GestaodeBolsaeDesconto/Instituicao', [
            'instituicao' => $instituicao
        ]);
    }

    public function updateInstituicao(Request $request, $id)
    {
        $request->validate([
            'Instituicao' => 'required',
            'nif' => 'required',
            'contacto' => 'required',
            'Endereco' => 'required',
            'sigla' => 'required',
        ], [
            'Instituicao.required' => "Campo de caracter obrigatório.",
            'nif.required' => "Campo de caracter obrigatório.",
            'contacto.required' => "Campo de caracter obrigatório.",
            'Endereco.required' => "Campo de caracter obrigatório.",
            'sigla.required' => "Campo de caracter obrigatório.",

        ]);

        $request = Instituicacao::where("codigo", $request->id)->update([
            'Instituicao' => $request->Instituicao,
            'nif' => $request->nif,
            'contacto' => $request->contacto,
            'Endereco' => $request->Endereco,
            'sigla' => $request->sigla,
            'tipo_instituicao' => "com renuncia",
        ]);

        return redirect()->back();
    }

    // public function bolsaInstuicao()
    // {
    //         $bolsa = TipoBolsa::get();
    //         return Inertia::render('GestaodeBolsaeDesconto/Instituicao',[

    //             "bolsa" => $bolsa,

    //         ]);
    //     }

    public function bolsaInstuicao(Request $request)
    {
        $request->validate([
            'tipo_bolsa' => 'required',
            'instituicao' => 'required',

        ], [
            'tipo_bolsa.required' => "Selecione o tipo de Bolsa.",
            'instituicao.required' => "Campo de caracter obrigatório.",
        ]);
        
        $request = TipoBolsaInsitituicao::create([
            'tipo_bolsa' => $request->tipo_bolsa,
            'instituicao' => $request->instituicao,
        ]);
    }


    public function isencaodePagamentos(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        if ($request->AnoLectivo == null) {
            $request->AnoLectivo = $ano->Codigo;
        }

        if ($request->Instituicao == null) {
            $request->Instituicao = "";
        }

        if ($request->TipoBolsa == "null") {
            $request->TipoBolsa = "";
        }
        if ($request->Servico == "null") {
            $request->Servico = "";
        }

        $anolectivos = AnoLectivo::orderBy('ordem', 'asc')->get();        $instituicao = Instituicacao::get();
        $tipoServico = TipoServico::get();
        $motivoIsencao = MotivoIsencao::get();

        $bolsa = TipoBolsaInsitituicao::when($request->Instituicao, function ($query, $value) {
            $query->where('tb_tipo_bolsa_instituicao.instituicao', $value);
        })
            ->join('tb_tipo_bolsas', 'tb_tipo_bolsa_instituicao.tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
            ->select('tb_tipo_bolsas.codigo', 'tb_tipo_bolsas.designacao')
            ->get();

        // inicialmente
        $insecaoPamento = Isencao::when($request->AnoLectivo, function ($query, $value) {
            $query->where('tb_isencoes.codigo_anoLectivo', $value);
        })
            ->when($request->Servico, function ($query, $value) {
                $query->where('tb_isencoes.codigo_servico', $value);
            })
            ->when($request->Motivo, function ($query, $value) {
                $query->where('tb_isencoes.Codigo_motivo', $value);
            })
            ->when($request->TipoBolsa, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
            })
            ->join('tb_bolseiros', 'tb_isencoes.codigo_matricula', '=', 'tb_bolseiros.codigo_matricula')
            ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
            ->join('tb_tipo_servicos', 'tb_isencoes.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('tb_ano_lectivo', 'tb_isencoes.codigo_anoLectivo', '=', 'tb_ano_lectivo.Codigo')
            ->join('tb_utilizadores', 'tb_isencoes.codigo_utilizador', '=', 'tb_utilizadores.Codigo')

            // ->join('tb_instituicao', 'tb_isencoes.codigo_instituicao', '=', 'tb_instituicao.codigo')
            ->select('tb_isencoes.codigo As referencia',  'tb_isencoes.data_isencao AS data', 'tb_tipo_servicos.Descricao AS servico', 'tb_ano_lectivo.Designacao AS Anolectivo', 'tb_utilizadores.Nome AS usuario')
            ->paginate(5);




        return Inertia::render('GestaodeBolsaeDesconto/IsencaoPagamento', [
            "anolectivos" => $anolectivos,
            "instituicao" => $instituicao,
            "bolsa" => $bolsa,
            "tipoServico" => $tipoServico,
            "insecaoPamento" => $insecaoPamento,
            "motivoIsencao" => $motivoIsencao,
            "filters" => $request->all("AnoLectivo", "Motivo", "Instituicao", "TipoBolsa", "Servico"),
        ]);
    }


    public function pdfImprimirisencaodePagamentos($AnoLectivo = null, $Instituicao = null, $TipoBolsa = null, $Motivo = null, $Servico = null)
    {

        if ($AnoLectivo == "null") {
            $AnoLectivo = "";
        }
        if ($Instituicao == "null") {
            $Instituicao = "";
        }
        if ($TipoBolsa == "null") {
            $TipoBolsa = "";
        }
        if ($Servico == "null") {
            $Servico = "";
        }
        if ($Motivo == "null") {
            $Motivo = "";
        }

        // inicialmente
        $data['insecaoPamento'] = Isencao::when($AnoLectivo, function ($query, $value) {
            $query->where('tb_isencoes.codigo_anoLectivo', $value);
        })->when($Servico, function ($query, $value) {
            $query->where('tb_isencoes.codigo_servico', $value);
        })
            ->when($Motivo, function ($query, $value) {
                $query->where('tb_isencoes.Codigo_motivo', $value);
            })
            ->when($TipoBolsa, function ($query, $value) {
                $query->where('tb_tipo_bolsa_instituicao.tipo_bolsa', $value);
            })
            ->when($Instituicao, function ($query, $value) {
                $query->where('tb_instituicao.', $value);
            })
            ->join('tb_tipo_servicos', 'tb_isencoes.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('tb_ano_lectivo', 'tb_isencoes.codigo_anoLectivo', '=', 'tb_ano_lectivo.Codigo')
            ->join('tb_utilizadores', 'tb_isencoes.codigo_utilizador', '=', 'tb_utilizadores.Codigo')

            ->select('tb_isencoes.codigo As referencia',  'tb_isencoes.data_isencao AS data', 'tb_tipo_servicos.Descricao AS servico', 'tb_ano_lectivo.Designacao AS Anolectivo', 'tb_utilizadores.Nome AS usuario')
            ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.Gestao-bolsa-desconto.isencao-pagamento', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }




    public function excelImprimirIsencaoPagamento()
    {
        dd("Isencao Pagamento EXCEL");
    }

    public function bolseiros(Request $request)
    {
    
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }
        
        $data['bolsa'] = Bolsa::get();
        $data['anolectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();        $data['turnos'] = Turno::get();
        $data['semestres'] = Simestre::get();
        $data['instituicao'] = Instituicacao::get();
        $data['bolseiro'] = Bolseiro::get();
        $data['cursos'] = Curso::get();

        $data['mesTemps'] = MesTemp::when($request->AnoLectivo, function ($query, $value) {
            $query->where('ano_lectivo', $value);
        })->get();
        
        // busca_matricula
        // busca_nome
        // busca_curso
        // busca_tipo_bolsa
        // busca_desconto
        // busca_estado
        // busca_semestre

        // inicialmente
        $data['listarbolseiro'] = Bolseiro::when($request->AnoLectivo, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_anoLectivo', $value);
        })->when($request->Curso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', $value);
        })
        ->when($request->Instituicao, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_Instituicao', $value);
        })
        ->when($request->TipoBolsa, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
        })
        ->when($request->Desconto, function ($query, $value) {
            $query->where('tb_bolseiros.desconto', $value);
        })
        ->when($request->Estado, function ($query, $value) {
            $query->where('tb_bolseiros.status', $value);
        })
        ->when($request->Semestre, function ($query, $value) {
            $query->where('tb_bolseiros.semestre', $value);
        })
        //BUSCAS DOS INPUTS 
        ->when($request->busca_nome, function ($query, $value) {
            $query->where('tb_preinscricao.Nome_Completo', "like" ,"%".$value."%");
        })
        
        ->when($request->busca_matricula, function ($query, $value) {
            $query->where('tb_matriculas.Codigo', $value);
        })
        
        ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
        ->join('tb_semestres', 'tb_bolseiros.semestre', '=', 'tb_semestres.Codigo')
         ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_bolseiros.codigo_matricula', 'tb_bolseiros.codigo', 'tb_tipo_bolsas.designacao AS tipobolsa', 
        'tb_bolseiros.desconto', 'tb_bolseiros.status', 'tb_semestres.Designacao AS semestreItem', 'tb_preinscricao.Nome_Completo As nome', 'tb_bolseiros.codigo_anoLectivo',
        'tb_bolseiros.codigo_Instituicao',
        'tb_bolseiros.semestre',
        'tb_bolseiros.afectacao',
        'tb_bolseiros.codigo_tipo_bolsa',
        'tb_bolseiros.desconto',
        'tb_bolseiros.status', 
        'tb_preinscricao.Codigo AS preninscricaoCodigo'
        )
        ->paginate($request->page_size ?? 7)
        ->withQueryString();

        return Inertia::render('GestaoCreditoInstituicional/ListarBolseiros', $data);
    }
    
    public function visualizarBolseiros(Request $request, $id)
    {
    
        // "codigo" => "4470"
        // "codigo_matriula" => "21212"
        
        
        $matricula = Matricula::findOrFail($id);
        $admissao = AlunoAdmissao::findOrFail($matricula->Codigo_Aluno);
        $data['aluno'] = PreInscricao::findOrFail($admissao->pre_incricao);
        
        $data['matricula'] = $matricula;
        $data['admissao'] = $admissao;
        
        $data['bolsa'] = Bolseiro::join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
        ->join('mca_tb_utilizador', 'tb_bolseiros.codigo_utilizador', '=', 'mca_tb_utilizador.codigo_importado')
        ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
        ->select('tb_bolseiros.desconto', 'mca_tb_utilizador.nome', 'tb_tipo_bolsas.designacao', 'tb_Instituicao.Instituicao', 'tb_Instituicao.nif' , 'tb_Instituicao.contacto', 'tb_Instituicao.Endereco', 'tb_bolseiros.data_inicio_bolsa', 'tb_bolseiros.data_fim_bolsa' )
        ->findOrFail($request->codigo);
        
        return response()->json($data, 200);
    }
    
    public function mudarEstadoBolseiros($id)
    {
        $update = Bolseiro::findOrFail($id);
        
        $novo_status = null;
        
        if($update->status == 0){
            $novo_status = 1;
        }
        
        if($update->status == 1){
            $novo_status = 0;
        }
        
        $update->status = $novo_status;
        $update->update();
        
        return redirect()->back();
        
    }

    public function updateBolseiros(Request $request, $id)
    {
        
        $request->validate([
            'anolectivo' => 'required',
            'instituicao' => 'required',
            'tipo_bolsa' => 'required',
            'afectacao' => 'required',
            'desconto' => 'required',
            'semestre' => 'required',
        ], []);
        
        $bolsaInicio = 0;
        $bolsaFinal = 0;
        
        $update = Bolseiro::findOrFail($id);
        
        $ano = AnoLectivo::where('Codigo', $request->anolectivo)->first();
        
        if($request->semestre == 1){
            $bolsaInicio = $ano->dataInicioPrimeiroSemestre;
            $bolsaFinal = $ano->dataFimPrimeiroSemestre;

        }else if($request->semestre == 2){
            $bolsaInicio = $ano->dataInicioSegundoSemestre;
            $bolsaFinal = $ano->dataFimSegundoSemestre;
        }
        
        $update->codigo_anoLectivo =  $request->anolectivo;
        $update->codigo_tipo_bolsa =  $request->tipo_bolsa;
        $update->codigo_Instituicao =  $request->instituicao;
        $update->semestre = $request->semestre;
        $update->afectacao = $request->afectacao;
        $update->desconto = $request->desconto;
        $update->data_inicio_bolsa = $bolsaInicio;
        $update->data_fim_bolsa = $bolsaFinal;
        
        $update->update();
        
        return redirect()->back();
    }


    public function pdfimprimirbolseiros($AnoLectivo = null, $Curso = null, $Instituicao = null, $TipoBolsa = null, $Estado = null, $Desconto = null, $Semestre = null)
    {

        if ($AnoLectivo == "null") {
            $AnoLectivo = "";
        }
        if ($Curso == "null") {
            $Curso = "";
        }
        if ($Instituicao == "null") {
            $Instituicao = "";
        }
        if ($TipoBolsa == "null") {
            $TipoBolsa = "";
        }
        if ($Desconto == "null") {
            $Desconto = "";
        }
        if ($Estado == "null") {
            $Estado = "";
        }
        if ($Semestre == "null") {
            $Semestre = "";
        }

        $data['bolseiros'] = Bolseiro::when($AnoLectivo, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_anoLectivo', $value);
        })->when($Curso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', $value);
        })
            ->when($Instituicao, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_Instituicao', $value);
            })
            ->when($TipoBolsa, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
            })
            ->when($Desconto, function ($query, $value) {
                $query->where('tb_bolseiros.desconto', $value);
            })
            ->when($Estado, function ($query, $value) {
                $query->where('tb_bolseiros.status', $value);
            })
            ->when($Semestre, function ($query, $value) {
                $query->where('tb_bolseiros.semestre', $value);
            })
            ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')

            ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_bolseiros.codigo_matricula', 'tb_bolseiros.codigo', 'tb_tipo_bolsas.designacao AS tipobolsa', 'tb_bolseiros.desconto', 'tb_bolseiros.status', 'tb_bolseiros.semestre', 'tb_preinscricao.Nome_Completo As nome')
            ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.Gestao-bolsa-desconto.bolseiros', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }


    public function excelImprimirBolseiros()
    {
        dd("Bolseiros EXCEL");
    }

    //** DESCONTOS */
    
    public function mudarEstadoDesconto($id)
    {
        $update = DescontoAluno::findOrFail($id);
        
        $novo_status = null;
        
        if($update->estatus_desconto_id == 1){
            $novo_status = 2;
        }
        
        if($update->estatus_desconto_id == 2){
            $novo_status = 1;
        }
        
        $update->estatus_desconto_id = $novo_status;
        $update->update();
        
        return redirect()->back();
        
    }
    
    
    public function updateDesconto(Request $request, $id)
    {
        $request->validate([
            'anolectivo' => 'required',
            'instituicao_id' => 'required',
            'afectacao' => 'required',
            'desconto' => 'required',
            'semestre' => 'required',
        ], []);
  
        $update = DescontoAluno::findOrFail($id);
  
        $update->codigo_anoLectivo =  $request->anolectivo;
        $update->instituicao_id =  $request->instituicao_id;
        $update->semestre = $request->semestre;
        $update->afectacao = $request->afectacao;
        $update->codigo_tipo_desconto = $request->desconto;
        $update->estatus_desconto_id = $request->status;
        
        $update->update();
        
        return redirect()->back();
    }



    public function pagamentoBolseiros(Request $request)
    {

        $ano = AnoLectivo::where('estado', 'Activo')->first();
        $anoSelecionado = $request->anoLectivoBusca;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }

        $data['anolectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();        $data['instituicoes'] = Instituicacao::get();
        $data['forma_pagamentos'] = FormaPagamento::get();
        $data['bancos'] = Banco::get();
        $data["mesTemps"] = MesTemp::when($anoSelecionado, function ($query, $value) {
            $query->where('ano_lectivo', $value);
        })->get();

        $data['filtros'] = $request->all('anoLectivoBusca', 'instituicaoBusca');

        $data['bolseiros'] = Bolseiro::when($request->instituicaoBusca, function ($query, $value) {
            $query->where('codigo_Instituicao', $value);
        })
            ->when($anoSelecionado, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_anoLectivo', $value);
            })
            ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
            ->join('tb_ano_lectivo', 'tb_bolseiros.codigo_anoLectivo', '=', 'tb_ano_lectivo.Codigo')
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->select('tb_ano_lectivo.Designacao AS anoLectivo', 'tb_bolseiros.afectacao', 'tb_matriculas.Codigo AS codigo', 'tb_tipo_bolsas.designacao AS bolsa', 'tb_preinscricao.Codigo AS CodigoPreinscricao', 'tb_preinscricao.Nome_Completo AS nome', 'tb_cursos.Designacao AS curso', 'tb_cursos.Designacao AS curso')
            ->paginate(5)
            ->withQueryString();

        return Inertia::render('GestaodeBolsaeDesconto/PagamentoBolseiros', $data);
    }

    public function pagamentoBolseirosCreate(Request $request)
    {

        $ano = AnoLectivo::findOrFail($request->anoLectivoBusca);

        $request->validate([
            'anoLectivoBusca' => 'required',
            'instituicaoBusca' => 'required',
            'forma_pagamento' => 'required',
            'banco' => 'required',
            'mes' => 'required',
            'data_banco' => 'required',
            'estudante' => 'required',
        ], [
            'anoLectivoBusca.required' => "Campo de caracter obrigatório.",
            'instituicaoBusca.required' => "Campo de caracter obrigatório.",
            'forma_pagamento.required' => "Campo de caracter obrigatório.",
            'banco.required' => "Campo de caracter obrigatório.",
            'mes.required' => "Campo de caracter obrigatório.",
            'data_banco.required' => "Campo de caracter obrigatório.",
            'estudante.required' => "Campo de caracter obrigatório.",
        ]);

        foreach ($request->estudante as $estud) {


            $estudante = PreInscricao::where('tb_preinscricao.Codigo', $estud)
                ->join('polos', 'tb_preinscricao.polo_id', '=', 'polos.id')
                ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
                ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
                ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
                ->select('polos.id AS poloId', 'tb_cursos.Designacao AS curso', 'tb_preinscricao.Codigo AS codigo_preinscricao', 'tb_matriculas.Codigo')
                ->first();

            $servico = TipoServico::where('polo_id', $estudante->poloId)
                ->where('codigo_ano_lectivo', $this->anoLectivoActivo())
                ->where('Descricao', 'Propina ' . $estudante->curso)
                ->first();


            try {
                //code...
                DB::beginTransaction();
                /**
                 * registro na tabela factura
                 */

                $verificar_factura = Factura::join('factura_items', 'factura.Codigo', '=', 'factura_items.CodigoFactura')
                    ->where('factura.CodigoMatricula', $estudante->Codigo)
                    ->where('factura.codigo_preinscricao', $estudante->codigo_preinscricao)
                    ->where('factura.ano_lectivo', $ano->Codigo)
                    ->where('factura_items.mes_temp_id', $request->mes)
                    ->first();

                $message = "Pagamento Duplicado, verica este pagamento parece já ter sido efecturado";

                // if($verificar_factura){
                //     return response()->json(['message', $message], 200);
                // }

                $factura = Factura::create([
                    'DataFactura' => date("Y-m-d H:i:s"),
                    'TotalPreco' => $servico->Preco,
                    'CodigoMatricula' => $estudante->Codigo,
                    'polo_id' => 1,
                    'Referencia' => "",
                    'ValorEntregue' => $servico->Preco,
                    'Desconto' => 0,
                    'ValorAPagar' => $servico->Preco,
                    'Troco' => 0,
                    'ValorAPagarExtenso' => NULL,
                    'Descricao' => NULL,
                    'ValorEntregueMltCX' => 0,
                    'codigo_descricao' => NULL,
                    'totalIVA' => 0,
                    'NextFactura' => NULL,
                    'dataVencimento' => date("Y-m-d"),
                    'obs' => NULL,
                    'hashValor' => NULL,
                    'contaCorrente' => NULL,
                    'faturaReference' => NULL,
                    'canal' => 3,
                    'ano_lectivo' => $ano->Codigo,
                    'estado' => 1,
                    'TotalMulta' => 0,
                    'corrente' => 1,
                    'codigo_preinscricao' => $estudante->codigo_preinscricao,
                ]);

                FacturaItem::create([
                    'CodigoProduto' => $servico->Codigo,
                    'CodigoFactura' => $factura->Codigo,
                    'Quantidade' => 1,
                    'Total' => $servico->Preco,
                    'OBS' => NULL,
                    'TotalIva' => 0,
                    'preco' => $servico->Preco,
                    'descontoProduto' => 0,
                    'Mes' => date("F"),
                    'Multa' => 0,
                    'mes_temp_id' => $request->mes,
                    'codigo_anoLectivo' => $ano->Codigo,
                    'estado' => 1,
                    'valor_pago' => $servico->Preco,
                    'valor_a_transportar' => $servico->Preco,
                ]);

                $pagamento = Pagamento::create([
                    'Data' => date("Y-m-d"),
                    'N_Operacao_Bancaria' => NULL,
                    'N_Operacao_Bancaria2' => NULL,
                    'Observacao' => $servico->Descricao,
                    'AnoLectivo' => $ano->Codigo,
                    'Totalgeral' => $servico->Preco,
                    'DataBanco' => $request->data_banco,
                    'Codigo_PreInscricao' => $estudante->codigo_preinscricao,
                    'forma_pagamento' => $request->forma_pagamento,
                    'valor_depositado' => $servico->Preco,
                    'ContaMovimentada' => $request->banco,
                    'Utilizador' => Auth::user()->codigo_importado,
                    'DataRegisto' => date("Y-m-d"),
                    'canal' => "3",
                    'estado' => 1,
                    'codigo_factura' => $factura->Codigo,
                    'statusMovimento' => 0,
                    'info_adicional' => NULL,
                    'corrente' => 0,
                    'fk_utilizador' => Auth::user()->codigo_importado,
                ]);

                $item = PagamentoItems::create([
                    'Codigo_Pagamento' => $pagamento->Codigo,
                    'Codigo_Servico' => $servico->Codigo,
                    'Valor_Pago' => $servico->Preco,
                    'Mes' => date("F"),
                    'Quantidade' => 1,
                    'Valor_Total' => $servico->Preco,
                    'Multa' => 0,
                    'Deconnto' => 0,
                    'Ano' => $ano->Designacao,
                    'Estado' => 1,
                    'mes_id' => NULL,
                    'mes_temp_id' => $request->mes,
                ]);

                DB::commit();

                return redirect()->back();
            } catch (Exception $e) {
                //throw $e;
                DB::rollback();
            }
        }
    }
    
    // NOVOS MODULOS
    /**
    * TUDO SOBRE DESCONTO CRIAÇÃO,  ALTERACAOES, ELIMANACAO E LISTAGEM START
    */

    public  function listarDescontos(Request $request)
    {
       
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }
       
        $Descontos = tipo_Desconto::when($request->codigo_busca, function ($query, $value) {
            $query->where('tb_tipo_descontos.Codigo', '=', $value);
        })
        ->when($request->designcao_busca, function ($query, $value) {
            $query->where('tb_tipo_descontos.designacao', 'like', "%".$value."%");
        })
        ->when($request->valor_busca, function ($query, $value) {
            $query->where('tb_tipo_descontos.valor_desconto', '=', $value);
        })
        ->join('tb_utilizadores', 'tb_tipo_descontos.codigo_utlizador', '=', 'tb_utilizadores.Codigo')
        ->join('tb_status', 'tb_tipo_descontos.codigo_status', '=', 'tb_status.Codigo')
        ->select('tb_utilizadores.Nome AS usuario', 'tb_tipo_descontos.Codigo', 'tb_tipo_descontos.designacao', 'tb_tipo_descontos.valor_desconto', 'tb_status.Designacao As estado', 'tb_status.Codigo As statusid')
        ->paginate(10);

        return inertia()->render('GestaodeBolsaeDesconto/ListarDesconto',['listarDescontos' => $Descontos]);
    }

    public function StoreTipoDesconto(Request $request)
    {
        $request->validate([
            'designacao' => 'required',
            'valor_desconto' => 'required',
        ], [
            'designacao.required' => "Campo de caracter obrigatório.",
            'valor_desconto.required' => "Campo de caracter obrigatório.",
        ]);

        $request = tipo_Desconto::create([
            'designacao' => $request->designacao,
            'valor_desconto' => $request->valor_desconto,
            'estado' => $request->codigo_status,
            'codigo_utlizador' => auth()->user()->codigo_importado,
        ]);

        return redirect()->back();
    }
    

    public function EditarTipoDesconto(Request $request, $id)
    {
        $request->validate([
            'designacao' => 'required',
            'valor_desconto' => 'required',
        ], [
            'designacao.required' => "Campo de caracter obrigatório.",
            'valor_desconto.required' => "Campo de caracter obrigatório.",
        ]);

        $update = tipo_Desconto::findOrFail($id);
        $update->designacao = $request->designacao;
        $update->valor_desconto = $request->valor_desconto;
        $update->codigo_status = $request->estado;
        $update->update();
        
        return redirect()->back();
    }
    
    
    public function eliminarTipoDesconto($id)
    {
        $update = tipo_Desconto::findOrFail($id);
        $update->delete();
        
        return redirect()->back();
    }

    /**
    * TUDO SOBRE DESCONTO CRIAÇÃO,  ALTERACAOES, ELIMANACAO E LISTAGEM END
    */

}
