<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\Bolseiro;
use App\Models\Curso;
use App\Models\Simestre;
use App\Models\TipoBolsa;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Cell;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithCustomValueBinder;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\DefaultValueBinder;
use PhpOffice\PhpSpreadsheet\Cell\Cell as CellCell;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ListarBolseirosExport extends DefaultValueBinder implements FromCollection, WithMapping, WithTitle, WithHeadings, WithDrawings, WithStyles, WithCustomStartCell, WithCustomValueBinder, ShouldAutoSize
{
    use TraitHelpers, Exportable;

    public $AnoLectivo, $Curso, $Instituicao, $TipoBolsa, $Desconto, $Estado, $Semestre, $titulo;

    public function __construct($request)
    {
        $this->AnoLectivo = $request->AnoLectivo;
        $this->Curso = $request->Curso;
        $this->Instituicao = $request->Instituicao;
        $this->TipoBolsa = $request->TipoBolsa;
        $this->Desconto = $request->Desconto;
        $this->Estado = $request->Estado;
        $this->Semestre = $request->Semestre;
        $this->titulo = "LISTAGEM ESTUDANTES BOLSEIROS";
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
    public function collection()
    {

        $data['bolseiros'] = Bolseiro::when($this->AnoLectivo, function ($query, $value) {
            $query->where('tb_bolseiros.codigo_anoLectivo', $value);
        })->when($this->Curso, function ($query, $value) {
            $query->where('tb_cursos.Codigo', $value);
        })
            ->when($this->Instituicao, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_Instituicao', $value);
            })
            ->when($this->TipoBolsa, function ($query, $value) {
                $query->where('tb_bolseiros.codigo_tipo_bolsa', $value);
            })
            ->when($this->Desconto, function ($query, $value) {
                $query->where('tb_bolseiros.desconto', $value);
            })
            ->when($this->Estado, function ($query, $value) {
                $query->where('tb_bolseiros.status', $value);
            })
            ->when($this->Semestre, function ($query, $value) {
                $query->where('tb_bolseiros.semestre', $value);
            })
            ->join('tb_matriculas', 'tb_bolseiros.codigo_matricula', '=', 'tb_matriculas.Codigo')
            ->join('tb_cursos', 'tb_matriculas.Codigo_Curso', '=', 'tb_cursos.Codigo')
            ->join('tb_admissao', 'tb_matriculas.Codigo_Aluno', '=', 'tb_admissao.codigo')
            ->join('tb_preinscricao', 'tb_admissao.pre_incricao', '=', 'tb_preinscricao.Codigo')
            ->join('tb_tipo_bolsas', 'tb_bolseiros.codigo_tipo_bolsa', '=', 'tb_tipo_bolsas.codigo')

            ->select('tb_matriculas.Codigo AS matricula', 'tb_cursos.Designacao AS curso', 'tb_bolseiros.codigo_matricula', 'tb_bolseiros.codigo', 'tb_tipo_bolsas.designacao AS tipobolsa', 'tb_bolseiros.desconto', 'tb_bolseiros.status', 'tb_bolseiros.semestre', 'tb_preinscricao.Nome_Completo As nome')
            ->get();

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
    public function startCell(): string
    {
        return 'A10';
    }
    
    
    public function styles(Worksheet $sheet)
    {
        
        $ano = AnoLectivo::find($this->AnoLectivo);
        $semestre = Simestre::find($this->Semestre);
        $curso = Curso::find($this->Curso);
        $bolsa = TipoBolsa::find($this->TipoBolsa);
    
        $sheet->setCellValue('A7', strtoupper($this->titulo));
        $sheet->setCellValue('D4', 'ANO LECTIVO: ');
        $sheet->setCellValue('E4', $ano->Designacao ?? '');
        $sheet->setCellValue('D5', 'SEMESTRE: ');
        $sheet->setCellValue('E5', $semestre->Designacao ?? 'TODAS');
        $sheet->setCellValue('D6', 'CURSO: ');
        $sheet->setCellValue('E6', $curso->Designacao ?? 'TODAS');
        $sheet->setCellValue('D7', 'TIPO BOLSA: ');
        $sheet->setCellValue('E7',  $bolsa->designacao ?? 'TODAS');

        $coordenadas = $sheet->getCoordinates();

        return [
            // Style the first row as bold text.
            10    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            'D4:F9'    => [
                'font' => ['bold' => false, 'color' => ['rgb' => 'FCFCFD']],
                'fill' => ['fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID, 'color' => ['rgb' => '2b5876']]

            ],

            // Styling a specific cell by coordinate.
            'A7' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],
            'F7' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],
            // 'G6' => ['font' => ['bold' => true, 'color' => ['rgb' => '00008B']]],

            'A11:' . end($coordenadas) => ['borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ]],


            // Styling an entire column.
            //'C'  => ['font' => ['size' => 16]],
        ];
        //$sheet->getStyle('A7')->getFont()->setBold(true);
    }

    public function bindValue(CellCell $cell, $value)
    {

        if (is_string($value)) {
            $cell->setValueExplicit(strval($value), DataType::TYPE_STRING);
            return true;
        }

        // else return default behavior
        return parent::bindValue($cell, strval($value));
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
