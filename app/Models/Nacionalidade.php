<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nacionalidade extends Model
{
    use HasFactory;
    
    protected $table = "tb_nacionalidades";
    
    public $timestamps = false;
    
    protected $primaryKey = 'Codigo';

    protected $fillable = [
        'Designacao',
    ];
  
}
