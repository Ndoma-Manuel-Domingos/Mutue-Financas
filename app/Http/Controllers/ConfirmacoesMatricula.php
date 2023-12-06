<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\Classe;
use App\Models\Curso;
use App\Models\EstadoMatricula;
use App\Models\Faculdade;
use App\Models\GradeCurricularAluno;
use App\Models\GrauAcademico;
use App\Models\Matricula;
use App\Models\Nacionalidade;
use App\Models\NecessidadeEspecial;
use App\Models\Sexo;
use App\Models\Turno;
use Illuminate\Http\Request;
use Inertia\Inertia;

use Illuminate\Support\Facades\DB;


class ConfirmacoesMatricula extends Controller
{
    //
    
    public function listagemGeralEstudantes(Request $request)
    {
        $data['ano_lectivos'] =  AnoLectivo::orderBy('ordem', 'desc')->get();
        $data['faculdades'] =  Faculdade::where('estado', 1)->get();
        $data['grau_academicos'] = GrauAcademico::where('status', 1)->get();
        
        $data['cursos'] = Curso::when($request->faculdade, function($query, $value){
            $query->where('faculdade_id', $value);
        })->when($request->grau_academico, function($query, $value){
            $query->where('tipo_candidatura', $value);
        })
        ->where('status', 1)
        ->get();
        
        $data['estados'] = EstadoMatricula::get();
        $data['turnos'] = Turno::where('status', 1)->get();
        $data['nacionalidades'] = Nacionalidade::get();
        $data['necessidades_especiais'] = NecessidadeEspecial::get();
        $data['sexos'] = Sexo::get();
        $data['classes'] = Classe::get();
        
        if($request->ano_lectivo){
            $request->ano_lectivo = $request->ano_lectivo;
        }else{
            $request->ano_lectivo = 21;
        }
        
        $data['estudantes'] = Matricula::leftJoin('tb_confirmacoes', 'tb_matriculas.Codigo' ,'=','tb_confirmacoes.Codigo_Matricula')
        ->leftJoin('tb_cursos', 'tb_matriculas.Codigo_Curso' ,'=','tb_cursos.Codigo')
        ->leftJoin('tb_faculdade', 'tb_cursos.faculdade_id' ,'=','tb_faculdade.codigo')
        ->leftJoin('tb_admissao', 'tb_matriculas.Codigo_Aluno' ,'=','tb_admissao.codigo')
        ->leftJoin('tb_preinscricao', 'tb_admissao.pre_incricao' ,'=','tb_preinscricao.Codigo')
        ->leftJoin('tb_nacionalidades', 'tb_preinscricao.Codigo_Nacionalidade' ,'=','tb_nacionalidades.Codigo')
        ->leftJoin('tb_ano_lectivo', 'tb_confirmacoes.Codigo_Ano_lectivo' ,'=','tb_ano_lectivo.Codigo')
        ->leftJoin('necessidade_especiais', 'tb_preinscricao.necessidade_especial_id' ,'=','necessidade_especiais.id')
        ->leftJoin('tb_periodos', 'tb_preinscricao.Codigo_Turno' ,'=','tb_periodos.Codigo')
        ->leftJoin('tb_grade_curricular_aluno', 'tb_matriculas.Codigo' ,'=','tb_grade_curricular_aluno.codigo_matricula')
        ->leftJoin('tb_grade_curricular', 'tb_grade_curricular_aluno.codigo_grade_curricular' ,'=','tb_grade_curricular.Codigo')
        ->leftJoin('tb_classes', 'tb_grade_curricular.Codigo_Classe' ,'=','tb_classes.Codigo')
        ->when($request->nacionalidade,  function($query, $value){
            $query->where('tb_nacionalidades.Codigo', $value);
        })
        ->when($request->ano_lectivo,  function($query, $value){
            $query->where('tb_ano_lectivo.Codigo', $value);
        })
        ->when($request->sexo,  function($query, $value){
            $query->where('tb_preinscricao.Sexo', $value);
        })
        ->when($request->curso,  function($query, $value){
            $query->where('tb_cursos.Codigo', $value);
        })
        ->when($request->faculdade,  function($query, $value){
            $query->where('tb_cursos.faculdade_id', $value);
        })
        ->when($request->turno,  function($query, $value){
            $query->where('tb_periodos.Codigo', $value);
        })
        ->when($request->necessidades_especial,  function($query, $value){
            $query->where('necessidade_especiais.id', $value);
        })
        ->when($request->grau_academico,  function($query, $value){
            $query->where('tb_cursos.tipo_candidatura', $value);
        })
        ->when($request->classe,  function($query, $value){
            $query->where('tb_grade_curricular.Codigo_Classe', $value);
        })
        ->select(
            'tb_matriculas.Codigo AS codigo',
            'tb_preinscricao.Nome_Completo AS nome',
            'tb_preinscricao.Sexo AS sexo',
            'tb_faculdade.designacao AS faculdade',
            'tb_cursos.Designacao AS curso',
            'tb_classes.Designacao AS classe',
            'tb_cursos.grau AS cursoGrau',
            'tb_ano_lectivo.Designacao AS anolectivo',
            'tb_nacionalidades.Designacao AS nacionalidade',
            'necessidade_especiais.designacao AS nessecidade',
            'tb_periodos.Designacao AS periodo',
            'tb_grade_curricular.Codigo_Classe AS anoCurricular'
        )
        ->distinct('tb_matriculas.Codigo')
        ->orderBy('tb_preinscricao.Nome_Completo', 'asc')
        ->paginate(20)
        ->withQueryString();
        
        return Inertia::render('ConfirmacoesMatricula/ListagemGeralEstudantes', $data);
    }    
    
