

<?php
ini_set('memory_limit', '1200M');
ini_set('max_execution_time', 1200);
?>
<!DOCTYPE html>
<html lang="pt">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><!DOCTYPE html>

        <title>LISTA ESTUDANTES COM PROPINAS PAGAS</title>

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
                font-size: 10pt;
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
                <th colspan="6" style="padding: 5px;">LISTA DE ESTUDANTES COM MENSALIDADES PAGAS</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Ano Lectivo:</th>
                <th colspan="3" style="padding: 5px;">{{ $ano ? $ano->Designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Faculdade:</th>
                <th colspan="3" style="padding: 5px;">{{ $faculdade ? $faculdade->designacao : 'Todos' }}</th>
            </tr>            
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Curso:</th>
                <th colspan="3" style="padding: 5px;">{{ $curso ? $curso->Designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Turno:</th>
                <th colspan="3" style="padding: 5px;">{{ $turno ?  $turno->Designacao : 'Todos' }}</th>
            </tr>
            
            <tr style="background-color: #a1a4b9;color: #ffffff;">
                <th colspan="3" style="padding: 5px;">Mês/Parcela:</th>
                <th colspan="3" style="padding: 5px;">{{ $mes ? $mes->designacao : 'Todos' }}</th>
            </tr>
        
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: center;padding: 4px 0" width="150px">Nº Matricula</th>
                <th style="text-align: center;padding: 4px 0">Nome</th>
                <th style="text-align: center;padding: 4px 0">Faculdade</th>
                <th style="text-align: center;padding: 4px 0">Curso</th>
                <th style="text-align: center;padding: 4px 0">Turno</th>
                <th style="text-align: center;padding: 4px 0">Mês/Parcela</th>
            </tr>
        </thead>
        @php
            $contador = 0;
        @endphp
        <tbody>
            @foreach ($items as $item)
                @php
                    $contador++;
                @endphp
                <tr>
                    <td style="padding: 5px;text-align: center">{{  $item->matricula }}</td>
                    <td style="text-align: center">{{ $item->aluno }}</td>
                    <td style="text-align: center">{{ $item->faculdade}}</td>
                    <td style="text-align: center">{{ $item->curso }}</td>
                    <td style="text-align: center">{{ $item->turno }}</td>
                    <td style="text-align: center">{{ $item->servico }} </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</main>

</body>
</html>



