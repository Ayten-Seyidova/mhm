@extends('admin.index')

@section('title')
    Nəticələr | Admin panel
@endsection

@section('css')
    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>

    <style>
        .select2-container--default .select2-selection--single {
            height: 56px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__rendered {
            line-height: 56px !important;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            top: 16px !important;
        }
    </style>
@endsection

@section('content')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Nəticələr</h4>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="exam_id">
                                        <option value="" disabled selected>İmtahan</option>
                                        @if(!empty($exams[0]))
                                            @foreach($exams as $exam)
                                                <option
                                                    value="{{$exam->id}}" {{isset($_GET['exam_id']) && $_GET['exam_id'] == $exam->id ? 'selected' : ''}}>
                                                    {{$exam->name}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="customer_id">
                                        <option value="" disabled selected>Tələbə</option>
                                        @if(!empty($customers[0]))
                                            @foreach($customers as $customer)
                                                <option
                                                    value="{{$customer->id}}" {{isset($_GET['customer_id']) && $_GET['customer_id'] == $customer->id ? 'selected' : ''}}>
                                                    {{$customer->name . ' - ' .$customer->username}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group col-4">
                                    <div class="form-item">
                                        <input id="search-input"
                                               value="{{isset($_GET['search']) ? $_GET['search'] : ''}}" name="search"
                                               type="search"
                                               placeholder="Axtarış et" class="form-control"
                                               style="border-top-right-radius: 0; border-bottom-right-radius: 0"/>
                                    </div>
                                    <button id="search-button" type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                                <div class="col-1">
                                    <button class="filter-search-btn btn btn-secondary clear-btn">Sıfırla</button>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="example3" class="display min-w850">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Seç</th>
                                        <th>İmtahan</th>
                                        <th>Tələbə</th>
                                        <th>Düzgün cavab sayı</th>
                                        <th>Səhv cavab sayı</th>
                                        <th>Vaxt</th>
                                        <th>Yaranma tarixi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td class="text-center"><input value="{{$postItem->id}}" class="checkedItem"
                                                                           name="checked" type="checkbox"></td>
                                            <td>
                                                @if(!empty($postItem->exam))
                                                    <a href="{{route('exam.index', ['exam_id'=>$postItem->exam_id])}}">{{$postItem->exam->name}}</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($postItem->customer))
                                                    <a href="{{route('customer.index', ['customer_id'=>$postItem->customer_id])}}">{{$postItem->customer->name.' - '.$postItem->customer->username}}</a>
                                                @endif
                                            </td>
                                            <td>{{$postItem->correct_count}}</td>
                                            <td>{{$postItem->incorrect_count}}</td>
                                            <td>{{$postItem->time}}</td>
                                            <td>{{$postItem->created_at ? $postItem->created_at->translatedFormat('d.m.Y H:i') : ''}}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                <div
                                    class="d-flex justify-content-center">{{$posts->appends(request()->input())->links()}}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')

    <script src="{{ asset('admin/vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('admin/js/plugins-init/datatables.init.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/js/select2.min.js"></script>

    <script>
        $(document).ready(function () {
            $(".search-select").select2();
        });

        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.clear-btn').click(function () {
                $('#searchForm input').val('');
                $('#searchForm select').val('');
            })
        });
    </script>
@endsection
