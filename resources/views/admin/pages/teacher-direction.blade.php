@extends('admin.index')
@section('title')
    Müəllim istiqamətləri | Admin panel
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
                            <h4 class="card-title">Müəllim İstiqamətləri</h4>
                            <button type="button" class="btn btn-primary btn-rounded mr-2" data-toggle="modal"
                                    data-target="#createModal"><span class="btn-icon-left text-primary"><i
                                        class="fa fa-plus color-info"></i></span>
                                Əlavə et
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="user_id">
                                        <option value="" disabled selected>Müəllim</option>
                                        @if(!empty($teachers[0]))
                                            @foreach($teachers as $teacher)
                                                <option
                                                    value="{{$teacher->id}}" {{isset($_GET['user_id']) && $_GET['user_id'] == $teacher->id ? 'selected' : ''}}>
                                                    {{$teacher->name}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="sub_direction_id">
                                        <option value="" disabled selected>İstiqamət</option>
                                        @if(!empty($subDirections[0]))
                                            @foreach($subDirections as $subDirection)
                                                <option
                                                    value="{{$subDirection->id}}" {{isset($_GET['sub_direction_id']) && $_GET['sub_direction_id'] == $subDirection->id ? 'selected' : ''}}>
                                                    {{$subDirection->title.($subDirection->direction ? ' ('.$subDirection->direction->title.')' :'')}}
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
                                        <th>Müəllim</th>
                                        <th>İstiqamət</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $key => $postItem)
                                        @php($directions = \App\Models\TeacherSubDirection::where('user_id', $postItem->id)->get())
                                        @if(!empty($directions[0]))
                                            <tr id="row{{$postItem->id}}" class="text-center">
                                                <td class="text-center"><input value="{{$postItem->id}}"
                                                                               class="checkedItem"
                                                                               name="checked" type="checkbox"></td>
                                                <td>{{$postItem->name}}</td>
                                                <td>
                                                    @foreach($directions as $direction)
                                                        {{($direction->subDirection ? $direction->subDirection->title : '') . ($direction->subDirection ? ($direction->subDirection->direction ? ' ('.$direction->subDirection->direction->title.')' : '') : '') }}
                                                        @if(!$loop->last)
                                                            <br>
                                                        @endif
                                                    @endforeach
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-center">
                                                        <a href="javascript:void(0)" data-id="{{$postItem->id}}"
                                                           data-target="#editModal"
                                                           data-toggle="modal"
                                                           class="btn btn-primary shadow btn-xs sharp mr-1 editModal"><i
                                                                class="fa fa-pencil"></i></a>
                                                        <a data-id="{{$postItem->id}}"
                                                           class="btn btn-danger shadow btn-xs sharp deleteItem"><i
                                                                class="fa fa-trash"></i></a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                @if(!empty($postItem))
                                    <div class="d-flex justify-content-start">
                                        <button class="checkedBtn btn-primary btn mr-3" value="2">SEÇİLƏNLƏRİ SİL
                                        </button>
                                    </div>
                                    <br>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="createModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Əlavə
                        et</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCreate" action="{{route('teacher-direction.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pb-0 pt-2">
                        <div class="form-group">
                            <label for="teacherId">Müəllim</label>
                            <select name="user_id" required class="form-control search-select"
                                    id="teacherId">
                                @if(!empty($teachers[0]))
                                    @foreach($teachers as $teacher)
                                        <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subDirectionId">İstiqamət</label>
                            <select name="sub_direction_id[]" multiple required class="form-control search-select"
                                    id="subDirectionId">
                                @if(!empty($subDirections[0]))
                                    @foreach($subDirections as $subDirection)
                                        <option value="{{$subDirection->id}}"
                                                data-direction_id="{{$subDirection->direction_id}}">{{$subDirection->title.($subDirection->direction ? ' ('.$subDirection->direction->title.')' :'')}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary btn-xs" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="submit" id="createBtn" class="btn btn-sm btn-primary btn-xs">Yadda
                            saxla
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog"
         aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Redaktə
                        et</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formEdit" action="" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body pb-0 pt-2">
                        <div class="form-group">
                            <label for="teacherIdEdit">Müəllim</label>
                            <select name="user_id" required class="form-control search-select"
                                    id="teacherIdEdit">
                                @if(!empty($teachers[0]))
                                    @foreach($teachers as $teacher)
                                        <option value="{{$teacher->id}}">{{$teacher->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="subDirectionIdEdit">İstiqamət</label>
                            <select name="sub_direction_id[]" multiple required class="form-control search-select"
                                    id="subDirectionIdEdit">
                                @if(!empty($subDirections[0]))
                                    @foreach($subDirections as $subDirection)
                                        <option value="{{$subDirection->id}}"
                                                data-direction_id="{{$subDirection->direction_id}}">{{$subDirection->title.($subDirection->direction ? ' ('.$subDirection->direction->title.')' :'')}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="submit" id="editPost" class="btn btn-sm btn-primary">Yadda
                            saxla
                        </button>
                    </div>
                </form>
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

            let checkedArr = [];

            $('.checkedItem').click(function () {
                let checkedID = $(this).val();
                if ($(this).is(':checked')) {
                    checkedArr.push(checkedID);
                    return checkedArr;
                } else {
                    checkedArr = checkedArr.filter(function (letter) {
                        return letter !== checkedID;
                    });
                }
            })

            $('.checkedBtn').click(function () {
                if (checkedArr.length != 0) {
                    let route = '{{route('teacher-direction.checked')}}';

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
                                method: 'POST',
                                data: {
                                    arr: checkedArr,
                                },
                                async: false,
                                success: function (response) {
                                    for (let i of checkedArr) {
                                        $('#row' + i).remove();
                                    }

                                    $('.checkedItem').prop('checked', false);
                                    checkedArr = [];
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Xəbərdarlıq',
                                        text: 'Uğurlu',
                                        confirmButtonColor: '#163A76',
                                        confirmButtonText: 'Tamam'
                                    })
                                }
                            })
                        }
                    })
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Xəbərdarlıq',
                        text: 'Heç bir seçim edilməmişdir',
                        confirmButtonColor: '#163A76',
                        confirmButtonText: 'Tamam'
                    })
                }

            });

            $('.deleteItem').click(function () {
                let dataID = $(this).data('id');
                let route = '{{route('teacher-direction.destroy', ['teacher_direction'=>'delete'])}}';
                route = route.replace('delete', dataID);
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
                                    text: "Uğurlu",
                                    confirmButtonColor: '#163A76',
                                    confirmButtonText: 'Tamam'
                                })
                            }
                        })
                    }
                })
            });

            function editUser(dataID) {
                let teacherIdEdit = $('#teacherIdEdit');
                let subDirectionIdEdit = $('#subDirectionIdEdit');

                let route = '{{route('teacher-direction.edit', ['teacher_direction'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('teacher-direction.update', ['teacher_direction' => 'update'])}}';
                routeUpdate = routeUpdate.replace('update', dataID);

                $('#formEdit').attr('action', routeUpdate);

                $.ajax({
                    url: route,
                    method: 'GET',
                    data: {
                        id: dataID
                    },
                    async: false,
                    success: function (response) {

                        var posts = response.posts;
                        teacherIdEdit.val(dataID).trigger('change');

                        if (posts && posts.length > 0) {
                            const selectedValues = posts.map(post => post.sub_direction_id.toString());

                            $('#subDirectionIdEdit option').each(function () {
                                const optionValue = $(this).val();
                                $(this).prop('selected', selectedValues.includes(optionValue));
                            });

                            $('#subDirectionIdEdit').trigger('change.select2');
                        }
                    }
                });
            }

            let searchParams = new URLSearchParams(window.location.search)
            if (searchParams.has('teacher-direction_id')) {
                let dataId = searchParams.get('teacher-direction_id');
                $('#editModal').modal('show');
                editUser(dataId);
            }

            $('.editModal').click(function () {
                let dataID = $(this).data('id');
                editUser(dataID);
            });

            $('.clear-btn').click(function () {
                $('#searchForm input').val('');
                $('#searchForm select').val('');
            })
        });
    </script>
@endsection

