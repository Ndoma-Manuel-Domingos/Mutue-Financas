
<!DOCTYPE html>
<html lang="pt">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/><!DOCTYPE html>
        <title>LISTA DE INSTITUIÇÕES</title>
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
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th colspan="6" style="text-align: left;padding: 5px;text-transform: uppercase">LISTA DAS INSTITUIÇOES {{ $tipo ? $tipo->designacao : 'TODAS' }}</th>
            </tr>
            <tr style="background-color: #3F51B5;color: #ffffff">
                <th style="text-align: center;padding: 1px 0">Instituição</th>
                <th style="text-align: center;padding: 1px 0">Sigla</th>
                <th style="text-align: center;padding: 1px 0">NIF</th>
                <th style="text-align: center;padding: 1px 0">Tipo Instituição</th>
                <th style="text-align: center;padding: 1px 0">Contacto</th>
                <th style="text-align: center;padding: 1px 0">Endereco</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($instituicao as $item)
                <tr>
                    <td style="padding: 5px;text-align: center">{{ $item->Instituicao}}</td>
                    <td style="padding: 5px;text-align: center">{{ $item->sigla ?? '...'}}</td>
                    <td style="padding: 5px;text-align: center">{{ $item->nif}}</td>
                    <td style="padding: 5px;text-align: center">{{ $item->tipo->designacao}}</td>
                    <td style="padding: 5px;text-align: center">{{ $item->contacto }}</td>
                    <td style="padding: 1px;text-align: center">{{ $item->Endereco}}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</main>

</body>
</html>



