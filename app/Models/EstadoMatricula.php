<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoMatricula extends Model
{
    use HasFactory;
    
    protected $table = "tb_estado_matricula";
    
    public $timestamps = false;
    
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'designacao',
        'descricao',
        'sigla',
        'obs',
        'parcela',
        'data_limite',
        'exige_matricula',
    ];

}
