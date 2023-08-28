<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Bolseiro;
use App\Models\BolseiroSiuma;
use App\Models\GradeCurricularAluno;
use App\Models\Mes;
use App\Models\MesTemp;
use App\Models\Pagamento;
use App\Models\TipoServico;
use App\Models\Turno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{


    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard(Request $request)
    {
        $user = auth()->user();
        
        $data['ano'] = AnoLectivo::where('estado', 'Activo')->first();

        $data['anos_lectivos'] = AnoLectivo::orderBy('ordem', 'asc')->get();

        return Inertia::render('Dashboard', $data);
    }

    public function carregarGraficosPagamentosPorTurnos(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        if($request->ano_lectivo == null){
            $request->ano_lectivo = $ano->Codigo;
        }

        $ano_lectivo_selectionado = AnoLectivo::findOrFail($request->ano_lectivo);
        
        $data['ano_lectivo'] = $ano_lectivo_selectionado->Designacao;

        //total de ultilizadores do sistema
        $data['utilizadores'] = number_format(User::count(), 2, ',', '.');
        
        $total_estudantes_inscritos = GradeCurricularAluno::when($request->ano_lectivo, function ($query, $value) {
            $query->where('codigo_ano_lectivo', '=', $value);
            $query->whereIn('Codigo_Status_Grade_Curricular', [1,2,3]);
        })
        ->distinct('codigo_matricula')
        ->count();

        $data['total_estudantes'] = number_format($total_estudantes_inscritos , 2, ',', '.');
        
        $data['periodos'] = Turno::where('status', 1)->select('Designacao', 'Codigo')->get();

        foreach ($data['periodos'] as $periodo) {

            $data['pagamentos_periodos'] = Pagamento::join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')->where('tb_pagamentos.AnoLectivo', $request->ano_lectivo)
            ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
            ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
            ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->where('tb_pagamentos.estado', 1)
            ->when($request->estado, function($query, $value){
                $query->where('tb_pagamentos.estado', $value);
            })
            ->where('tb_periodos.Codigo', $periodo->Codigo)
            ->select('tb_pagamentos.Totalgeral', 'tb_pagamentosi.Valor_Total')
            ->sum('tb_pagamentosi.Valor_Total');

            $charts_turnos[] = [$periodo->Designacao, $data['pagamentos_periodos']];

        }
                
        $data['charts_turno'] = $charts_turnos;
      
        return response()->json($data);
    }

    public function carregarGraficosPagamentosPorMeses(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        if($request->ano_lectivo == null){
            $request->ano_lectivo = $ano->Codigo;
        }
    
        $ano_lectivo_selectionado = AnoLectivo::findOrFail($request->ano_lectivo);
        $data['ano_lectivo'] = $ano_lectivo_selectionado->Designacao;
       
        
        if($request->ano_lectivo >=  2 AND $request->ano_lectivo <= 15){
            $data['mesTemps'] = Mes::select('codigo AS id', 'mes AS designacao')->get();
        }else{
            $data['mesTemps'] = MesTemp::where('activo', '1')->where('ano_lectivo', $request->ano_lectivo)->get();
        }
        
        foreach ($data['mesTemps'] as $meses) {
            
            $query = Pagamento::join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
            ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
            ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->where('tb_pagamentos.AnoLectivo', $request->ano_lectivo)
            ->where('tb_pagamentos.estado', 1);
            
            if($request->ano_lectivo >=  2 AND $request->ano_lectivo <= 15){
                $query->where('tb_pagamentosi.mes_id', $meses->id);
            }else{
                $query->where('tb_pagamentosi.mes_temp_id', $meses->id);
            }
            
            $data['pagamentos'] =  $query->when($request->estado, function($query, $value){
                $query->where('tb_pagamentos.estado', $value);
            })
            ->select('tb_pagamentosi.mes_temp_id', 'tb_pagamentos.AnoLectivo', 'tb_pagamentos.Totalgeral', 'tb_pagamentosi.Valor_Total')
            ->sum('tb_pagamentosi.Valor_Total');

            $charts_meses[] = [$meses->designacao, $data['pagamentos']];

        }
        
        $ano = AnoLectivo::find($request->ano_lectivo)->first();
        
        $data['charts_meses'] = $charts_meses;

        return response()->json($data);
    

    }

    public function carregarGraficosTotalEstudantesDevodores(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        if($request->ano_lectivo == null){
            $anoSelecionado = $ano->Codigo;
        }else{
            $anoSelecionado = $request->ano_lectivo;
        }
        
        if($request->mes_temp_id){
            $mes = $request->mes_temp_id;
        }else{
            $mes = "";
        }
        
        $estudante_propinas_devedores = DB::table('tb_matriculas as tm_p')
        ->select('tm_p.Codigo as matricula', 'tp_p.Nome_Completo as nome', 'tc2.Designacao as curso', 'tp2.Designacao as turno')
        ->join('tb_admissao as ta_p', 'ta_p.codigo', '=', 'tm_p.Codigo_Aluno')
        ->join('tb_preinscricao as tp_p', 'tp_p.Codigo', '=', 'ta_p.pre_incricao')
        ->join('tb_cursos as tc2', 'tc2.Codigo', '=', 'tm_p.Codigo_Curso')
        ->join('tb_periodos as tp2', 'tp2.Codigo', '=', 'tp_p.Codigo_Turno')
        ->whereRaw('tm_p.Codigo IN (SELECT tgca.codigo_matricula FROM tb_grade_curricular_aluno tgca WHERE tgca.codigo_ano_lectivo = ? AND tgca.Codigo_Status_Grade_Curricular IN (2, 3))', [$anoSelecionado])
        ->whereNotIn('tm_p.Codigo', function ($query) use($mes) {
            $query->select('tm_pp.Codigo')
                ->from('tb_matriculas as tm_pp')
                ->join('tb_admissao as ta_p', 'ta_p.codigo', '=', 'tm_pp.Codigo_Aluno')
                ->join('tb_preinscricao as tp_p', 'tp_p.Codigo', '=', 'ta_p.pre_incricao')
                ->join('tb_cursos as tc2', 'tc2.Codigo', '=', 'tm_pp.Codigo_Curso')
                ->join('tb_periodos as tp2', 'tp2.Codigo', '=', 'tp_p.Codigo_Turno')
                ->join('factura as f', 'f.CodigoMatricula', '=', 'tm_pp.Codigo')
                ->leftJoin('factura_items as fi', 'fi.CodigoFactura', '=', 'f.Codigo')
                ->where('fi.estado', 1)
                ->where('fi.mes_temp_id', $mes);
        })
        ->where('tc2.tipo_candidatura', 1)
        ->whereNotIn('tm_p.Codigo', function ($query) use($anoSelecionado) {
            $query->select('codigo_matricula')
                ->from('tb_bolseiros')
                ->where('codigo_anoLectivo', $anoSelecionado)
                ->where('status', 0)
                ->whereIn('desconto', [100, 0]);
        })
        ->orderBy('tp_p.Nome_Completo', 'ASC')
        ->get();

        $data['total_estudantes_devedores'] = number_format($estudante_propinas_devedores->count() , 2, ',', '.');

      
        return response()->json($data);
    }

    public function carregarGraficosTotalEstudantesPropinasPagas(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        if($request->ano_lectivo == null){
            $request->ano_lectivo = $ano->Codigo;
        }
  
        $estudante_propinas_pagas = Pagamento::when($request->ano_lectivo, function ($query, $value) {
            $query->where('tb_pagamentos.AnoLectivo', '=', $value);
        });
        
        if ($request->ano_lectivo >= 2 AND $request->ano_lectivo <= 15){
            $estudante_propinas_pagas->when($request->mes_temp_id, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_id', '=', $value);
            });
        }else{
            $estudante_propinas_pagas->when($request->mes_temp_id, function ($query, $value) {
                $query->where('tb_pagamentosi.mes_temp_id', '=', $value);
            });
        }
     
        $estudante_propinas_pagas->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->join('tb_preinscricao', 'tb_pagamentos.Codigo_PreInscricao', '=', 'tb_preinscricao.Codigo')
        ->join('tb_admissao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
        ->join('tb_matriculas', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
        ->where('tb_pagamentos.estado', 1)
        ->where('tb_pagamentos.corrente', 1);
        

        $data["estudante_propinas_pagas"] = number_format($estudante_propinas_pagas->count() , 2, ',', '.');

      
        return response()->json($data);
    }

    public function carregarGraficosEstudanteBolseiros(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        if($request->ano_lectivo == null){
            $request->ano_lectivo = $ano->Codigo;
        }

        $ano_lectivo_selectionado = AnoLectivo::findOrFail($request->ano_lectivo);

        // totla bolseriros deste ano lectivo
        if($ano_lectivo_selectionado->Codigo >=  2 AND $ano_lectivo_selectionado->Codigo <= 15){
            $bolseiros_total = BolseiroSiuma::where('ano', $ano_lectivo_selectionado->Designacao)->count();
            $bolseiros_com_renuncia = 0;
            $bolseiros_sem_renuncia = 0;
            
        }else{
            
            $bolseiros = Bolseiro::where(function ($query) {
                $query->whereHas('instituicao', function ($query) {
                    $query->where('tipo_instituicao', 2);
                })->orWhereHas('instituicao', function ($query) {
                    $query->where('tipo_instituicao', 1);
                });
            })
            ->with('instituicao')
            ->where('codigo_anoLectivo', $ano_lectivo_selectionado->Codigo)
            ->where('status', 0)
            ->get();
            
            $bolseiros_com_renuncia = $bolseiros->where('instituicao.tipo_instituicao', 2)->count();
            $bolseiros_sem_renuncia = $bolseiros->where('instituicao.tipo_instituicao', 1)->count();
            $bolseiros_total = $bolseiros->count();
        }
        
        // dd($bolseiros_total, $bolseiros_sem_renuncia);
        
        
        $data["bolseiros"] = number_format( $bolseiros_total, 2, ',', '.');
        $data["bolseiros_sem_renuncia"] = number_format( $bolseiros_sem_renuncia, 2, ',', '.');
        $data["bolseiros_com_renuncia"] = number_format( $bolseiros_com_renuncia, 2, ',', '.');
   
        return response()->json($data);
    }

}
