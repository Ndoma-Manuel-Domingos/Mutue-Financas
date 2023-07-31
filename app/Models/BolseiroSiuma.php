<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BolseiroSiuma extends Model
{
    use HasFactory;
    
    protected $table = "tb_bolseiro_siiuma";
    
    public $timestamps = false;
    
    protected $primaryKey = 'codigo';

    protected $fillable = [
        'codigo_matricula',
        'nome',
        'instituicao',
        'ano',
        'desconto',
    ];

    public function matricula()
    {
        return $this->belongsTo(Matricula::class, 'codigo_matricula', 'Codigo');
    }
}
