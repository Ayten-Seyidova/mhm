@extends('admin.index')

@section('title')
    Videolar | Admin panel
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
                            <h4 class="card-title">Videolar</h4>
                            <button type="button" class="btn btn-primary btn-rounded mr-2" data-toggle="modal"
                                    data-target="#createModal"><span class="btn-icon-left text-primary"><i
                                        class="fa fa-plus color-info"></i></span>
                                Əlavə et
                            </button>
                        </div>
                        <div class="card-body">
                            <form method="get" id="searchForm" class="row justify-content-center" action="">
                                <input type="hidden" name="is_deleted" value="{{isset($_GET['is_deleted']) ? $_GET['is_deleted'] : ''}}">
                                <div class="col-3">
                                    <select class="form-control search-select" onchange="form.submit()"
                                            name="subject_id">
                                        <option value="" disabled selected>Mövzu</option>
                                        @if(!empty($subjects[0]))
                                            @foreach($subjects as $subject)
                                                <option
                                                    value="{{$subject->id}}" {{isset($_GET['subject_id']) && $_GET['subject_id'] == $subject->id ? 'selected' : ''}}>
                                                    {{$subject->name}}
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
                                        <a href="{{route('video.index')}}"
                                           class="btn btn-primary clear-btn">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-1">
                                        <a href="{{route('video.index', ['is_deleted'=>1])}}"
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
                                        <th>Şəkil</th>
                                        <th>Video adı</th>
                                        <th>Video kurs</th>
                                        <th>Mövzu</th>
                                        <th>Link</th>
                                        <th>Yaranma tarixi</th>
                                        <th>Əməliyyatlar</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($posts as $key => $postItem)
                                        <tr id="row{{$postItem->id}}" class="text-center">
                                            <td class="text-center"><input value="{{$postItem->id}}" class="checkedItem"
                                                                           name="checked" type="checkbox"></td>
                                            <td>
                                                <img class="d-block m-auto" style="width: 100px"
                                                     src="{{asset($postItem->image)}}" alt=""></td>
                                            <td>{{$postItem->name}}</td>
                                            <td>
                                                @if(!empty($postItem->subject))
                                                    <a href="{{route('video-course.index', ['video_course_id'=>$postItem->subject->video_course_id])}}">{{$postItem->subject->course->name}}</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if(!empty($postItem->subject))
                                                    <a href="{{route('subject.index', ['subject_id'=>$postItem->subject_id])}}">{{$postItem->subject->name}}</a>
                                                @endif
                                            </td>
                                            <td>
                                                <button class="btn btn-secondary btn-sm" data-toggle="modal"
                                                        data-target="#videoModal{{$key}}">Videoya bax
                                                </button>
                                                <div class="modal fade" id="videoModal{{$key}}" tabindex="-1"
                                                     role="dialog"
                                                     aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-md" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-body p-2">
                                                                <iframe width="480" height="315"
                                                                        src="https://www.youtube.com/embed/{{$postItem->link}}"
                                                                        title="YouTube video player" frameborder="0"
                                                                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                                                        referrerpolicy="strict-origin-when-cross-origin"
                                                                        allowfullscreen></iframe>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{$postItem->created_at ? $postItem->created_at->translatedFormat('d.m.Y H:i') : ''}}</td>
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
                                        @if(isset($_GET['is_deleted']) && $_GET['is_deleted'] == 1)
                                            <button class="checkedBtn btn-primary btn">SEÇİLƏNLƏRİ BƏRPA ET
                                            </button>
                                        @else
                                            <button class="checkedBtn btn-primary btn">SEÇİLƏNLƏRİ SİL
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
                <form id="formCreate" action="{{route('video.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pb-0 pt-2 row">
                        <div class="col-5">
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
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group">
                                <label for="name">Video adı</label>
                                <input class="form-control" value="{{old('name')}}"
                                       type="text" required maxlength="500"
                                       name="name" id="name"/>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <label for="link">Link</label>
                                <input class="form-control" value="{{old('link')}}"
                                       type="text" required maxlength="190"
                                       name="link" id="link"/>
                            </div>
                            <div class="form-group">
                                <label for="course">Video kurs</label>
                                <select name="course_id" class="form-control" required id="course">
                                    @if(!empty($courses[0]))
                                        @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{$course->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subject">Mövzu</label>
                                <select name="subject_id" class="form-control" required id="subject">
                                    @if(!empty($subjects[0]))
                                        @foreach($subjects as $subject)
                                            <option value="{{$subject->id}}"
                                                    data-category-id="{{$subject->video_course_id}}">{{$subject->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
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
                    <div class="modal-body pb-0 pt-2 row">
                        <div class="col-5">
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
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group">
                                <label for="nameEdit">Video adı</label>
                                <input class="form-control"
                                       type="text" required maxlength="500"
                                       name="name" id="nameEdit"/>
                            </div>
                        </div>
                        <div class="col-7">
                            <div class="form-group">
                                <label for="linkEdit">Link</label>
                                <input class="form-control"
                                       type="text" required maxlength="190"
                                       name="link" id="linkEdit"/>
                            </div>
                            <div class="form-group">
                                <label for="courseEdit">Video kurs</label>
                                <select name="course_id" class="form-control" required id="courseEdit">
                                    @if(!empty($courses[0]))
                                        @foreach($courses as $course)
                                            <option value="{{$course->id}}">{{$course->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="subjectEdit">Mövzu</label>
                                <select name="subject_id" class="form-control" required id="subjectEdit">
                                    @if(!empty($subjects[0]))
                                        @foreach($subjects as $subject)
                                            <option value="{{$subject->id}}"
                                                    data-category-id="{{$subject->video_course_id}}">{{$subject->name}}</option>
                                        @endforeach
                                    @endif
                                </select>
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

        function PreviewImageCreate() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage-create").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage-create").src = oFREvent.target.result;
            };
        };

        function deleteImageCreate() {
            document.getElementById("previewImage-create").src = '{{asset('admin/images/noPhoto.png')}}';
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
            document.getElementById("previewImage").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById('hiddenInput').value = '0';
        }

        $(function () {

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var $categorySelect = $('#course');
            var $subcategorySelect = $('#subject');
            var $subcategoryOptions = $subcategorySelect.find('option');

            $categorySelect.on('change', function () {
                var selectedCategoryId = $(this).val();

                $subcategoryOptions.each(function () {
                    var $option = $(this);
                    if ($option.data('category-id') == selectedCategoryId || !$option.data('category-id')) {
                        $option.show().prop('disabled', false);
                    } else {
                        $option.hide().prop('disabled', true);
                    }
                });

                $subcategorySelect.val('');
            });

            $categorySelect.trigger('change');

            var $categorySelect1 = $('#courseEdit');
            var $subcategorySelect1 = $('#subjectEdit');
            var $subcategoryOptions1 = $subcategorySelect1.find('option');

            $categorySelect1.on('change', function () {
                var selectedCategoryId1 = $(this).val();

                $subcategoryOptions1.each(function () {
                    var $option1 = $(this);
                    if ($option1.data('category-id') == selectedCategoryId1 || !$option1.data('category-id')) {
                        $option1.show().prop('disabled', false);
                        $option1.addClass('d-block');
                    } else {
                        $option1.hide().prop('disabled', true);
                        $option1.addClass('d-none');
                    }
                });

                $subcategorySelect1.val('');
            });

            $categorySelect1.trigger('change');

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
                    let route = '{{route('video.checked')}}';

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
                let route = '{{route('video.destroy', ['video'=>'delete'])}}';
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
                let nameEdit = $('#nameEdit');
                let linkEdit = $('#linkEdit');
                let subjectEdit = $('#subjectEdit');
                let imageEdit = $('#previewImage');
                let courseEdit = $('#courseEdit');

                let route = '{{route('video.edit', ['video'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('video.update', ['video' => 'update'])}}';
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
                        linkEdit.val(post.link);
                        subjectEdit.val(post.subject_id);
                        courseEdit.val(post.subject.video_course_id);
                        imageEdit.attr("src", ('/' + post.image));

                        var $subcategorySelect2 = $('#subjectEdit');
                        var $subcategoryOptions2 = $subcategorySelect2.find('option');

                        var selectedCategoryId2 = post.subject.video_course_id;

                        $subcategoryOptions2.each(function () {
                            var $option2 = $(this);
                            if ($option2.data('category-id') == selectedCategoryId2 || !$option2.data('category-id')) {
                                $option2.show().prop('disabled', false);
                            } else {
                                $option2.hide().prop('disabled', true);
                            }
                        });
                    }
                })
            }

            let searchParams = new URLSearchParams(window.location.search)
            if (searchParams.has('video_id')) {
                let dataId = searchParams.get('video_id');
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
