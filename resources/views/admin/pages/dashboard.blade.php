@extends('admin.index')

@section('title')
    Admin panel
@endsection

@section('css')
@endsection

@section('content')
    <div class="content-body mb-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xl-4 col-md-12 mb-1">
                    <div class="card border-left-primary shadow">
                        <div class="card-body">
                            <div class="row align-items-center">
                                <div class="col mr-2">
                                    <a href="{{route("dashboard")}}" class="font-weight-normal text-purple"
                                       style="text-align:center; font-size: 20px;">
                                        Ana səhifə</a>
                                </div>
                                <div class="col-auto">
                                    <i class="flaticon-381-home-2 text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('js')
@endsection
