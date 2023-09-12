
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><!DOCTYPE html>
        <title>LISTA DOS PAGAMENTO POR VALIDAR</title>
    
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
                font-size: 19pt;
            }
            p{
                /* margin-bottom: 20px; */
                line-height: 25px;
                font-size: 9pt;
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
                font-size: 10pt;
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
                font-size: 10px;
                margin: 0;
                padding: 0;
            }
            strong{
                font-size: 13px;
            }
        </style>
    </head>
<body>
    
@include('pdf.estudantes.header')

<main>

    <table class="table table-stripeds" style="">
        <thead>
            <tr style="background-color: #3F51B5;color: #ffffff;">
                <th colspan="9" style="padding: 5px;">LISTA DOS PAGAMENTO POR VALIDAR</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Tipo De serviço:</th>
                <th colspan="6" style="padding: 5px;">{{ $requests['tipo_servico'] ?? 'Todos os Serviços' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Prestação:</th>
                <th colspan="6" style="padding: 5px;">{{ $requests['prestacao'] ?? 'Todas' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Grau:</th>
                <th colspan="6" style="padding: 5px;">{{ $requests['grau_academico'] ?? 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Forma de Pagamento:</th>
                <th colspan="6" style="padding: 5px;">{{ $requests['forma_pagamento'] ?? 'Todas Formas' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Data Inicio:</th>
                <th colspan="6" style="padding: 5px;">{{ $requests['data_inicio'] ?? 'Todas'}}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Data Fim:</th>
                <th colspan="6" style="padding: 5px;">{{ $requests['data_final'] ?? 'Todas' }}</th>
            </tr>
            
            
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th colspan="9" style="padding: 5px;">Total de Registro: {{ count($items) }}</th>
            </tr>
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="padding: 1px;text-align: center">Matricula</th>
                <th style="padding: 1px;text-align: center">Estudante</th>
                <th style="padding: 1px;text-align: center">Factura</th>
                <th style="padding: 1px;text-align: center">Recibo</th>
                <th style="padding: 1px;text-align: center">Serviço</th>
                <th style="padding: 1px;text-align: center">Data Pagamento</th>
                <th style="padding: 1px;text-align: center">Data Inserção</th>
                <th style="padding: 1px;text-align: center">Valor Depositado</th>
                <th style="padding: 1px;text-align: center">Forma Pagamento</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($items as $item)
                <tr>
                    <td style="text-align: center;padding: 2px">{{ $item->matricula ?? "#####" }}</td>
                    <td style="text-align: center;padding: 2px">{{ $item->Nome_Completo ?? "" }}</td>
                    <td style="text-align: center;padding: 2px">{{ $item->codigo_factura ?? "" }}</td>
                    <td style="text-align: center;padding: 2px">{{ $item->Codigo ?? "" }}</td>
                    <td style="text-align: center;padding: 2px">{{ $item->servico ?? "" }}</td>
                    <td style="text-align: center;padding: 2px">{{ $item->DataBanco ?? "" }}</td>
                    <td style="text-align: center;padding: 2px">{{ $item->Data ?? "" }}</td>
                    <td style="text-align: center;padding: 2px">AOA {{ number_format($item->valor_depositado ?? 0, 2, ',', '.') }}</td>
                    <td style="text-align: center;padding: 2px">{{ $item->forma_pagamento ?? "" }}</td>
                </tr>     
            @endforeach
        </tbody>
    </table> 
</main>

</body>
</html>



