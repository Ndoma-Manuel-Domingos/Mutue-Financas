
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><!DOCTYPE html>
        <title>LISTA DE BOLSEIROS</title>

        <style type="text/css">
            *{
                margin: 0;
                padding: 0;
                -webkit-box-sizing: border-box;
                box-sizing: border-box;
                font-family: Arial, Helvetica, sans-serif;
                text-align: left;
            }
            body{
                padding: 20px;
                font-family: Arial, Helvetica, sans-serif;
            }

            h1{
                font-size: 15pt;
                margin-bottom: 10px;
            }
            h2{
                font-size: 12pt;
            }
            p{
                /* margin-bottom: 20px; */
                line-height: 25px;
                font-size: 12pt;
                text-align: justify;
            }
            strong{
                font-size: 12pt;
            }

            table{
                width: 100%;
                text-align: left;
                border-spacing: 2;
                margin-bottom: 10px;
                /* border: 1px solid rgb(0, 0, 0); */
                font-size: 12pt;
            }
            thead{
                background-color: #fdfdfd;
                font-size: 10px;
            }
            td{
                border-bottom: 1px solid rgb(255, 255, 255);
            }
            th, td{
                padding: 6px;
                font-size: 13px;
                margin: 0;
                padding: 0;
            }
            strong{
                font-size: 13px;
            }
        </style>
    </head>
<body>

<div style="text-align: center;width: 100%;padding: 20px 0;">
    <table style="border-bottom: 1px solid #000;padding-bottom: 10px">
        <tr>
            <td rowspan="5" style="width: 100px">
                <img src="{{ public_path('images/logotipo.png') }}" style="width: 200px;height: 120px;" />
            </td>
            <td style="text-align: right;font-size: 16px">Universidade Metodista</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 16px">Rua Nossa Senhora da Muxima Nº 10,</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 16px">Bairro Kinaxixi, Luanda</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 16px">+244 947716133/+244 942364667</td>
        </tr>
        <tr>
            <td style="text-align: right;font-size: 16px">geral@uma.co.ao</td>
        </tr>
    </table>
</div>

<main>

    <table class="table table-stripeds" style="">
        <thead>
        
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: center;padding: 4px 2px" colspan="8">LISTAGEM DE ESTUANTES BOLSEIROS</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: left;padding: 4px 2px" colspan="3">TOTAL REGISTROS</th>
                <th style="text-align: left;padding: 4px 2px" colspan="5">{{ count($bolseiros) }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: left;padding: 4px 2px" colspan="3">ANO LECTIVO</th>
                <th style="text-align: left;padding: 4px 2px" colspan="5">{{ $ano ? $ano->Designacao : "TODOS"  }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: left;padding: 4px 2px" colspan="3">CURSO</th>
                <th style="text-align: left;padding: 4px 2px" colspan="5">{{ $curso ? $curso->Designacao : "TODOS"  }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: left;padding: 4px 2px" colspan="3">TIPO BOLSA</th>
                <th style="text-align: left;padding: 4px 2px" colspan="5">{{ $bolsa ? $bolsa->designacao : "TODOS"  }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: left;padding: 4px 2px" colspan="3">SEMESTRE</th>
                <th style="text-align: left;padding: 4px 2px" colspan="5">{{ $semestre ? $semestre->Designacao : "TODOS" }}</th>
            </tr>
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: left;padding: 4px 2px" width="150px">Codigo</th>
                <th style="text-align: left;padding: 4px 2px">Matricula</th>
                <th style="text-align: left;padding: 4px 2px">Nome</th>
                <th style="text-align: left;padding: 4px 2px">Curso</th>
                <th style="text-align: left;padding: 4px 2px">Tipo Bolsa</th>
                <th style="text-align: left;padding: 4px 2px">Desconto</th>
                <th style="text-align: left;padding: 4px 2px">Estado</th>
                <th style="text-align: left;padding: 4px 2px">Semestre</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($bolseiros as $item)
                <tr>
                    <td style="padding: 5px;text-align: left">{{ $item->codigo}}</td>
                    <td style="padding: 5px;text-align: left">{{ $item->matricula}}</td>
                    <td style="padding: 5px;text-align: left">{{ $item->nome}}</td>
                    <td style="padding: 5px;text-align: left">{{ $item->curso }}</td>
                    <td style="padding: 5px;text-align: left">{{ $item->tipobolsa}}</td>
                    <td style="padding: 5px;text-align: left">{{ $item->desconto}}</td>
                    <td style="padding: 5px;text-align: left">{{ $item->status == 0 ? 'ACTIVO' : 'DESACTIVO' }}</td>
                    <td style="padding: 5px;text-align: left">{{ $item->semestre == 1 ? 'I-SEMESTRE' : ($item->semestre == 2 ? 'II-SEMESTRE' : 'TODOS' )}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>

</body>
</html>



