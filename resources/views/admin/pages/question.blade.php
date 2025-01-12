@extends('admin.index')

@section('title')
    Suallar | Admin panel
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
                            <h4 class="card-title">Suallar</h4>
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
                                            name="exam_id">
                                        <option value="" disabled selected>İmtahan</option>
                                        @if(!empty($exams[0]))
                                            @foreach($exams as $exam)
                                                <option
                                                    value="{{$exam->id}}" {{isset($_GET['exam_id']) && $_GET['exam_id'] == $exam->id ? 'selected' : ''}}>
                                                    {{$exam->name. ' - ' .$exam->subject}}
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
                                        <a href="{{route('question.index')}}"
                                           class="btn btn-primary clear-btn">
                                            <i class="fas fa-list"></i>
                                        </a>
                                    </div>
                                @else
                                    <div class="col-1">
                                        <a href="{{route('question.index', ['is_deleted'=>1])}}"
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
                                        <th>İmtahan</th>
                                        <th>Sual</th>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th>D</th>
                                        <th>E</th>
                                        <th>Düzgün cavab</th>
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
                                            <td>
                                                @if(!empty($postItem->exam))
                                                    <a href="{{route('exam.index', ['exam_id'=>$postItem->exam_id])}}">{{$postItem->exam->name. ' - ' .$postItem->exam->subject}}</a>
                                                @endif
                                            </td>
                                            <td>
                                                @if($postItem->title_type=='text')
                                                    {!! $postItem->title !!}
                                                @else
                                                    <img class="d-block m-auto" style="width: 100px"
                                                         src="{{asset($postItem->title)}}" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                @if($postItem->variant_type=='text')
                                                    {!! $postItem->A !!}
                                                @else
                                                    <img class="d-block m-auto" style="width: 100px"
                                                         src="{{asset($postItem->A)}}" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                @if($postItem->variant_type=='text')
                                                    {!! $postItem->B !!}
                                                @else
                                                    <img class="d-block m-auto" style="width: 100px"
                                                         src="{{asset($postItem->B)}}" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                @if($postItem->variant_type=='text')
                                                    {!! $postItem->C !!}
                                                @else
                                                    <img class="d-block m-auto" style="width: 100px"
                                                         src="{{asset($postItem->C)}}" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                @if($postItem->variant_type=='text')
                                                    {!! $postItem->D !!}
                                                @else
                                                    <img class="d-block m-auto" style="width: 100px"
                                                         src="{{asset($postItem->D)}}" alt="">
                                                @endif
                                            </td>
                                            <td>
                                                @if($postItem->variant_type=='text')
                                                    {!! $postItem->E !!}
                                                @else
                                                    <img class="d-block m-auto" style="width: 100px"
                                                         src="{{asset($postItem->E)}}" alt="">
                                                @endif
                                            </td>
                                            <td>{{$postItem->correct}}</td>
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
        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLongTitle">Əlavə
                        et</h5>
                    <button type="button" class="close" data-dismiss="modal"
                            aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="formCreate" action="{{route('question.store')}}" method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body pb-0 pt-2 row">
                        <div class="col-4">
                            <div class="form-group">
                                <label for="exam">İmtahan</label>
                                <select name="exam_id" class="form-control search-select" required id="exam">
                                    @if(!empty($exams[0]))
                                        @foreach($exams as $exam)
                                            <option
                                                value="{{$exam->id}}">{{$exam->name. ' - ' .$exam->subject}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title_type">Sual tipi</label>
                                <select name="title_type" class="form-control title-type" required id="title_type">
                                    <option value="text">Text</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="variant_type">Variant tipi</label>
                                <select name="variant_type" class="form-control variant-type" required
                                        id="variant_type">
                                    <option value="text">Text</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="correct">Düzgün cavab</label>
                                <select name="correct" class="form-control" required id="correct">
                                    <option value="">Seç</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
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
                        <div class="col-8">
                            <div class="form-group text-section">
                                <label for="title">Sual</label>
                                <textarea name="title" class="editor"
                                          id="editor" required cols="30"
                                          rows="10">{{old('title')}}</textarea>
                            </div>
                            <div class="form-group img-section image-section d-none">
                                <label for="uploadImage-create">Sual</label>
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
                            <div class="form-group text-section-variant">
                                <label for="A">A</label>
                                <textarea name="A" class="editor1"
                                          id="editor1" cols="30"
                                          rows="10">{{old('A')}}</textarea>
                            </div>
                            <div class="form-group text-section-variant">
                                <label for="B">B</label>
                                <textarea name="B" class="editor2"
                                          id="editor2" cols="30"
                                          rows="10">{{old('B')}}</textarea>
                            </div>
                            <div class="form-group img-section image-section-variant d-none">
                                <label for="uploadImage-create1">A</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage-create1" type="file"
                                           name="image1" class="form-control-file"
                                           onchange="PreviewImageCreate1();">
                                    <div class="delete-img1 c-pointer" onclick="deleteImageCreate1();">
                                        <i class="fas fa-trash"></i></div>
                                </div>
                                <img class="preview-img1" id='previewImage-create1'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group img-section image-section-variant d-none">
                                <label for="uploadImage-create2">B</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage-create2" type="file"
                                           name="image2" class="form-control-file"
                                           onchange="PreviewImageCreate2();">
                                    <div class="delete-img2 c-pointer" onclick="deleteImageCreate2();">
                                        <i class="fas fa-trash"></i></div>
                                </div>
                                <img class="preview-img2" id='previewImage-create2'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group text-section-variant">
                                <label for="C">C</label>
                                <textarea name="C" class="editor3"
                                          id="editor3" cols="30"
                                          rows="10">{{old('C')}}</textarea>
                            </div>
                            <div class="form-group text-section-variant">
                                <label for="D">D</label>
                                <textarea name="D" class="editor4"
                                          id="editor4" cols="30"
                                          rows="10">{{old('D')}}</textarea>
                            </div>
                            <div class="form-group text-section-variant">
                                <label for="E">E</label>
                                <textarea name="E" class="editor5"
                                          id="editor5" cols="30"
                                          rows="10">{{old('E')}}</textarea>
                            </div>
                            <div class="form-group img-section image-section-variant d-none">
                                <label for="uploadImage-create3">C</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage-create3" type="file"
                                           name="image3" class="form-control-file"
                                           onchange="PreviewImageCreate3();">
                                    <div class="delete-img3 c-pointer" onclick="deleteImageCreate3();">
                                        <i class="fas fa-trash"></i></div>
                                </div>
                                <img class="preview-img3" id='previewImage-create3'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group img-section image-section-variant d-none">
                                <label for="uploadImage-create4">D</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage-create4" type="file"
                                           name="image4" class="form-control-file"
                                           onchange="PreviewImageCreate4();">
                                    <div class="delete-img4 c-pointer" onclick="deleteImageCreate4();">
                                        <i class="fas fa-trash"></i></div>
                                </div>
                                <img class="preview-img4" id='previewImage-create4'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group img-section image-section-variant d-none">
                                <label for="uploadImage-create5">E</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage-create5" type="file"
                                           name="image5" class="form-control-file"
                                           onchange="PreviewImageCreate5();">
                                    <div class="delete-img5 c-pointer" onclick="deleteImageCreate5();">
                                        <i class="fas fa-trash"></i></div>
                                </div>
                                <img class="preview-img5" id='previewImage-create5'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
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
        <div class="modal-dialog modal-xl" role="document">
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
                        <div class="col-4">
                            <div class="form-group">
                                <label for="examEdit">İmtahan</label>
                                <select name="exam_id" class="form-control search-select" required id="examEdit">
                                    @if(!empty($exams[0]))
                                        @foreach($exams as $exam)
                                            <option
                                                value="{{$exam->id}}">{{$exam->name. ' - ' .$exam->subject}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="title_typeEdit">Sual tipi</label>
                                <select name="title_type" class="form-control title-typeEdit" required
                                        id="title_typeEdit">
                                    <option value="text">Text</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="variant_typeEdit">Variant tipi</label>
                                <select name="variant_type" class="form-control variant-typeEdit" required
                                        id="variant_typeEdit">
                                    <option value="text">Text</option>
                                    <option value="image">Image</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="correctEdit">Düzgün cavab</label>
                                <select name="correct" class="form-control" required id="correctEdit">
                                    <option value="">Seç</option>
                                    <option value="A">A</option>
                                    <option value="B">B</option>
                                    <option value="C">C</option>
                                    <option value="D">D</option>
                                    <option value="E">E</option>
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
                        <div class="col-8">
                            <div class="form-group text-sectionEdit">
                                <label for="titleEdit">Sual</label>
                                <textarea name="title" class="editorEdit"
                                          id="editorEdit" required cols="30"
                                          rows="10"></textarea>
                            </div>
                            <div class="form-group img-section image-sectionEdit d-none">
                                <label for="uploadImage">Sual</label>
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
                            <div class="form-group text-section-variantEdit">
                                <label for="AEdit">A</label>
                                <textarea name="A" class="editorEdit1 editor-textarea"
                                          id="editorEdit1" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <div class="form-group text-section-variantEdit">
                                <label for="BEdit">B</label>
                                <textarea name="B" class="editorEdit2"
                                          id="editorEdit2" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <div class="form-group img-section image-section-variantEdit d-none">
                                <label for="uploadImage1">A</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage1" type="file"
                                           name="image1" value="" class="form-control-file"
                                           onchange="PreviewImage1();">
                                    <div class="delete-img1 c-pointer" onclick="deleteImage1();">
                                        <i class="fas fa-trash"></i></div>
                                    <input id="hiddenInput1" type="hidden" name="hidden1" value="1">
                                </div>

                                <img class="preview-img1" id='previewImage1'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group img-section image-section-variantEdit d-none">
                                <label for="uploadImage2">B</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage2" type="file"
                                           name="image2" value="" class="form-control-file"
                                           onchange="PreviewImage2();">
                                    <div class="delete-img2 c-pointer" onclick="deleteImage2();">
                                        <i class="fas fa-trash"></i></div>
                                    <input id="hiddenInput2" type="hidden" name="hidden2" value="1">
                                </div>

                                <img class="preview-img2" id='previewImage2'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group text-section-variantEdit">
                                <label for="CEdit">C</label>
                                <textarea name="C" class="editorEdit3"
                                          id="editorEdit3" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <div class="form-group text-section-variantEdit">
                                <label for="DEdit">D</label>
                                <textarea name="D" class="editorEdit4"
                                          id="editorEdit4" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <div class="form-group text-section-variantEdit">
                                <label for="EEdit">E</label>
                                <textarea name="E" class="editorEdit5"
                                          id="editorEdit5" cols="30"
                                          rows="10"></textarea>
                            </div>
                            <div class="form-group img-section image-section-variantEdit d-none">
                                <label for="uploadImage3">C</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage3" type="file"
                                           name="image3" value="" class="form-control-file"
                                           onchange="PreviewImage3();">
                                    <div class="delete-img3 c-pointer" onclick="deleteImage3();">
                                        <i class="fas fa-trash"></i></div>
                                    <input id="hiddenInput3" type="hidden" name="hidden3" value="1">
                                </div>

                                <img class="preview-img3" id='previewImage3'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group img-section image-section-variantEdit d-none">
                                <label for="uploadImage4">D</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage4" type="file"
                                           name="image4" value="" class="form-control-file"
                                           onchange="PreviewImage4();">
                                    <div class="delete-img4 c-pointer" onclick="deleteImage4();">
                                        <i class="fas fa-trash"></i></div>
                                    <input id="hiddenInput4" type="hidden" name="hidden4" value="1">
                                </div>

                                <img class="preview-img4" id='previewImage4'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
                            </div>
                            <div class="form-group img-section image-section-variantEdit d-none">
                                <label for="uploadImage5">E</label>
                                <div class="img-input d-flex justify-content-between mb-2">
                                    <input id="uploadImage5" type="file"
                                           name="image5" value="" class="form-control-file"
                                           onchange="PreviewImage5();">
                                    <div class="delete-img5 c-pointer" onclick="deleteImage5();">
                                        <i class="fas fa-trash"></i></div>
                                    <input id="hiddenInput5" type="hidden" name="hidden5" value="1">
                                </div>

                                <img class="preview-img5" id='previewImage5'
                                     src="{{asset('admin/images/noPhoto.png')}}"
                                     style="width: 100%;" alt="">
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

        function PreviewImageCreate1() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage-create1").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage-create1").src = oFREvent.target.result;
            };
        };

        function deleteImageCreate1() {
            document.getElementById("previewImage-create1").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById("uploadImage-create1").value = '';
        }

        function PreviewImage1() {
            document.getElementById('hiddenInput1').value = '1';
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage1").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage1").src = oFREvent.target.result;
            };
        };

        function deleteImage1() {
            document.getElementById("previewImage1").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById('hiddenInput1').value = '0';
        }

        function PreviewImageCreate2() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage-create2").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage-create2").src = oFREvent.target.result;
            };
        };

        function deleteImageCreate2() {
            document.getElementById("previewImage-create2").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById("uploadImage-create2").value = '';
        }

        function PreviewImage2() {
            document.getElementById('hiddenInput2').value = '1';
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage2").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage2").src = oFREvent.target.result;
            };
        };

        function deleteImage2() {
            document.getElementById("previewImage2").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById('hiddenInput2').value = '0';
        }

        function PreviewImageCreate3() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage-create3").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage-create3").src = oFREvent.target.result;
            };
        };

        function deleteImageCreate3() {
            document.getElementById("previewImage-create3").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById("uploadImage-create3").value = '';
        }

        function PreviewImage3() {
            document.getElementById('hiddenInput3').value = '1';
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage3").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage3").src = oFREvent.target.result;
            };
        };

        function deleteImage3() {
            document.getElementById("previewImage3").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById('hiddenInput3').value = '0';
        }

        function PreviewImageCreate4() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage-create4").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage-create4").src = oFREvent.target.result;
            };
        };

        function deleteImageCreate4() {
            document.getElementById("previewImage-create4").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById("uploadImage-create4").value = '';
        }

        function PreviewImage4() {
            document.getElementById('hiddenInput4').value = '1';
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage4").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage4").src = oFREvent.target.result;
            };
        };

        function deleteImage4() {
            document.getElementById("previewImage4").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById('hiddenInput4').value = '0';
        }

        function PreviewImageCreate5() {
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage-create5").files[0]);

            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage-create5").src = oFREvent.target.result;
            };
        };

        function deleteImageCreate5() {
            document.getElementById("previewImage-create5").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById("uploadImage-create5").value = '';
        }

        function PreviewImage5() {
            document.getElementById('hiddenInput5').value = '1';
            var oFReader = new FileReader();
            oFReader.readAsDataURL(document.getElementById("uploadImage5").files[0]);
            oFReader.onload = function (oFREvent) {
                document.getElementById("previewImage5").src = oFREvent.target.result;
            };
        };

        function deleteImage5() {
            document.getElementById("previewImage5").src = '{{asset('admin/images/noPhoto.png')}}';
            document.getElementById('hiddenInput5').value = '0';
        }

        $('textarea.editor').each(function () {
            CKEDITOR.replace('editor', {
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })

        })

        $('textarea.editor1').each(function () {
            CKEDITOR.replace('editor1', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editor2').each(function () {
            CKEDITOR.replace('editor2', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editor3').each(function () {
            CKEDITOR.replace('editor3', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editor4').each(function () {
            CKEDITOR.replace('editor4', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editor5').each(function () {
            CKEDITOR.replace('editor5', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editorEdit').each(function () {
            CKEDITOR.replace('editorEdit', {
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editorEdit1').each(function () {
            CKEDITOR.replace('editorEdit1', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editorEdit2').each(function () {
            CKEDITOR.replace('editorEdit2', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editorEdit3').each(function () {
            CKEDITOR.replace('editorEdit3', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editorEdit4').each(function () {
            CKEDITOR.replace('editorEdit4', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

        $('textarea.editorEdit5').each(function () {
            CKEDITOR.replace('editorEdit5', {
                height: 100,
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form',
                toolbar: [
                    {
                        name: 'basicstyles',
                        items: ['Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript']
                    },
                    {name: 'styles', items: ['FontSize', 'Font']}
                ],
                font_names: 'Poppins/Poppins;Arial/Arial, Helvetica, sans-serif;Comic Sans MS/Comic Sans MS;Tahoma/Tahoma, Geneva, sans-serif;Times New Roman/Times New Roman, Times, serif;Verdana/Verdana, Geneva, sans-serif',
                font_defaultLabel: 'Poppins',
                fontSize_defaultLabel: '16px',
                fontSize_sizes: '12/12px;14/14px;16/16px;18/18px;20/20px',
            })
        })

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
                    let route = '{{route('question.checked')}}';
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
                    url: '{{route('question.changeStatus')}}',
                    method: 'POST',
                    data: {
                        id: dataID
                    },
                    async: false,
                })
            });

            $('.deleteItem').click(function () {
                let dataID = $(this).data('id');
                let route = '{{route('question.destroy', ['question'=>'id'])}}';
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

            $('.title-type').on('change', function () {
                if ($(this).find(":selected").val() == 'text') {
                    $('.image-section input').prop('required', false)
                    $('.image-section').addClass('d-none');
                    $('.text-section').removeClass('d-none');
                } else {
                    $('.image-section input').prop('required', true)
                    $('.image-section').removeClass('d-none');
                    $('.text-section').addClass('d-none');
                }
            })

            $('.variant-type').on('change', function () {
                if ($(this).find(":selected").val() == 'text') {
                    $('.image-section-variant').addClass('d-none');
                    $('.text-section-variant').removeClass('d-none');
                } else {
                    $('.image-section-variant').removeClass('d-none');
                    $('.text-section-variant').addClass('d-none');
                }
            })

            $('.title-typeEdit').on('change', function () {
                if ($(this).find(":selected").val() == 'text') {
                    $('.image-sectionEdit input').prop('required', false)
                    $('.image-sectionEdit').addClass('d-none');
                    $('.text-sectionEdit').removeClass('d-none');
                } else {
                    $('.image-sectionEdit input').prop('required', true)
                    $('.image-sectionEdit').removeClass('d-none');
                    $('.text-sectionEdit').addClass('d-none');
                }
            })

            $('.variant-typeEdit').on('change', function () {
                if ($(this).find(":selected").val() == 'text') {
                    $('.image-section-variantEdit').addClass('d-none');
                    $('.text-section-variantEdit').removeClass('d-none');
                } else {
                    $('.image-section-variantEdit').removeClass('d-none');
                    $('.text-section-variantEdit').addClass('d-none');
                }
            })

            function editUser(dataID) {
                let examEdit = $('#examEdit');
                let title_typeEdit = $('#title_typeEdit');
                let variant_typeEdit = $('#variant_typeEdit');
                let correctEdit = $('#correctEdit');
                let statusEdit = $('#statusEdit');
                let imageEdit = $('#previewImage');
                let imageEdit1 = $('#previewImage1');
                let imageEdit2 = $('#previewImage2');
                let imageEdit3 = $('#previewImage3');
                let imageEdit4 = $('#previewImage4');
                let imageEdit5 = $('#previewImage5');

                imageEdit.attr("src", ('/postImage/noPhoto.png'));
                imageEdit1.attr("src", ('/postImage/noPhoto.png'));
                imageEdit2.attr("src", ('/postImage/noPhoto.png'));
                imageEdit3.attr("src", ('/postImage/noPhoto.png'));
                imageEdit4.attr("src", ('/postImage/noPhoto.png'));
                imageEdit5.attr("src", ('/postImage/noPhoto.png'));

                let route = '{{route('question.edit', ['question'=>'edit'])}}';
                route = route.replace('edit', dataID);
                let routeUpdate = '{{route('question.update', ['question' => 'update'])}}';
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

                        examEdit.val(post.exam_id);
                        title_typeEdit.val(post.title_type);
                        variant_typeEdit.val(post.variant_type);
                        correctEdit.val(post.correct);

                        if (post.title_type == 'text') {
                            $('.text-sectionEdit').removeClass('d-none');
                            $('.image-sectionEdit').addClass('d-none');
                            CKEDITOR.instances['editorEdit'].setData(post.title);
                        } else {
                            $('.text-sectionEdit').addClass('d-none');
                            $('.image-sectionEdit').removeClass('d-none');
                            imageEdit.attr("src", ('/' + post.title));
                        }

                        if (post.variant_type == 'text') {
                            $('.text-section-variantEdit').removeClass('d-none');
                            $('.image-section-variantEdit').addClass('d-none');
                            CKEDITOR.instances['editorEdit1'].setData(post.A);
                            CKEDITOR.instances['editorEdit2'].setData(post.B);
                            CKEDITOR.instances['editorEdit3'].setData(post.C);
                            CKEDITOR.instances['editorEdit4'].setData(post.D);
                            CKEDITOR.instances['editorEdit5'].setData(post.E);
                        } else {
                            $('.text-section-variantEdit').addClass('d-none');
                            $('.image-section-variantEdit').removeClass('d-none');
                            imageEdit1.attr("src", ('/' + (post.A ? post.A : 'postImage/noPhoto.png')));
                            imageEdit2.attr("src", ('/' + (post.B ? post.B : 'postImage/noPhoto.png')));
                            imageEdit3.attr("src", ('/' + (post.C ? post.C : 'postImage/noPhoto.png')));
                            imageEdit4.attr("src", ('/' + (post.D ? post.D : 'postImage/noPhoto.png')));
                            imageEdit5.attr("src", ('/' + (post.E ? post.E : 'postImage/noPhoto.png')));
                        }

                        if (post.status == 1) {
                            statusEdit.attr('checked', true);
                        } else {
                            statusEdit.attr('checked', false);
                        }
                    }
                })
            }

            let searchParams = new URLSearchParams(window.location.search)
            if (searchParams.has('question_id')) {
                let dataId = searchParams.get('question_id');
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
