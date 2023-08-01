<?php

namespace App\Http\Controllers;

use App\Exports\ActualizacaoSaldoExport;
use App\Exports\PagamentosPorValidarExport;
use App\Exports\TalaoEmDesusoExport;
use App\Models\ActualizarSaldoAluno;
use App\Models\AnoLectivo;
use App\Models\Bolseiro;
use App\Models\Curso;
use App\Models\EstadoPagamento;
use App\Models\FormaPagamento;
use App\Models\GrauAcademico;
use App\Models\Grupo;
use App\Models\GrupoUtilizador;
use App\Models\Instituicacao;
use App\Models\Matricula;
use App\Models\MesTemp;
use App\Models\MotivoEliminaFacturaPagamento;
use App\Models\MotivoRejeicao;
use App\Models\Pagamento;
use App\Models\PagamentoItems;
use App\Models\PreInscricao;
use App\Models\Prestacao;
use App\Models\RejeicaoPagamento;
use App\Models\Simestre;
use App\Models\TipoBolsa;
use App\Models\TipoServico;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class PagamentoCorrentesController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    // estudantes com propinas pagas
    public function consultarOperacao(Request $request)
    {
        $data['pagamentos'] = Pagamento::when($request->numero_comprovativo, function ($query, $value) {
            $query->where('N_Operacao_Bancaria', $value);
            $query->orWhere('N_Operacao_Bancaria2', $value);
        })
            ->with('anolectivo')
            ->with('preinscricao.curso')
            ->with('canal')
            ->where('estado', 0)
            ->limit(1)
            ->paginate(5)
            ->withQueryString();

        $data['filtros'] = $request->all('numero_comprovativo');

        return Inertia::render('AreaFinanceira/ConsultarOperacao', $data);
    }


    public function consultarComprovativoOperacao(Request $request)
    {
        $data['pagamentos'] = Pagamento::when($request->numero_comprovativo, function ($query, $value) {
            $query->where('N_Operacao_Bancaria', $value);
            $query->orWhere('N_Operacao_Bancaria2', $value);
        })
            ->with('anolectivo')
            ->with('preinscricao.curso')
            ->with('canal')
            ->where('estado', 0)
            ->limit(1)
            ->paginate(5)
            ->withQueryString();

        return response()->json($data, 200);
    }

    function percentagemAproveitamento(Request $request)
    {

        // ano_lectivo
        // grau
        // curso
        // instituicao
        // tipo_bolsa
        // semestre
        // percentagem
        // estado

        if ($request->page_size == -1) {
            $request->page_size = 15;
        }
        $data['items'] = Bolseiro::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })->when(!isset($request->sort_by), function ($query) {
            $query->latest();
        })
            ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_semestres', 'tb_bolseiros.semestre', '=', 'tb_semestres.Codigo')
            ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
            ->join('tb_grau_academico', 'tb_preinscricao.codigo_grau_academico', '=', 'tb_grau_academico.Codigo')

            ->when($request->ano_lectivo, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_anoLectivo', $value);
            })

            ->when($request->instituicao, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_Instituicao', $value);
            })

            ->when($request->tipo_bolsa, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
            })

            ->when($request->percentagem, function ($query, $value) {
                $query->where('tb_bolseiros.desconto', $value);
            })

            ->when($request->semestre, function ($query, $value) {
                $query->where('tb_bolseiros.semestre', $value);
            })

            ->when($request->curso, function ($query, $value) {
                $query->where('tb_matriculas.Codigo_Curso', $value);
            })

            ->when($request->grau, function ($query, $value) {
                $query->where('tb_preinscricao.codigo_grau_academico', $value);
            })
            // ->orderBy('nome')
            ->select(
                'tb_matriculas.Codigo AS Cod_Matricula',
                'tb_preinscricao.Nome_Completo',
                'tb_cursos.Designacao AS curso',
                'tb_Instituicao.Instituicao',
                'tb_semestres.Designacao AS semestre',
                'tb_grau_academico.Designacao AS grau'
                // ,
                // DB::select("SELECT COUNT(*) FROM tb_grade_curricular_aluno AS total WHERE Codigo_Status_Grade_Curricular IN ('2', '3')")
            )
            ->paginate($request->page_size ?? 7)
            ->withQueryString();

        $data['semestres'] = Simestre::get();
        $data['graus'] = GrauAcademico::get();
        $data['cursos'] = Curso::get();
        $data['instituicoes'] = Instituicacao::get();
        $data['anos_lectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data['bolsas'] = TipoBolsa::when($request->instituicao, function ($query, $value) {
            $query->where('tb_tipo_bolsa_instituicao.instituicao', $value);
        })->join('tb_tipo_bolsa_instituicao', 'tb_tipo_bolsas.codigo', '=', 'tb_tipo_bolsa_instituicao.tipo_bolsa')->get();

        return Inertia::render('GestaoCreditoInstituicional/Percentagem/Index', $data);
    }

    public function controleActualizacaoSaldo(Request $request)
    {
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        if ($request->operador == null) {
            $request->operador = "";
        }

        $data['saldos'] = ActualizarSaldoAluno::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
        ->when(!isset($request->sort_by), function ($query) {
            $query->get();
        })
        ->when($request->operador, function ($query, $value) {
            $query->where('mca_tb_utilizador.pk_utilizador', '=', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_actualizacao', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_actualizacao', '<=', Carbon::createFromDate($value));
        })
        ->join('tb_preinscricao', 'tb_actualizacao_saldo_aluno.aluno_id', '=', 'tb_preinscricao.Codigo')
        ->join('mca_tb_utilizador', DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
        ->select(
            DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.desc") as nome'),
            'tb_preinscricao.Nome_Completo AS aluno',
            'tb_actualizacao_saldo_aluno.data_actualizacao',
            'tb_actualizacao_saldo_aluno.saldo_anterior',
            'tb_actualizacao_saldo_aluno.saldo_actual',
            'tb_actualizacao_saldo_aluno.obs',
            'tb_actualizacao_saldo_aluno.id',
        )
        ->paginate($request->page_size ?? 10)
        ->withQueryString();

        // grupos permitido em acessar o sistema
        $admins = Grupo::where('designacao', 'Administrador')->select('pk_grupo')->first();
        $finans = Grupo::where('designacao', 'Area Financeira')->select('pk_grupo')->first();
        $tesous = Grupo::where('designacao', 'Tesouraria')->select('pk_grupo')->first();

        $data['utilizadores'] = GrupoUtilizador::whereIn('fk_grupo', [$admins->pk_grupo, $finans->pk_grupo, $tesous->pk_grupo])->with('utilizadores')->get();
        $data['filtros'] = $request->all("data_inicio", "data_final", "operador");

        return Inertia::render('AreaFinanceira/ControleActualizacaoSaldo', $data);
    }

    public function controleActualizacaoSaldoDetalhe($id)
    {
        $data = ActualizarSaldoAluno::join('tb_preinscricao', 'tb_actualizacao_saldo_aluno.aluno_id', '=', 'tb_preinscricao.Codigo')->findOrFail($id);
        
        return response()->json($data);
    }

    public function pdfImprimirControleActualizacaoSaldo($o = null, $di = null, $df = null)
    {
        if ($o == "null") {
            $o = "";
        }
        if ($di == "null") {
            $di = "";
        }
        if ($df == "null") {
            $df = "";
        }

        $data['items'] = ActualizarSaldoAluno::when($o, function ($query, $value) {
            $query->where('mca_tb_utilizador.pk_utilizador', '=', $value);
        })
            ->when($di, function ($query, $value) {
                $query->whereDate('data_actualizacao', '>=', Carbon::createFromDate($value));
            })
            ->when($df, function ($query, $value) {
                $query->whereDate('data_actualizacao', '<=', Carbon::createFromDate($value));
            })
            ->join('tb_preinscricao', 'tb_actualizacao_saldo_aluno.aluno_id', '=', 'tb_preinscricao.Codigo')
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
            ->select(
                DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.desc") as nome'),
                'tb_preinscricao.Nome_Completo AS aluno',
                'tb_actualizacao_saldo_aluno.data_actualizacao',
                'tb_actualizacao_saldo_aluno.saldo_anterior',
                'tb_actualizacao_saldo_aluno.saldo_actual',
                'tb_actualizacao_saldo_aluno.obs',
                'tb_actualizacao_saldo_aluno.id',
            )
            ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.actualizacao-saldo', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function excelImprimirControleActualizacaoSaldo($o = null, $di = null, $df = null)
    {
        if ($o == "null") {
            $o = "";
        }
        if ($di == "null") {
            $di = "";
        }
        if ($df == "null") {
            $df = "";
        }

        return Excel::download(new ActualizacaoSaldoExport($o, $di, $df), 'controle-actualizacao-saldo.xlsx');
    }

    public function visualizarActualizacaoSaldo($id)
    {
        dd("fgfdgd");
    }


    public function consultarOperacaoDetalhe(Request $request)
    {
        $pagamentos = Pagamento::with('preinscricao.polo')
            ->with('preinscricao.curso')
            ->with('anolectivo')
            ->with('canal')
            ->where('estado', 0)
            ->where('Codigo', $request->numero_comprovativo)
            // ->orwhere('N_Operacao_Bancaria2', $request->numero_comprovativo)
            ->get();

        return Inertia::render('AreaFinanceira/ConsultarOperacao', [
            "pagamentos" => $pagamentos,
        ]);
    }

    public function consultarOperacaoVisualizar($comprovativo)
    {
        $pagamento = Pagamento::where('Codigo', $comprovativo)
            ->with('anolectivo')
            ->with('preinscricao.curso')
            ->with('preinscricao.polo')
            ->with('canal')
            ->first();

        $items = PagamentoItems::where('Codigo_Pagamento', $pagamento->Codigo)
            ->join('tb_tipo_servicos', 'tb_pagamentosi.Codigo_Servico', '=', 'tb_tipo_servicos.Codigo')
            ->select('tb_pagamentosi.Mes', 'tb_pagamentosi.mes_temp_id', 'tb_pagamentosi.Valor_Pago', 'tb_pagamentosi.Ano', 'tb_tipo_servicos.Descricao')
            ->get();

        $arrays = [];

        foreach ($items as $details) {

            if ($details->mes_temp_id != null) {
                $mes = MesTemp::find($details->mes_temp_id)->designacao;
            } else {
                $mes = $details->Mes;
            }

            // if ($details->Ano) {
            //     $ano = AnoLectivo::find($details->codigo_anoLectivo)->Designacao;
            // } else {
            //     $ano = "#";
            // }

            $arrays[] = [
                "Mes" => $mes,
                "ano" => $details->Ano,
                "servico" => $details->Descricao,
                "servico_preco" => $details->Valor_Pago,
                "Multa" => $details->Multa,
            ];
        }

        $estudante = PreInscricao::join('polos', 'tb_preinscricao.polo_id', '=', 'polos.id')
            ->join('tb_admissao', 'tb_preinscricao.codigo', '=', 'tb_admissao.pre_incricao')
            ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->select('tb_matriculas.Codigo', 'tb_cursos.Designacao AS curso', 'polos.designacao AS polo', 'tb_preinscricao.Nome_Completo', 'tb_preinscricao.Contactos_Telefonicos')
            ->findOrFail($pagamento->Codigo_PreInscricao);

        return response()->json([
            "dados" => $pagamento,
            "estudante" => $estudante,
            "items" => $arrays,
        ], 200);

        // return response()->json($data, 200);
    }

    public function pagamentoPorValidar(Request $request)
    {

        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $ano = AnoLectivo::where('estado', 'Activo')->first();
        $data['grau'] = GrauAcademico::get();

        $data['items'] = Pagamento::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
            ->when($request->prestacao, function ($query, $value) {
                $query->where('mes_temp.id', $value);
            })
            ->when($request->forma_pagamento, function ($query, $value) {
                $query->where('tb_pagamentos.forma_pagamento', $value);
            })
            ->when($request->tipo_servico, function ($query, $value) {
                $query->where('factura_items.CodigoProduto', $value);
            })
            ->when($request->grau_academico, function ($query, $value) {
                $query->where('tb_tipo_candidatura.id', ($value));
            })
            ->when($request->data_inicio, function ($query, $value) {
                $query->whereDate('tb_pagamentos.Data', '>=', Carbon::createFromDate($value));
            })
            ->when($request->data_final, function ($query, $value) {
                $query->whereDate('tb_pagamentos.Data', '<=', Carbon::createFromDate($value));
            })
            ->where('tb_pagamentos.estado', 0)
            ->join('factura_items', 'tb_pagamentos.codigo_factura', '=', 'factura_items.CodigoFactura')
            ->join('tb_tipo_servicos', 'factura_items.CodigoProduto', '=', 'tb_tipo_servicos.Codigo')
            ->join('mes_temp', 'factura_items.mes_temp_id', '=', 'mes_temp.id')
            ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_tipo_candidatura', 'tb_preinscricao.codigo_tipo_candidatura', '=', 'tb_tipo_candidatura.id')
            ->select(
                'tb_preinscricao.Nome_Completo',
                'tb_pagamentos.codigo_factura',
                'tb_pagamentos.Codigo',
                'tb_pagamentos.DataBanco',
                'tb_pagamentos.Data',
                'tb_pagamentos.estado',
                'tb_pagamentos.forma_pagamento',
                'mes_temp.designacao AS prestacao',
                'tb_pagamentos.valor_depositado',
                'tb_tipo_candidatura.designacao AS grau_academico',
                'tb_tipo_servicos.Descricao AS servico',
            )
            ->paginate(5)
            ->withQueryString();


        $data['motivos'] = MotivoRejeicao::get();
        $data['servicos'] = TipoServico::where('TipoServico', '!=', NULL)->get();
        $data['prestacoes'] = MesTemp::where('ano_lectivo', $ano->Codigo)->get();
        $data['formaPagamentos'] = FormaPagamento::orderBy('descricao')->get();
        $data["filtros"] = $request->all("prestacao", "forma_pagamento", "data_inicio", "data_final","grau_academico",);

        return Inertia::render('AreaFinanceira/PagamentoPorValidar', $data);
    }


    public function pdfImprimirPagamentoPorValidar(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $data['items'] = Pagamento::when($request->p, function ($query, $value) {
            $query->where('mes_temp.id', $value);
        })
        ->when($request->s, function ($query, $value) {
            $query->where('factura_items.CodigoProduto', $value);
        })
        ->when($request->f, function ($query, $value) {
            $query->where('tb_pagamentos.forma_pagamento', $value);
        })
        ->when($request->di, function ($query, $value) {
            $query->whereDate('tb_pagamentos.Data', '>=', Carbon::createFromDate($value));
        })
        ->when($request->df, function ($query, $value) {
            $query->whereDate('tb_pagamentos.Data', '<=', Carbon::createFromDate($value));
        })
        ->where('tb_pagamentos.estado', 0)
        ->join('factura_items', 'tb_pagamentos.codigo_factura', '=', 'factura_items.CodigoFactura')
        ->join('tb_tipo_servicos', 'factura_items.CodigoProduto', '=', 'tb_tipo_servicos.Codigo')
        ->join('mes_temp', 'factura_items.mes_temp_id', '=', 'mes_temp.id')
        ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
        ->select(
            'tb_preinscricao.Nome_Completo',
            'tb_pagamentos.codigo_factura',
            'tb_pagamentos.Codigo',
            'tb_pagamentos.DataBanco',
            'tb_pagamentos.Data',
            'tb_pagamentos.estado',
            'tb_pagamentos.forma_pagamento',
            'mes_temp.designacao AS prestacao',
            'tb_pagamentos.valor_depositado',
            'tb_tipo_servicos.Descricao AS servico',
        )
        ->get();

        $data['requests'] =  $request->all('p', 's', 'f', 'di', 'df');

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.pagamento-por-validar', $data);
        // ->setPaper('A3', 'portrait');
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function excelImprimirPagamentoPorValidar(Request $request)
    {
        return Excel::download(new PagamentosPorValidarExport($request), 'pagamentos-por-validar.xlsx');
    }


    public function validarPagamentoStore($id)
    {
        $pagamento = Pagamento::findOrFail($id);
        $pagamento->estado = 1;
        $pagamento->update();


        return redirect()->back();
    }

    public function rejeitarPagamentoStore($id, $motivos = null)
    {
        $pagamento = Pagamento::findOrFail($id);
        $pagamento->estado = 3;
        $pagamento->update();

        RejeicaoPagamento::create([
            'codigo_pagamento' => $pagamento->codigo,
            'data_rejeicao' => date("Y-m-d"),
            'user_id' => Auth::user()->codigo_importado,
            'status' => 1,
            'data_validacao' => date("Y-m-d"),
            'obs' => "O Comprovativo não Abre para visualização. deve Reenviar!", 
            'canal' => 5, 
            'motivo_id' => $motivos, 
        ]);

        return redirect()->back();
    }

    // todos os talões em desusos
    public function talaoDesuso(Request $request)
    {
        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        // dd($request->all());

        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $data['items'] = Pagamento::when($request->sort_by, function ($query, $value) {
            $query->orderBy($value, request('order_by', 'asc'));
        })
        ->when(!isset($request->sort_by), function ($query) {
            $query->latest();
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('Data', '>=', Carbon::createFromDate($value));
        })
        ->when($request->data_final, function ($query, $value) {
            $query->whereDate('Data', '<=', Carbon::createFromDate($value));
        })
        ->when($request->operador, function ($query, $value) {
            $query->where('fk_utilizador', '=', $value);
        })
        ->where('AnoLectivo', $ano->Codigo)
        ->where('estado', 3)
        ->with('preinscricao.polo')
        ->with('preinscricao.curso')
        ->with('anolectivo')
        ->with('utilizadores')
        ->orderBy('created_at', 'DESC')
        ->paginate($request->page_size ?? 5)
        ->withQueryString();

        $admins = Grupo::where('designacao', 'Administrador')->select('pk_grupo')->first();
        $finans = Grupo::where('designacao', 'Area Financeira')->select('pk_grupo')->first();
        $tesous = Grupo::where('designacao', 'Tesouraria')->select('pk_grupo')->first();

        $data['filtros'] = $request->all("data_inicio", "data_final", "operador");
        $data['utilizadores'] = GrupoUtilizador::whereIn('fk_grupo', [$admins->pk_grupo, $finans->pk_grupo, $tesous->pk_grupo])->with('utilizadores')->get();

        return Inertia::render('AreaFinanceira/TalaoEmDesuso', $data);
    }

    public function adicionarTalaoDesuso()
    {
        return Inertia::render('AreaFinanceira/AdicionarTalaoEmDesuso');
    }

    public function storeTalaoDesuso(Request $request)
    {

        $request->validate([
            "matricula" => "required",
            "operacao1" => "required|exists:tb_pagamentos,N_Operacao_Bancaria",
            "operacao2" => "nullable",
            "valor" => "required",
            "descricao" => "required",
        ], []);

        $codico = NULL;

        $verificarMatricula = Matricula::where("Codigo", $request->matricula)->first();

        $verificarOperacao1 = Matricula::where("N_Operacao_Bancaria", $request->operacao1)->first();
        $verificarOperacao2 = Matricula::where("N_Operacao_Bancaria2", $request->operacao2)->first();

        if ($verificarOperacao1) {
            return back()->withErrors([
                'warning' => 'The provided credentials do not match our records.',
            ]);
        }

        if (!$verificarMatricula || $verificarMatricula == null) {
            return back()->withErrors([
                'warning' => 'The provided credentials do not match our records.',
            ]);
        } else {
            $codico =  Matricula::where("tb_matriculas.Codigo", $request->matricula)
                ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
                ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
                ->select('tb_preinscricao.Codigo')
                ->first()->Codigo;
        }

        $ano = AnoLectivo::where('estado', 'Activo')->first();


        try {
            //code...
            DB::beginTransaction();

            $create = Pagamento::create([
                'Data' => date("Y-m-d"),
                'N_Operacao_Bancaria' => $request->operacao1,
                'N_Operacao_Bancaria2' => $request->operacao2,
                'Observacao' => $request->descricao,
                'AnoLectivo' => $ano->Codigo,
                'DataBanco' => date("Y-m-d"),
                'Codigo_PreInscricao' => $codico,
                'valor_depositado' => $request->valor,
                'DataRegisto' => date("Y-m-d"),
                'canal' => 1,
                'estado' => 3,
                'statusMovimento' => 0,
                'corrente' => 0,
                'fk_utilizador' => "1513",
            ]);

            if ($request->hasFile("image")) {
                $imagem = $request->image;
                $fileName = $imagem->getClientOriginalName();
                $filePath = $imagem->storeAs("comprovativos", $fileName, "public");
                $create->nome_documento = $filePath;

                $create->save();
            }

            DB::commit();
        } catch (Exception $e) {
            //throw $e;
            DB::rollback();
        }
    }


    public function pdfImprimirTalaoDesuso($o = null, $di = null, $df = null)
    {
        if ($o == "null") {
            $o = "";
        }
        if ($di == "null") {
            $di = "";
        }
        if ($df == "null") {
            $df = "";
        }

        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $data['items'] = Pagamento::when($di, function ($query, $value) {
            $query->whereDate('Data', '>=', Carbon::createFromDate($value));
        })
            ->when($df, function ($query, $value) {
                $query->whereDate('Data', '<=', Carbon::createFromDate($value));
            })
            ->when($o, function ($query, $value) {
                $query->where('fk_utilizador', '=', $value);
            })
            ->where('AnoLectivo', $ano->Codigo)
            ->where('estado', 3)
            ->with('preinscricao.polo')
            ->with('preinscricao.curso')
            ->with('anolectivo')
            ->with('utilizadores')
            ->orderBy('created_at', 'DESC')
            ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.talao-em-desuso', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function excelImprimirTalaoDesuso($o = null, $di = null, $df = null)
    {
        if ($o == "null") {
            $o = "";
        }
        if ($di == "null") {
            $di = "";
        }
        if ($df == "null") {
            $df = "";
        }

        return Excel::download(new TalaoEmDesusoExport($o, $di, $df), 'talao-em-desuso.xlsx');
    }
}
