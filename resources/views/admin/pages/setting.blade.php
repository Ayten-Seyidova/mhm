@extends('admin.index')

@section('title')
    Tənzimləmələr | Admin panel
@endsection

@section('css')
@endsection

@section('content')
    <div class="content-body">
        <div class="container-fluid">
            <form action="{{route('settings.update',['setting' => 1])}}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-xl-12 col-lg-12">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">Tənzimləmələr</h4>
                            </div>
                            <div class="card-body row">
                                <div class="form-group col-4">
                                    <label for="facebook">Facebook</label>
                                    <input type="text" name="facebook" class="form-control"
                                           value="{{$setting->facebook}}" maxlength="190"
                                           id="facebook">
                                </div>
                                <div class="form-group col-4">
                                    <label for="instagram">Instagram</label>
                                    <input type="text" name="instagram" class="form-control"
                                           value="{{$setting->instagram}}" maxlength="190"
                                           id="instagram">
                                </div>
                                <div class="form-group col-4">
                                    <label for="customer_service">Müştəri xidmətləri</label>
                                    <input type="text" name="customer_service" class="form-control"
                                           value="{{$setting->customer_service}}" maxlength="190"
                                           id="customer_service">
                                </div>
                                <div class="form-group col-12">
                                    <label for="security">Gizlilik</label>
                                    <textarea name="security" class="editor"
                                              id="editor" cols="30"
                                              rows="10">{{$setting->security}}</textarea>
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
        $('textarea.editor').each(function () {
            CKEDITOR.replace('editor', {
                filebrowserUploadUrl: "{{route('editor.upload',['_token'=>csrf_token()])}}",
                filebrowserUploadMethod: 'form'
            })
        })
    </script>
@endsection
