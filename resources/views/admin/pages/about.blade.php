@extends('admin.index')
@section('title')
    Kurs haqqında məlumat | Admin panel
@endsection
@section('css')
@endsection
@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <form action="{{route('about.update',['about' => 1])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Kurs haqqında məlumat</h4>
                            </div>
                            <div class="card-body row">
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
                                                 src="{{asset($setting->image)}}"
                                                 style="width: 100%;" alt="">
                                        </div>
                                    </div>
                                    <div class="col-8">
                                        <div class="form-group col-12">
                                            <label for="about">Məzmun</label>
                                            <textarea name="about" class="editor"
                                                      id="editor" cols="30"
                                                      rows="10">{{$setting->about}}</textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button type="submit" name="submit" value="submit"
                                            class="btn btn-primary btn-block">Yadda
                                        saxla
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script>
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
        $('textarea.editor').each(function () {
            CKEDITOR.replace('editor', {
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form'
            })
        })
    </script>
@endsection
