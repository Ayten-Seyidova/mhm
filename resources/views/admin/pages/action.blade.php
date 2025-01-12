@extends('admin.index')

@section('title')
    Admin hərəkətləri | Admin panel
@endsection

@section('css')
    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
@endsection

@section('content')

    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Admin hərəkətləri</h4>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <div class="input-group col-4">
                                    <div class="form-item">
                                        <input id="search-input" name="search" type="search"
                                               placeholder="Axtarış et"
                                               value="{{isset($_GET['search']) ? $_GET['search'] : ''}}"
                                               class="form-control"
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
                                    <tr class="text-start">
                                        <th class="text-center">№</th>
                                        <th>Məzmun</th>
                                        <th>Yaranma tarixi</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $key => $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-start">
                                            <td class="text-center">
                                                @if(request('page'))
                                                    {{(request('page')-1)*20 + ($key+1)}}
                                                @else
                                                    {{$key+1}}
                                                @endif
                                            </td>
                                            <td>{{$postItem->title}}</td>
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

    <script>
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.clear-btn').click(function () {
                $('#searchForm input').val('');
            })
        });
    </script>
@endsection
