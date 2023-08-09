<?php

namespace App\Exports;

use App\Http\Controllers\TraitHelpers;
use App\Models\AnoLectivo;
use App\Models\GradeCurricularAluno;
use App\Models\Pagamento;
use App\Models\TipoServico;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithMapping;

use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class EstudanteDevedorExport implements FromCollection,
    WithHeadings,
    ShouldAutoSize,
    WithMapping,
    WithEvents,
    WithDrawings,
    WithCustomStartCell
{
    use TraitHelpers;

    protected $facturas;

    public function __construct($facturas)
    {
        $this->facturas = $facturas;
    }

    public function headings():array
    {
        return[
            'Matricula',
            'Nome',
            'Curso',
            'Turno',


            // 'Ano Lectivo',
        ];
    }

    public function map($caixa):array
    {

            return[
                $caixa->matricula,
                $caixa->nome,
                $caixa->curso,
                $caixa->turno,
            ];


    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        return collect($this->facturas);




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
        $drawing->setDescription('FECHO DO CAIXA');
        $drawing->setPath(public_path('/images/logotipo.png'));
        $drawing->setHeight(90);
        $drawing->setCoordinates('A1');

        return $drawing;
    }


}
