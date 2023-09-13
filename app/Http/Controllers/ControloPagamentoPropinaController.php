<?php

namespace App\Http\Controllers;

use App\Exports\EstudanteDevedorExport;
use App\Exports\EstudantePropinaPagaExport;
use App\Exports\PagamentoPropinasPorMesExport;
use App\Models\AlunoAdmissao;
use App\Models\AnoLectivo;
use App\Models\Bolseiro;
use App\Models\Curso;
use App\Models\Factura;
use App\Models\Faculdade;
use App\Models\GradeCurricularAluno;
use App\Models\Matricula;
use App\Models\Mes;
use App\Models\MesTemp;
use App\Models\Pagamento;
use App\Models\PreInscricao;
use App\Models\TipoServico;
use App\Models\Turno;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

class ControloPagamentoPropinaController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }


    // estudantes com propinas pagas
    public function estudantePropinaPaga(Request $request)
    {
             
        $ano = AnoLectivo::where('estado', 'Activo')->first();
       
        $anoSelecionado = $request->searchAnoLectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }

        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $query = Pagamento::when($anoSelecionado, function ($query, $value) {
            $query->where('tb_pagamentos.AnoLectivo', '=', $value);
        });

        if ($anoSelecionado >= 2 and $anoSelecionado <= 15) {
            $query->when($request->searchMes, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_id', '=', $value);
            });
        } else {
            $query->when($request->searchMes, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
            });
        }
        
        $query->when($request->searchFaculdade, function ($query, $value) {
            $query->where('tb_cursos.faculdade_id', '=', $value);
        })
        ->when($request->searchCurso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', '=', $value);
        })
        ->when($request->searchTurno, function ($query, $value) {
            $query->where('tb_periodos.Codigo', '=', $value);
        })
        ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
        ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
        ->join('tb_ano_lectivo', 'tb_pagamentos.AnoLectivo', '=', 'tb_ano_lectivo.Codigo')
        ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
        ->where('tb_pagamentos.estado', 1)
        // ->where('tb_pagamentos.corrente', 1)
        ;

        if ($anoSelecionado >= 2 and $anoSelecionado <= 15) {
            $query->join('meses', 'tb_pagamentosi.mes_id', '=', 'meses.codigo');
            $query->select(
                'meses.mes AS servico',
                'meses.codigo AS IdServico',
                'tb_matriculas.Codigo AS matricula',
                'tb_preinscricao.Nome_Completo AS aluno',
                'tb_cursos.Designacao AS curso',
                'tb_periodos.Designacao AS turno',
                'tb_faculdade.designacao AS faculdade',
                'tb_pagamentos.Totalgeral AS valores',
                'tb_ano_lectivo.Designacao AS anolectivo',
                'tb_pagamentos.Codigo AS CodigoPagamento',
                'tb_pagamentosi.Valor_Total AS valorPago',
            );
        } else {
            $query->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id');
            $query->select(
                'mes_temp.designacao AS servico',
                'mes_temp.id AS IdServico',
                'tb_matriculas.Codigo AS matricula',
                'tb_preinscricao.Nome_Completo AS aluno',
                'tb_cursos.Designacao AS curso',
                'tb_periodos.Designacao AS turno',
                'tb_faculdade.designacao AS faculdade',
                'tb_pagamentos.Totalgeral AS valores',
                'tb_ano_lectivo.Designacao AS anolectivo',
                'tb_pagamentos.Codigo AS CodigoPagamento',
                'tb_pagamentosi.Valor_Total AS valorPago',
            );
        }

        $data['facturas'] = $query->distinct('tb_matriculas.Codigo')
            ->orderBy('tb_preinscricao.Nome_Completo', 'asc')
            ->paginate(20)
            ->withQueryString();


        $query2 = Pagamento::when($anoSelecionado, function ($query, $value) {
            $query->where('tb_pagamentos.AnoLectivo', '=', $value);
        });

        if ($anoSelecionado >= 2 and $anoSelecionado <= 15) {
            $query2->when($request->searchMes, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_id', '=', $value);
            });
        } else {
            $query2->when($request->searchMes, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
            });
        }
        $query2->when($request->searchFaculdade, function ($query, $value) {
            $query->where('tb_cursos.faculdade_id', '=', $value);
        })
            ->when($request->searchCurso, function ($query, $value) {
                $query->where('tb_cursos.Codigo', '=', $value);
            })
            ->when($request->searchTurno, function ($query, $value) {
                $query->where('tb_periodos.Codigo', '=', $value);
            })
            ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
            ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
            ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
            ->join('tb_ano_lectivo', 'tb_pagamentos.AnoLectivo', '=', 'tb_ano_lectivo.Codigo')
            ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
            ->where('tb_pagamentos.estado', 1)
            ->where('tb_pagamentos.corrente', 1)
            ;

        if ($anoSelecionado >= 2 and $anoSelecionado <= 15) {
            $query->join('meses', 'tb_pagamentosi.mes_id', '=', 'meses.codigo');
        } else {
            $query2->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id');
        }


        $data['valor_total_pagamentos'] = $query2->sum('Valor_Total');

        $data["anolectivos"] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data["turnos"] = Turno::where('status', 1)->get();
        $data["faculdades"] = Faculdade::where('estado', 1)->get();

        if ($anoSelecionado >= 2 and $anoSelecionado <= 15) {
            $data["mesTemps"] = Mes::select('codigo AS id', 'mes AS designacao')->get();
        } else {
            $data["mesTemps"] = MesTemp::when($anoSelecionado, function ($query, $value) {
                $query->where('ano_lectivo', $value);
            })->get();
        }

        $data["cursos"] = Curso::when($request->searchFaculdade, function ($query, $value) {
            $query->where('faculdade_id', $value);
        })->get();

        $data['ano_lectivo'] = $ano->Designacao;
        $data['ano_selecionado'] = $request->ano_lectivo;
        $data['Totalgeral'] = $request->totalgeral;
        $data["ano_lectivo_activo"] = $ano;

        $data['requests'] = $request->all('searchFaculdade', 'searchAnoLectivo', 'searchMes', 'searchCurso', 'searchTurno');

        if (isset($matricula)) {
            $data['bolseiro'] = Bolseiro::where('codigo_matricula', $matricula->Codigo)
            
            ->where('codigo_anoLectivo', $ano->Codigo)
            ->where('status', 0)
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
            ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
            ->first();
        } else {
            // Lida com a situação em que $matricula não está definida
            // Isso pode incluir a definição de um valor padrão ou a exibição de uma mensagem de erro.
        }

        return Inertia::render('AreaFinanceira/EstudantePropinasPaga', $data);
    }

    public function estudanteCarregarPropinaPaga(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->searchAnoLectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }

        $grade_curriculares = GradeCurricularAluno::when($anoSelecionado, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
            $query->whereIn('Codigo_Status_Grade_Curricular', [2, 3]);
        })->distinct('codigo_matricula')->pluck('codigo_matricula');

        $data['periodos'] = Turno::where('status', 1)->select('Designacao', 'Codigo')->get();

        // /**turnos */
        foreach ($data['periodos'] as $periodo) {

            $pagamento_turno = Pagamento::when($anoSelecionado, function ($query, $value) {
                $query->where('tb_pagamentos.AnoLectivo', '=', $value);
            })
                ->when($request->searchMes, function ($query, $value) {
                    $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
                })
                ->when($request->searchFaculdade, function ($query, $value) {
                    $query->where('tb_cursos.faculdade_id', '=', $value);
                })
                ->when($request->searchCurso, function ($query, $value) {
                    $query->where('tb_cursos.Codigo', '=', $value);
                })
                ->when($request->searchTurno, function ($query, $value) {
                    $query->where('tb_preinscricao.Codigo_Turno', '=', $value);
                })
                ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
                ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
                ->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id')
                ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
                ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
                ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
                ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
                ->where('tb_preinscricao.Codigo_Turno', '=', $periodo->Codigo)
                ->where('tb_pagamentos.estado', 1)
                ->whereIn('tb_matriculas.Codigo', $grade_curriculares)
                ->select('tb_pagamentos.Totalgeral')
                ->sum('tb_pagamentos.Totalgeral');

            $datasetTurno[] = [$periodo->Designacao, $pagamento_turno];
        }

        $data['charts_turno'] = $datasetTurno;

        return response()->json($data);
    }

    public function pagamentosPorMes(Request $request, $id)
    {

        $mes_temp = MesTemp::where('id', $id)->first();

        $data['facturas'] = Pagamento::when($mes_temp->id, function ($query, $value) {
            $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
        })
        ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
        ->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id')
        ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
        ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
        ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
        ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
        ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
        ->where('tb_pagamentos.estado', 1)
        ->select(
            'tb_matriculas.Codigo AS matricula',
            'tb_preinscricao.Nome_Completo AS aluno',
            'tb_cursos.Designacao AS curso',
            'tb_periodos.Designacao AS turno',
            'mes_temp.designacao AS servico',
            'tb_faculdade.designacao AS faculdade',
            'tb_pagamentosi.Valor_Total AS valores'
        )
        ->distinct('tb_matriculas.Codigo')
        ->paginate(20)
        ->withQueryString();

        $data['valor_total_facturas'] = number_format(Pagamento::when($mes_temp->id, function ($query, $value) {
            $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
        })
        ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
        ->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id')
        ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
        ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
        ->where('tb_pagamentos.estado', 1)
        ->sum('tb_pagamentosi.Valor_Total'), 2, '.', ',');

        $data['mes_selecionado'] = $mes_temp->designacao;
        $data['mes_temp_id'] = $id;

        return Inertia::render('AreaFinanceira/PagamentoPorMes', $data);
    }

    public function pdfImprimirestudantePropinaPaga(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->searchAnoLectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }

        // recuperar os servicos deste ano lectivo primeiramente mais somente servicos de propinas
        $servicos = TipoServico::where('Descricao', 'like', 'Propina %')->pluck('Codigo');

        $grade_curriculares = GradeCurricularAluno::when($anoSelecionado, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
            $query->whereIn('Codigo_Status_Grade_Curricular', [2, 3]);
        })->distinct('codigo_matricula')->pluck('codigo_matricula');


        $query = Pagamento::when($anoSelecionado, function ($query, $value) {
            $query->where('tb_pagamentos.AnoLectivo', '=', $value);
        });

        if ($anoSelecionado >= 2 and $anoSelecionado <= 15) {
            $query->when($request->searchMes, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_id', '=', $value);
            });
        } else {
            $query->when($request->searchMes, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
            });
        }

        $query->when($request->searchFaculdade, function ($query, $value) {
            $query->where('tb_cursos.faculdade_id', '=', $value);
        })
            ->when($request->searchCurso, function ($query, $value) {
                $query->where('tb_cursos.Codigo', '=', $value);
            })
            ->when($request->searchTurno, function ($query, $value) {
                $query->where('tb_periodos.Codigo', '=', $value);
            })
            ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
            ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
            ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
            ->join('tb_ano_lectivo', 'tb_pagamentos.AnoLectivo', '=', 'tb_ano_lectivo.Codigo')
            ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
            ->where('tb_pagamentos.estado', 1)
            // ->whereIn('tb_matriculas.Codigo', $grade_curriculares)
            ->whereIn('tb_pagamentosi.Codigo_Servico', $servicos);

        if ($anoSelecionado >= 2 and $anoSelecionado <= 15) {
            $query->join('meses', 'tb_pagamentosi.mes_id', '=', 'meses.codigo');
            $query->select(
                'meses.mes AS servico',
                'meses.codigo AS IdServico',
                'tb_matriculas.Codigo AS matricula',
                'tb_preinscricao.Nome_Completo AS aluno',
                'tb_cursos.Designacao AS curso',
                'tb_periodos.Designacao AS turno',
                'tb_faculdade.designacao AS faculdade',
                'tb_pagamentos.Totalgeral AS valores',
                'tb_ano_lectivo.Designacao AS anolectivo',
                'tb_pagamentos.Codigo AS CodigoPagamento',
                'tb_pagamentosi.Valor_Pago AS valorPago',
            );
        } else {
            $query->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id');
            $query->select(
                'mes_temp.designacao AS servico',
                'mes_temp.id AS IdServico',
                'tb_matriculas.Codigo AS matricula',
                'tb_preinscricao.Nome_Completo AS aluno',
                'tb_cursos.Designacao AS curso',
                'tb_periodos.Designacao AS turno',
                'tb_faculdade.designacao AS faculdade',
                'tb_pagamentos.Totalgeral AS valores',
                'tb_pagamentos.Codigo AS CodigoPagamento',
                'tb_pagamentosi.Valor_Pago AS valorPago',
            );
        }

        $data['valor_total_pagamentos'] = $query->distinct('tb_matriculas.Codigo')->sum('valorPago');

        $data['items'] = $query->distinct('tb_matriculas.Codigo')
            ->limit(2000)
            ->get();


        $data['faculdade'] = Faculdade::find($request->searchFaculdade);
        $data['curso'] = Curso::find($request->searchCurso);
        $data['turno'] = Turno::find($request->searchTurno);
        $data['mes'] = MesTemp::find($request->searchMes);
        $data['ano'] = AnoLectivo::find($anoSelecionado);

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.estudante-propina-paga', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function excelImprimirestudantePropinaPaga(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->searchAnoLectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }

        return Excel::download(new EstudantePropinaPagaExport($anoSelecionado, $request), 'estudante-com-propinas-pagas.xlsx');
    }


    public function pdfImprimirpagamentosPorMes($id = null)
    {
        if ($id == "null") {
            $id = "";
        }
        if ($id == null) {
            $id = "";
        }

        $mes_temp = MesTemp::where('id', $id)->first();

        $grade_curriculares = GradeCurricularAluno::when($mes_temp->ano_lectivo, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
            $query->whereIn('Codigo_Status_Grade_Curricular', [2, 3]);
        })->distinct('codigo_matricula')->pluck('codigo_matricula');

        $data['items'] = Pagamento::when($mes_temp->id, function ($query, $value) {
            $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
        })
            ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
            ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
            ->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id')
            ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
            ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
            ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
            ->where('tb_pagamentos.estado', 1)
            ->whereIn('tb_matriculas.Codigo', $grade_curriculares)
            ->select(
                'tb_matriculas.Codigo AS matricula',
                'tb_preinscricao.Nome_Completo AS aluno',
                'tb_cursos.Designacao AS curso',
                'tb_periodos.Designacao AS turno',
                'mes_temp.designacao AS servico',
                'tb_faculdade.designacao AS faculdade',
                'tb_pagamentos.Totalgeral AS valores'
            )
            ->distinct('tb_matriculas.Codigo')
            ->limit(1000)
            ->get();

        $data['valor_total_facturas'] = number_format(Pagamento::when($mes_temp->id, function ($query, $value) {
            $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
        })
            ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
            ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
            ->join('mes_temp', 'tb_pagamentosi.mes_temp_id', '=', 'mes_temp.id')
            ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
            ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->where('tb_pagamentos.estado', 1)
            ->whereIn('tb_matriculas.Codigo', $grade_curriculares)
            ->sum('tb_pagamentos.Totalgeral'), 2, '.', ',');

        $data['mes_selecionado'] = $mes_temp->designacao;

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.pagamentos-propinas-por-mes', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function excelImprimirpagamentosPorMes($id = null)
    {
        if ($id == "null") {
            $id = "";
        }
        if ($id == null) {
            $id = "";
        }

        return Excel::download(new PagamentoPropinasPorMesExport($id), 'pagamento-propinas-por-mes.xlsx');
    }

    // estudantes devedores
    public function estudanteDevedores(Request $request)
    {
      
        $admissao = AlunoAdmissao::where('Codigo', $request->Codigo_Aluno)->first();
        $searchFaculdade = $request->searchFaculdade ?? "";
        $searchCurso = $request->searchCurso ?? "";
        $searchTurno = $request->searchTurno ?? ""; 
        $searchMes = $request->searchMes ?? "";
      
        
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->searchAnoLectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }
            
        $data['facturas'] = Matricula::with(['admissao.preinscricao.polo', 'admissao.preinscricao.curso', 'admissao.preinscricao.grau_academico', 'admissao.preinscricao.turno'])
        ->whereRaw('tb_matriculas.Codigo IN (SELECT tgca.codigo_matricula FROM tb_grade_curricular_aluno tgca WHERE tgca.codigo_ano_lectivo = ? AND tgca.Codigo_Status_Grade_Curricular IN (2, 3))', [$anoSelecionado])
        ->whereNotIn('tb_matriculas.Codigo', function ($query) use($searchMes) {
            $query->select('tm_pp.Codigo')
                ->from('tb_matriculas as tm_pp')
                ->join('tb_admissao as tb_admissao', 'tb_admissao.codigo', '=', 'tm_pp.Codigo_Aluno')
                ->join('tb_preinscricao as tb_preinscricao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
                ->join('tb_cursos as tb_cursos', 'tb_cursos.Codigo', '=', 'tm_pp.Codigo_Curso')
                ->join('tb_periodos as tp2', 'tp2.Codigo', '=', 'tb_preinscricao.Codigo_Turno')
                ->join('factura as f', 'f.CodigoMatricula', '=', 'tm_pp.Codigo')
                ->leftJoin('factura_items as fi', 'fi.CodigoFactura', '=', 'f.Codigo')
                ->where('fi.estado', 1)
                ->where('fi.mes_temp_id', $searchMes);

                $data['bolseiro'] = Bolseiro::where('codigo_matricula')
                ->where('status', 0)
                ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
                ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
                ->first();
                
        })

       

        ->whereNotIn('Codigo', function ($query) use($anoSelecionado) {
            $query->select('codigo_matricula')
                ->from('tb_bolseiros')
                ->where('codigo_anoLectivo', $anoSelecionado)
                ->where('status', 0)
                ->whereIn('desconto', [100, 0]);
        })
        ->whereHas('admissao.preinscricao', function($query){
            $query->orderBy('Nome_Completo', 'asc');
        })
        ->whereHas('admissao.preinscricao.curso', function($query) use($searchCurso){
            $query->when($searchCurso, function($query) use($searchCurso){
                $query->where('Codigo', $searchCurso);
            });
            $query->where('tipo_candidatura', 1);
        })
        ->whereHas('admissao.preinscricao.turno', function($query) use($searchTurno){
            $query->when($searchTurno, function($query) use($searchTurno){
                $query->where('Codigo', $searchTurno);
            });
        })
        ->paginate(20)
        ->withQueryString();
           
            
       
        $data["anolectivos"] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data["turnos"] = Turno::where('status', 1)->get();
        $data["faculdades"] = Faculdade::where('estado', 1)->get();

           

        $data["mesTemps"] = MesTemp::when($anoSelecionado, function ($query, $value) {
            $query->where('ano_lectivo', $value);
        })->where('activo', 1)->get();

        $data["cursos"] = Curso::when($request->searchFaculdade, function ($query, $value) {
            $query->where('faculdade_id', $value);
        })->get();

        //Ano lectivo ativo
        $data["anolectivoactivo"] = AnoLectivo::where('Codigo', $anoSelecionado)->first();

        $data["filters"] = $request->all("searchAnoLectivo", "searchTurno", "searchMes", "searchCurso", "searchFaculdade");
        $data["ano_lectivo_activo"] = $ano;
        

              
        if (isset($matricula)) {
            $data['bolseiro'] = Bolseiro::where('codigo_matricula', $matricula->Codigo)
            
            ->where('codigo_anoLectivo', $ano->Codigo)
            ->where('status', 0)
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
            ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
            ->first();
        } else {
            // Lida com a situação em que $matricula não está definida
            // Isso pode incluir a definição de um valor padrão ou a exibição de uma mensagem de erro.
        }

        // $data['bolseiro'] = Bolseiro::where('codigo_matricula',$matricula->Codigo)
        //     ->where('codigo_anoLectivo', $ano->Codigo)
        //     ->where('status', 0)
        //     ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
        //     ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
        //     ->first();

        return Inertia::render('AreaFinanceira/EstudanteDevedores',$data);
    }

    public function ImprimirPDFestudanteDevedores(Request $request)
    {
        $searchFaculdade = $request->searchFaculdade ?? "";
        $searchCurso = $request->searchCurso ?? "";
        $searchTurno = $request->searchTurno ?? ""; 
        $searchMes = $request->searchMes ?? "";
        
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->searchAnoLectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }
            
        $data['items'] = Matricula::with(['admissao.preinscricao.polo', 'admissao.preinscricao.curso', 'admissao.preinscricao.grau_academico', 'admissao.preinscricao.turno'])
        ->whereRaw('tb_matriculas.Codigo IN (SELECT tgca.codigo_matricula FROM tb_grade_curricular_aluno tgca WHERE tgca.codigo_ano_lectivo = ? AND tgca.Codigo_Status_Grade_Curricular IN (2, 3))', [$anoSelecionado])
        ->whereNotIn('tb_matriculas.Codigo', function ($query) use($searchMes) {
            $query->select('tm_pp.Codigo')
                ->from('tb_matriculas as tm_pp')
                ->join('tb_admissao as tb_admissao', 'tb_admissao.codigo', '=', 'tm_pp.Codigo_Aluno')
                ->join('tb_preinscricao as tb_preinscricao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
                ->join('tb_cursos as tb_cursos', 'tb_cursos.Codigo', '=', 'tm_pp.Codigo_Curso')
                ->join('tb_periodos as tp2', 'tp2.Codigo', '=', 'tb_preinscricao.Codigo_Turno')
                ->join('factura as f', 'f.CodigoMatricula', '=', 'tm_pp.Codigo')
                ->leftJoin('factura_items as fi', 'fi.CodigoFactura', '=', 'f.Codigo')
                ->where('fi.estado', 1)
                ->where('fi.mes_temp_id', $searchMes);
        })
        ->whereNotIn('Codigo', function ($query) use($anoSelecionado) {
            $query->select('codigo_matricula')
                ->from('tb_bolseiros')
                ->where('codigo_anoLectivo', $anoSelecionado)
                ->where('status', 0)
                ->whereIn('desconto', [100, 0]);
        })
        ->whereHas('admissao.preinscricao', function($query){
            $query->orderBy('Nome_Completo', 'asc');
        })
        ->whereHas('admissao.preinscricao.curso', function($query) use($searchCurso){
            $query->when($searchCurso, function($query) use($searchCurso){
                $query->where('Codigo', $searchCurso);
            });
            $query->where('tipo_candidatura', 1);
        })
        ->whereHas('admissao.preinscricao.turno', function($query) use($searchTurno){
            $query->when($searchTurno, function($query) use($searchTurno){
                $query->where('Codigo', $searchTurno);
            });
        })
        ->paginate(20)
        ->withQueryString();
        
        $data['faculdade'] = Faculdade::find($request->searchFaculdade);
        $data['curso'] = Curso::find($request->searchCurso);
        $data['turno'] = Turno::find($request->searchTurno);
        $data['mes'] = MesTemp::find($request->searchMes);
        $data['ano'] = AnoLectivo::find($anoSelecionado);


            
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.estudante-devedores', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }


    public function excelImprimirestudanteDevedor(Request $request)
    {
        return Excel::download(new EstudanteDevedorExport($request), 'estudante-devedores.xlsx');
    }


    public function getDescricaoTiposAlunos()
    {

        $data['descricao_tipo1'] = DB::table('tb_tipo_aluno')->select('designacao', 'descricao', 'status')->where('id', 1)->where('status', 1)->first();

        $data['descricao_tipo2'] = DB::table('tb_tipo_aluno')->select('designacao', 'descricao', 'status')->where('id', 2)->where('status', 1)->first();

        $data['descricao_tipo3'] = DB::table('tb_tipo_aluno')->select('designacao', 'descricao', 'status')->where('id', 3)->where('status', 1)->first();

        $data['descricao_tipo4'] = DB::table('tb_tipo_aluno')->select('designacao', 'descricao', 'status')->where('id', 4)->where('status', 1)->first();

        return response()->json($data);
    }
}
