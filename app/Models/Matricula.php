<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matricula extends Model
{
    use HasFactory;

    protected $table = "tb_matriculas";
    
    protected $primaryKey = 'Codigo';
    
    public $timestamps = false;

    protected $fillable = [
        'Codigo_Aluno',
        'Data_Matricula',
        'Codigo_Curso',
        'CodigoPagamento',
        'numeroAluno',
        'estado_matricula',
        'canal'
    ];
    
    public function confirmacao()
    {
        return $this->belongsTo(Confirmacao::class, 'Codigo', 'Codigo_Matricula');
    }

    public function grade_curricular_aluno()
    {
        return $this->belongsTo(GradeCurricularAluno::class, 'Codigo', 'codigo_matricula');
    }

    public function admissao()
    {
        return $this->belongsTo(AlunoAdmissao::class, 'Codigo_Aluno', 'codigo');
    }

    public function curso()
    {
        return $this->belongsTo(Curso::class, 'Codigo_Curso', 'Codigo');
    }
    
    public function propina_estudante($matricula)
    {
        return "matricula";
    } 
    
    

}
