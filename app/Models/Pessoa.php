<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;
    
    protected $table = "tb_pessoa";
    
    public $timestamps = false;
    
    protected $primaryKey = 'pk_pessoa';

    protected $fillable = [
        'nome_completo',
        'nome_do_pai',
        'nome_da_mae',
        'data_de_nascimento',
        'num_doc_identificacao',
        'fk_tipo_documento_identificacao',
        'data_de_emissao_documento',
        'data_de_expiracao_documento',
        'fk_genero',
        'fk_nacionalidade',
        'endereco',
        'fk_naturalidade',
        'fk_estado_civil',
        'telefone1',
        'telefone2',
        'email',
    ];
  
}