    public function listarEstudantesPorEstado(Request $request)
    {
        $data['ano_lectivos'] =  AnoLectivo::orderBy('ordem', 'desc')->get();
        $data['cursos'] = Curso::where('status', 1)->get();
        $data['estados'] = EstadoMatricula::get();
        $data['turnos'] = Turno::where('status', 1)->get();
            
        $data['estudantes'] = Matricula::leftJoin('tb_confirmacoes', 'tb_matriculas.Codigo' ,'=','tb_confirmacoes.Codigo_Matricula')
        ->leftJoin('tb_cursos', 'tb_matriculas.Codigo_Curso' ,'=','tb_cursos.Codigo')
        ->leftJoin('tb_admissao', 'tb_matriculas.Codigo_Aluno' ,'=','tb_admissao.codigo')
        ->leftJoin('tb_preinscricao', 'tb_admissao.pre_incricao' ,'=','tb_preinscricao.Codigo')
        ->leftJoin('tb_ano_lectivo', 'tb_confirmacoes.Codigo_Ano_lectivo' ,'=','tb_ano_lectivo.Codigo')
        ->leftJoin('tb_periodos', 'tb_preinscricao.Codigo_Turno' ,'=','tb_periodos.Codigo')
        ->when($request->ano_lectivo,  function($query, $value){
           $query->where('tb_ano_lectivo.Codigo', $value);
        })
        ->when($request->curso,  function($query, $value){
            $query->where('tb_cursos.Codigo', $value);
        })
        ->when($request->turno,  function($query, $value){
            $query->where('tb_periodos.Codigo', $value);
        })
        ->when($request->estado,  function($query, $value){
            $query->where('tb_matriculas.estado_matricula', $value);
        })
        ->select(
            'tb_matriculas.Codigo AS codigo',
            'tb_matriculas.estado_matricula AS estado',
            'tb_preinscricao.Nome_Completo AS nome',
            'tb_preinscricao.Email AS email',
            'tb_preinscricao.Contactos_Telefonicos AS contacto',
            'tb_cursos.Designacao AS curso',
            'tb_periodos.Designacao AS periodo'
        )
        ->distinct('tb_matriculas.Codigo')
        ->paginate(20)
        ->withQueryString();
        
        $data['resultado'] = Matricula::leftJoin('tb_confirmacoes', 'tb_matriculas.Codigo' ,'=','tb_confirmacoes.Codigo_Matricula')
        ->leftJoin('tb_cursos', 'tb_matriculas.Codigo_Curso' ,'=','tb_cursos.Codigo')
        ->leftJoin('tb_admissao', 'tb_matriculas.Codigo_Aluno' ,'=','tb_admissao.codigo')
        ->leftJoin('tb_preinscricao', 'tb_admissao.pre_incricao' ,'=','tb_preinscricao.Codigo')
        ->leftJoin('tb_ano_lectivo', 'tb_confirmacoes.Codigo_Ano_lectivo' ,'=','tb_ano_lectivo.Codigo')
        ->leftJoin('tb_periodos', 'tb_preinscricao.Codigo_Turno' ,'=','tb_periodos.Codigo')
        ->select(
            DB::raw('COUNT(*) AS total'),
            DB::raw('SUM(CASE WHEN tb_matriculas.estado_matricula = "activo" THEN 1 ELSE 0 END) AS total_activo'),
            DB::raw('SUM(CASE WHEN tb_matriculas.estado_matricula = "diplomado" THEN 1 ELSE 0 END) AS total_diplomado'),
            DB::raw('SUM(CASE WHEN tb_matriculas.estado_matricula = "inactivo" THEN 1 ELSE 0 END) AS total_inactivo'),
            DB::raw('SUM(CASE WHEN tb_matriculas.estado_matricula = "pendente" THEN 1 ELSE 0 END) AS total_pendente'),
        )
        ->when($request->ano_lectivo,  function($query, $value){
            $query->where('tb_ano_lectivo.Codigo', $value);
         })
         ->when($request->curso,  function($query, $value){
             $query->where('tb_cursos.Codigo', $value);
         })
         ->when($request->turno,  function($query, $value){
             $query->where('tb_periodos.Codigo', $value);
         })
         ->when($request->estado,  function($query, $value){
             $query->where('tb_matriculas.estado_matricula', $value);
         })
        ->first();
        
        return Inertia::render('ConfirmacoesMatricula/listarEstudantesPorEstado', $data);
    }
    
