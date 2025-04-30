<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <title>{{$result->guest ? $result->guest->name : 'Nəticə'}}</title>
    <style>
        * {
            font-family: DejaVu Sans, sans-serif !important;
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        td {
            padding: 5px;
        }

        .main-page {
            width: 80%;
            margin: 60px auto;
            text-align: center;
        }

        .td-head {
            font-weight: bold;
            font-size: 16px;
        }

        tr {
            border-bottom: 1px solid gray !important;
        }
    </style>
</head>
<body>
<div class="main-page">
    <div style="margin: 0 auto 30px auto;">
        <img src="./admin/images/logo.png" style="width: 100px; display: block;" alt="">
        <h3 style="margin-top: 10px">MHM Tədris Mərkəzi</h3>
    </div>
    <table>
        <tbody>
        <tr>
            <td class="td-head">İstifadəçi: </td>
            <td>
                @if(!empty($result->guest))
                    {{$result->guest->name}}
                @endif
            </td>
        </tr>
        <tr>
            <td class="td-head">İmtahan: </td>
            <td>
                @if(!empty($result->guestExam))
                    {{$result->guestExam->name}}
                @endif
            </td>
        </tr>
        <tr>
            <td class="td-head">Düzgün cavab sayı: </td>
            <td>{{$result->correct_count}}</td>
        </tr>
        <tr>
            <td class="td-head">Səhv cavab sayı: </td>
            <td>{{$result->incorrect_count}}</td>
        </tr>
        <tr>
            <td class="td-head">Vaxt: </td>
            <td>{{$result->time}}</td>
        </tr>
        <tr>
            <td class="td-head">Tarix: </td>
            <td>{{$result->created_at ? $result->created_at->translatedFormat('d.m.Y H:i') : ''}}</td>
        </tr>
        <tr>
            <td class="td-head">Bal: </td>
            <td style="font-size: 18px; font-weight: bold">{{$result->point}}</td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
