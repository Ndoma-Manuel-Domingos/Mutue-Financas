<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Confirmacao extends Model
{
    use HasFactory;
    
    protected $table = "tb_confirmacoes";
    
    public $timestamps = false;
    
    protected $primaryKey = 'Codigo';

    protected $fillable = [
        'Codigo_Matricula',
        'Data_Confirmacao',
        'Codigo_Turma',
        'Codigo_Ano_lectivo',
        'GradeCurricularDisciplina',
        'Estado',
        'Classe',
        'canal',
        'Cadeirante',
        'principal',
        'ref_horario',
    ];
    
    public function ano_lectivo()
    {
        return $this->belongsTo(AnoLectivo::class, 'Codigo_Ano_lectivo', 'Codigo');
    }

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'codigo_matricula', 'Codigo');
    }
}
