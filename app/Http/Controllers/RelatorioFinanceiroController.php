<?php

namespace App\Http\Controllers;

use App\Exports\FechoGeralExport;
use App\Exports\FechoUtilizadorExport;
use App\Exports\ListarEstudanteIsentoExport;
use App\Exports\ListarEstudantesComDescontoExport;
use App\Exports\ListarEstudantesCreditoEducacionalExport;
use App\Exports\ListarEstudantesExport;
use App\Models\AnoLectivo;
use App\Models\Bolsa;
use App\Models\Bolseiro;
use App\Models\Curso;
use App\Models\DescontoAluno;
use App\Models\EstadoPagamento;
use App\Models\Faculdade;
use App\Models\FormaPagamento;
use App\Models\GradeCurricularAluno;
use App\Models\GrauAcademico;
use App\Models\Grupo;
use App\Models\GrupoUtilizador;
use App\Models\Instituicacao;
use App\Models\Isencao;
use App\Models\Matricula;
use App\Models\MesTemp;
use App\Models\Pagamento;
use App\Models\PagamentoItems;
use App\Models\PreInscricao;
use App\Models\tipo_Desconto;
use App\Models\TipoInstituicao;
use App\Models\TipoServico;
use App\Models\Turno;
use App\Models\Utilizador;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RelatorioFinanceiroController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index(Request $request)
    {
        $data["filters"] = "";

        return Inertia::render('RelatoriosPagamentos/Index', $data);
    }
    
    /**
     * fecho do caixa geral
     */
    public function fechoCaixa(Request $request)
    {
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->anolectivo;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }

        $data['items'] = Pagamento::select('tb_pagamentos.fk_utilizador', 'mca_tb_utilizador.nome', DB::raw('SUM(tb_pagamentos.valor_depositado) as total_arrecadado'))
        ->when($request->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->when($request->operador, function($query, $value){
            $query->where('tb_pagamentos.fk_utilizador', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('DataRegisto', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('DataRegisto', '<=', Carbon::createFromDate($value));
        })
        ->where('tb_pagamentos.estado', 1)
        ->leftjoin('mca_tb_utilizador', 'tb_pagamentos.fk_utilizador', '=', 'mca_tb_utilizador.codigo_importado')
        ->groupBy('tb_pagamentos.fk_utilizador', 'mca_tb_utilizador.nome')
        ->paginate($request->page_size ?? 20)
        ->withQueryString();
        
        $validacao = Grupo::where('designacao', "Validação de Pagamentos")->select('pk_grupo')->first();
        $admins = Grupo::where('designacao', 'Administrador')->select('pk_grupo')->first();
        $finans = Grupo::where('designacao', 'Area Financeira')->select('pk_grupo')->first();
        $tesous = Grupo::where('designacao', 'Tesouraria')->select('pk_grupo')->first();

        $data['utilizadores'] = GrupoUtilizador::whereIn('fk_grupo', [$validacao->pk_grupo, $finans->pk_grupo, $tesous->pk_grupo])->with('utilizadores')->get();
        $data['tipoServicos'] = TipoServico::where('codigo_ano_lectivo', $ano->Codigo)->whereNull('codigo_grade_currilular')->get();
        
        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data['grausAcademicos'] = GrauAcademico::get();
        $data['formaPagamentos'] = FormaPagamento::get();
        $data['estadoPagamento'] = EstadoPagamento::get();
        
        $data['requests'] = $request->all('data_inicio', 'data_final');

        return Inertia::render('RelatoriosPagamentos/Caixa/FechoCaixa', $data);
    }



    /**
     * fecho do caixa geral
     */
    public function fechoCaixaGeral(Request $request)
    {
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->anolectivo;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }
        
        if($request->data_inicio){
            $request->data_inicio = $request->data_inicio;
        }else{
            $request->data_inicio = date('Y-m-d');
        }
                
        $codigo = $request->codigo_produto;
        
        $data['items'] = Pagamento::with(['detalhes.servico', 'operador_novos'])
        ->when($request->operador, function($query, $value){
            $query->where('tb_pagamentos.fk_utilizador', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('DataRegisto', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('DataRegisto', '<=', Carbon::createFromDate($value));
        })
        ->whereHas('detalhes', function($query) use($codigo){
            $query->when($codigo, function($query) use($codigo){
                $query->where('Codigo_Servico', $codigo);
            });
        })
        ->when($request->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->where('tb_pagamentos.estado', 1)
        ->paginate($request->page_size ?? 20)
        ->withQueryString();
        
        $data['total'] = Pagamento::with(['detalhes.servico', 'operador_novos'])
        ->when($request->operador, function($query, $value){
            $query->where('tb_pagamentos.fk_utilizador', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('DataRegisto', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('DataRegisto', '<=', Carbon::createFromDate($value));
        })
        ->whereHas('detalhes', function($query) use($codigo){
            $query->when($codigo, function($query) use($codigo){
                $query->where('Codigo_Servico', $codigo);
            });
        })
        ->when($request->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->where('tb_pagamentos.estado', 1)
        ->sum('valor_depositado');
        
        
        $validacao = Grupo::where('designacao', "Validação de Pagamentos")->select('pk_grupo')->first();
        $admins = Grupo::where('designacao', 'Administrador')->select('pk_grupo')->first();
        $finans = Grupo::where('designacao', 'Area Financeira')->select('pk_grupo')->first();
        $tesous = Grupo::where('designacao', 'Tesouraria')->select('pk_grupo')->first();

        $data['utilizadores'] = GrupoUtilizador::whereIn('fk_grupo', [$validacao->pk_grupo, $finans->pk_grupo, $tesous->pk_grupo])->with('utilizadores')->get();
        $data['tipoServicos'] = TipoServico::where('codigo_ano_lectivo', $ano->Codigo)->whereNull('codigo_grade_currilular')->get();
        
        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data['grausAcademicos'] = GrauAcademico::get();
        $data['formaPagamentos'] = FormaPagamento::get();
        $data['estadoPagamento'] = EstadoPagamento::get();
        
        $data['requests'] = $request->all('data_inicio', 'data_final', 'operador', 'forma_pagamento', 'estado_pagamento', 'codigo_produto', 'anolectivo');

        return Inertia::render('RelatoriosPagamentos/Caixa/FechoCaixaGeral', $data);
    }

    /**
     * fecho do caixa geral
     */
    public function fechoCaixaMensalidade(Request $request)
    {


        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->anolectivo;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }

        if($request->estado_pagamento  == "0"){
            $request->estado_pagamento = 0;
        }else
        if($request->estado_pagamento  == "1"){
            $request->estado_pagamento = 1;
        }else
        if($request->estado_pagamento  == "2"){
            $request->estado_pagamento = 2;
        }else
        if($request->estado_pagamento  == "3"){
            $request->estado_pagamento = 3;
        }else{
            $request->estado_pagamento = 1;
        }


        $codigoProduto = $request->codigo_produto;
        $mes_temps = $request->mes_temp;
        $grau_academico = $request->grau_academico;


         // recuperar os servicos deste ano lectivo primeiramente mais somente servicos de propinas
        $servicos = TipoServico::where('Descricao', 'like', 'Propina %')->where('codigo_ano_lectivo', $anoSelecionado)->pluck('Codigo');


        $data['items'] = Pagamento::when($anoSelecionado, function ($query, $value) {
            $query->where('AnoLectivo', $value);
        })
        ->with('items.mes_temps', 'anolectivo', 'preinscricao.polo', 'preinscricao.grau_academico')

        ->whereHas('items.mes_temps', function ($query) use ($codigoProduto, $mes_temps, $servicos) {

            $query->whereIn('tb_pagamentosi.Codigo_Servico', $servicos);

            $query->when($codigoProduto, function($query) use ($codigoProduto){
                $query->where('Codigo_Servico', $codigoProduto);
            });

            $query->when($mes_temps, function($query) use ($mes_temps){
                $query->where('mes_temp_id', $mes_temps);
            });
        })
        ->whereHas('preinscricao', function ($query) use ($grau_academico) {
            $query->when($grau_academico, function($query) use ($grau_academico){
                $query->where('codigo_tipo_candidatura', $grau_academico);
            });
        })
        ->when($request->forma_pagamento, function ($query, $value) {
            $query->where('forma_pagamento', $value);
        })

        ->when($request->data_inicio_banco, function ($query, $value) {
            $query->whereDate('DataBanco', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final_banco, function ($query, $value) {
            $query->whereDate('DataBanco', '<=', Carbon::createFromDate($value));
        })

        ->when($request->data_inicio_validacao, function ($query, $value) {
            $query->whereDate('updated_at', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final_validacao, function ($query, $value) {
            $query->whereDate('updated_at', '<=', Carbon::createFromDate($value));
        })
        ->where('estado',  $request->estado_pagamento)
        ->orderBy('Codigo', 'desc')
        ->paginate($request->page_size ?? 5)
        ->withQueryString();


        $validacao = Grupo::where('designacao', "Validação de Pagamentos")->select('pk_grupo')->first();
        // $admins = Grupo::where('designacao', 'Administrador')->select('pk_grupo')->first();
        $finans = Grupo::where('designacao', 'Area Financeira')->select('pk_grupo')->first();
        $tesous = Grupo::where('designacao', 'Tesouraria')->select('pk_grupo')->first();

        $data['utilizadores'] = GrupoUtilizador::whereIn('fk_grupo', [$validacao->pk_grupo, $finans->pk_grupo, $tesous->pk_grupo])->with('utilizadores')->get();
        $data['tipoServicos'] = TipoServico::where('descricao', 'like', 'Propina %')->where('codigo_ano_lectivo', $ano->Codigo)->whereNull('codigo_grade_currilular')->get();
        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data['grausAcademicos'] = GrauAcademico::get();
        $data['formaPagamentos'] = FormaPagamento::get();
        $data['estadoPagamento'] = EstadoPagamento::get();
        $data['total'] = Pagamento::count();

        $data['filtros'] = $request->all("anolectivo", "operador", "data_inicio", "data_inicio", "data_final", "estado_pagamento", "mes_temp", "grau_academico", "forma_pagamento", "codigo_produto");

        $data['mesTemps'] = MesTemp::when($anoSelecionado, function ($query, $value) {
            $query->where('ano_lectivo', '=', $value);
        })->get();

        return Inertia::render('RelatoriosPagamentos/Caixa/FechoCaixaMensalidade',$data);
    }


    public function visualizarDetalhesPagamento($id)
    {
        $pagamento = Pagamento::with(['detalhes.servico', 'operador_novos'])->findOrFail($id);
        
        $preinscricao = PreInscricao::join('tb_cursos', 'tb_preinscricao.Curso_Candidatura', '=', 'tb_cursos.Codigo')->join('polos','tb_preinscricao.polo_id', '=', 'polos.id')->findOrFail($pagamento->Codigo_PreInscricao);

        $data['detalhes'] = [
            'recibo' => $pagamento->Codigo,
            'valor' => $pagamento->valor_depositado,
            'operacao' => $pagamento->N_Operacao_Bancaria,
            'operacao2' => $pagamento->N_Operacao_Bancaria2,
            'pagamento' => $pagamento->forma_pagamento,
            'data_registro' => date($pagamento->DataRegisto,  strtotime("d-m-Y")),
            'data_banco' => date($pagamento->DataBanco,  strtotime("d-m-Y")),
            'data_validacao' => date($pagamento->updated_at,  strtotime("d-m-Y")),
            'factura' => $pagamento->codigo_factura,

            'img1' => $pagamento->nome_documento,
            'img2' => $pagamento->nome_documento2,
        ];

        $data['dados'] = [
            "codigo" => $preinscricao->Codigo,
            "nome" => $preinscricao->Nome_Completo,
            "campus" => $preinscricao->designacao,
            "curso" => $preinscricao->Designacao,
            "contacto" => $preinscricao->Contactos_Telefonicos,
        ];
        
        return response()->json(
        [
            'dados' => $data['dados'], 
            'detalhes' => $data['detalhes'],
            'pagamento_detalhes' => $pagamento                
        ], 200);
    }

    /**
     * fecho de caixa caixa em PDF (PRINT)
     */
    public function pdfImprimirGeral(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->anolectivo;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }
        
        if($request->data_inicio){
            $request->data_inicio = $request->data_inicio;
        }else{
            $request->data_inicio = date('Y-m-d');
        }
                
        $codigo = $request->codigo_produto;
        
        $data['items'] = Pagamento::with(['detalhes.servico', 'operador_novos'])
        ->when($request->operador, function($query, $value){
            $query->where('tb_pagamentos.fk_utilizador', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('DataRegisto', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('DataRegisto', '<=', Carbon::createFromDate($value));
        })
        ->whereHas('detalhes', function($query) use($codigo){
            $query->when($codigo, function($query) use($codigo){
                $query->where('Codigo_Servico', $codigo);
            });
        })
        ->when($request->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->where('tb_pagamentos.estado', 1)
        ->get();
        
        $data['requests'] = $request->all('data_inicio', 'data_final', 'forma_pagamento'); 
        $data['servico'] = TipoServico::find($codigo);
        
        $data['operador'] = Utilizador::where('codigo_importado', $request->operador)->first();
        $data['ttulo'] = "LISTAGEM DO FECHO DE CAIXA GERAL";
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.fecho-caixa.fecho-caixa-geral', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    /**
     * fecho de caixa caixa em EXCEL (PRINT)
     */
    public function excelImprimirGeral(Request $request)
    {
        return Excel::download(new FechoGeralExport($request), 'fecho-geral.xlsx');
    }

    /**
     * ############################################################################################################
     */

    /**
     * listar estudantes isentos
     */

    public function listarEstudanteIsento(Request $request)
    {
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->anolectivo;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }

        $data['items'] = Isencao::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
        ->when($anoSelecionado, function ($query, $value) {
            $query->where('tb_isencoes.codigo_anoLectivo', '=', $value);
        })
        ->when($request->turno, function ($query, $value) {
            $query->where('tb_periodos.Codigo', '=', $value);
        })
        ->when($request->faculdade, function ($query, $value) {
            $query->where('tb_faculdade.codigo', '=', $value);
        })
        ->when($request->servico, function ($query, $value) {
            $query->where('tb_isencoes.codigo_servico', '=', $value);
        })
        ->when($request->curso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', '=', $value);
        })
        ->where('estado_isensao', 'Activo')
        ->join('tb_ano_lectivo', 'tb_isencoes.codigo_anoLectivo', '=', 'tb_ano_lectivo.Codigo')
        ->join('mes_temp', 'tb_isencoes.mes_temp_id', '=', 'mes_temp.id')
        ->join('tb_tipo_servicos', 'tb_isencoes.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
        ->join('tb_matriculas', 'tb_isencoes.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.Codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
        ->select('tb_isencoes.codigo_matricula AS codigoMatricula',
                'tb_ano_lectivo.Designacao',
                'mes_temp.designacao AS mesIsencao',
                'tb_tipo_servicos.Descricao AS servicoIsencao',
                'tb_preinscricao.Nome_Completo AS nomeEstudante',
                'tb_periodos.Designacao AS turnoIsencao',
                'tb_cursos.Designacao AS cursoIsencao',
        )
        ->paginate($request->page_size ?? 20)
        ->withQueryString();

        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();        $data['faculdades'] = Faculdade::where('estado', 1)->get();
        $data['cursos'] = Curso::get();
        $data['turnos'] = Turno::where('status', 1)->get();

        $data['total'] = Isencao::count();

        $data['servicos'] = TipoServico::when($request->anolectivo, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
        })->get();

        $data['filtros'] = $request->all("anolectivo", "turnos", "faculdade", "servicos", "cursos");

        return Inertia::render('RelatoriosPagamentos/ListarEstudanteIsento', $data);
    }

    /**
     * listar estudantes isentos pdf (PRINT)
     */
    public function pdfImprimirListarEstudanteIsento(Request $request)
    {

        $ano = AnoLectivo::where('estado', 'Activo')->first();
        $anoSelecionado = $request->a;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }

        $data['items'] = Isencao::when($anoSelecionado, function ($query, $value) {
            $query->where('tb_isencoes.codigo_anoLectivo', '=', $value);
        })
        ->when($request->t, function ($query, $value) {
            $query->where('tb_periodos.Codigo', '=', $value);
        })
        ->when($request->f, function ($query, $value) {
            $query->where('tb_faculdade.codigo', '=', $value);
        })
        ->when($request->s, function ($query, $value) {
            $query->where('tb_isencoes.codigo_servico', '=', $value);
        })
        ->when($request->c, function ($query, $value) {
            $query->where('tb_cursos.Codigo', '=', $value);
        })
        ->join('tb_ano_lectivo', 'tb_isencoes.codigo_anoLectivo', '=', 'tb_ano_lectivo.Codigo')
        ->join('mes_temp', 'tb_isencoes.mes_temp_id', '=', 'mes_temp.id')
        ->join('tb_tipo_servicos', 'tb_isencoes.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
        ->join('tb_matriculas', 'tb_isencoes.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.Codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
        ->select('tb_isencoes.codigo_matricula AS codigoMatricula',
                'tb_ano_lectivo.Designacao',
                'mes_temp.designacao AS mesIsencao',
                'tb_tipo_servicos.Descricao AS servicoIsencao',
                'tb_preinscricao.Nome_Completo AS nomeEstudante',
                'tb_periodos.Designacao AS turnoIsencao',
                'tb_cursos.Designacao AS cursoIsencao',
        )
        ->limit(1000)
        ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.fecho-caixa.listar-estudante-isento', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    /**
     * lsitar estudantes isentos excel (PRINT)
     */
    public function excelImprimirListarEstudanteIsento(Request $request)
    {
        return Excel::download(new ListarEstudanteIsentoExport($request->a, $request->f, $request->c, $request->t, $request->s), 'listar-estudantes-isentos.xlsx');
    }


    /**
     * #################################################################################################################
     */
    /**
     * fecho de caixa por utilizador
     */

    public function fechoCaixaUtilizador(Request $request)
    {
        $user = auth()->user();
    
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }
        
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->anolectivo;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }
        
        if($request->data_inicio){
            $request->data_inicio = $request->data_inicio;
        }else{
            $request->data_inicio = date('Y-m-d');
        }
                
        $codigo = $request->codigo_produto;
        
        $data['items'] = Pagamento::with(['detalhes.servico', 'operador_novos'])
        ->when($request->operador, function($query, $value){
            $query->where('tb_pagamentos.fk_utilizador', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('DataRegisto', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('DataRegisto', '<=', Carbon::createFromDate($value));
        })
        ->whereHas('detalhes', function($query) use($codigo){
            $query->when($codigo, function($query) use($codigo){
                $query->where('Codigo_Servico', $codigo);
            });
        })
        ->when($request->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->where('tb_pagamentos.fk_utilizador', $user->codigo_importado)
        ->where('tb_pagamentos.estado', 1)
        ->paginate($request->page_size ?? 20)
        ->withQueryString();
        
        
        $data['total'] = Pagamento::with(['detalhes.servico', 'operador_novos'])
        ->when($request->operador, function($query, $value){
            $query->where('tb_pagamentos.fk_utilizador', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('DataRegisto', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('DataRegisto', '<=', Carbon::createFromDate($value));
        })
        ->whereHas('detalhes', function($query) use($codigo){
            $query->when($codigo, function($query) use($codigo){
                $query->where('Codigo_Servico', $codigo);
            });
        })
        ->when($request->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->where('tb_pagamentos.fk_utilizador', $user->pk_utilizador)
        ->where('tb_pagamentos.estado', 1)
        ->sum('valor_depositado');
        
        $validacao = Grupo::where('designacao', "Validação de Pagamentos")->select('pk_grupo')->first();
        $admins = Grupo::where('designacao', 'Administrador')->select('pk_grupo')->first();
        $finans = Grupo::where('designacao', 'Area Financeira')->select('pk_grupo')->first();
        $tesous = Grupo::where('designacao', 'Tesouraria')->select('pk_grupo')->first();

        $data['utilizadores'] = GrupoUtilizador::whereIn('fk_grupo', [$validacao->pk_grupo, $finans->pk_grupo, $tesous->pk_grupo])->with('utilizadores')->get();
        $data['tipoServicos'] = TipoServico::where('codigo_ano_lectivo', $ano->Codigo)->whereNull('codigo_grade_currilular')->get();
        
        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data['grausAcademicos'] = GrauAcademico::get();
        $data['formaPagamentos'] = FormaPagamento::get();
        $data['estadoPagamento'] = EstadoPagamento::get();

        return Inertia::render('RelatoriosPagamentos/Caixa/FechoCaixaUtilizador', $data);
    }

    /**
     * imprimir fecho de caixa pdf por utilizador (PRINT)
     */
    public function pdfImprimirUtilizador(Request $request)
    {

        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->anolectivo;

        if(!$anoSelecionado){
            $anoSelecionado = $ano->Codigo;
        }
        
        if($request->data_inicio){
            $request->data_inicio = $request->data_inicio;
        }else{
            $request->data_inicio = date('Y-m-d');
        }
                
        $codigo = $request->codigo_produto;
        
        $data['items'] = Pagamento::with(['detalhes.servico', 'operador_novos'])
        ->when($request->operador, function($query, $value){
            $query->where('tb_pagamentos.fk_utilizador', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('DataRegisto', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('DataRegisto', '<=', Carbon::createFromDate($value));
        })
        ->whereHas('detalhes', function($query) use($codigo){
            $query->when($codigo, function($query) use($codigo){
                $query->where('Codigo_Servico', $codigo);
            });
        })
        ->when($request->forma_pagamento, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->where('tb_pagamentos.fk_utilizador', Auth::user()->codigo_importado)
        ->where('tb_pagamentos.estado', 1)
        ->get();
        
        $data['requests'] = $request->all('data_inicio', 'data_final', 'forma_pagamento'); 
        $data['servico'] = TipoServico::find($codigo);
        
        $data['operador'] = Utilizador::where('codigo_importado', $request->operador)->first();
        $data['titulo'] = "LISTAGEM DO FECHO DE CAIXA POR UTILIZADOR";

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.fecho-caixa.fecho-caixa-utilizador', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();

    }

    /**
     * fecho de caixa excel por utilizador (PRINT)
     */
    public function excelImprimirUtilizador(Request $request)
    {
        return Excel::download(new FechoUtilizadorExport($request), 'fecho-utilizador.xlsx');
    }


    /**
    * LISTA ESTUDANTES START
    */
    public function listarEstudantes(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'activo')->first();

        if(!$request->anolectivo){
            $request->anolectivo = $ano->Codigo;
        }

        $grade_curriculares = GradeCurricularAluno::when($request->anolectivo, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
            $query->whereIn('Codigo_Status_Grade_Curricular', [2,3]);
        })->distinct('codigo_matricula')->pluck('codigo_matricula');

        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $data['items'] = Matricula::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
        ->when($request->turno, function ($query, $value) {
            $query->where('tb_periodos.Codigo', '=', $value);
        })
        ->when($request->faculdade, function ($query, $value) {
            $query->where('tb_faculdade.codigo', '=', $value);
        })
        ->when($request->estado_matricula, function ($query, $value) {
            $query->where('tb_matriculas.estado_matricula', '=', $value);
        })
        ->when($request->curso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', '=', $value);
        })
        ->when($request->input_search, function ($query, $value) {
            $query->where('tb_preinscricao.Nome_Completo', 'like', "%".$value."%");
            $query->orWhere('tb_preinscricao.Bilhete_Identidade', '=', $value);
        })
        ->whereIn('tb_matriculas.Codigo', $grade_curriculares)
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.Codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
        ->select('tb_matriculas.Codigo AS codigo',
            'tb_preinscricao.Nome_Completo AS nome',
            'tb_preinscricao.Bilhete_Identidade AS bilheite',
            'tb_faculdade.designacao AS faculdade',
            'tb_periodos.Designacao AS periodo',
            'tb_cursos.Designacao AS curso',
            'tb_matriculas.estado_matricula',
        )
        ->orderBy('nome' , 'asc')
        ->paginate($request->page_size ?? 20)
        ->withQueryString();

        $data['anoLectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data['faculdades'] = Faculdade::where('estado', 1)->get();
        $data['cursos'] = Curso::get();
        $data['turnos'] = Turno::where('status', 1)->get();
        $data['ano_lectivo_activo'] = $ano;


        return Inertia::render('RelatoriosPagamentos/Estudantes/ListarEstudantes', $data);

    }

    /**
    * LISTA ESTUIDANTES PDF
    */

    public function listarEstudantesImprimirPdf(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'activo')->first();

        if(!$request->anolectivo){
            $request->anolectivo = $ano->Codigo;
        }

        $grade_curriculares = GradeCurricularAluno::when($request->anolectivo, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
            $query->whereIn('Codigo_Status_Grade_Curricular', [2,3]);
        })->distinct('codigo_matricula')->pluck('codigo_matricula');

        $data['items'] = Matricula::when($request->turno, function ($query, $value) {
            $query->where('tb_periodos.Codigo', '=', $value);
        })
        ->when($request->faculdade, function ($query, $value) {
            $query->where('tb_faculdade.codigo', '=', $value);
        })
        ->when($request->curso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', '=', $value);
        })
        ->whereIn('tb_matriculas.Codigo', $grade_curriculares)
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.Codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
        ->select('tb_matriculas.Codigo AS codigo',
            'tb_preinscricao.Nome_Completo AS nome',
            'tb_preinscricao.Bilhete_Identidade AS bilheite',
            'tb_faculdade.designacao AS faculdade',
            'tb_periodos.Designacao AS periodo',
            'tb_cursos.Designacao AS curso',
        )
        ->limit(2002)
        ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.listar-estudantes', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }
    /**
    * LISTA ESTUIDANTES EXCEL
    */

    public function listarEstudantesImprimirExcel(Request $request)
    {
        return Excel::download(new ListarEstudantesExport($request), 'listar-estudantes.xlsx');
    }

    /** LISTAR ESTUDANTES END */


    /**
    * LISTA ESTUIDANTES CREDITOS EDUCACIONAL STATUS
    */
    public function listarEstudantesCreditoInstituicao(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'activo')->first();    
        
        if($request->ano_lectivo){
            $request->ano_lectivo = $request->ano_lectivo;
        }else{
            $request->ano_lectivo = $ano->Codigo;
        }

        $data['items'] = Bolseiro::when($request->instituicao, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_Instituicao', $value);
        })
        ->when($request->bolsa, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
        })
        ->when($request->ano_lectivo, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_anoLectivo', $value);
        })
        ->when($request->desconto, function ($query, $value) {
            $query->where('tb_bolseiros.desconto', $value);
        })
        ->when($request->tipo_instituicao, function($query, $value){
            $query->where('tb_Instituicao.tipo_instituicao', $value);
        })
        ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
        ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
        ->join('tb_semestres', 'tb_bolseiros.semestre', '=', 'tb_semestres.Codigo')
         ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_bolseiros.codigo_matricula', 'tb_bolseiros.codigo', 'tb_tipo_bolsas.designacao AS tipobolsa',
            'tb_bolseiros.desconto', 'tb_bolseiros.status', 'tb_semestres.Designacao AS semestreItem', 'tb_preinscricao.Nome_Completo As nome', 'tb_bolseiros.codigo_anoLectivo',
            'tb_bolseiros.codigo_Instituicao',
            'tb_Instituicao.instituicao',
            'tb_bolseiros.semestre',
            'tb_bolseiros.afectacao',
            'tb_bolseiros.codigo_tipo_bolsa',
            'tb_bolseiros.desconto',
            'tb_bolseiros.status',
            'tb_preinscricao.Codigo AS preninscricaoCodigo'
        )
        ->orderBy('nome', 'asc')
        ->paginate($request->page_size ?? 20)
        ->withQueryString();

        $data['tipo_instituicoes'] = TipoInstituicao::get();
        $data['anos_lectivos'] = AnoLectivo::get();
        $data['instituicoes'] = Instituicacao::when($request->tipo_instituicao, function($query, $value){
            $query->where('tipo_instituicao', $value);
        })
        ->get();

        $data['bolsas'] = Bolsa::join('tb_tipo_bolsa_instituicao', 'tb_tipo_bolsas.codigo', '=', 'tb_tipo_bolsa_instituicao.tipo_bolsa')
        ->when($request->instituicao, function($query, $value){
            $query->where('tb_tipo_bolsa_instituicao.instituicao', $value);
        })
        ->get();


        return Inertia::render('RelatoriosPagamentos/Estudantes/ListarEstudantesCreditoInstituicao', $data);

    }


    public function listarEstudantesCreditoInstituicaoImprimirPdf(Request $request)
    {
        $data['items'] = Bolseiro::when($request->instituicao, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_Instituicao', $value);
        })
        ->when($request->bolsa, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
        })
        ->when($request->desconto, function ($query, $value) {
            $query->where('tb_bolseiros.desconto', $value);
        })
        ->when($request->tipo_instituicao, function($query, $value){
            $query->where('tb_Instituicao.tipo_instituicao', $value);
        })
        ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
        ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
        ->join('tb_semestres', 'tb_bolseiros.semestre', '=', 'tb_semestres.Codigo')
         ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_bolseiros.codigo_matricula', 'tb_bolseiros.codigo', 'tb_tipo_bolsas.designacao AS tipobolsa',
            'tb_bolseiros.desconto', 'tb_bolseiros.status', 'tb_semestres.Designacao AS semestreItem', 'tb_preinscricao.Nome_Completo As nome', 'tb_bolseiros.codigo_anoLectivo',
            'tb_bolseiros.codigo_Instituicao',
            'tb_Instituicao.instituicao',
            'tb_bolseiros.semestre',
            'tb_bolseiros.afectacao',
            'tb_bolseiros.codigo_tipo_bolsa',
            'tb_bolseiros.desconto',
            'tb_bolseiros.status',
            'tb_preinscricao.Codigo AS preninscricaoCodigo'
        )
        ->orderBy('nome', 'asc')
        ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.listar-estudantes-credito-educacional', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function listarEstudantesCreditoInstituicaoImprimirExcel(Request $request)
    {
        return Excel::download(new ListarEstudantesCreditoEducacionalExport($request->tipo_instituicao, $request->instituicao, $request->bolsa,  $request->desconto), 'listar-estudantes-credito-educacional.xlsx');
    }

     /**
    * LISTA ESTUIDANTES CREDITOS EDUCACIONAL END
    */


    /**
    * LISTA ESTUIDANTES COM DESCONTO START
    */
    public function listarEstudantesDesconto(Request $request)
    {
        $data['items'] = DescontoAluno::when($request->instituicao, function ($query, $value) {
            $query->where('tb_descontos_alunoo.instituicao_id', $value);
        })
        ->when($request->tipo_desconto, function ($query, $value) {
            $query->where('tb_descontos_alunoo.codigo_tipo_desconto', $value);
        })
        ->when($request->tipo_instituicao, function($query, $value){
            $query->where('tb_Instituicao.tipo_instituicao', $value);
        })
        ->join('tb_matriculas', 'tb_descontos_alunoo.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_tipo_descontos', 'tb_descontos_alunoo.codigo_tipo_desconto', '=', 'tb_tipo_descontos.Codigo')
        ->join('tb_Instituicao', 'tb_descontos_alunoo.instituicao_id', '=', 'tb_Instituicao.codigo')
        ->join('tb_status', 'tb_descontos_alunoo.estatus_desconto_id', '=', 'tb_status.Codigo')
        ->join('tb_semestres', 'tb_descontos_alunoo.semestre', '=', 'tb_semestres.Codigo')
         ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_descontos_alunoo.codigo_matricula', 'tb_descontos_alunoo.codigo', 'tb_tipo_descontos.designacao AS tipoDesconto', 'tb_semestres.Designacao AS semestreItem', 'tb_preinscricao.Nome_Completo As nome', 'tb_descontos_alunoo.codigo_anoLectivo',
            'tb_descontos_alunoo.instituicao_id',
            'tb_Instituicao.instituicao',
            'tb_descontos_alunoo.semestre',
            'tb_descontos_alunoo.afectacao',
            'tb_descontos_alunoo.codigo_tipo_desconto',
            'tb_preinscricao.Codigo AS preninscricaoCodigo',
            'tb_status.Designacao'
        )
        ->orderBy('nome', 'asc')
        ->paginate($request->page_size ?? 20)
        ->withQueryString();

        $data['tipo_instituicoes'] = TipoInstituicao::get();
        $data['instituicoes'] = Instituicacao::when($request->tipo_instituicao, function($query, $value){
            $query->where('tipo_instituicao', $value);
        })
        ->get();

        $data['tipo_descontos'] = tipo_Desconto::get();

        return Inertia::render('RelatoriosPagamentos/Estudantes/ListarEstudantesDesconto', $data);

    }

    public function listarEstudantesDescontoImprimirPdf(Request $request)
    {
        $data['items'] = DescontoAluno::when($request->instituicao, function ($query, $value) {
            $query->where('tb_descontos_alunoo.instituicao_id', $value);
        })
        ->when($request->tipo_desconto, function ($query, $value) {
            $query->where('tb_descontos_alunoo.codigo_tipo_desconto', $value);
        })
        ->when($request->tipo_instituicao, function($query, $value){
            $query->where('tb_Instituicao.tipo_instituicao', $value);
        })
        ->join('tb_matriculas', 'tb_descontos_alunoo.codigo_matricula', '=', 'tb_matriculas.Codigo')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
        ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_tipo_descontos', 'tb_descontos_alunoo.codigo_tipo_desconto', '=', 'tb_tipo_descontos.Codigo')
        ->join('tb_Instituicao', 'tb_descontos_alunoo.instituicao_id', '=', 'tb_Instituicao.codigo')
        ->join('tb_status', 'tb_descontos_alunoo.estatus_desconto_id', '=', 'tb_status.Codigo')
        ->join('tb_semestres', 'tb_descontos_alunoo.semestre', '=', 'tb_semestres.Codigo')
         ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_descontos_alunoo.codigo_matricula', 'tb_descontos_alunoo.codigo', 'tb_tipo_descontos.designacao AS tipoDesconto', 'tb_semestres.Designacao AS semestreItem', 'tb_preinscricao.Nome_Completo As nome', 'tb_descontos_alunoo.codigo_anoLectivo',
            'tb_descontos_alunoo.instituicao_id',
            'tb_Instituicao.instituicao',
            'tb_descontos_alunoo.semestre',
            'tb_descontos_alunoo.afectacao',
            'tb_descontos_alunoo.codigo_tipo_desconto',
            'tb_preinscricao.Codigo AS preninscricaoCodigo',
            'tb_status.Designacao'
        )
        ->orderBy('nome', 'asc')
        ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.listar-estudantes-com-desconto', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function listarEstudantesDescontoImprimirExcel(Request $request)
    {
        return Excel::download(new ListarEstudantesComDescontoExport($request->tipo_instituicao, $request->instituicao, $request->tipo_desconto), 'listar-estudantes-com-desconto.xlsx');
    }
    /**
    * LISTA ESTUIDANTES COM DESCONTO END
    */

}
