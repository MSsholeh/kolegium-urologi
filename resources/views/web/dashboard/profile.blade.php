@extends('web.layout.content')

@section('content')

    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ $title }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    {!! Daster::breadcrumb($breadcrumbs) !!}
                </div>
            </div>
            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">
                    <span class="btn kt-subheader__btn-secondary">{{ Daster::tanggal(now(), 2) }}</span>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content Head -->

    <div class="kt-container  kt-grid__item kt-grid__item--fluid">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Informasi Akun
                            </h3>
                        </div>
                    </div>
                    <form class="kt-form kt-form--label-right">
                        <div class="kt-portlet__body">
                            <div class="kt-section kt-section--first">
                                <div class="kt-section__body">
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Email</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Nomor KTP</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->nik }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Asal FK</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->university->name }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Tahun Ajaran Masuk</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->year }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Semester</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->semester }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Tahap Kompetensi</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ config('constant.tahap_kompetensi.'.$user->competency) }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Nama Lengkap</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->name }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Tempat Lahir</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->pob }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Tanggal Lahir</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ Daster::tanggal($user->dob) }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Alamat</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->address }}</p>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-xl-3 col-lg-3 col-form-label">Nomor Handphone</label>
                                        <div class="col-lg-9 col-xl-6">
                                            <p class="form-control-plaintext">: {{ $user->phone }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection
