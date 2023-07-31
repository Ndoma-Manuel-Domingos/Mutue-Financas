<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MotivoRejeicao extends Model
{
    use HasFactory;
    
    protected $table = "tb_motivo_rejeicao";
    
    protected $primaryKey = "id";
    
    public $timestamps = false;

    protected $fillable = [
        'designacao',
    ];
}
