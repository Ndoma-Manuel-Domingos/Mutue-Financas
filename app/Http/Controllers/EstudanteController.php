<?php

namespace App\Http\Controllers;

use App\Exports\EstudanteFinalistaInactivoExport;
use App\Exports\EstudanteFinalistasExport;
use App\Exports\EstudanteInactivoExport;
use App\Exports\FacturasEstudanteExport;
use App\Exports\HistoricoActualizacaoSaldoExport;
use App\Exports\PagamentosEstudanteExport;
use App\Exports\ServicosPagosEstudanteExport;
use App\Models\ActualizarSaldoAluno;
use App\Models\AlunoAdmissao;
use App\Models\AnoLectivo;
use App\Models\Bolseiro;
use App\Models\Curso;
use App\Models\DescontoAluno;
use App\Models\Factura;
use App\Models\FacturaItem;
use App\Models\Faculdade;
use App\Models\GradeCurricularAluno;
use App\Models\GrauAcademico;
use App\Models\Grupo;
use App\Models\GrupoUtilizador;
use App\Models\Isencao;
use App\Models\IsencaoMulta;
use App\Models\ListaTipoMovimento;
use App\Models\Matricula;
use App\Models\Mes;
use App\Models\MesTemp;
use App\Models\MotivoEliminaFacturaPagamento;
use App\Models\MotivoIsencao;
use App\Models\Pagamento;
use App\Models\PagamentoItems;
use App\Models\PreInscricao;
use App\Models\TipoMovimento;
use App\Models\TipoServico;
use App\Models\Turno;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Maatwebsite\Excel\Facades\Excel;

use Illuminate\Support\Facades\Http;

class EstudanteController extends Controller
{
    use TraitHelpers;
    //

    public function __construct()
    {
        $this->middleware('auth');
    }


    public function show($numero)
    {
        session()->forget('confirmar_estudante_bolsa_mutue_finance');
        
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $resultado = Matricula::where('tb_matriculas.Codigo', $numero)
            ->orWhere('tb_preinscricao.Nome_Completo', 'like', '%' . $numero . '%')
            ->orWhere('tb_preinscricao.Bilhete_Identidade',  $numero)
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.Codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
            ->select(
                'tb_matriculas.Codigo AS codigo',
                'tb_preinscricao.Nome_Completo AS nome',
                'tb_preinscricao.Bilhete_Identidade AS bilheite',
                'tb_periodos.Designacao AS periodo',
                'tb_cursos.Designacao AS curso',
            )
            ->first();
            
        if ($resultado) {
        
            $bolseiro = Bolseiro::where('codigo_matricula', $resultado->codigo)
            ->where('codigo_anoLectivo', $ano->Codigo)
            ->first();
            
            return response()->json(
            [
                'estudante' => $resultado,
                'bolseiro' => $bolseiro
            ], 200);
        } else {
            return response()->json(['errors' => true], 200);
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'estudante' => 'required',
            'anolectivo' => 'required',
            'instituicao' => 'required',
            'tipo_bolsa' => 'required',
            'afectacao' => 'required',
            'desconto' => 'required',
            'semestre' => 'required',
        ], []);

        /**
         * variaveis
         */
        $bolsaInicio = 0;
        $bolsaFinal = 0;

        $resultado = Matricula::where('Codigo', $request->estudante)->first();


        if ($resultado) {

            $ano = AnoLectivo::where('Codigo', $request->anolectivo)->first();

            // verificar bolsa de estudantes
            // dd($request->all(), $resultado,  $ano);

            $verificarBolsa = Bolseiro::where('status', 0)
                ->where('codigo_matricula', $resultado->Codigo)
                ->where('codigo_anoLectivo', $ano->Codigo)
                ->first();

            if ($verificarBolsa) {
                return response()->json(['error' => "Este estudante já tem uma bolsa de estudo activo"], 404);
            }

            if ($request->semestre == 1) {
                $bolsaInicio = $ano->dataInicioPrimeiroSemestre;
                $bolsaFinal = $ano->dataFimPrimeiroSemestre;
            } else if ($request->semestre == 2) {
                $bolsaInicio = $ano->dataInicioSegundoSemestre;
                $bolsaFinal = $ano->dataFimSegundoSemestre;
            } else if ($request->semestre == 3) {
                $bolsaInicio = $ano->dataInicioPrimeiroSemestre;
                $bolsaFinal = $ano->dataFimSegundoSemestre;
            }

            Bolseiro::create([
                'codigo_matricula' => $resultado->Codigo,
                'codigo_tipo_bolsa' => $request->tipo_bolsa,
                'desconto' => $request->desconto,
                'isentar_multa' => NULL,
                'codigo_utilizador' => Auth::user()->codigo_importado,
                'data_inicio_bolsa' => $bolsaInicio,
                'data_fim_bolsa' => $bolsaFinal,
                'codigo_Instituicao' => $request->instituicao,
                'pagarTaxasAdicionais',
                'codigo_anoLectivo' =>  $ano->Codigo,
                'afectacao' => $request->afectacao,
                'observacao' => "observacao",
                'historico' => "historico",
                'status' => 0,
                'semestre' => $request->semestre,
                'estadoBolsa' => NULL,
                'ref_utilizador' => Auth::user()->codigo_importado,
                'canal' => 1,
            ]);

            session()->forget('confirmar_estudante_bolsa_mutue_finance');

            return redirect()->back();
        }

