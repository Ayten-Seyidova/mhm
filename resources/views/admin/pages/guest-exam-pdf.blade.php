<!DOCTYPE html>
<html lang="az">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, shrink-to-fit=9">
    <title>{{$exam->name}}</title>
    <style>
        body {
            font-family: "DejaVu Sans", sans-serif !important;
        }

        * {
            font-family: "DejaVu Sans", sans-serif !important;
        }
    </style>
</head>
<body class="d-flex flex-column h-100">
<div class="invoice clearfix">
    <div class="container">
        <div class="row justify-content-md-center">
            <div class="col-lg-8 col-md-10" id="printTable">
                <div class="invoice-body">
                    <div class="invoice_footer">
                        <div class="main-card">
                            <div class="row g-0" style="display: flex">
                                <div class="col-lg-5"
                                     style="border: 1px solid rgba(5,117,230,0.1); padding: 10px 20px; background-color: rgba(5,117,230,0.1); border-top-right-radius: 10px; border-top-left-radius: 10px;">
                                    <div class="QR-dt p-4">
                                        <div class="QR-counter-type">
                                            <div>
                                                <h3 style="text-align: center">{{$exam->name}}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div
                                    style="border: 1px solid lightgray; padding: 10px 20px; border-bottom-left-radius: 10px; border-bottom-right-radius: 10px;">
                                    @foreach($posts as $key => $post)
                                        <div>
                                            <table class="option-table">
                                                <tr>
                                                    <td class="opt-label">{{$key+1}}.</td>
                                                    <td class="opt-body">
                                                        @if ($post->title_type == 'text')
                                                            {!! $post->title !!}
                                                        @else
                                                            <img src="{{ $post->title }}" alt=""
                                                                 style="width:100%; height:auto;">
                                                        @endif
                                                    </td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="event-order-dt p-4">
                                            <div class="option">
                                                <table class="option-table">
                                                    <tr>
                                                        <td class="opt-label">A)</td>
                                                        <td class="opt-body">
                                                            @if ($post->variant_type == 'text')
                                                                {!! $post->A !!}
                                                            @else
                                                                <img src="{{ $post->A }}" alt=""
                                                                     style="max-width:200px; height:auto;">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="option">
                                                <table class="option-table">
                                                    <tr>
                                                        <td class="opt-label">B)</td>
                                                        <td class="opt-body">
                                                            @if ($post->variant_type == 'text')
                                                                {!! $post->B !!}
                                                            @else
                                                                <img src="{{ $post->B }}" alt=""
                                                                     style="max-width:200px; height:auto;">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="option">
                                                <table class="option-table">
                                                    <tr>
                                                        <td class="opt-label">C)</td>
                                                        <td class="opt-body">
                                                            @if ($post->variant_type == 'text')
                                                                {!! $post->C !!}
                                                            @else
                                                                <img src="{{ $post->C }}" alt=""
                                                                     style="max-width:200px; height:auto;">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="option">
                                                <table class="option-table">
                                                    <tr>
                                                        <td class="opt-label">D)</td>
                                                        <td class="opt-body">
                                                            @if ($post->variant_type == 'text')
                                                                {!! $post->D !!}
                                                            @else
                                                                <img src="{{ $post->D }}" alt=""
                                                                     style="max-width:200px; height:auto;">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <div class="option" style="display: flex">
                                                <table class="option-table">
                                                    <tr>
                                                        <td class="opt-label">E)</td>
                                                        <td class="opt-body">
                                                            @if ($post->variant_type == 'text')
                                                                {!! $post->E !!}
                                                            @else
                                                                <img src="{{ $post->E }}" alt=""
                                                                     style="max-width:200px; height:auto;">
                                                            @endif
                                                        </td>
                                                    </tr>
                                                </table>
                                            </div>
                                            <p>Düzgün cavab - <b>{{$post->correct}}</b></p>
                                        </div>
                                        @if(!$loop->last)
                                            <hr style="color: lightgray">
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