    public function listarEstudantesMatriculados(Request $request)
    {  
        $data['ano_lectivos'] =  AnoLectivo::orderBy('ordem', 'desc')->get();
        $data['faculdades'] =  Faculdade::where('estado', 1)->get();
        $data['grau_academicos'] = GrauAcademico::where('status', 1)->get();
        
        $data['cursos'] = Curso::when($request->faculdade, function($query, $value){
            $query->where('faculdade_id', $value);
        })->when($request->grau_academico, function($query, $value){
            $query->where('tipo_candidatura', $value);
        })
        ->where('status', 1)
        ->get();
        
        if($request->ano_lectivo){
            $request->ano_lectivo = $request->ano_lectivo;
        }else {
            $request->ano_lectivo = 21;
        }
        
        $data['estados'] = EstadoMatricula::get();
        $data['turnos'] = Turno::where('status', 1)->get();
        $data['nacionalidades'] = Nacionalidade::get();
        $data['necessidades_especiais'] = NecessidadeEspecial::get();
        $data['sexos'] = Sexo::get();
        $data['classes'] = Classe::get();
        $data['candidaturas'] = GrauAcademico::get();
        
        $data['estudantes'] = GradeCurricularAluno::select('tb_grade_curricular_aluno.codigo_matricula','tb_preinscricao.Nome_Completo as nome', 'tb_classes.Designacao AS classe', 'tb_preinscricao.Contactos_Telefonicos as telefone', 'tb_preinscricao.Sexo as genero', 'tb_ano_lectivo.Designacao as anoLectivo', 'tb_cursos.Designacao as curso')
        ->leftJoin('tb_matriculas', 'tb_grade_curricular_aluno.codigo_matricula' ,'=','tb_matriculas.Codigo')
        ->leftJoin('tb_cursos', 'tb_matriculas.Codigo_Curso' ,'=','tb_cursos.Codigo')
        ->leftJoin('tb_admissao', 'tb_matriculas.Codigo_Aluno' ,'=','tb_admissao.codigo')
        ->leftJoin('tb_preinscricao', 'tb_admissao.pre_incricao' ,'=','tb_preinscricao.Codigo')
        ->leftJoin('tb_confirmacoes', 'tb_matriculas.Codigo' ,'=','tb_confirmacoes.Codigo_Matricula')
        ->leftJoin('tb_classes', 'tb_confirmacoes.Classe' ,'=','tb_classes.Codigo')
        // ->leftJoin('tb_periodos', 'tb_preinscricao.Codigo_Turno' ,'=','tb_periodos.Codigo')
        ->leftJoin('tb_ano_lectivo', 'tb_confirmacoes.Codigo_Ano_lectivo' ,'=','tb_ano_lectivo.Codigo')
        ->when($request->ano_lectivo, function($query, $value){
            $query->where('tb_grade_curricular_aluno.codigo_ano_lectivo', $value);
        })
        ->when($request->curso,  function($query, $value){
            $query->where('tb_cursos.Codigo', $value);
        })
        // ->when($request->classe,  function($query, $value){
        //     $query->where('tb_confirmacoes.Classe', $value);
        // })
        ->when($request->candidatura,  function($query, $value){
            $query->where('tb_cursos.tipo_candidatura', $value);
        })
        ->whereIn('Codigo_Status_Grade_Curricular', [2])
        // ->whereIn('tb_matriculas.estado_matricula', ['activo'])
        ->distinct('tb_grade_curricular_aluno.codigo_matricula')
        ->orderBy('tb_preinscricao.Nome_Completo', 'ASC')
        ->paginate(20)
        ->withQueryString();
        
        
        // $data['estudantes'] = GradeCurricularAluno::with(['matricula.admissao.preinscricao'])->where('tb_grade_curricular_aluno.codigo_ano_lectivo', $request->ano_lectivo)
        // leftJoin('tb_matriculas', 'tb_grade_curricular_aluno.codigo_matricula' ,'=','tb_matriculas.Codigo')
        // ->leftJoin('tb_cursos', 'tb_matriculas.Codigo_Curso' ,'=','tb_cursos.Codigo')
        // ->leftJoin('tb_admissao', 'tb_matriculas.Codigo_Aluno' ,'=','tb_admissao.codigo')
        // ->leftJoin('tb_preinscricao', 'tb_admissao.pre_incricao' ,'=','tb_preinscricao.Codigo')
        // ->leftJoin('tb_ano_lectivo', 'tb_grade_curricular_aluno.codigo_ano_lectivo' ,'=','tb_ano_lectivo.Codigo')
        // ->leftJoin('tb_confirmacoes', 'tb_matriculas.Codigo' ,'=','tb_confirmacoes.Codigo_Matricula')
        // // 
        // ->whereIn('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', ['2'])
        // ->when($request->ano_lectivo, function($query, $value){
        //     $query->where('tb_grade_curricular_aluno.codigo_ano_lectivo', $value);
        // })
        // ->when($request->curso,  function($query, $value){
        //     $query->where('tb_cursos.Codigo', $value);
        // })
        // ->when($request->turno,  function($query, $value){
        //     $query->where('tb_preinscricao.Codigo_Turno', $value);
        // })
        // ->distinct('tb_grade_curricular_aluno.codigo_matricula')
        // ->orderBy('tb_preinscricao.Nome_Completo', 'asc')
        // ->count();
        // // ->paginate(20)
        // // ->withQueryString();
        
        // dd($data['estudantes']);
        
        // "SELECT DISTINCT g.codigo_matricula, tp.Nome_Completo as nome, \n"
        // + "tp.Contactos_Telefonicos as telefone, tp.Sexo as genero, tal.Designacao as anoLectivo, tc.Designacao as curso, tp2.Designacao AS periudo\n"
        // + "FROM tb_grade_curricular_aluno g\n"
        // + "inner join tb_matriculas tm on g.codigo_matricula = tm.Codigo\n"
        // + "inner join tb_cursos tc on tc.Codigo  = tm.Codigo_Curso \n"
        // + "inner join tb_admissao ta on ta.codigo  = tm.Codigo_Aluno\n"
        // + "inner join tb_preinscricao tp  on ta.pre_incricao  = tp.Codigo \n"
        // + "INNER JOIN tb_periodos tp2 ON tp2.Codigo = tp.Codigo_Turno \n"
        // + "inner join tb_ano_lectivo tal  on tal.Codigo = tp.anoLectivo \n"
        // + "where g.Codigo_Status_Grade_Curricular in(2) and  g.codigo_ano_lectivo = ? \n"
        // + "AND (tc.Codigo = ? OR ? = 404) \n"
        // + "AND tp.anoLectivo != ? \n"
        // + "AND (tp2.Codigo = ? OR ? = 404) \n"
        // + "AND tc.tipo_candidatura = ? \n"
        // + "GROUP by g.codigo_matricula \n"
        // + "ORDER BY tp.Nome_Completo;"
        
        
        // $data['estudantes'] = GradeCurricularAluno::leftJoin('tb_matriculas', 'tb_grade_curricular_aluno.codigo_matricula' ,'=','tb_matriculas.Codigo')
        // ->distinct('tb_grade_curricular_aluno.codigo_matricula')
        // ->leftJoin('tb_cursos', 'tb_matriculas.Codigo_Curso' ,'=','tb_cursos.Codigo')
        // ->leftJoin('tb_confirmacoes', 'tb_matriculas.Codigo' ,'=','tb_confirmacoes.Codigo_Matricula')
        // ->leftJoin('tb_faculdade', 'tb_cursos.faculdade_id' ,'=','tb_faculdade.codigo')
        // ->leftJoin('tb_admissao', 'tb_matriculas.Codigo_Aluno' ,'=','tb_admissao.codigo')
        // ->leftJoin('tb_preinscricao', 'tb_admissao.pre_incricao' ,'=','tb_preinscricao.Codigo')
        // ->leftJoin('tb_ano_lectivo', 'tb_preinscricao.anoLectivo' ,'=','tb_ano_lectivo.Codigo')
        // // ->leftJoin('tb_grade_curricular', 'tb_grade_curricular_aluno.codigo_grade_curricular' ,'=','tb_grade_curricular.Codigo')
        // ->leftJoin('tb_classes', 'tb_confirmacoes.Classe' ,'=','tb_classes.Codigo')
        // ->whereIn('tb_grade_curricular_aluno.Codigo_Status_Grade_Curricular', ['2'])
        // ->when($request->ano_lectivo, function($query, $value){
        //     $query->where('tb_grade_curricular_aluno.codigo_ano_lectivo', $value);
        // })   
        // ->when($request->classe,  function($query, $value){
        //     $query->where('tb_confirmacoes.Classe', $value);
        // })
        // ->when($request->curso,  function($query, $value){
        //     $query->where('tb_cursos.Codigo', $value);
        // })
        // ->when($request->turno,  function($query, $value){
        //     $query->where('tb_preinscricao.Codigo_Turno', $value);
        // })
        // ->select(
        //     'tb_matriculas.Codigo AS codigo',
        //     'tb_preinscricao.Nome_Completo AS nome',
        //     'tb_preinscricao.Sexo AS sexo',
        //     'tb_classes.Designacao AS classe',
        //     'tb_preinscricao.Contactos_Telefonicos AS telefone',
        //     'tb_ano_lectivo.Designacao AS anolectivo',
        //     'tb_cursos.Designacao AS curso'
        // )
        // ->orderBy('tb_preinscricao.Nome_Completo', 'asc')
        // ->paginate(20)
        // ->withQueryString();

        return Inertia::render('ConfirmacoesMatricula/ListarEstudantesMatriculados', $data);
    }
    
    public function listarEstudantesInscritoEmUnidadeCurricular(Request $request)
    {
        $data = [];
        
        return Inertia::render('ConfirmacoesMatricula/ListarEstudantesInscritoEmUnidadeCurricular', $data);
    }
    
    public function listarEstudantesPersonalizados(Request $request)
    {
        $data = [];
        
        return Inertia::render('ConfirmacoesMatricula/ListarEstudantesPersonalizados', $data);
    }
    
}
