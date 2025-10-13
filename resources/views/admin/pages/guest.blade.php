@extends('admin.index')
@section('title')
    Qonaqlar | Admin panel
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
                            <h4 class="card-title">Qonaqlar</h4>
                            <?php
                            $queryParams = request()->query();

                            $downloadUrl = route('downloadGuest');

                            if (!empty($queryParams)) {
                                $downloadUrl .= '?' . http_build_query($queryParams);
                            }
                            ?>
                            <a href="{{ $downloadUrl }}"
                               class="btn btn-primary text-white btn-xs rounded-pill shadow-none"
                               style="padding: 10px 15px;">Yüklə <i class="fas fa-download"></i></a>
{{--                            <button type="button" class="btn btn-primary btn-rounded mr-2" data-toggle="modal"--}}
{{--                                    data-target="#createModal"><span class="btn-icon-left text-primary"><i--}}
{{--                                        class="fa fa-plus color-info"></i></span>--}}
{{--                                Əlavə et--}}
{{--                            </button>--}}
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <input type="hidden" name="is_deleted"
                                       value="{{isset($_GET['is_deleted']) ? $_GET['is_deleted'] : ''}}">
                                <div class="col-2">
                                    <select class="form-control search-select" onchange="form.submit()" name="status"
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
                                <div class="col-2">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="is_student">
                                        <option value="" disabled selected>MHM tələbəsi</option>
                                        <option
                                            value="1" {{isset($_GET['is_student']) && $_GET['is_student'] == '1' ? 'selected' : ''}} >
                                            MHM tələbəsidir
                                        </option>
                                        <option
                                            value="'0'" {{isset($_GET['is_student']) && $_GET['is_student'] == "'0'" ? 'selected' : ''}} >
                                            MHM tələbəsi deyil
                                        </option>
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="direction_id">
                                        <option value="" disabled selected>Hazırlıq istiqaməti</option>
                                        @if(!empty($directions[0]))
                                            @foreach($directions as $direction)
                                                <option
                                                    value="{{$direction->id}}" {{isset($_GET['direction_id']) && $_GET['direction_id'] == $direction->id ? 'selected' : ''}}>
                                                    {{$direction->title}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="col-2">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="sub_direction_id">
                                        <option value="" disabled selected>İstiqamət</option>
                                        @if(!empty($subDirections[0]))
                                            @foreach($subDirections as $subDirection)
                                                <option
                                                    value="{{$subDirection->id}}" {{isset($_GET['sub_direction_id']) && $_GET['sub_direction_id'] == $subDirection->id ? 'selected' : ''}}>
                                                    {{$subDirection->title}}
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="input-group col-2 flex-nowrap">
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
                                    <button class="filter-search-btn btn btn-secondary clear-btn"><i
                                            class="fas fa-eraser"></i></button>
                                </div>
                                @if(isset($_GET['is_deleted']) && $_GET['is_deleted'] == 1)
                                    <div class="col-1">
                                        <a href="{{route('guest.index')}}"
                                           class="btn btn-primary clear-btn">
                                            <i class="fas fa-users"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-1">
                                        <a href="{{route('guest.index', ['is_deleted'=>1])}}"
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
                                        <th>№</th>
                                        <th>Şəkil</th>
                                        <th>Ad və soyad</th>
                                        <th>Telefon</th>
                                        <th>Hazırlıq istiqaməti</th>
                                        <th>İstiqamət</th>
                                        <th>MHM tələbəsi</th>
                                        <th>Status</th>
                                        <th>Əməliyyatlar</th>
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
                                            <td>
                                                <img class="d-block" style="width: 50px; margin: auto"
                                                     src="{{asset($postItem->image)}}"
                                                     alt=""></td>
                                            <td>{{$postItem->name}}</td>
                                            <td><a href="tel:{{$postItem->phone}}">{{$postItem->phone}}</a></td>
                                            <td>{{$postItem->subDirection ? ($postItem->subDirection->direction ? $postItem->subDirection->direction->title : '') : ''}}</td>
                                            <td>{{$postItem->subDirection ? $postItem->subDirection->title : ''}}</td>
                                            <td class="m-auto text-center">
                                                <input
                                                    type="checkbox" {{$postItem->is_student == 1 ? 'checked' : ''}}/>
                                            </td>
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
                                                           class="btn btn-success shadow btn-xs sharp mr-1 deleteItem"><i
                                                                class="fa fa-reply"></i></a>
                                                        <a data-id="{{$postItem->id}}"
                                                           class="btn btn-danger shadow btn-xs sharp delete-permanent"><i
                                                                class="fa fa-trash"></i></a>
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
                                            <button class="checkedBtn btn-primary btn mr-3" value="2">SEÇİLƏNLƏRİ BƏRPA ET
                                            </button>
                                            <button class="checkedBtn btn-primary btn mr-3" value="5">QALICI OLARAQ SİL
                                            </button>
                                        @else
                                            <button class="checkedBtn btn-primary btn mr-3" value="2">SEÇİLƏNLƏRİ SİL
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
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Əlavə
                        et</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCreate" action="{{route('guest.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pb-0 pt-2">
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group img-section">
                                    <label for="uploadImage-create">Şəkil</label>
                                    <div class="img-input d-flex justify-content-between mb-2">
                                        <input id="uploadImage-create" type="file"
                                               name="image" class="form-control-file"
                                               onchange="PreviewImageCreate();">
                                        <div class="delete-img c-pointer" onclick="deleteImageCreate();">
                                            <i class="fas fa-trash"></i></div>
                                    </div>

                                    <img class="preview-img" id='previewImage-create'
                                         src="{{asset('admin/images/noUser.png')}}"
                                         style="width: 100%;" alt="">
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
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="name">Ad və soyad</label>
                                    <input class="form-control" value="{{old('name')}}"
                                           type="text" maxlength="100" required
                                           name="name" id="name"/>
                                </div>
                                <div class="form-group">
                                    <label for="phone">Telefon</label>
                                    <input class="form-control" value="{{old('phone')}}"
                                           type="text" maxlength="100"
                                           name="phone" id="phone"/>
                                </div>
                                <div class="form-group">
                                    <label for="directionId">Hazırlıq istiqaməti</label>
                                    <select name="direction_id" required class="form-control search-select"
                                            id="directionId">
                                        @if(!empty($directions[0]))
                                            @foreach($directions as $direction)
                                                <option value="{{$direction->id}}">{{$direction->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subDirectionId">İstiqamət</label>
                                    <select name="sub_direction_id" required class="form-control search-select"
                                            id="subDirectionId">
                                        @if(!empty($subDirections[0]))
                                            @foreach($subDirections as $subDirection)
                                                <option value="{{$subDirection->id}}"
                                                        data-direction_id="{{$subDirection->direction_id}}">{{$subDirection->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group d-flex mt-4">
                                    <label for="isStudent">MHM tələbəsidir</label>
                                    <div class="form-check form-switch ml-4">
                                        <input class="form-check-input"
                                               type="checkbox" name="is_student"
                                               id="isStudent"/>
                                    </div>
                                </div>
                            </div>
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
        <div class="modal-dialog modal-lg" role="document">
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
                        <div class="row">
                            <div class="col-4">
                                <div class="form-group img-section">
                                    <label for="uploadImage">Şəkil</label>
                                    <div class="img-input d-flex justify-content-between mb-2">
                                        <input id="uploadImage" type="file"
                                               name="image" value="" class="form-control-file"
                                               onchange="PreviewImage();">
                                        <div class="delete-img c-pointer" onclick="deleteImage();">
                                            <i class="fas fa-trash"></i></div>
                                        <input id="hiddenInput" type="hidden" name="hidden" value="1">
                                    </div>

                                    <img class="preview-img" id='previewImage'
                                         src="{{asset('admin/images/noUser.png')}}"
                                         style="width: 100%;" alt="">
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
                            <div class="col-8">
                                <div class="form-group">
                                    <label for="nameEdit">Ad və soyad</label>
                                    <input class="form-control"
                                           type="text" maxlength="100" required
                                           name="name" id="nameEdit"/>
                                </div>
                                <div class="form-group">
                                    <label for="phoneEdit">Telefon</label>
                                    <input class="form-control"
                                           type="text" maxlength="100"
                                           name="phone" id="phoneEdit"/>
                                </div>
                                <div class="form-group">
                                    <label for="directionIdEdit">Hazırlıq istiqaməti</label>
                                    <select name="direction_id" required class="form-control search-select"
                                            id="directionIdEdit">
                                        @if(!empty($directions[0]))
                                            @foreach($directions as $direction)
                                                <option value="{{$direction->id}}">{{$direction->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="subDirectionIdEdit">İstiqamət</label>
                                    <select name="sub_direction_id" required class="form-control search-select"
                                            id="subDirectionIdEdit">
                                        @if(!empty($subDirections[0]))
                                            @foreach($subDirections as $subDirection)
                                                <option value="{{$subDirection->id}}"
                                                        data-direction_id="{{$subDirection->direction_id}}">{{$subDirection->title}}</option>
                                            @endforeach
                                        @endif
                                    </select>
                                </div>
                                <div class="form-group d-flex mt-4">
                                    <label for="isStudentEdit">MHM tələbəsidir</label>
                                    <div class="form-check form-switch ml-4">
                                        <input class="form-check-input"
                                               type="checkbox" name="is_student"
                                               id="isStudentEdit"/>
                                    </div>
                                </div>
                            </div>
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
        function PreviewImageCreate() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage-create").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage-create").src = oFREvent.target.result;
            };
        };

        function deleteImageCreate() {
            document.getElementById("previewImage-create").src = '{{asset('admin/images/noUser.png')}}';
            document.getElementById("uploadImage-create").value = '';
        }

        function PreviewImage() {
            document.getElementById('hiddenInput').value = '1';
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage").src = oFREvent.target.result;
            };
        };

        function deleteImage() {
            document.getElementById("previewImage").src = '{{asset('admin/images/noUser.png')}}';
            document.getElementById('hiddenInput').value = '0';
        }

        $(document).ready(function () {
            $(".search-select").select2();
        });
        $(function () {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            let allOptions = $('#subDirectionId option').clone();

            $('#directionId').on('change', function () {
                let selectedDirection = $(this).val();
                let $subDirectionSelect = $('#subDirectionId');
                $subDirectionSelect.empty();

                allOptions.each(function () {
                    if ($(this).attr('data-direction_id') === selectedDirection || $(this).val() === '') {
                        $subDirectionSelect.append($(this).clone());
                    }
                });

                $subDirectionSelect.trigger('change.select2');
            });

            $('#directionId').trigger('change');

            $('#directionIdEdit').on('change', function () {
                let selectedDirection = $(this).val();
                let $subDirectionSelect = $('#subDirectionIdEdit');
                $subDirectionSelect.empty();

                allOptions.each(function () {
                    if ($(this).attr('data-direction_id') === selectedDirection || $(this).val() === '') {
                        $subDirectionSelect.append($(this).clone());
                    }
                });

                $subDirectionSelect.trigger('change.select2');
            });

            $('#directionIdEdit').trigger('change');


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
                    let route = '{{route('guest.checked')}}';
                    let currentVal = $(this).val();

                    let text = '';
                    let resultText = '';

                    if (currentVal == 4) {
                        $('#sendModal').modal('show');
                    } else {
                        if (currentVal == '0') {
                            text = 'Seçilənləri deaktiv etmək istədiyinizə əminsiniz?';
                            resultText = 'Deaktiv edildi';
                        } else if (currentVal == '1') {
                            text = 'Seçilənləri aktiv etmək istədiyinizə əminsiniz?';
                            resultText = 'Aktiv edildi';
                        }else if (currentVal == '5') {
                            text = 'Seçilənləri qalıcı olaraq silmək istədiyinizə əminsiniz?';
                            resultText = 'Silindi';
                        } else if (currentVal == '2') {
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
                                        } else if (currentVal == '2') {
                                            for (let i of checkedArr) {
                                                $('#row' + i).remove();
                                            }
                                        } else if (currentVal == '5') {
                                            for (let i of checkedArr) {
                                                $('#row' + i).remove();
                                            }
                                        }

                                        $('.checkedItem').prop('checked', false);
                                        checkedArr = [];
                                        Swal.fire({
                                            icon: 'success',
                                            title: 'Xəbərdarlıq',
                                            confirmButtonColor: '#163A76',
                                            text: resultText,
                                            confirmButtonText: 'Tamam'
                                        })
                                    }
                                })
                            }
                        })
                    }
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
                let route = '{{route('guest.destroy', ['guest'=>'id'])}}';
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

            $('.changeStatus').click(function () {
                let dataID = $(this).data('id');

                $.ajax({
                    url: '{{route('guest.changeStatus')}}',
                    method: 'POST',
                    data: {
                        id: dataID
                    },
                    async: false,
                })
            });

            function editUser(dataID) {
                let nameEdit = $('#nameEdit');
                let phoneEdit = $('#phoneEdit');
                let statusEdit = $('#statusEdit');
                let isStudentEdit = $('#isStudentEdit');
                let imageEdit = $('#previewImage');
                let directionIdEdit = $('#directionIdEdit');
                let subDirectionIdEdit = $('#subDirectionIdEdit');

                let route = '{{route('guest.edit', ['guest'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('guest.update', ['guest' => 'update'])}}';
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
                        phoneEdit.val(post.phone);
                        directionIdEdit.val(post.sub_direction ? post.sub_direction.direction_id : '');
                        $('#directionIdEdit').trigger('change');
                        subDirectionIdEdit.val(post.sub_direction_id);
                        imageEdit.attr("src", (post.image));

                        if (post.status == 1) {
                            statusEdit.attr('checked', true);
                        } else {
                            statusEdit.attr('checked', false);
                        }

                        if (post.is_student == 1) {
                            isStudentEdit.attr('checked', true);
                        } else {
                            isStudentEdit.attr('checked', false);
                        }
                    }
                });
            }

            let searchParams = new URLSearchParams(window.location.search)
            if (searchParams.has('guest_id')) {
                let dataId = searchParams.get('guest_id');
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

