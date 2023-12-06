<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GradeCurricular extends Model
{
    use HasFactory;

    protected $table = "tb_grade_curricular";

    protected $primaryKey = 'Codigo';
    
    public $timestamps = false;

    protected $fillable = [
        'Codigo_Curso',
        'Codigo_Disciplina',
        'Codigo_Classe',
        'Codigo_Semestre',
        'HorasTotais',
        'HorasTeoricas',
        'HorasTeoricosPraticas',
        'HorasPraticas',
        'data_registo',
        'data_ultimaa_atualizacao',
        'user',
        'HorasEstagio',
        'HorasSeminario',
        'HorasRelatorio',
        'Num_max_faltas',
        'valor_inscricao',
        'canal',
        'status',
        'peso_primeira_freq',
        'nota_min_primeira_freq',
        'peso_segunda_freq',
        'nota_min_segunda_freq',
        'peso_pratica',
        'nota_min_pratica',
        'formula_defida_por',
        'utilizador',
    ];
    
    
    public function grade_curricular()
    {
        return $this->belongsTo(GradeCurricular::class, 'Codigo', 'codigo_matricula');
    }
    
    public function classe()
    {
        return $this->belongsTo(Classe::class, 'Codigo_Classe', 'Codigo');
    }
}
