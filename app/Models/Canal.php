<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canal extends Model
{
    use HasFactory;
    
    protected $table = "tb_canal_comunicacao";
    
    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'designacao',
        'status',
        'descricao',
    ];
}
