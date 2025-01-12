@extends('admin.index')

@section('title')
    Mövzular | Admin panel
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
                            <h4 class="card-title">Mövzular</h4>
                            <button type="button" class="btn btn-primary btn-rounded mr-2" data-toggle="modal"
                                    data-target="#createModal"><span class="btn-icon-left text-primary"><i
                                        class="fa fa-plus color-info"></i></span>
                                Əlavə et
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <input type="hidden" name="is_deleted" value="{{isset($_GET['is_deleted']) ? $_GET['is_deleted'] : ''}}">
                                <div class="col-2">
                                    <select class="form-control default-select" onchange="form.submit()" name="status"
                                            id="searchOption">
                                        <option value="" disabled selected>Status</option>
                                        <option
                                            value="1" {{isset($_GET['status']) && $_GET['status'] == '1' ? 'selected' : ''}} >
                                            Aktiv
                                        </option>
                                        <option
                                            value="'0'" {{isset($_GET['status']) && $_GET['status'] == "'0'" ? 'selected' : ''}} >
                                            Deaktiv
                                        </option>
                                    </select>
                                </div>
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="video_course_id">
                                        <option value="" disabled selected>Video kurs</option>
                                        @if(!empty($courses[0]))
                                            @foreach($courses as $course)
                                                <option
                                                    value="{{$course->id}}" {{isset($_GET['video_course_id']) && $_GET['video_course_id'] == $course->id ? 'selected' : ''}}>
                                                    {{$course->name}}
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
                                @if(isset($_GET['is_deleted']) && $_GET['is_deleted'] == 1)
                                    <div class="col-1">
                                        <a href="{{route('subject.index')}}"
                                           class="btn btn-primary clear-btn">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-1">
                                        <a href="{{route('subject.index', ['is_deleted'=>1])}}"
                                           class="btn btn-success clear-btn">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                    </div>
                                @endif
                            </form>
                            <div class="table-responsive">
                                <table id="example3" class="display min-w850">
                                    <thead>
                                    <tr class="text-center">
                                        <th>Seç</th>
                                        <th>Mövzu adı</th>
                                        <th>Video kurs</th>
                                        <th>Yaranma tarixi</th>
                                        <th>Status</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td class="text-center"><input value="{{$postItem->id}}" class="checkedItem"
                                                                           name="checked" type="checkbox"></td>
                                            <td>{{$postItem->name}}</td>
                                            <td>
                                                @if(!empty($postItem->course))
                                                    <a href="{{route('video-course.index', ['video_course_id'=>$postItem->video_course_id])}}">{{$postItem->course->name}}</a>
                                                @endif
                                            </td>
                                            <td>{{$postItem->created_at ? $postItem->created_at->translatedFormat('d.m.Y H:i') : ''}}</td>
                                            <td class="m-auto text-center">
                                                @if($postItem->status)
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input changeStatus checkStatus{{$postItem->id}}"
                                                            data-id="{{$postItem->id}}" type="checkbox"
                                                            id="flexSwitchCheckDefault" checked/>
                                                    </div>
                                                @else
                                                    <div class="form-check form-switch">
                                                        <input
                                                            class="form-check-input changeStatus checkStatus{{$postItem->id}}"
                                                            data-id="{{$postItem->id}}" type="checkbox"
                                                            id="flexSwitchCheckDefault"/>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center justify-content-center">
                                                    <a href="javascript:void(0)" data-id="{{$postItem->id}}"
                                                       data-target="#editModal"
                                                       data-toggle="modal"
                                                       class="btn btn-primary shadow btn-xs sharp mr-1 editModal"><i
                                                            class="fa fa-pencil"></i></a>
                                                    @if(isset($_GET['is_deleted']) && $_GET['is_deleted'] == 1)
                                                        <a data-id="{{$postItem->id}}"
                                                           class="btn btn-success shadow btn-xs sharp deleteItem"><i
                                                                class="fa fa-reply"></i></a>
                                                    @else
                                                        <a data-id="{{$postItem->id}}"
                                                           class="btn btn-danger shadow btn-xs sharp deleteItem"><i
                                                                class="fa fa-trash"></i></a>
                                                    @endif
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <br>
                                @if(!empty($postItem))
                                    <div class="d-flex justify-content-start">
                                        <button class="checkedBtn btn-primary btn mr-3" value="0">SEÇİLƏNLƏRİ DEAKTİV ET
                                        </button>
                                        <button class="checkedBtn btn-primary btn mr-3" value="1">SEÇİLƏNLƏRİ AKTİV ET
                                        </button>
                                        @if(isset($_GET['is_deleted']) && $_GET['is_deleted'] == 1)
                                            <button class="checkedBtn btn-primary btn" value="2">SEÇİLƏNLƏRİ BƏRPA ET
                                            </button>
                                        @else
                                            <button class="checkedBtn btn-primary btn" value="2">SEÇİLƏNLƏRİ SİL
                                            </button>
                                        @endif
                                    </div>
                                    <br>
                                @endif
                                <div
                                    class="d-flex justify-content-center">{{$posts->appends(request()->input())->links()}}</div>
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
                <form id="formCreate" action="{{route('subject.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pb-0 pt-2">
                        <div class="form-group">
                            <label for="name">Mövzu adı</label>
                            <input class="form-control" value="{{old('name')}}"
                                   type="text" required maxlength="190"
                                   name="name" id="name"/>
                        </div>
                        <div class="form-group">
                            <label for="videoCourse">Video kurs</label>
                            <select name="video_course_id" class="form-control search-select" required id="videoCourse">
                                @if(!empty($courses[0]))
                                    @foreach($courses as $course)
                                        <option value="{{$course->id}}">{{$course->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group d-flex mt-4">
                            <label for="status">Status</label>
                            <div class="form-check form-switch ml-4">
                                <input class="form-check-input"
                                       type="checkbox" checked name="status"
                                       id="status"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="submit" id="createBtn" class="btn btn-primary btn-xs">Yadda
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
                            <label for="nameEdit">Mövzu adı</label>
                            <input class="form-control"
                                   type="text" required maxlength="190"
                                   name="name" id="nameEdit"/>
                        </div>
                        <div class="form-group">
                            <label for="videoCourseEdit">Video kurs</label>
                            <select name="video_course_id" class="form-control search-select" required
                                    id="videoCourseEdit">
                                @if(!empty($courses[0]))
                                    @foreach($courses as $course)
                                        <option value="{{$course->id}}">{{$course->name}}</option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        <div class="form-group d-flex mt-4">
                            <label for="statusEdit">Status</label>
                            <div class="form-check form-switch ml-4">
                                <input class="form-check-input"
                                       type="checkbox" checked name="status"
                                       id="statusEdit"/>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-xs" data-dismiss="modal">
                            Ləğv et
                        </button>
                        <button type="submit" id="editPost" class="btn btn-primary btn-xs">Yadda
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
                    let route = '{{route('subject.checked')}}';
                    let currentVal = $(this).val();

                    let text = '';
                    let resultText = '';

                    if (currentVal == '0') {
                        text = 'Seçilənləri deaktiv etmək istədiyinizə əminsiniz?';
                        resultText = 'Deaktiv edildi';
                    } else if (currentVal == '1') {
                        text = 'Seçilənləri aktiv etmək istədiyinizə əminsiniz?';
                        resultText = 'Aktiv edildi';
                    } else {
                        text = 'Əminsinizmi?';
                        resultText = 'Uğurlu';
                    }
                    Swal.fire({
                        title: 'Xəbərdarlıq',
                        text: text,
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
                                    val: currentVal,
                                },
                                async: false,
                                success: function (response) {
                                    if (currentVal == '0') {
                                        for (let i of checkedArr) {
                                            $('.checkStatus' + i).attr('checked', false);
                                        }
                                    } else if (currentVal == '1') {
                                        for (let i of checkedArr) {
                                            $('.checkStatus' + i).attr('checked', true);
                                        }
                                    } else {
                                        for (let i of checkedArr) {
                                            $('#row' + i).remove();
                                        }
                                    }

                                    $('.checkedItem').prop('checked', false);
                                    checkedArr = [];
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Xəbərdarlıq',
                                        text: resultText,
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

            $('.changeStatus').click(function () {
                let dataID = $(this).data('id');

                $.ajax({
                    url: '{{route('subject.changeStatus')}}',
                    method: 'POST',
                    data: {
                        id: dataID
                    },
                    async: false,
                })
            });

            $('.deleteItem').click(function () {
                let dataID = $(this).data('id');
                let route = '{{route('subject.destroy', ['subject'=>'id'])}}';
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
                let nameEdit = $('#nameEdit');
                let videoCourseEdit = $('#videoCourseEdit');
                let statusEdit = $('#statusEdit');

                let route = '{{route('subject.edit', ['subject'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('subject.update', ['subject' => 'update'])}}';
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

                        var post = response.post;

                        nameEdit.val(post.name);
                        videoCourseEdit.val(post.video_course_id);

                        if (post.status == 1) {
                            statusEdit.attr('checked', true);
                        } else {
                            statusEdit.attr('checked', false);
                        }
                    }
                })
            }

            let searchParams = new URLSearchParams(window.location.search)
            if (searchParams.has('subject_id')) {
                let dataId = searchParams.get('subject_id');
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
