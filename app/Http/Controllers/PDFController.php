<?php

namespace App\Http\Controllers;

use App\Exports\InstituicaoRenunciaExport;
use App\Exports\InstituicaoSemRenunciaExport;
use App\Exports\TipoBolsaExport;
use App\Models\InstituicaoRenuncia;
use App\Models\TipoBolsa;
use App\Models\TipoInstituicao;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class PDFController extends Controller
{
    //
    // isntituições com renuncia pdf  
    public function pdf(Request $request)
    {
        $data['instituicao'] = InstituicaoRenuncia::when($request->tipo_instituicao, function($query, $value){
            $query->where('tipo_instituicao', $value);
        })->with('tipo')
        ->get();

        $data['tipo'] = TipoInstituicao::find($request->tipo_instituicao); 

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.Instituicao-renuncia.instituicoes', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }
    
    // isntituições com renuncia excel
    public function excel(Request $request)
    {
        return Excel::download(new InstituicaoRenunciaExport($request->tipo_instituicao), 'instituicoes-com-renuncias.xlsx');
    }
    
    
    public function pdfSemRenuncia(Request $request)
    {
             
        $data['instituicao'] = InstituicaoRenuncia::when($request->tipo_instituicao, function($query, $value){
            $query->where('tipo_instituicao', $value);
        })->with('tipo')->get();
        
        $data['tipo'] = TipoInstituicao::find($request->tipo_instituicao); 

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.Instituicao-renuncia.instituicoes', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }
    
    // isntituições com renuncia excel
    public function excelSemRenuncia(Request $request)
    {
        return Excel::download(new InstituicaoSemRenunciaExport($request->tipo_instituicao), 'instituicoes-sem-renuncias.xlsx');
    }
        
    
    public function pdfTipoBolsa()
    {
        $data['tiposBolsas'] = TipoBolsa::get();

        $pdf = \App::make('dompdf.wrapper');
        $pdf->loadView('pdf.tipos-bolsas', $data);
        $pdf->getDOMPdf()->set_option('isPhpEnabled', true);
        return $pdf->stream();
    }
    
    // isntituições com renuncia excel
    public function excelTipoBolsa()
    {
        return Excel::download(new TipoBolsaExport, 'tipos-de-bolsas.xlsx');
    }   

}