        return response()->json(['errors' => true], 200);
    }

    public function confirmar($numero)
    {
        session()->forget('confirmar_estudante_bolsa_mutue_finance');
        session()->put('confirmar_estudante_bolsa_mutue_finance', $numero);
    }

    public function storeDesconto(Request $request)
    {

        $request->validate([
            'estudante' => 'required',
            'anolectivo' => 'required',
            // 'instituicao_id' => 'required',
            'afectacao' => 'required',

            'semestre' => 'required',
        ], []);
        /**
         * variaveis
         */
        $resultado = Matricula::where('Codigo', $request->estudante)->first();

        if ($resultado) {

            $ano = AnoLectivo::where('Codigo', $request->anolectivo)->first();

            $verificarDesconto = DescontoAluno::where('estatus_desconto_id', 1)
                ->where('codigo_matricula', $resultado->Codigo)
                ->where('codigo_anoLectivo', $ano->Codigo)
                ->first();

            if ($verificarDesconto) {
                return response()->json(['error' => "Este estudante já tem um Desconto activo"], 404);
            }

            DescontoAluno::create([
                'codigo_matricula' => $resultado->Codigo,
                'codigo_tipo_desconto' => $request->tipoDesconto,
                'isentar_multa' => NULL,
                'codigo_utilizador' => Auth::user()->codigo_importado,
                'instituicao_id' => 9,
                'codigo_anoLectivo' =>  $ano->Codigo,
                'afectacao' => $request->afectacao,
                'observacao' => "Observação",
                'historico' => "Histórico",
                'estatus_desconto_id' => 1,
                'semestre' => $request->semestre,
                'codigo_utilizador' => Auth::user()->codigo_importado,
                'canal' => 1,
            ]);

            session()->forget('confirmar_estudante_bolsa_mutue_finance');

            return redirect()->back();
        }

        return response()->json(['errors' => true], 200);
    }


    public function confirmarDesconto($numero)
    {
        session()->forget('confirmar_estudante_bolsa_mutue_finance');
        session()->put('confirmar_estudante_bolsa_mutue_finance', $numero);
    }

    public function carregarEstado($id)
    {
        $response = Http::get("http://10.10.6.13:8080/mutue/mga/estado_matricula?matricula={$id}");
        $data = $response->json();
        
        return response()->json(['data' => $data], 200);
    }

    public function visualizarPerfilEstudante($id)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $matricula = Matricula::where('Codigo', $id)->first();
        $admissao = AlunoAdmissao::where('Codigo', $matricula->Codigo_Aluno)->first();

        $data['preinscricao'] = PreInscricao::where('Codigo', $admissao->pre_incricao)->select('saldo_anterior', 'saldo', 'Codigo', 'codigo_tipo_candidatura')->first();
        $data['matricula'] = $matricula;
        $data['admissao'] = $admissao;

        $total_grade_curricular_primeiro_ano = 0;
        $total_grade_curricular_segundo_ano = 0;
        $total_grade_curricular_terceiro_ano = 0;
        $total_grade_curricular_quarto_ano = 0;
        $total_grade_curricular_quinto_ano = 0;

        $grades = GradeCurricularAluno::join('tb_grade_curricular', 'tb_grade_curricular_aluno.codigo_grade_curricular', '=', 'tb_grade_curricular.Codigo')
            ->where('Codigo_Status_Grade_curricular', '2')
            ->where('codigo_matricula', $matricula->Codigo)
            ->where('codigo_ano_lectivo', $this->anoLectivoActivo())
            ->get();
        
        if ($grades) {
            foreach ($grades as $grade) {
                if ($grade->Codigo_Classe == 1) {
                    $total_grade_curricular_primeiro_ano++;
                }

                if ($grade->Codigo_Classe == 2) {
                    $total_grade_curricular_segundo_ano++;
                }

                if ($grade->Codigo_Classe == 3) {
                    $total_grade_curricular_terceiro_ano++;
                }

                if ($grade->Codigo_Classe == 4) {
                    $total_grade_curricular_quarto_ano++;
                }

                if ($grade->Codigo_Classe == 5) {
                    $total_grade_curricular_quinto_ano++;
                }
            }
        }

        $data['ano_curricular'] = "";
        if ($total_grade_curricular_primeiro_ano > $total_grade_curricular_segundo_ano and $total_grade_curricular_primeiro_ano > $total_grade_curricular_terceiro_ano and $total_grade_curricular_primeiro_ano > $total_grade_curricular_quarto_ano and $total_grade_curricular_primeiro_ano > $total_grade_curricular_quinto_ano) {
            $data['ano_curricular'] = "1º Ano";
        } else {
            if ($total_grade_curricular_segundo_ano > $total_grade_curricular_primeiro_ano and $total_grade_curricular_segundo_ano > $total_grade_curricular_terceiro_ano and $total_grade_curricular_segundo_ano > $total_grade_curricular_quarto_ano and $total_grade_curricular_segundo_ano > $total_grade_curricular_quinto_ano) {
                $data['ano_curricular'] = "2º Ano";
            } else if ($total_grade_curricular_terceiro_ano > $total_grade_curricular_primeiro_ano and $total_grade_curricular_terceiro_ano > $total_grade_curricular_segundo_ano and $total_grade_curricular_terceiro_ano > $total_grade_curricular_quarto_ano and $total_grade_curricular_terceiro_ano > $total_grade_curricular_quinto_ano) {
                $data['ano_curricular'] = "3º Ano";
            } else if ($total_grade_curricular_quarto_ano > $total_grade_curricular_primeiro_ano and $total_grade_curricular_quarto_ano > $total_grade_curricular_segundo_ano and $total_grade_curricular_quarto_ano > $total_grade_curricular_terceiro_ano and $total_grade_curricular_quarto_ano > $total_grade_curricular_quinto_ano) {
                $data['ano_curricular'] = "4º Ano";
            } else if ($total_grade_curricular_quinto_ano > $total_grade_curricular_primeiro_ano and $total_grade_curricular_quinto_ano > $total_grade_curricular_segundo_ano and $total_grade_curricular_quinto_ano > $total_grade_curricular_terceiro_ano and $total_grade_curricular_quinto_ano > $total_grade_curricular_quarto_ano) {
                $data['ano_curricular'] = "5º Ano";
            }
        }

        $data['lista_historico_saldos'] = ActualizarSaldoAluno::where('aluno_id', $data['preinscricao']->Codigo)
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.pk")'), '=', 'mca_tb_utilizador.codigo_importado')
            ->select(
                DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.desc") as nome'),
                'tb_actualizacao_saldo_aluno.data_actualizacao',
                'tb_actualizacao_saldo_aluno.saldo_anterior',
                'tb_actualizacao_saldo_aluno.saldo_actual',
                'tb_actualizacao_saldo_aluno.obs',
                'tb_actualizacao_saldo_aluno.id',
            )
            ->orderBy('id', 'desc')
            ->get();

        $data['estudante'] = Matricula::where('tb_matriculas.Codigo', $id)
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_faculdade', 'tb_cursos.faculdade_id', '=', 'tb_faculdade.codigo')
            ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
            ->join('polos', 'tb_preinscricao.polo_id', '=', 'polos.id')
            ->join('tb_ocupacao', 'tb_preinscricao.Codigo_Ocupacao', '=', 'tb_ocupacao.Codigo')
            ->join('tb_profissao', 'tb_preinscricao.Codigo_Profissao', '=', 'tb_profissao.Codigo')
            ->join('tb_nacionalidades', 'tb_preinscricao.Codigo_Nacionalidade', '=', 'tb_nacionalidades.Codigo')
            ->select(
                'tb_matriculas.Codigo AS codigo',
                'tb_preinscricao.Codigo AS codigo_preinscricao',
                'tb_preinscricao.Nome_Completo AS nome',
                'tb_preinscricao.Contactos_Telefonicos AS contacto_principal',
                'tb_preinscricao.contacto_de_emergencia AS contac to_alternativo',
                'tb_preinscricao.Email AS email',
                'tb_preinscricao.Naturalidade AS Naturalidade',
                'tb_preinscricao.Data_Nascimento AS Data_Nascimento',
                'tb_preinscricao.Sexo AS Genero',
                'tb_preinscricao.Bilhete_Identidade AS Numero_BI',
                'tb_preinscricao.data_emissao_bi AS Data_Emissao',
                'tb_preinscricao.data_validade_bi AS Data_Validade',
                'tb_preinscricao.Pai AS Nome_Pai',
                'tb_preinscricao.Mae AS Nome_Mae',
                'tb_nacionalidades.Designacao AS Nacionalidade',
                'tb_preinscricao.Morada_Completa AS Morada',
                'tb_ocupacao.Designacao AS Ocupacao',
                'tb_profissao.Designacao AS Profissao',
                'tb_matriculas.estado_matricula AS estadoMatricula',
                'polos.designacao AS campus',
                'polos.id AS poloId',
                'tb_cursos.Designacao AS curso',
                'tb_faculdade.designacao AS faculdade',
                'tb_periodos.Designacao AS turno',
            )->first();

        
        
        $data["preco_curso"] = TipoServico::where('polo_id', $data['estudante']['poloId'])
            ->where('Descricao', 'Propina ' . $data['estudante']['curso'])
            ->first();


        $data['bolseiro'] = Bolseiro::where('codigo_matricula', $matricula->Codigo)
            ->where('codigo_anoLectivo', $ano->Codigo)
            ->where('status', 0)
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')
            ->join('tb_Instituicao', 'tb_bolseiros.codigo_Instituicao', '=', 'tb_Instituicao.codigo')
            ->first();

        // $data["anolectivos"] = AnoLectivo::get();
        $data["anolectivos"] = AnoLectivo::whereIn('Codigo', $this->anoLectivoEstudante($id))->orderBy('Codigo', 'desc')->get();
        
        $data["anolectivosinscritosactual"] = AnoLectivo::where('Codigo', $this->anoLectivoActivo())->has('mes_temps')->orderBy('Codigo', 'desc')->first();
        $data["anolectivosinscritosanterior"] = AnoLectivo::where('Codigo', $this->anoLectivoActivoAnterior())->has('mes_temps')->orderBy('Codigo', 'desc')->first();
        
        
        //######################################################
        //##################### MENSALIDADE DO ESTUDANTE CARTAÃO############
        //######################################################
        $prestacoes = collect([]);
        $prestacoes_anterior = collect([]);
        
        $preinscricao = $data['preinscricao'];
        $preinscricao_anterior = $data['preinscricao'];
        
        $prestacoes_por_ano = MesTemp::where(function ($q) use ($preinscricao){
          if ($preinscricao->codigo_tipo_candidatura == 1) {
            $q->where('activo', 1);
            $q->where('ano_lectivo', $this->anoLectivoActivo());
          } else {
            $q->where('activo_posgraduacao', 1);
            $q->where('ano_lectivo', $this->anoLectivoActivoMestrado());
          }
        })->get();
        
        $prestacoes_por_ano_anterior = MesTemp::where(function ($q) use ($preinscricao_anterior){
            if ($preinscricao_anterior->codigo_tipo_candidatura == 1) {
                $q->where('activo', 1);
                $q->where('ano_lectivo', $this->anoLectivoActivoAnterior());
            } else {
                $q->where('activo_posgraduacao', 1);
                $q->where('ano_lectivo', $this->anoLectivoActivoMestrado());
            }
        })->get();
        
        foreach ($prestacoes_por_ano as $key => $prestacao) {
            $prestacaoPaga = FacturaItem::whereHas('factura', function ($q) use ($matricula) {
              $q->where('CodigoMatricula', $matricula->Codigo)
                ->where('estado', '!=', 3); //de propinas
            })->where('mes_temp_id', $prestacao->id)->with('factura')->first();
      
            $prestacao['factura_item'] = $prestacaoPaga;
            //Verificar isenção
            $prestacao['suspenso'] = $this->checkIsencao($matricula->Codigo, $prestacao->id, $this->anoLectivoActivo(), $preinscricao);
            $prestacao['bolseiro'] = $this->BolsaPorSemestre($matricula->Codigo, $this->anoLectivoActivo(), $prestacao->semestre);
      
            $prestacoes->push($prestacao);
        }
        
        foreach ($prestacoes_por_ano_anterior as $key => $prestacao) {
            $prestacaoPaga_anterior = FacturaItem::whereHas('factura', function ($q) use ($matricula) {
              $q->where('CodigoMatricula', $matricula->Codigo)
                ->where('estado', '!=', 3); //de propinas
            })->where('mes_temp_id', $prestacao->id)->with('factura')->first();
      
            $prestacao['factura_item_anterior'] = $prestacaoPaga_anterior;
            //Verificar isenção
            $prestacao['suspenso_anterior'] = $this->checkIsencao($matricula->Codigo, $prestacao->id, $this->anoLectivoActivoAnterior(), $preinscricao);
            $prestacao['bolseiro'] = $this->BolsaPorSemestre($matricula->Codigo, $this->anoLectivoActivoAnterior(), $prestacao->semestre);
      
            $prestacoes_anterior->push($prestacao);
        }
        
        $data["prestacoes"] = $prestacoes;
        $data["prestacoes_anterior"] = $prestacoes_anterior;
        
        //######################################################
        //##################### MENSALIDADE DO ESTUDANTE CARTAÃO############
        //######################################################
        

        $data["anolectivospagamentos"] = AnoLectivo::where('Codigo', $this->anoLectivoActivoAnterior())->orWhere('Codigo', $this->anoLectivoActivo())->get();
        $data["motivos_eliminar_facturas_pagamentos"] = MotivoEliminaFacturaPagamento::get();

        // utilizadores validadores
        // utilizadores adiministrativos
        // utilizadores área financeira
        // utilizadores tesouraria
        $validacao = Grupo::where('designacao', "Validação de Pagamentos")->select('pk_grupo')->first();
        $admins = Grupo::where('designacao', 'Administrador')->select('pk_grupo')->first();
        $finans = Grupo::where('designacao', 'Area Financeira')->select('pk_grupo')->first();
        $tesous = Grupo::where('designacao', 'Tesouraria')->select('pk_grupo')->first();

        $data['utilizadores'] = GrupoUtilizador::whereIn('fk_grupo', [$validacao->pk_grupo, $finans->pk_grupo, $tesous->pk_grupo])->with('utilizadores')->get();
        
        $data['tipos_movimentos'] = TipoMovimento::get();

        $data["ano_lectivo_mestrado"] = AnoLectivo::where('Codigo', $this->anoLectivoActivoMestrado())->first();
        $data["ano_lectivo_doutorado"] = AnoLectivo::where('Codigo', $this->anoLectivoActivoDoutorado())->first();
        
        $data["ano_lectivo_activo"] = $ano;

        $data['motivos'] = MotivoIsencao::get();

        return Inertia::render('Estudantes/PerfilEstudante', $data);
    }
    
    public function checkIsencao($codigo_matricula, $mes_temp_id, $codigo_anoLectivo, $preinscricao)
    {
        $isencoes = Isencao::where('estado_isensao', 'Activo')->where('codigo_matricula', $codigo_matricula)->where('mes_temp_id', $mes_temp_id)->where('codigo_anoLectivo', $codigo_anoLectivo)->get();
       
        return filled($isencoes);
    }
    
    
    public function BolsaPorSemestre($codigo_matricula,$codigo_anoLectivo,$semestre_id)
    {
         // estado ativo é 0 e desativado é 1
       $bolseiro = DB::table('tb_bolseiros')
        ->join('tb_tipo_bolsas','tb_tipo_bolsas.codigo','tb_bolseiros.codigo_tipo_bolsa')
        ->where('tb_bolseiros.codigo_matricula', $codigo_matricula)
        ->where('tb_bolseiros.codigo_anoLectivo', $codigo_anoLectivo)
        ->where('tb_bolseiros.semestre', $semestre_id)
      //  ->where('status', $this->anoAtualPrincipal->index()==$codigo_anoLectivo ? 0 : 1)->select('*','tb_tipo_bolsas.designacao as tipo_bolsa')->first(); //Abordagem do ano 2021-2022 
        ->where('status', 0)->select('*','tb_tipo_bolsas.designacao as tipo_bolsa')
        ->first(); //Abordagem do ano Actual
        
      
       return $bolseiro;
    }
    
    public function bolseiro_siiuma($codigo_matricula, $codigo_anoLectivo){
        $bolseiro = DB::table('tb_bolseiro_siiuma')
        ->where('tb_bolseiro_siiuma.codigo_matricula', $codigo_matricula)
        ->where('tb_bolseiro_siiuma.ano', $codigo_anoLectivo)
        ->select('*')->first();
    
        return $bolseiro;
    
     }
    
  
    public function carregarInscricoes($id, $ano)
    {
        $query = GradeCurricularAluno::where('codigo_matricula', $id)->whereIn('Codigo_Status_Grade_Curricular', [2, 3])
        ->join('tb_grade_curricular', 'tb_grade_curricular_aluno.codigo_grade_curricular', '=', 'tb_grade_curricular.Codigo')
        ->join('tb_disciplinas', 'tb_grade_curricular.Codigo_Disciplina', '=', 'tb_disciplinas.Codigo')
        ->join('tb_classes', 'tb_grade_curricular.Codigo_Classe', '=', 'tb_classes.Codigo')
        ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
        ->join('tb_duracao', 'tb_disciplinas.duracao', '=', 'tb_duracao.codigo')
        ->where('codigo_ano_lectivo', $ano);
        if($ano < 18){
            $query->join('tb_turmas', 'tb_grade_curricular_aluno.turma', '=', 'tb_turmas.Codigo')
            ->where('codigo_ano_lectivo', $ano)
            ->select(
                'tb_grade_curricular_aluno.codigo AS codigo',
                'tb_semestres.Designacao AS semestre',
                'tb_disciplinas.Designacao AS disciplina',
                'tb_duracao.designacao AS duracao',
                'tb_classes.Designacao AS classe',
                'tb_turmas.Designacao AS horario',
            )
            ->orderBy('codigo','desc');
        }else {
            $query
            ->select(
                DB::raw('json_extract(tb_grade_curricular_aluno.ref_horario, "$.desc") as horario'),
                'tb_grade_curricular_aluno.codigo AS codigo',
                'tb_semestres.Designacao AS semestre',
                'tb_disciplinas.Designacao AS disciplina',
                'tb_duracao.designacao AS duracao',
                'tb_classes.Designacao AS classe'
            )
            ->orderBy('codigo','desc');
        }    
        
        $inscricoes = $query->get();

        return response()->json([
            "inscricoes" => $inscricoes
        ], 200);
    }

    public function carregarIsencaoPagamentoMatricula($matricula)
    {

        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $isencoes_pagamentos = Isencao::join('tb_tipo_servicos', 'tb_isencoes.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('mes_temp', 'tb_isencoes.mes_temp_id', '=', 'mes_temp.id')
            ->where('codigo_matricula',  $matricula)
            // ->where('codigo_anoLectivo',  $ano->Codigo)
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_isencoes.ref_utilizado, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
            ->select('tb_isencoes.codigo', 'tb_isencoes.estado_isensao', 'tb_isencoes.data_isencao', 'mes_temp.designacao', 'tb_tipo_servicos.Descricao', 'mca_tb_utilizador.nome')
            ->get();

        $isencoes_pagamentos_count = count($isencoes_pagamentos);

        $isencoes_multas = IsencaoMulta::join('tb_tipo_servicos', 'tb_isencoe_multa.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('mes_temp', 'tb_isencoe_multa.mes_temp_id', '=', 'mes_temp.id')
            ->where('codigo_matricula',  $matricula)
            // ->where('codigo_anoLectivo',  $ano->Codigo)
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_isencoe_multa.ref_utilizado, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
            ->select('tb_isencoe_multa.codigo', 'tb_isencoe_multa.estado_isensao', 'tb_isencoe_multa.data_isencao', 'mes_temp.designacao', 'tb_tipo_servicos.Descricao', 'mca_tb_utilizador.nome')
            ->get();

        $isencoes_multas_count = count($isencoes_multas);

        $meses = MesTemp::where('ano_lectivo', $ano->Codigo)->get();
        $servicos = TipoServico::where('codigo_ano_lectivo', $ano->Codigo)->get();

        return response()->json([
            "isencoes_pagamentos" => $isencoes_pagamentos,
            "isencoes_pagamentos_count" => $isencoes_pagamentos_count,

            "isencoes_multas" => $isencoes_multas,
            "isencoes_multas_count" => $isencoes_multas_count,

            "meses" => $meses,
            "servicos" => $servicos,
        ], 200);
    }

    public function carregarIsencaoPagamento($ano_id, $matricula)
    {
        $ano = AnoLectivo::findOrFail($ano_id);
        
        if ($ano->Codigo >= 2 and $ano->Codigo <= 15) {
            $meses = Mes::select('codigo AS id', 'mes AS designacao')->get();
        } else {
            $meses = MesTemp::where('ano_lectivo', $ano->Codigo)->where('activo', 1)->get();
        }
        $servicos = TipoServico::where('codigo_ano_lectivo', $ano->Codigo)->get();
   
        $isencoes_pagamentos = Isencao::join('tb_tipo_servicos', 'tb_isencoes.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('mes_temp', 'tb_isencoes.mes_temp_id', '=', 'mes_temp.id')
            ->where('codigo_matricula',  $matricula)
            ->where('codigo_anoLectivo',  $ano->Codigo)
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_isencoes.ref_utilizado, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
            ->select('tb_isencoes.codigo', 'tb_isencoes.estado_isensao', 'tb_isencoes.data_isencao', 'mes_temp.designacao', 'tb_tipo_servicos.Descricao', 'mca_tb_utilizador.nome')
            ->get();
  

        $isencoes_multas = IsencaoMulta::join('tb_tipo_servicos', 'tb_isencoe_multa.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('mes_temp', 'tb_isencoe_multa.mes_temp_id', '=', 'mes_temp.id')
            ->where('codigo_matricula',  $matricula)
            ->where('codigo_anoLectivo',  $ano->Codigo)
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_isencoe_multa.ref_utilizado, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
            ->select('tb_isencoe_multa.codigo', 'tb_isencoe_multa.estado_isensao', 'tb_isencoe_multa.data_isencao', 'mes_temp.designacao', 'tb_tipo_servicos.Descricao', 'mca_tb_utilizador.nome')
            ->get();   
            

        $isencoes_pagamentos_count = count($isencoes_pagamentos);
        $isencoes_multas_count = count($isencoes_multas);

        return response()->json([
            "isencoes_pagamentos" => $isencoes_pagamentos,
            "isencoes_pagamentos_count" => $isencoes_pagamentos_count,

            "isencoes_multas" => $isencoes_multas,
            "isencoes_multas_count" => $isencoes_multas_count,

            "meses" => $meses,
            "servicos" => $servicos,
            "ano" => $ano,
        ], 200);
    }

    
    // #####################################################################
    // ################### INICIO CARREGAR SERVICOS PAGOS DO ESTUDANTES     ####################
    // #####################################################################

    public function carregarServicoPagos(Request $request)
    {
        $servicos = TipoServico::where('Descricao', 'not like', 'Propina %')
        ->where('codigo_ano_lectivo', $request->ano)
        ->pluck('Codigo');
    
        $facturas = Pagamento::where('Codigo_PreInscricao', $request->codigo)
        ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->whereIn('Codigo_Servico', $servicos)
        ->where('AnoLectivo', $request->ano)
        ->with('items.servico')
        ->get();

        return response()->json([
            "facturas" => $facturas,
        ], 200);
    }

    public function carregarServicoPagosPDF(Request $request)
    {
        $servicos = TipoServico::where('Descricao', 'not like', 'Propina %')
        ->where('codigo_ano_lectivo', $request->ano)
        ->pluck('Codigo');
    
        $data['facturas'] = Pagamento::where('Codigo_PreInscricao', $request->codigo)
        ->join('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->whereIn('Codigo_Servico', $servicos)
        ->where('AnoLectivo', $request->ano)
        ->with('items.servico')
        ->get();
        
        $data['aluno'] = PreInscricao::findOrFail($request->codigo);
                
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.lista-servicos-pagos-estudantes', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }
    
    
    public function carregarServicoPagosEXCEL(Request $request)
    {
        return Excel::download(new ServicosPagosEstudanteExport($request), 'servicos-pagos-estudantes.xlsx');
    }

    
    // #####################################################################
    // ################### INICIO CARREGAR FACTURAS DO ESTUDANTES     ####################
    // #####################################################################

    public function carregarFacturasEstudante(Request $request)
    {
        $facturas = Factura::when($request->ano, function ($query, $value) {
            $query->where('ano_lectivo', $value);
        })
        ->where('estado', $request->estado)
        ->with('items.servico')
        ->where('CodigoMatricula', $request->codigo)
        ->get();

        return response()->json([
            "facturas" => $facturas,
        ], 200);
    }
    
    public function carregarFacturasEstudantePDF(Request $request)
    {
        $data['facturas'] = Factura::when($request->ano, function ($query, $value) {
            $query->where('ano_lectivo', $value);
        })
        ->where('estado', $request->estado)
        ->with('items.servico')
        ->where('CodigoMatricula', $request->codigo)
        ->get();
                
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.carregar-facturas-estudantes', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();

    }
    
    public function carregarFacturasEstudanteEXCEL(Request $request)
    {
        return Excel::download(new FacturasEstudanteExport($request), 'carregar-facturas-estudantes.xlsx');
    }
    
    // #####################################################################
    // ################### FINAL CARREGAR FACTURAS DO ESTUDANTES     ####################
    // #####################################################################

    public function carregarDetalheFactura($code)
    {
        $factura = Factura::join('tb_matriculas', 'factura.CodigoMatricula', '=', 'tb_matriculas.Codigo')
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('polos', 'tb_preinscricao.polo_id', '=', 'polos.id')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->select('factura.Codigo', 'tb_matriculas.Codigo AS codMatriucla', 'tb_cursos.Designacao AS curso', 'tb_preinscricao.Nome_Completo AS nome', 'polos.designacao AS polo', 'factura.ValorEntregue', 'factura.DataFactura', 'factura.ValorAPagar')
            ->findOrFail($code);

        $facturas_details = FacturaItem::join('tb_tipo_servicos', 'factura_items.CodigoProduto', '=', 'tb_tipo_servicos.Codigo')
            ->select('tb_tipo_servicos.Descricao AS servico', 'tb_tipo_servicos.Preco AS servico_preco', 'factura_items.Multa', 'factura_items.Mes', 'factura_items.mes_temp_id', 'factura_items.codigo_anoLectivo')
            ->where('CodigoFactura', $factura->Codigo)
            ->get();

        $arrays = [];

        foreach ($facturas_details as $details) {

            if ($details->mes_temp_id) {
                $mes = MesTemp::find($details->mes_temp_id)->designacao;
            } else {
                $mes = $details->Mes;
            }

            if ($details->codigo_anoLectivo) {
                $ano = AnoLectivo::find($details->codigo_anoLectivo)->Designacao;
            } else {
                $ano = "#";
            }

            $arrays[] = [
                "Mes" => $mes,
                "ano" => $ano,
                "servico" => $details->servico,
                "servico_preco" => $details->servico_preco,
                "Multa" => $details->Multa,
            ];
        }


        return response()->json([
            "message" => "Pagamento Removido com sucesso!",
            "factura" => $factura,
            "facturas_details" => $arrays,
        ], 200);
    }

    public function removerIsencaoPagamentoPost(Request $request)
    {
        $isencao = Isencao::findOrFail($request->codigo);
        $isencao->obs = $request->message;
        $isencao->estado_isensao = "Inactivo";
        $isencao->update();

        $isencoes_pagamentos = Isencao::join('tb_tipo_servicos', 'tb_isencoes.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('mes_temp', 'tb_isencoes.mes_temp_id', '=', 'mes_temp.id')
            ->where('codigo_matricula', $request->matricula)
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_isencoes.ref_utilizado, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
            ->select('tb_isencoes.codigo', 'tb_isencoes.estado_isensao', 'tb_isencoes.data_isencao', 'mes_temp.designacao', 'tb_tipo_servicos.Descricao', 'mca_tb_utilizador.nome')
            ->get();

        return response()->json([
            "message" => "Pagamento Removido com sucesso!",
            "isencoes_pagamentos" => $isencoes_pagamentos
        ], 200);
    }

    public function removerFacturaPagamentoPost(Request $request)
    {
        $factura = Factura::findOrFail($request->codigo);
        $factura_item = FacturaItem::where('CodigoFactura', $factura->Codigo)->get();

        if ($factura_item) {
            foreach ($factura_item as $item) {
                FacturaItem::findOrFail($item->Codigo)->delete();
            }
        }

        $pagamentos = Pagamento::where('codigo_factura', $factura->Codigo)->get();
        $pgamentosItem = PagamentoItems::where('Codigo_Pagamento', $pagamentos->Codigo)->get();

        if ($pgamentosItem) {
            foreach ($pgamentosItem as $item) {
                PagamentoItems::findOrFail($item->Codigo)->delete();
            }
        }

        $pagamentos->delete();
        $factura->delete();


        return response()->json([
            "message" => "Pagamento Removido com sucesso!",
        ], 200);
    }

    public function carregarIsencaoMultaPost(Request $request)
    {

        $arrays = json_encode([
            'pk' => Auth::user()->pk_utilizador,
            'desc' => Auth::user()->nome,
            'corLetra' => "black",
            'disponivel' => false,
        ]);
        
        
        foreach($request->mes_isencao as $item){
        
            $verificar = IsencaoMulta::where('codigo_servico', $request->servico_isencao)
                ->where('codigo_anoLectivo', $request->ano_isencao)
                ->where('mes_temp_id', $item)
                ->where('codigo_matricula', $request->codigo)
                ->first();
                
            if (!$verificar) {
            
                IsencaoMulta::create([
                    'codigo_matricula' => $request->codigo,
                    'codigo_servico' => $request->servico_isencao,
                    'codigo_utilizador' => Auth::user()->codigo_importado,
                    'mes_temp_id' => $item,
                    'data_isencao' => date("y-m-d"),
                    'canal' => 3,
                    'obs' => NULL,
                    'estado_isensao' => 'Activo',
                    'codigo_anoLectivo' => $request->ano_isencao,
                    'Codigo_motivo' => $request->motivo_isencao,
                    'mes_id' => NULL,
                    'ref_utilizado' => $arrays,
                ]);
            }
            
        }

        // if ($verificar) {
        //     return response()->json([
        //         "message" => "Este Multa  já esta isentado!"
        //     ], 201);
        // }

        // IsencaoMulta::create([
        //     'codigo_matricula' => $request->codigo,
        //     'codigo_servico' => $request->servico_isencao,
        //     'codigo_utilizador' => Auth::user()->codigo_importado,
        //     'mes_temp_id' => $request->mes_isencao,
        //     'data_isencao' => date("y-m-d"),
        //     'canal' => 3,
        //     'obs' => NULL,
        //     'estado_isensao' => 'Activo',
        //     'codigo_anoLectivo' => $request->ano_isencao,
        //     'Codigo_motivo' => $request->motivo_isencao,
        //     'mes_id' => NULL,
        //     'ref_utilizado' => $arrays,
        // ]);


        $isencoes_multas = IsencaoMulta::join('tb_tipo_servicos', 'tb_isencoe_multa.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('mes_temp', 'tb_isencoe_multa.mes_temp_id', '=', 'mes_temp.id')
            ->where('codigo_matricula', $request->codigo)
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_isencoe_multa.ref_utilizado, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
            ->select('tb_isencoe_multa.codigo', 'tb_isencoe_multa.estado_isensao', 'tb_isencoe_multa.data_isencao', 'mes_temp.designacao', 'tb_tipo_servicos.Descricao', 'mca_tb_utilizador.nome')
            ->get();

        return response()->json([
            "message" => "Multa isentado com sucesso, se por acaso nós meses selcionado já tenha um mês isentado já não será isento!",
            "isencoes_multas" => $isencoes_multas,
            "isencoes_multas_count" => count($isencoes_multas),
        ], 200);
    }

    public function carregarIsencaoPagamentoPost(Request $request)
    {
        $arrays = json_encode([
            'pk' => Auth::user()->pk_utilizador,
            'desc' => Auth::user()->nome,
            'corLetra' => "black",
            'disponivel' => false,
        ]);

        
        foreach ($request->mes_isencao as $item){
        
            $verificar = Isencao::where('codigo_servico', $request->servico_isencao)
                ->where('codigo_anoLectivo', $request->ano_isencao)
                ->where('mes_temp_id', $item)
                ->where('codigo_matricula', $request->codigo)
                ->first();
                
                
            if(!$verificar){
                Isencao::create([
                    'codigo_matricula' => $request->codigo,
                    'codigo_servico' => $request->servico_isencao,
                    'codigo_utilizador' => Auth::user()->codigo_importado,
                    'mes_temp_id' => $item,
                    'data_isencao' => date("y-m-d"),
                    'canal' => 3,
                    'obs' => NULL,
                    'estado_isensao' => 'Activo',
                    'codigo_anoLectivo' => $request->ano_isencao,
                    'Codigo_motivo' => $request->motivo_isencao,
                    'mes_id' => NULL,
                    'ref_utilizado' => $arrays,
                ]);            
            }
        }

        
        // if ($verificar) {
        //     return response()->json([
        //         "message" => "Este Pagamento  já esta isentado!"
        //     ], 201);
        // }
    
        // Isencao::create([
        //     'codigo_matricula' => $request->codigo,
        //     'codigo_servico' => $request->servico_isencao,
        //     'codigo_utilizador' => Auth::user()->codigo_importado,
        //     'mes_temp_id' => $request->mes_isencao,
        //     'data_isencao' => date("y-m-d"),
        //     'canal' => 3,
        //     'obs' => NULL,
        //     'estado_isensao' => 'Activo',
        //     'codigo_anoLectivo' => $request->ano_isencao,
        //     'Codigo_motivo' => $request->motivo_isencao,
        //     'mes_id' => NULL,
        //     'ref_utilizado' => $arrays,
        // ]);

        

        $isencoes_pagamentos = Isencao::join('tb_tipo_servicos', 'tb_isencoes.codigo_servico', '=', 'tb_tipo_servicos.Codigo')
            ->join('mes_temp', 'tb_isencoes.mes_temp_id', '=', 'mes_temp.id')
            ->where('codigo_matricula', $request->codigo)
            ->join('mca_tb_utilizador', DB::raw('json_extract(tb_isencoes.ref_utilizado, "$.pk")'), '=', 'mca_tb_utilizador.pk_utilizador')
            ->select('tb_isencoes.codigo', 'tb_isencoes.estado_isensao', 'tb_isencoes.data_isencao', 'mes_temp.designacao', 'tb_tipo_servicos.Descricao', 'mca_tb_utilizador.nome')
            ->get();

        return response()->json([
            "isencoes_pagamentos" => $isencoes_pagamentos,
            "isencoes_pagamentos_count" => count($isencoes_pagamentos),
            "message" => "Pagamento isentado com sucesso, se por acaso nós meses selcionado já tenha um mês isentado já não será isento!",
        ], 200);
    }


    public function carregarTipoMovimentos(Request $request)
    {
        $lista = ListaTipoMovimento::when($request->codigo, function ($query, $value) {
            $query->where('matricula', $value);
        })
        ->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_movimento', '>=', Carbon::createFromDate($value));
        })->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_movimento', '<=', Carbon::createFromDate($value));
        })->when($request->tipo, function ($query, $value) {
            $query->where('codigoTipoMovimento', $value);
        })->when($request->opreador, function ($query, $value) {
            $query->where('codigoUtilizador', $value);
        })
        ->with('tipo_movimento')
        ->paginate(20);

        $soma = ListaTipoMovimento::when($request->codigo, function ($query, $value) {
            $query->where('matricula', $value);
        })->when($request->data_inicio, function ($query, $value) {
            $query->whereDate('data_movimento', '>=', Carbon::createFromDate($value));
        })->when($request->data_final, function ($query, $value) {
            $query->whereDate('data_movimento', '<=', Carbon::createFromDate($value));
        })->when($request->tipo, function ($query, $value) {
            $query->where('codigoTipoMovimento', $value);
        })->sum('debito');

        return response()->json([
            "lista_movimentos" => $lista,
            "lista_movimentos_soma" => $soma,
            "total_lista_movimentos" => count($lista),
        ], 200);
    }

    public function pesquisarNumeroMatricula($matricula = null)
    {
        $data = Matricula::where('tb_matriculas.Codigo', $matricula)
            ->orWhere('tb_preinscricao.Nome_Completo', "like", "%{$matricula}%")
            ->orWhere('tb_preinscricao.Bilhete_Identidade', "{$matricula}")
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->select(
                'tb_preinscricao.Codigo AS inscricao',
                'tb_matriculas.Codigo',
                'tb_preinscricao.Nome_Completo',
                'tb_preinscricao.Contactos_Telefonicos AS contacto_principal',
                'tb_preinscricao.Bilhete_Identidade',
                'tb_cursos.Designacao',
            )
            ->first();

        return response()->json([
            "matricula" => $data
        ], 200);
    }
    
    // #####################################################################
    // ################### INICIO CARREGAMENTO DE PGAMENTPS     ####################
    // #####################################################################


    public function carregarPagamentos(Request $request)
    {
        $servicos = TipoServico::where('Descricao', 'like', 'Propina %')->where('codigo_ano_lectivo', $request->ano)->pluck('Codigo');
        
        $pagamentos = Pagamento::when($request->ano, function ($query, $value) {
            $query->where('AnoLectivo', $value);
        })
        ->leftjoin('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->leftjoin('tb_tipo_servicos', 'tb_pagamentosi.Codigo_Servico', '=', 'tb_tipo_servicos.Codigo')
        ->whereIn('Codigo_Servico', $servicos)
        ->where('Codigo_PreInscricao', $request->codigo)
        ->where('tb_pagamentos.estado', $request->estado)
        ->select('tb_tipo_servicos.Descricao', 'tb_pagamentos.Codigo', 'tb_pagamentos.estado', 'tb_pagamentos.DataBanco', 'tb_pagamentos.Data', 'tb_pagamentos.codigo_factura', 'tb_pagamentos.valor_depositado')
        ->get();

        return response()->json([
            "lista_pagamentos" => $pagamentos
        ], 200);
    }
    
    
    public function carregarPagamentosPDF(Request $request)
    {
        $servicos = TipoServico::where('Descricao', 'like', 'Propina %')->where('codigo_ano_lectivo', $request->ano)->pluck('Codigo');
        
        $data['pagamentos'] = Pagamento::when($request->ano, function ($query, $value) {
            $query->where('AnoLectivo', $value);
        })
        ->leftjoin('tb_pagamentosi', 'tb_pagamentos.Codigo', '=', 'tb_pagamentosi.Codigo_Pagamento')
        ->leftjoin('tb_tipo_servicos', 'tb_pagamentosi.Codigo_Servico', '=', 'tb_tipo_servicos.Codigo')
        ->whereIn('Codigo_Servico', $servicos)
        ->where('Codigo_PreInscricao', $request->codigo)
        ->where('tb_pagamentos.estado', $request->estado)
        ->select('tb_tipo_servicos.Descricao', 'tb_pagamentos.Codigo', 'tb_pagamentos.estado', 'tb_pagamentos.DataBanco', 'tb_pagamentos.Data', 'tb_pagamentos.codigo_factura', 'tb_pagamentos.valor_depositado')
        ->get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.carregar-pagamentos-estudantes', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }
    
    
    public function carregarPagamentosEXCEL(Request $request)
    {
        return Excel::download(new PagamentosEstudanteExport($request), 'lista-de-pagamentos-estudantes.xlsx');
    }
    
    // #####################################################################
    // ################### INICIO CARREGAMENTO DE PGAMENTPS     ####################
    // #####################################################################
    
    
    // #####################################################################
    // ################### INICIO ACTUALIZAÇÔES DOS SALDOS     ####################
    // #####################################################################

    public function actualizarSaldo(Request $request)
    {
        $request->validate([
            'saldo_a_actualizar' => 'required|integer',
            'saldo_actual' => 'required|integer',
            'saldo_motivo' => 'required'
        ], [
            'saldo_a_actualizar.required' => "Campo Obrigatorio!",
            'saldo_a_actualizar.integer' => "O valor deve sempre um número!",
            'saldo_actual.required' => "Campo Obrigatorio!",
            'saldo_actual.integer' => "O valor deve sempre um número!",
            'saldo_motivo.required' => "Campo Obrigatorio",
        ]);
    
        $update = PreInscricao::findOrFail($request->codigo);
        
        $update->saldo = $request->saldo_a_actualizar;
        $update->saldo_anterior = $request->saldo_actual;
        $update->obs_saldo = $request->saldo_motivo;
        
        $update->update();

        $dados_utilizadores = [
            "pk" => Auth::user()->codigo_importado,
            "desc" => Auth::user()->nome,
            "corLetra" => "black",
            "disponivel" => false,
        ];

        ActualizarSaldoAluno::create([
            'aluno_id' => $update->Codigo,
            'user_id' => Auth::user()->codigo_importado,
            'data_actualizacao' => date("Y-m-d H:i:s"),
            'saldo_anterior' => $request->saldo_actual,
            'saldo_actual' => $request->saldo_a_actualizar,
            'canal' => 3,
            'obs' => $request->saldo_motivo,
            'ref_utilizador' => json_encode($dados_utilizadores),
        ]);
    
        return redirect()->back();
    }


    public function carregarActualizacoesSaldo(Request $request)
    {
        $data['lista_historico_saldos'] = ActualizarSaldoAluno::where('aluno_id', $request->codigo)
        ->join('mca_tb_utilizador', DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.pk")'), '=', 'mca_tb_utilizador.codigo_importado')
        ->select(
            DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.desc") as nome'),
            'tb_actualizacao_saldo_aluno.data_actualizacao',
            'tb_actualizacao_saldo_aluno.saldo_anterior',
            'tb_actualizacao_saldo_aluno.saldo_actual',
            'tb_actualizacao_saldo_aluno.obs',
            'tb_actualizacao_saldo_aluno.id',
        )
        ->orderBy('tb_actualizacao_saldo_aluno.id', 'desc')
        ->get();

        return response()->json($data);
    }
    

    public function carregarActualizacoesSaldoPDF(Request $request)
    {
        $data['lista_historico_saldos'] = ActualizarSaldoAluno::where('aluno_id', $request->codigo)
        ->join('mca_tb_utilizador', DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.pk")'), '=', 'mca_tb_utilizador.codigo_importado')
        ->select(
            DB::raw('json_extract(tb_actualizacao_saldo_aluno.ref_utilizador, "$.desc") as nome'),
            'tb_actualizacao_saldo_aluno.data_actualizacao',
            'tb_actualizacao_saldo_aluno.saldo_anterior',
            'tb_actualizacao_saldo_aluno.saldo_actual',
            'tb_actualizacao_saldo_aluno.obs',
            'tb_actualizacao_saldo_aluno.id',
            'tb_preinscricao.Nome_Completo',
        )
        ->leftjoin('tb_preinscricao', 'tb_actualizacao_saldo_aluno.aluno_id', '=', 'tb_preinscricao.Codigo')
        ->orderBy('tb_actualizacao_saldo_aluno.id', 'desc')
        ->get();
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.historico-de-actualizacao-saldo', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();

    }

    public function carregarActualizacoesSaldoEXCEL(Request $request)
    {
        return Excel::download(new HistoricoActualizacaoSaldoExport($request), 'historicos-de-actualizacao-de-saldos-estudantes.xlsx');
    }
    
    // #####################################################################
    // ################### FINAL ACTUALIZAÇÔES DOS SALDOS     ####################
    // #####################################################################


    public function mostrarOperador($string = null)
    {
        if ($string == "null") {
            $operador = "Ndoma estudante";
        } else {
            $operador = "Ndoma operador";
        }
        return response()->json([
            "operador" => $operador
        ], 200);
    }


    // public function estudanteFinalistas()
    // {
    //     return Inertia::render('Estudantes/PerfilEstudante');
    // }
    
    public function estudanteInactivo(Request $request)
    {
        $data['graus'] = GrauAcademico::get();
        $data["anolectivos"] = AnoLectivo::orderBy('ordem', 'asc')->get();   
        $data["faculdades"] = Faculdade::where('estado', 1)->get();
        $data["cursos"] = Curso::where('status', 1)->when($request->faculdade, function($query, $value){
            $query->where('faculdade_id', $value);
        })->get();
    
        $ano = AnoLectivo::where('estado', 'activo')->first();

        if(!$request->ano_inicio){
            $ordem = $ano->ordem;
        }else{
            $ano = AnoLectivo::findOrFail($request->ano_inicio);
            $ordem = $ano->ordem;
        }
        
        if(!$request->ano_final){
            $ordem2 = $ano->ordem;
        }else{
            $ano = AnoLectivo::findOrFail($request->ano_final);
            $ordem2 = $ano->ordem;
        }
        
        $query = DB::table('tb_matriculas AS tm')
        ->select(
            DB::raw('DISTINCTROW tp.Nome_Completo AS nome'),
            'tp.Bilhete_Identidade AS bilhete',
            'tp.Sexo AS genero',
            'tm.Codigo AS matricula',
            'tc.Designacao AS curso',
            'us.telefone AS telefone',
            'us.email AS email',
            'tal.Designacao AS anoLectivo'
        )
        ->join('tb_admissao AS ta', 'ta.codigo', '=', 'tm.Codigo_Aluno')
        ->join('tb_preinscricao AS tp', 'tp.Codigo', '=', 'ta.pre_incricao')
        ->join('users AS us', 'us.id', '=', 'tp.user_id')
        ->join('tb_provincias', 'tb_provincias.Codigo', '=', 'tp.codigo_provincia_residencia_permanente')
        ->join('tb_nacionalidades', 'tp.Codigo_Nacionalidade', '=', 'tb_nacionalidades.Codigo')
        ->join('tb_municipios AS tm2', 'tm2.Codigo', '=', 'tp.codigo_municipio')
        ->join('tb_cursos AS tc', 'tc.Codigo', '=', 'tm.Codigo_Curso')
        ->join('tb_faculdade', 'tb_faculdade.codigo', '=', 'tc.faculdade_id')
        ->join('tb_grade_curricular_aluno AS tgca2', 'tgca2.codigo_matricula', '=', 'tm.Codigo')
        ->join('tb_ano_lectivo AS tal', 'tal.Codigo', '=', 'tp.anoLectivo')
        ->where('tm.estado_matricula', 'inactivo')
        ->whereBetween('tal.ordem', [$ordem, $ordem2])
        ->when($request->curso, function($query, $value){
            $query->where('tc.Codigo', $value);
        })
        ->when($request->faculdade, function($query, $value){
            $query->where('tc.faculdade_id', $value);
        })
        ->when($request->grau, function($query, $value){
            $query->where('tc.tipo_candidatura', $value);
        });
        
        $data['estudantes_total'] = count($query->get());
        $data['estudantes'] = $query->paginate(20)
        ->withQueryString();

        
        return Inertia::render('AreaFinanceira/EstudanteInactivo', $data);
    }
    
    public function estudanteInactivoPdf(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'activo')->first();

        if(!$request->ano_inicio){
            $ordem = $ano->ordem;
        }else{
            $ano = AnoLectivo::findOrFail($request->ano_inicio);
            $ordem = $ano->ordem;
        }
        
        if(!$request->ano_final){
            $ordem2 = $ano->ordem;
        }else{
            $ano = AnoLectivo::findOrFail($request->ano_final);
            $ordem2 = $ano->ordem;
        }
        
        $data['estudantes'] = DB::table('tb_matriculas AS tm')
        ->select(
            DB::raw('DISTINCTROW tp.Nome_Completo AS nome'),
            'tp.Bilhete_Identidade AS bilhete',
            'tp.Sexo AS genero',
            'tm.Codigo AS matricula',
            'tc.Designacao AS curso',
            'us.telefone AS telefone',
            'us.email AS email',
            'tal.Designacao AS anoLectivo'
        )
        ->join('tb_admissao AS ta', 'ta.codigo', '=', 'tm.Codigo_Aluno')
        ->join('tb_preinscricao AS tp', 'tp.Codigo', '=', 'ta.pre_incricao')
        ->join('users AS us', 'us.id', '=', 'tp.user_id')
        ->join('tb_provincias', 'tb_provincias.Codigo', '=', 'tp.codigo_provincia_residencia_permanente')
        ->join('tb_nacionalidades', 'tp.Codigo_Nacionalidade', '=', 'tb_nacionalidades.Codigo')
        ->join('tb_municipios AS tm2', 'tm2.Codigo', '=', 'tp.codigo_municipio')
        ->join('tb_cursos AS tc', 'tc.Codigo', '=', 'tm.Codigo_Curso')
        ->join('tb_faculdade', 'tb_faculdade.codigo', '=', 'tc.faculdade_id')
        ->join('tb_grade_curricular_aluno AS tgca2', 'tgca2.codigo_matricula', '=', 'tm.Codigo')
        ->join('tb_ano_lectivo AS tal', 'tal.Codigo', '=', 'tp.anoLectivo')
        ->where('tm.estado_matricula', 'inactivo')
        ->whereBetween('tal.ordem', [$ordem, $ordem2])
        ->when($request->curso, function($query, $value){
            $query->where('tc.Codigo', $value);
        })
        ->when($request->faculdade, function($query, $value){
            $query->where('tc.faculdade_id', $value);
        })
        ->when($request->grau, function($query, $value){
            $query->where('tc.tipo_candidatura', $value);
        })
        ->get();
        
        $data['ano_inicio'] = AnoLectivo::find($request->ano_inicio);
        $data['ano_final'] = AnoLectivo::find($request->ano_final);
        $data['faculdade'] = Faculdade::find($request->faculdade);
        $data['curso'] = Curso::find($request->curso);
        $data['grau'] = GrauAcademico::find($request->grau);
        
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.estudantes-inactivos', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }
    
    public function estudanteInactivoExcel(Request $request)
    {
        return Excel::download(new EstudanteInactivoExport($request), 'estudantes-inactivos.xlsx');
    }
    
    public function estudanteFinalistaInactivo(Request $request)
    {
        $data['graus'] = GrauAcademico::get();
        $data["anolectivos"] = AnoLectivo::orderBy('ordem', 'asc')->get();   
        $data["cursos"] = Curso::where('status', 1)->get();
        
        $ano = AnoLectivo::where('estado', 'activo')->first();

        if(!$request->ano_inicio){
            $ordem = $ano->ordem;
        }else{
            $ano = AnoLectivo::findOrFail($request->ano_inicio);
            $ordem = $ano->ordem;
        }
        
        if(!$request->ano_final){
            $ordem2 = $ano->ordem;
        }else{
            $ano = AnoLectivo::findOrFail($request->ano_final);
            $ordem2 = $ano->ordem;
        }

        $data['estudantes'] = DB::table('tb_matriculas AS tm')
        ->selectRaw('DISTINCTROW tp.Nome_Completo AS nome, tp.Bilhete_Identidade AS bilhete, tp.Sexo AS genero, tm.Codigo AS matricula, tc.Designacao AS curso, us.telefone AS telefone, us.email AS email, tal.Designacao AS anoLectivo')
        ->selectRaw('((SELECT COUNT(tpcg.codigo_grade_curricular)
            FROM tb_plano_curricular_grade tpcg
            INNER JOIN tb_plano_curricular_curso tpcc ON tpcg.codigo_plano_curricular_curso = tpcc.codigo
            WHERE tpcc.codigo_curso = tm.Codigo_Curso
            AND tpcc.codigo_ano_lectivo = 1) +
            (SELECT COUNT(tpcg.codigo_grade_curricular)
            FROM tb_plano_curricular_grade tpcg
            INNER JOIN tb_plano_curricular_curso tpcc ON tpcg.codigo_plano_curricular_curso = tpcc.codigo
            INNER JOIN tb_preinscricao tp2 ON ta.pre_incricao = tp2.Codigo
            WHERE tpcc.codigo_curso = tp2.Curso_Candidatura
            AND tpcc.codigo_ano_lectivo = 1
            AND tp2.Curso_Candidatura != tm.Codigo_Curso)) AS qdtCadeirasCurso')
        ->selectRaw('((SELECT COUNT(tgca.codigo_grade_curricular)
            FROM tb_grade_curricular_aluno tgca
            INNER JOIN tb_grade_curricular tgc ON tgc.Codigo = tgca.codigo_grade_curricular
            WHERE tgca.codigo_matricula = tm.Codigo
            AND tgca.Codigo_Status_Grade_Curricular = 3
            AND tgc.status NOT IN (0,3)
            AND tm.Codigo_Curso = tgc.Codigo_Curso)) AS qtdCadeirasConcluidas')
        ->join('tb_admissao AS ta', 'ta.codigo', '=', 'tm.Codigo_Aluno')
        ->join('tb_preinscricao AS tp', 'tp.Codigo', '=', 'ta.pre_incricao')
        ->join('users AS us', 'us.id', '=', 'tp.user_id')
        ->join('tb_provincias', 'tb_provincias.Codigo', '=', 'tp.codigo_provincia_residencia_permanente')
        ->join('tb_nacionalidades', 'tp.Codigo_Nacionalidade', '=', 'tb_nacionalidades.Codigo')
        ->join('tb_municipios AS tm2', 'tm2.Codigo', '=', 'tp.codigo_municipio')
        ->join('tb_cursos AS tc', 'tc.Codigo', '=', 'tm.Codigo_Curso')
        ->join('tb_faculdade', 'tb_faculdade.codigo', '=', 'tc.faculdade_id')
        ->join('tb_grade_curricular_aluno AS tgca2', 'tgca2.codigo_matricula', '=', 'tm.Codigo')
        ->join('tb_ano_lectivo AS tal', 'tal.Codigo', '=', 'tp.anoLectivo')
        ->where('tm.estado_matricula', '=', 'inactivo')
        ->whereBetween('tal.ordem', [$ordem, $ordem2])
        ->havingRaw('(qdtCadeirasCurso - qtdCadeirasConcluidas) <= 10')
        ->when($request->curso, function($query, $value){
            $query->where('tc.Codigo', $value);
        })
        ->when($request->faculdade, function($query, $value){
            $query->where('tc.faculdade_id', $value);
        })
        ->when($request->grau, function($query, $value){
            $query->where('tc.tipo_candidatura', $value);
        })
        ->paginate(20)
        ->withQueryString();

        
        return Inertia::render('AreaFinanceira/EstudanteFinalistaInactivo', $data);
    }
    
    public function estudanteFinalistaInactivoPdf(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'activo')->first();

        if(!$request->ano_inicio){
            $ordem = $ano->ordem;
        }else{
            $ano = AnoLectivo::findOrFail($request->ano_inicio);
            $ordem = $ano->ordem;
        }
        
        if(!$request->ano_final){
            $ordem2 = $ano->ordem;
        }else{
            $ano = AnoLectivo::findOrFail($request->ano_final);
            $ordem2 = $ano->ordem;
        }

        $data['estudantes'] = DB::table('tb_matriculas AS tm')
        ->selectRaw('DISTINCTROW tp.Nome_Completo AS nome, tp.Bilhete_Identidade AS bilhete, tp.Sexo AS genero, tm.Codigo AS matricula, tc.Designacao AS curso, us.telefone AS telefone, us.email AS email, tal.Designacao AS anoLectivo')
        ->selectRaw('((SELECT COUNT(tpcg.codigo_grade_curricular)
            FROM tb_plano_curricular_grade tpcg
            INNER JOIN tb_plano_curricular_curso tpcc ON tpcg.codigo_plano_curricular_curso = tpcc.codigo
            WHERE tpcc.codigo_curso = tm.Codigo_Curso
            AND tpcc.codigo_ano_lectivo = 1) +
            (SELECT COUNT(tpcg.codigo_grade_curricular)
            FROM tb_plano_curricular_grade tpcg
            INNER JOIN tb_plano_curricular_curso tpcc ON tpcg.codigo_plano_curricular_curso = tpcc.codigo
            INNER JOIN tb_preinscricao tp2 ON ta.pre_incricao = tp2.Codigo
            WHERE tpcc.codigo_curso = tp2.Curso_Candidatura
            AND tpcc.codigo_ano_lectivo = 1
            AND tp2.Curso_Candidatura != tm.Codigo_Curso)) AS qdtCadeirasCurso')
        ->selectRaw('((SELECT COUNT(tgca.codigo_grade_curricular)
            FROM tb_grade_curricular_aluno tgca
            INNER JOIN tb_grade_curricular tgc ON tgc.Codigo = tgca.codigo_grade_curricular
            WHERE tgca.codigo_matricula = tm.Codigo
            AND tgca.Codigo_Status_Grade_Curricular = 3
            AND tgc.status NOT IN (0,3)
            AND tm.Codigo_Curso = tgc.Codigo_Curso)) AS qtdCadeirasConcluidas')
        ->join('tb_admissao AS ta', 'ta.codigo', '=', 'tm.Codigo_Aluno')
        ->join('tb_preinscricao AS tp', 'tp.Codigo', '=', 'ta.pre_incricao')
        ->join('users AS us', 'us.id', '=', 'tp.user_id')
        ->join('tb_provincias', 'tb_provincias.Codigo', '=', 'tp.codigo_provincia_residencia_permanente')
        ->join('tb_nacionalidades', 'tp.Codigo_Nacionalidade', '=', 'tb_nacionalidades.Codigo')
        ->join('tb_municipios AS tm2', 'tm2.Codigo', '=', 'tp.codigo_municipio')
        ->join('tb_cursos AS tc', 'tc.Codigo', '=', 'tm.Codigo_Curso')
        ->join('tb_faculdade', 'tb_faculdade.codigo', '=', 'tc.faculdade_id')
        ->join('tb_grade_curricular_aluno AS tgca2', 'tgca2.codigo_matricula', '=', 'tm.Codigo')
        ->join('tb_ano_lectivo AS tal', 'tal.Codigo', '=', 'tp.anoLectivo')
        ->where('tm.estado_matricula', '=', 'inactivo')
        ->whereBetween('tal.ordem', [$ordem, $ordem2])
        ->havingRaw('(qdtCadeirasCurso - qtdCadeirasConcluidas) <= 10')
        ->when($request->curso, function($query, $value){
            $query->where('tc.Codigo', $value);
        })
        ->when($request->faculdade, function($query, $value){
            $query->where('tc.faculdade_id', $value);
        })
        ->when($request->grau, function($query, $value){
            $query->where('tc.tipo_candidatura', $value);
        })
        ->get();
        
        $data['ano_inicio'] = AnoLectivo::find($request->ano_inicio);
        $data['ano_final'] = AnoLectivo::find($request->ano_final);
        $data['faculdade'] = Faculdade::find($request->faculdade);
        $data['curso'] = Curso::find($request->curso);
        $data['grau'] = GrauAcademico::find($request->grau);
                
        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.estudantes-finalista-inactivos', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }
    
    public function estudanteFinalistaInactivoExcel(Request $request)
    {
        return Excel::download(new EstudanteFinalistaInactivoExport($request), 'estudantes-finalista-inactivos.xlsx');
    }
    
    
    public function estudanteFinalistas(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->anolectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }
        

        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $data["estudantes"] = Matricula::whereNotNull('tb_matriculas.Codigo')
        ->selectRaw('DISTINCT
            tb_preinscricao.Nome_Completo AS nome,
            tb_preinscricao.Bilhete_Identidade AS bilhete,
            tb_periodos.Designacao AS turno,
            COALESCE(tb_matriculas.Codigo,"") AS matricula,
            tb_cursos.Designacao AS curso,
            ((SELECT COUNT(tb_plano_curricular_grade.codigo_grade_curricular)
                FROM tb_plano_curricular_grade
                INNER JOIN tb_plano_curricular_curso ON tb_plano_curricular_grade.codigo_plano_curricular_curso = tb_plano_curricular_curso.codigo
                WHERE tb_plano_curricular_curso.codigo_curso = tb_matriculas.Codigo_Curso
                AND tb_plano_curricular_curso.codigo_ano_lectivo = 18
                )+ (SELECT COUNT(tb_plano_curricular_grade.codigo_grade_curricular)
                    FROM tb_plano_curricular_grade
                    INNER JOIN tb_plano_curricular_curso ON tb_plano_curricular_grade.codigo_plano_curricular_curso = tb_plano_curricular_curso.codigo
                    INNER JOIN tb_preinscricao ON tb_admissao.pre_incricao = tb_preinscricao.Codigo
                    WHERE tb_plano_curricular_curso.codigo_curso = tb_preinscricao.Curso_Candidatura
                    AND tb_plano_curricular_curso.codigo_ano_lectivo = 18
                    AND tb_preinscricao.Curso_Candidatura != tb_matriculas.Codigo_Curso
            )) AS qdtCadeirasCurso,
            ((SELECT COUNT(tb_grade_curricular_aluno.codigo_grade_curricular)
                FROM tb_grade_curricular_aluno
                INNER JOIN tb_grade_curricular ON tb_grade_curricular.Codigo = tb_grade_curricular_aluno.codigo_grade_curricular
                WHERE tb_grade_curricular_aluno.codigo_matricula = tb_matriculas.Codigo
                AND tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular = 3
                AND tb_grade_curricular.status NOT IN (0, 3)
                AND tb_matriculas.Codigo_Curso = tb_grade_curricular.Codigo_Curso
            )) AS qtdCadeirasConcluidas')
            ->when($request->searchCurso, function ($query, $value) {
                $query->where('tb_cursos.Codigo', '=', $value);
            })
            ->when($request->searchTurno, function ($query, $value) {
                $query->where('tb_periodos.Codigo', '=', $value);
            })
            // ->when($anoSelecionado, function ($query, $value) {
            //     $query->where('tb_plano_curricular_curso.codigo_ano_lectivo', '=', $value);
            // })
            ->leftjoin('tb_admissao', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->leftjoin('tb_preinscricao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
            ->leftjoin('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
            ->leftjoin('tb_provincias', 'tb_provincias.Codigo', '=', 'tb_preinscricao.codigo_provincia_residencia_permanente')
            ->leftjoin('tb_nacionalidades', 'tb_preinscricao.Codigo_Nacionalidade', '=', 'tb_nacionalidades.Codigo')
            ->leftjoin('tb_municipios', 'tb_municipios.Codigo', '=', 'tb_preinscricao.codigo_municipio')
            ->leftjoin('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_matriculas.Codigo_Curso')
            ->leftjoin('tb_faculdade', 'tb_faculdade.codigo', '=', 'tb_cursos.faculdade_id')
            ->leftjoin('tb_grade_curricular_aluno', 'tb_grade_curricular_aluno.codigo_matricula', '=', 'tb_matriculas.Codigo')
            // ->leftjoin('tb_plano_curricular_curso', 'tb_plano_curricular_grade.codigo_plano_curricular_curso', '=', 'tb_plano_curricular_curso.codigo')
            ->whereNotIn('tb_matriculas.estado_matricula', ['inactivo', 'diplomado'])
            ->where('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', 2)
            ->where('tb_grade_curricular_aluno.codigo_ano_lectivo', '=', $anoSelecionado)
            ->havingRaw('(qdtCadeirasCurso - qtdCadeirasConcluidas) <= 3')
            ->whereNotNull('tb_matriculas.Codigo')
            ->orderBy('tb_preinscricao.Nome_Completo', 'asc')
            ->paginate($request->page_size ?? 20)
            ->withQueryString();

        $data["anolectivos"] = AnoLectivo::orderBy('ordem', 'asc')->get();
        $data["turnos"] = Turno::where('status', 1)->get();
        $data["cursos"] = Curso::get();
        $data["ano_lectivo_activo"] = $ano;

        return Inertia::render('RelatoriosPagamentos/Estudantes/EstudanteFinalistas', $data);
    }
    
    
    public function estudanteFinalistasPdf(Request $request)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();

        $anoSelecionado = $request->searchAnoLectivo;

        if (!$anoSelecionado) {
            $anoSelecionado = $ano->Codigo;
        }

        if ($request->page_size == -1) {
            $request->page_size = 15;
        }

        $data["estudantes"] = DB::table('tb_matriculas')
            ->whereNotNull('tb_matriculas.Codigo')
            ->selectRaw('DISTINCT
            tb_preinscricao.Nome_Completo AS nome,
            tb_preinscricao.Bilhete_Identidade AS bilhete,
            tb_periodos.Designacao AS turno,
            COALESCE(tb_matriculas.Codigo,"") AS matricula,
            tb_cursos.Designacao AS curso,
            (
                (
                    SELECT COUNT(tb_plano_curricular_grade.codigo_grade_curricular)
                    FROM tb_plano_curricular_grade
                    INNER JOIN tb_plano_curricular_curso ON tb_plano_curricular_grade.codigo_plano_curricular_curso = tb_plano_curricular_curso.codigo
                    WHERE tb_plano_curricular_curso.codigo_curso = tb_matriculas.Codigo_Curso
                    AND tb_plano_curricular_curso.codigo_ano_lectivo = 18
                ) +
                (
                    SELECT COUNT(tb_plano_curricular_grade.codigo_grade_curricular)
                    FROM tb_plano_curricular_grade
                    INNER JOIN tb_plano_curricular_curso ON tb_plano_curricular_grade.codigo_plano_curricular_curso = tb_plano_curricular_curso.codigo
                    INNER JOIN tb_preinscricao ON tb_admissao.pre_incricao = tb_preinscricao.Codigo
                    WHERE tb_plano_curricular_curso.codigo_curso = tb_preinscricao.Curso_Candidatura
                    AND tb_plano_curricular_curso.codigo_ano_lectivo = 18
                    AND tb_preinscricao.Curso_Candidatura != tb_matriculas.Codigo_Curso
                )
            ) AS qdtCadeirasCurso,
            (
                (
                    SELECT COUNT(tb_grade_curricular_aluno.codigo_grade_curricular)
                    FROM tb_grade_curricular_aluno
                    INNER JOIN tb_grade_curricular ON tb_grade_curricular.Codigo = tb_grade_curricular_aluno.codigo_grade_curricular
                    WHERE tb_grade_curricular_aluno.codigo_matricula = tb_matriculas.Codigo
                    AND tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular = 3
                    AND tb_grade_curricular.status NOT IN (0, 3)
                    AND tb_matriculas.Codigo_Curso = tb_grade_curricular.Codigo_Curso
                )
            ) AS qtdCadeirasConcluidas')

            ->when($request->searchCurso, function ($query, $value) {
                $query->where('tb_cursos.Codigo', '=', $value);
            })
            ->when($request->searchTurno, function ($query, $value) {
                $query->where('tb_periodos.Codigo', '=', $value);
            })
            ->join('tb_admissao', 'tb_admissao.codigo', '=', 'tb_matriculas.Codigo_Aluno')
            ->join('tb_preinscricao', 'tb_preinscricao.Codigo', '=', 'tb_admissao.pre_incricao')
            ->join('tb_periodos', 'tb_preinscricao.Codigo_Turno', '=', 'tb_periodos.Codigo')
            ->join('tb_provincias', 'tb_provincias.Codigo', '=', 'tb_preinscricao.codigo_provincia_residencia_permanente')
            ->join('tb_nacionalidades', 'tb_preinscricao.Codigo_Nacionalidade', '=', 'tb_nacionalidades.Codigo')
            ->join('tb_municipios', 'tb_municipios.Codigo', '=', 'tb_preinscricao.codigo_municipio')
            ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_matriculas.Codigo_Curso')
            ->join('tb_faculdade', 'tb_faculdade.codigo', '=', 'tb_cursos.faculdade_id')
            ->join('tb_grade_curricular_aluno', 'tb_grade_curricular_aluno.codigo_matricula', '=', 'tb_matriculas.Codigo')
            ->whereNotIn('tb_matriculas.estado_matricula', ['inactivo', 'diplomado'])
            ->where('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', 2)
            ->where('tb_grade_curricular_aluno.codigo_ano_lectivo', 18)
        ->havingRaw('(qdtCadeirasCurso - qtdCadeirasConcluidas) <= 3')
        ->whereNotNull('tb_matriculas.Codigo')
        ->orderBy('tb_preinscricao.Nome_Completo', 'asc')->paginate(10);

        $data["anolectivos"] = AnoLectivo::get();
        $data["turnos"] = Turno::where('status', 1)->get();
        $data["cursos"] = Curso::get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.estudantes.estudantes-finalistas', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }

    public function estudanteFinalistasExcel(Request $request)
    {
        return Excel::download(new EstudanteFinalistasExport($request), 'estudantes-finalista.xlsx');
    }
    
    
    public function getDescricaoTiposAlunos()
    {

        $data['descricao_tipo1'] = DB::table('tb_tipo_aluno')->select('designacao', 'descricao', 'status')->where('id', 1)->where('status', 1)->first();

        $data['descricao_tipo2'] = DB::table('tb_tipo_aluno')->select('designacao', 'descricao', 'status')->where('id', 2)->where('status', 1)->first();

        $data['descricao_tipo3'] = DB::table('tb_tipo_aluno')->select('designacao', 'descricao', 'status')->where('id', 3)->where('status', 1)->first();

        $data['descricao_tipo4'] = DB::table('tb_tipo_aluno')->select('designacao', 'descricao', 'status')->where('id', 4)->where('status', 1)->first();

        return response()->json($data);
    }
    

    public function descontoBolsa(Request $request, $codigo_matricula)
    {
        $ano = AnoLectivo::where('estado', 'Activo')->first();
        
        $bolseiro = DB::table('tb_bolseiros')->join('tb_tipo_bolsas','tb_tipo_bolsas.codigo','tb_bolseiros.codigo_tipo_bolsa')
        ->where('tb_bolseiros.codigo_matricula', $codigo_matricula)
        ->where('tb_bolseiros.codigo_anoLectivo', $ano->Codigo)
        ->where('status',  0)
        ->select('*','tb_tipo_bolsas.designacao as tipo_bolsa')->first(); //Abordagem do ano actual
   
        if ($bolseiro) {
            return Response()->json($bolseiro);
        } else {
            return Response()->json('');
        }
    }
    
    
    public function finalista(Request $request,  $codigo_matricula)
    {

        $aluno = Matricula::with(['admissao.preinscricao'])->findOrFail($codigo_matricula);

        $curso_matricula = $aluno->Codigo_Curso;
        $curso_preinscricao = $aluno->admissao->preinscricao->Curso_Candidatura;

        $ano_lectivo = $request->ano_lectivo;
        $ultimo_ano_lecivo = $this->anoLectivoService->getUltimoAnoLectivoInscrito($codigo_matricula)->Codigo;

        if (!$ano_lectivo) {
            $ano_lectivo = $this->anoAtualPrincipal->index();
        }

        $id = $aluno->admissao->preinscricao->user_id;

        $collection = collect([]);


        $cadeirasRestantes = 0;

        $planoCurricular = DB::table('tb_grade_curricular')
            ->join('tb_disciplinas', 'tb_disciplinas.Codigo', '=', 'tb_grade_curricular.Codigo_Disciplina')
            ->join('tb_classes', 'tb_classes.Codigo', '=', 'tb_grade_curricular.Codigo_Classe')
            ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_grade_curricular.Codigo_Curso')
            ->join('tb_duracao', 'tb_duracao.codigo', '=', 'tb_disciplinas.duracao')
            ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
            ->where('tb_grade_curricular.Codigo_Curso', $curso_matricula)
            ->select('tb_disciplinas.Designacao as disciplina', 'tb_semestres.Designacao as semestre', 'tb_classes.Designacao as classe', 'tb_duracao.designacao as duracao_disciplina', 'tb_grade_curricular.Codigo as codigo_grade', 'tb_classes.Codigo as codigo_ano', 'tb_duracao.codigo as codigo_duracao')->whereIn('tb_grade_curricular.status', [1, 2])
            ->distinct('disciplina')->get()->count();

        $collection = collect([]);



        //dd($planoCurricular);
        $planoCurricular1 = DB::table('tb_grade_curricular')
            ->join('tb_disciplinas', 'tb_disciplinas.Codigo', '=', 'tb_grade_curricular.Codigo_Disciplina')
            ->join('tb_classes', 'tb_classes.Codigo', '=', 'tb_grade_curricular.Codigo_Classe')
            ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_grade_curricular.Codigo_Curso')
            ->join('tb_duracao', 'tb_duracao.codigo', '=', 'tb_disciplinas.duracao')
            ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
            ->where('tb_grade_curricular.Codigo_Curso', $curso_preinscricao)
            ->select('tb_disciplinas.Designacao as disciplina', 'tb_semestres.Designacao as semestre', 'tb_classes.Designacao as classe', 'tb_duracao.designacao as duracao_disciplina', 'tb_grade_curricular.Codigo as codigo_grade', 'tb_classes.Codigo as codigo_ano', 'tb_duracao.codigo as codigo_duracao')
            ->whereIn('tb_grade_curricular.status', [1, 2])
            ->distinct('disciplina')->get()->count();




        $cadeirasEliminadas1 = DB::table('tb_grade_curricular')
            ->join('tb_disciplinas', 'tb_disciplinas.Codigo', '=', 'tb_grade_curricular.Codigo_Disciplina')
            ->join('tb_classes', 'tb_classes.Codigo', '=', 'tb_grade_curricular.Codigo_Classe')
            ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_grade_curricular.Codigo_Curso')
            ->join('tb_grade_curricular_aluno', 'tb_grade_curricular_aluno.codigo_grade_curricular', '=', 'tb_grade_curricular.Codigo')
            ->join('tb_duracao', 'tb_duracao.codigo', '=', 'tb_disciplinas.duracao')
            ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
            ->select('tb_disciplinas.Designacao as disciplina', 'tb_semestres.Designacao as semestre', 'tb_classes.Designacao as classe', 'tb_duracao.designacao as duracao_disciplina', 'tb_grade_curricular.Codigo as codigo_grade', 'tb_classes.Codigo as codigo_ano', 'tb_grade_curricular.valor_inscricao as valor_cadeira', 'tb_duracao.codigo as codigo_duracao')
            ->where('tb_grade_curricular.Codigo_Curso', $curso_preinscricao)
            ->where('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', 3)
            ->whereIn('tb_grade_curricular.status', [1, 2])
            ->where('tb_grade_curricular_aluno.codigo_matricula', $aluno->matricula)
            ->distinct('disciplina')->get()->count();

        $cadeirasEliminadas = DB::table('tb_grade_curricular')
            ->join('tb_disciplinas', 'tb_disciplinas.Codigo', '=', 'tb_grade_curricular.Codigo_Disciplina')
            ->join('tb_classes', 'tb_classes.Codigo', '=', 'tb_grade_curricular.Codigo_Classe')
            ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_grade_curricular.Codigo_Curso')
            ->join('tb_grade_curricular_aluno', 'tb_grade_curricular_aluno.codigo_grade_curricular', '=', 'tb_grade_curricular.Codigo')
            ->join('tb_duracao', 'tb_duracao.codigo', '=', 'tb_disciplinas.duracao')
            ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
            ->select('tb_disciplinas.Designacao as disciplina', 'tb_semestres.Designacao as semestre', 'tb_classes.Designacao as classe', 'tb_duracao.designacao as duracao_disciplina', 'tb_grade_curricular.Codigo as codigo_grade', 'tb_classes.Codigo as codigo_ano', 'tb_grade_curricular.valor_inscricao as valor_cadeira', 'tb_duracao.codigo as codigo_duracao')
            ->where('tb_grade_curricular.Codigo_Curso', $curso_matricula)
            ->where('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', 3)
            ->whereIn('tb_grade_curricular.status', [1, 2])
            ->where('tb_grade_curricular_aluno.codigo_matricula', $aluno->matricula)
            ->distinct('disciplina')->get()->count();

        // dd($cadeirasEliminadas1, $cadeirasEliminadas);

        if ($ano_lectivo != $this->anoAtualPrincipal->index() || !$ano_lectivo) {
            $cadeirasEliminadas = DB::table('tb_grade_curricular')
                ->join('tb_disciplinas', 'tb_disciplinas.Codigo', '=', 'tb_grade_curricular.Codigo_Disciplina')
                ->join('tb_classes', 'tb_classes.Codigo', '=', 'tb_grade_curricular.Codigo_Classe')
                ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_grade_curricular.Codigo_Curso')
                ->join('tb_grade_curricular_aluno', 'tb_grade_curricular_aluno.codigo_grade_curricular', '=', 'tb_grade_curricular.Codigo')
                ->join('tb_duracao', 'tb_duracao.codigo', '=', 'tb_disciplinas.duracao')
                ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
                ->select('tb_disciplinas.Designacao as disciplina', 'tb_semestres.Designacao as semestre', 'tb_classes.Designacao as classe', 'tb_duracao.designacao as duracao_disciplina', 'tb_grade_curricular.Codigo as codigo_grade', 'tb_classes.Codigo as codigo_ano', 'tb_grade_curricular.valor_inscricao as valor_cadeira', 'tb_duracao.codigo as codigo_duracao')
                ->where('tb_grade_curricular.Codigo_Curso', $curso_matricula)
                ->where('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', 3)
                ->whereIn('tb_grade_curricular.status', [1, 2])
                ->where('tb_grade_curricular_aluno.codigo_ano_lectivo', '!=', $ultimo_ano_lecivo)
                ->where('tb_grade_curricular_aluno.codigo_matricula', $aluno->matricula)
                ->distinct('disciplina')->get()->count();
        }


        $planoCurricular1 = DB::table('tb_grade_curricular')
            ->join('tb_disciplinas', 'tb_disciplinas.Codigo', '=', 'tb_grade_curricular.Codigo_Disciplina')
            ->join('tb_classes', 'tb_classes.Codigo', '=', 'tb_grade_curricular.Codigo_Classe')
            ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_grade_curricular.Codigo_Curso')
            ->join('tb_duracao', 'tb_duracao.codigo', '=', 'tb_disciplinas.duracao')
            ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
            ->where('tb_grade_curricular.Codigo_Curso', $curso_preinscricao)
            ->select('tb_disciplinas.Designacao as disciplina', 'tb_semestres.Designacao as semestre', 'tb_classes.Designacao as classe', 'tb_duracao.designacao as duracao_disciplina', 'tb_grade_curricular.Codigo as codigo_grade', 'tb_classes.Codigo as codigo_ano', 'tb_duracao.codigo as codigo_duracao')
            ->whereIn('tb_grade_curricular.status', [1, 2])
            ->distinct('disciplina')->get()->count();

        $cadeirasEliminadaAnoCorrente = DB::table('tb_grade_curricular')
            ->join('tb_disciplinas', 'tb_disciplinas.Codigo', '=', 'tb_grade_curricular.Codigo_Disciplina')
            ->join('tb_classes', 'tb_classes.Codigo', '=', 'tb_grade_curricular.Codigo_Classe')
            ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_grade_curricular.Codigo_Curso')
            ->join('tb_grade_curricular_aluno', 'tb_grade_curricular_aluno.codigo_grade_curricular', '=', 'tb_grade_curricular.Codigo')
            ->join('tb_duracao', 'tb_duracao.codigo', '=', 'tb_disciplinas.duracao')
            ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
            ->join('tb_ano_lectivo', 'tb_ano_lectivo.codigo', 'tb_grade_curricular_aluno.codigo_ano_lectivo')
            ->select('tb_disciplinas.Designacao as disciplina', 'tb_semestres.Designacao as semestre', 'tb_classes.Designacao as classe', 'tb_duracao.designacao as duracao_disciplina', 'tb_grade_curricular.Codigo as codigo_grade', 'tb_classes.Codigo as codigo_ano', 'tb_grade_curricular.valor_inscricao as valor_cadeira', 'tb_duracao.codigo as codigo_duracao')
            ->where('tb_grade_curricular.Codigo_Curso', $curso_preinscricao)
            ->where('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', 3)
            ->whereIn('tb_grade_curricular.status', [1, 2])
            ->where('tb_grade_curricular_aluno.codigo_matricula', $aluno->matricula)
            ->where('tb_ano_lectivo.estado', 'Activo')
            ->distinct('disciplina')->get()->count();


        $cadeirasEliminadaAnoCorrente1 = DB::table('tb_grade_curricular')
            ->join('tb_disciplinas', 'tb_disciplinas.Codigo', '=', 'tb_grade_curricular.Codigo_Disciplina')
            ->join('tb_classes', 'tb_classes.Codigo', '=', 'tb_grade_curricular.Codigo_Classe')
            ->join('tb_cursos', 'tb_cursos.Codigo', '=', 'tb_grade_curricular.Codigo_Curso')
            ->join('tb_grade_curricular_aluno', 'tb_grade_curricular_aluno.codigo_grade_curricular', '=', 'tb_grade_curricular.Codigo')
            ->join('tb_duracao', 'tb_duracao.codigo', '=', 'tb_disciplinas.duracao')
            ->join('tb_semestres', 'tb_grade_curricular.Codigo_Semestre', '=', 'tb_semestres.Codigo')
            ->join('tb_ano_lectivo', 'tb_ano_lectivo.codigo', 'tb_grade_curricular_aluno.codigo_ano_lectivo')
            ->select('tb_disciplinas.Designacao as disciplina', 'tb_semestres.Designacao as semestre', 'tb_classes.Designacao as classe', 'tb_duracao.designacao as duracao_disciplina', 'tb_grade_curricular.Codigo as codigo_grade', 'tb_classes.Codigo as codigo_ano', 'tb_grade_curricular.valor_inscricao as valor_cadeira', 'tb_duracao.codigo as codigo_duracao')
            ->where('tb_grade_curricular.Codigo_Curso', $curso_matricula)
            ->where('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', 3)
            ->whereIn('tb_grade_curricular.status', [1, 2])
            ->where('tb_grade_curricular_aluno.codigo_matricula', $aluno->matricula)
            ->where('tb_ano_lectivo.estado', 'Activo')
            ->distinct('disciplina')->get()->count();

        $naoFinalista = 100;



        if ($aluno) {
            if (($curso_preinscricao == 1 || $curso_preinscricao == 5 || $curso_preinscricao == 9 || $curso_matricula == 28 || $curso_matricula == 29 || $curso_matricula == 30 || $curso_matricula == 31 || $curso_matricula == 32 || $curso_matricula == 33 || $curso_matricula == 34 || $curso_matricula == 35)) { //SE O ALUNO ESTA INSCRITO EM UM CURSO DE ESPECIALIDADE

                if (($curso_preinscricao == 1 || $curso_preinscricao == 5 || $curso_preinscricao == 9) && ($curso_preinscricao == $curso_matricula)) {

                    $cadeirasRestantes = $planoCurricular - $cadeirasEliminadas;
                    //Cadeiras Elminadas no Ano Corrente
                    $cadeirasRestantes = $cadeirasRestantes + $cadeirasEliminadaAnoCorrente;

                    //dd($cadeirasEliminadas);

                } elseif ($curso_matricula == 28 || $curso_matricula == 29 || $curso_matricula == 30 || $curso_matricula == 31 || $curso_matricula == 32 || $curso_matricula == 33 || $curso_matricula == 34 || $curso_matricula == 35) {

                    if ($curso_preinscricao != $curso_matricula) {

                        $cadeirasRestantes = ($planoCurricular1 + $planoCurricular) - ($cadeirasEliminadas + $cadeirasEliminadas1);

                        //Cadeiras Elminadas no Ano Corrente
                        $cadeirasRestantes = $cadeirasRestantes + $cadeirasEliminadaAnoCorrente + $cadeirasEliminadaAnoCorrente1;
                    }
                } else {

                    //ESTUDANTE EMIGRADO
                    $cadeirasRestantes = $planoCurricular - $cadeirasEliminadas;
                    //Cadeiras eliminadas no ANo Corrente
                    $cadeirasRestantes = $cadeirasRestantes + $cadeirasEliminadaAnoCorrente;
                }
            } else { // SE O ALUNO NAO ESTA INSCRITO EM UM CURSO DE ESPECIALIDADE



                $cadeirasRestantes = $planoCurricular - $cadeirasEliminadas;
                //Cadeiras Elminadas no Ano Corrente

                $cadeirasRestantes = $cadeirasRestantes + $cadeirasEliminadaAnoCorrente;
            }
        }

        return Response()->json($cadeirasRestantes);
    }
    
    
    public function getPrecoPropina($polo,  $curso)
    {
        $preco_curso = TipoServico::where('polo_id', $polo)
            ->where('Descricao', 'Propina ' . $curso)
            ->first();       

        return Response()->json($preco_curso);
    }
       
    
    

}
