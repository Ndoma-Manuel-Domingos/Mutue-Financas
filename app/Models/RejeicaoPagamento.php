<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RejeicaoPagamento extends Model
{
    use HasFactory;
    
    protected $table = "tb_rejeicao_pagamento";
    
    protected $primaryKey = "id";
    
    public $timestamps = false;

    protected $fillable = [
        'codigo_pagamento',
        'data_rejeicao',
        'user_id',
        'status',
        'data_validacao',
        'obs', 
        'canal', 
        'motivo_id', 
    ];
}
