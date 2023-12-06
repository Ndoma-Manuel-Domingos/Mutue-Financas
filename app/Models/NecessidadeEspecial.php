<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NecessidadeEspecial extends Model
{
    use HasFactory;
    
    protected $table = "necessidade_especiais";
    
    public $timestamps = false;
    
    protected $primaryKey = 'id';

    protected $fillable = [
        'designacao',
    ];
  
}
