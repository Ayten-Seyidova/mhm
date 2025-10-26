@extends('admin.index')
@section('title')
    Rəylər | Admin panel
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
                            <h4 class="card-title">Rəylər</h4>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <input type="hidden" name="post_id" value="{{request('post_id')}}">
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="guest_id">
                                        <option value="" disabled selected>Qonaqlar</option>
                                        @if(!empty($guests[0]))
                                            @foreach($guests as $guest)
                                                <option
                                                    value="{{$guest->id}}" {{isset($_GET['guest_id']) && $_GET['guest_id'] == $guest->id ? 'selected' : ''}}>
                                                    {{$guest->name}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group col-4 flex-nowrap">
                                    <div class="form-item">
                                        <input id="search-input"
                                               value="{{request('search')}}" name="search"
                                               type="search"
                                               placeholder="Axtarış et" class="form-control"
                                               style="border-top-right-radius: 0; border-bottom-right-radius: 0"/>
                                    </div>
                                    <button id="search-button" type="submit" class="btn btn-primary">
                                        <i class="fas fa-search"></i>
                                    </button>
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
                                        <th></th>
                                        <th>Qonaq</th>
                                        <th>Rəy</th>
                                        <th>Tarix</th>
                                        <th>Sil</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $key => $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td class="text-center"></td>
                                            <td>
                                                @if($postItem->guest)
                                                    @auth('admin')
                                                        <a href="{{route('guest.index', ['guest_id'=>$postItem->guest_id])}}">{{$postItem->guest->name}}</a>
                                                    @else
                                                        {{$postItem->guest->name}}
                                                    @endauth
                                                @endif
                                            </td>
                                            <td>{{$postItem->comment}}</td>
                                            <td>{{$postItem->created_at ? $postItem->created_at->format('d.m.Y H:i') : ''}}</td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <a data-id="{{$postItem->id}}"
                                                       class="btn btn-danger shadow btn-xs sharp deleteItem"><i
                                                            class="fa fa-trash"></i></a>
                                                </div>
                                            </td>
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

            $('.deleteItem').click(function () {
                let dataID = $(this).data('id');
                let route = '{{route('postComment.destroy', ['postComment'=>'id'])}}';
                route = route.replace('id', dataID);
                Swal.fire({
                    title: 'Xəbərdarlıq',
                    text: 'Əminsinizmi?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#163A76',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Bəli',
                    cancelButtonText: 'Xeyr'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: route,
                            method: 'DELETE',
                            data: {
                                id: dataID,
                            },
                            async: false,
                            success: function (response) {
                                $('#row' + dataID).remove();

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Xəbərdarlıq',
                                    confirmButtonColor: '#163A76',
                                    text: "Uğurlu",
                                    confirmButtonText: 'Tamam'
                                })
                            }
                        })
                    }
                })
            });

            $('.clear-btn').click(function () {
                $('#searchForm #search-input').val('');
                $('#searchForm select').val('');
            })
        });
    </script>
@endsection

