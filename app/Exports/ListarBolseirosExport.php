<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\Bolseiro;
use App\Models\Instituicacao;
use App\Models\InstituicaoRenuncia;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class ListarBolseirosExport implements FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping,
    WithEvents,
    WithDrawings,
    WithCustomStartCell
{
    use TraitHelpers;

    public  $anolectivo, $curso;

    public function __construct($request)
    {
        $this->anolectivo=$request->anolectivo;


    }


    public function headings():array
    {
        return[
            'Nome',
            'Curso',
            'Tipo de Bolsa',
            'Tipo de Instituição',
            'Desconta',
            'Periodo',
            'Estado',
        ];
    }

    public function map($item):array
    {

        return[
            $item->nome,
            $item->curso,
            $item->tipoBolsa,
            // $item->tipo->designacao,
            $item->desconto,
            $item->semestre,
            $item->status,
        ];
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection($AnoLectivo = null, $Curso = null, $Instituicao = null, $TipoBolsa = null, $Estado = null, $Desconto = null, $Semestre = null)
    {

        $data['bolseiros'] = Bolseiro::when($AnoLectivo, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_anoLectivo', $value);
        })->when($Curso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', $value);
        })
            ->when($Instituicao, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_Instituicao', $value);
            })
            ->when($TipoBolsa, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
            })
            ->when($Desconto, function ($query, $value) {
                $query->where('tb_bolseiros.desconto', $value);
            })
            ->when($Estado, function ($query, $value) {
                $query->where('tb_bolseiros.status', $value);
            })
            ->when($Semestre, function ($query, $value) {
                $query->where('tb_bolseiros.semestre', $value);
            })
            ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')

            ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_bolseiros.codigo_matricula', 'tb_bolseiros.codigo', 'tb_tipo_bolsas.designacao AS tipobolsa', 'tb_bolseiros.desconto', 'tb_bolseiros.status', 'tb_bolseiros.semestre', 'tb_preinscricao.Nome_Completo As nome')
            ->get();


        // return InstituicaoRenuncia::with('tipo')->where('tipo_instituicao',  $this->instituicao)->get();
    }


    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event){
                $event->sheet->getStyle('A6:G6')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'borders' => [
                        'outline' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THICK,
                            'color' => ['rgb' => '000000'],
                        ],
                    ]
                ]);

            }
        ];
    }

    public function startCell(): String
    {
        return "A6";
    }

    public function drawings()
    {
        $drawing = new Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('LISTAGEM DE BOLSEIROS');
        $drawing->setPath(public_path('/images/logotipo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }


}
