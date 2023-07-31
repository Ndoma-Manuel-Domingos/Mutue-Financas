<?php

namespace App\Http\Controllers;

use App\Models\AnoLectivo;
use App\Models\GradeCurricularAluno;

Trait TraitHelpers{

    /** 
     * Ano lectivo  activo
     * */
    public function anoLectivoActivo()
    {
        $ano_lectivo = AnoLectivo::where('estado', 'Activo')->first();
        if(!$ano_lectivo){
            return false;
        }
        return $ano_lectivo->Codigo;
    }
    
    public function anoLectivoActivoMestrado()
    {
        $ano_lectivo = AnoLectivo::where('Codigo', 19)->first();
        if(!$ano_lectivo){
            return false;
        }
        return $ano_lectivo->Codigo;
    }
    
    public function anoLectivoActivoDoutorado()
    {
        $ano_lectivo = AnoLectivo::where('Codigo', 20)->first();
        if(!$ano_lectivo){
            return false;
        }
        return $ano_lectivo->Codigo;
    }



    public function anoLectivoEstudante($codigo_matricula)
    {
        $anos = GradeCurricularAluno::whereIn('codigo_matricula', [$codigo_matricula])->distinct()->orderBy('codigo_ano_lectivo', "desc")->get(['codigo_ano_lectivo']);
        
        return $anos;
    }


    public function anoLectivoActivoAnterior()
    {
        $ano_lectivo = AnoLectivo::where('Codigo', $this->anoLectivoActivo())->first();

        if(!$ano_lectivo){
            return false;
        }
        $ordem = $ano_lectivo->ordem - 1;
        $ano_lectivo_ordem = AnoLectivo::where('ordem', $ordem)->first();

        if(!$ano_lectivo_ordem){
            return false;
        }
        return $ano_lectivo_ordem->Codigo;
    }

}