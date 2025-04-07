@extends('admin.index')
@section('title')
    Cavablar | Admin panel
@endsection
@section('css')
    <link href="{{ asset('admin/vendor/datatables/css/jquery.dataTables.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.0.13/dist/css/select2.min.css" rel="stylesheet"/>
@endsection
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Cavablar</h4>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="customer_id">
                                        <option value="" disabled selected>Tələbə</option>
                                        @if(!empty($customers[0]))
                                            @foreach($customers as $customer)
                                                <option
                                                    value="{{$customer->id}}" {{isset($_GET['customer_id']) && $_GET['customer_id'] == $customer->id ? 'selected' : ''}}>
                                                    {{$customer->username}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="post_id">
                                        <option value="" disabled selected>Paylaşım</option>
                                        @if(!empty($lists[0]))
                                            @foreach($lists as $list)
                                                <option
                                                    value="{{$list->id}}" {{isset($_GET['post_id']) && $_GET['post_id'] == $list->id ? 'selected' : ''}}>
                                                    {{$list->content}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-1">
                                    <button class="filter-search-btn btn btn-secondary clear-btn"><i
                                            class="fas fa-eraser"></i></button>
                                </div>
                            </form>
                            <div class="table-responsive">
                                <table id="example3" class="display min-w850">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Seç</th>
                                        <th>№</th>
                                        <th>Paylaşım</th>
                                        <th>Tələbə</th>
                                        <th>Tələbənin cavabı</th>
                                        <th>Düzgün cavab</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $key => $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td class="text-center"><input value="{{$postItem->id}}" class="checkedItem"
                                                                           name="checked" type="checkbox"></td>
                                            <td class="text-center">
                                                @if(request('page'))
                                                    {{(request('page')-1)*50 + ($key+1)}}
                                                @else
                                                    {{$key+1}}
                                                @endif
                                            </td>
                                            <td>{{$postItem->post ? $postItem->post->content : ''}}</td>
                                            <td>
                                                @if($postItem->customer)
                                                    @auth('admin')
                                                        <a href="{{route('customer.index', ['customer_id'=>$postItem->customer_id])}}">{{$postItem->customer->username}}</a>
                                                    @else
                                                        {{$postItem->customer->username}}
                                                    @endauth
                                                @endif
                                            </td>
                                            <td>{{$postItem->answer}}</td>
                                            <td>{{$postItem->post ? $postItem->post->correct : ''}}</td>
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

