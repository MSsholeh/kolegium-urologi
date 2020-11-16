<!DOCTYPE html>

<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} - Register</title>
    <meta name="description" content="Login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Barlow:300,400,500,700|Roboto:300,400,500,600,700">

    <!--end::Fonts -->

    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ asset('assets') }}/media/logo/logo-kui.ico" />
    <style>
.stepwizard-step p {
    margin-top: 10px;
}
.stepwizard-row {
    display: table-row;
}
.stepwizard {
    display: table;
    width: 100%;
    position: relative;
}
.stepwizard-step button[disabled] {
    opacity: 1 !important;
    filter: alpha(opacity=100) !important;
}
.stepwizard-row:before {
    top: 14px;
    bottom: 0;
    position: absolute;
    content: " ";
    width: 100%;
    height: 1px;
    background-color: #ccc;
    z-order: 0;
}
.stepwizard-step {
    display: table-cell;
    text-align: center;
    position: relative;
}
.btn-circle {
    width: 30px;
    height: 30px;
    text-align: center;
    padding: 6px 0;
    font-size: 12px;
    line-height: 1.428571429;
    border-radius: 15px;
}
    </style>
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<div class="container">
    <nav class="navbar navbar-expand-sm fixed-top bg-dark navbar-dark">
      <a class="navbar-brand" style="padding-left: 10px;">
        <img src="{{ asset('assets') }}/media/logo/logo-light.png" width="190" height="50" class="d-inline-block align-top" alt="">
      </a>

      <ul class="navbar-nav ml-auto" style="margin-right:20px">
        <li class="nav-item">
          <a class="nav-link" href="https://kolegium-urologi.id">Website</a>
        </li>
        @if(Auth::check())
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" id="navbardrop" data-toggle="dropdown">
            <span class="kt-badge kt-badge--username kt-badge--unified-light kt-badge--lg kt-badge--rounded kt-badge--bold">{{ substr(auth()->user()->name, 0, 1) }}</span>
            Hi, {{ auth()->user()->name }}
          </a>
          <div class="dropdown-menu">
              <form action="{{ route('admin.logout') }}" method="POST">
                @csrf
                <button type="submit" target="_blank" class="dropdown-item">Keluar</button>
               </form>
          </div>
        </li>
        @endif
      </ul>
    </nav>
</div>

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root" style="margin-top:70px">
    <div class="kt-grid kt-grid--hor kt-grid--root  kt-login kt-login--v1" id="kt_login">
        <div class="kt-grid__item kt-grid__item--fluid kt-grid kt-grid--desktop kt-grid--ver-desktop kt-grid--hor-tablet-and-mobile">

            <!--begin::Content-->
            <div class="kt-grid__item kt-grid__item--fluid  kt-grid__item--order-tablet-and-mobile-1  kt-login__wrapper">

                <!--begin::Head-->
                <div class="kt-login__head">
                </div>

                <!--end::Head-->

                <!--begin::Body-->
                <div class="kt-login__body">

                    <!--begin::Signin-->
                    <div class="kt-login__form">

                        <div class="kt-login__title">
                            <h3>Pendaftaran Akun</h3>
                        </div>

                        <div class="stepwizard">
                            <div class="stepwizard-row">
                              <div class="stepwizard-step">
                                <a type="button" class="btn btn-primary btn-circle" style="color:white">1</a>
                                <p>Pendaftaran Akun</p>
                              </div>
                              <div class="stepwizard-step ">
                                <a type="button" class="btn btn-dark btn-circle" style="color:white">2</a>
                                <p>Pengisian Data</p>
                              </div>
                            </div>
                        </div>
                        @if ($errors->any())
                            <br>
                            <div class="alert alert-danger">
                                <ul style="margin-bottom: 0; padding-left: 5px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <div class="alert alert-primary" role="alert">
                            Apabila anda sudah pernah mendaftar, Silahkan login dengan akun yang sudah pernah dibuat sebelumnya !
                        </div>

                        <!--begin::Form-->
                        <form class="kt-form" action="{{ route('web.register') }}" method="POST">
                            @csrf
                            <h4 class="font-weight-bold">Data Akun</h4>
                            <div class="form-group mt-3">
                                <label>Email<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="email" required placeholder="Email" name="email" value="{{ old('email') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Password<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="password" required placeholder="Password" name="password" value="{{ old('password') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Ulangi Password<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="password" required placeholder="Ulangi Password" name="password_confirmation" value="{{ old('password_confirmation') }}" autocomplete="off">
                            </div><br>

                            <h4 class="font-weight-bold mt-4">Informasi Pribadi</h4>

                            <div class="form-group mt-3">
                                <label>Nomor KTP<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Nomor KTP" name="nik" value="{{ old('nik') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>NPA IDI<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="NPA IDI PUSAT" name="npa" value="{{ old('npa') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Asal Universitas<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Asal Universitas" name="university" value="{{ old('university') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Nama Lengkap<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Nama Lengkap" name="name" value="{{ old('name') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Tempat Lahir<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Tempat Lahir" name="pob" value="{{ old('pob') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Tanggal Lahir<span style="color:red">*</span></label>
                                <input class="form-control mt-0 input-datepicker-dob" type="text" required placeholder="Tanggal Lahir" name="dob" value="{{ old('dob') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Alamat<span style="color:red">*</span></label>
                                <textarea class="form-control mt-0" type="text" required placeholder="Alamat" name="address">{{ old('address') }}</textarea>
                            </div>
                            <div class="form-group mt-3">
                                <label>Nomor Handphone<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Nomor Handphone" name="phone" value="{{ old('phone') }}" autocomplete="off">
                            </div><br>
<!--

                            <h4 class="font-weight-bold mt-4">Fakultas Yang Dituju</h4>
                            <div class="form-group mt-3">
                                <label>Fakultas<span style="color:red">*</span></label>
                                <select name="university_id" class="select2-university">
                                </select>
                                <span class="form-text text-muted"></span>
                            </div><br>

                            <h4 class="font-weight-bold mt-4">Periode Seleksi Ujian Masuk</h4>
                            <div class="form-group mt-3">
                                <label>Periode<span style="color:red">*</span></label>
                                <select name="period_id" class="select2-period">
                                </select>
                                <span class="form-text text-muted"></span>
                            </div><br>

-->
<!--
                            <h4 class="font-weight-bold mt-4">Klik checkbox captcha untuk verifikasi di bawah</h4>
                            <div class="form-group mt-3">
                                {!! app('captcha')->display($attributes = [], $options = ['lang'=> 'id']) !!}
                            </div>
-->

                            <!--begin::Action-->
                            <div class="kt-login__actions">
                                <span class="">
                                    Sudah Punya Akun?<br><a class="kt-link" href="{{url('/login')}}">Login</a><br><a class="kt-link" href="{{url('/password/reset')}}">Lupa password</a>
                                </span>
                                <button type="submit" id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Daftar</button>
                            </div>

                            <!--end::Action-->
                        </form>

                        <!--end::Form-->
                    </div>

                    <!--end::Signin-->
                </div>

                <!--end::Body-->
            </div>

            <!--end::Content-->
        </div>
    </div>
</div>

<!-- end:: Page -->

<!-- begin::Global Config(global config for global JS sciprts) -->
<script>
    var KTAppOptions = {
        "colors": {
            "state": {
                "brand": "#5d78ff",
                "dark": "#282a3c",
                "light": "#ffffff",
                "primary": "#5867dd",
                "success": "#34bfa3",
                "info": "#36a3f7",
                "warning": "#ffb822",
                "danger": "#fd3995"
            },
            "base": {
                "label": [
                    "#c5cbe3",
                    "#a1a8c3",
                    "#3d4465",
                    "#3e4466"
                ],
                "shape": [
                    "#f0f3ff",
                    "#d9dffa",
                    "#afb4d4",
                    "#646c9a"
                ]
            }
        }
    };

    let baseUrl = '{{ url('/') }}';
    let homeUrl = '{{ url()->previous() }}';
</script>

<!-- end::Global Config -->

<script src="{{ asset('assets/js/app.js') }}" type="text/javascript"></script>

<script>
    $(function(){
        $('.input-datepicker-dob').datepicker({
            endDate: '10Y',
            format: 'dd MM yyyy',
            language: 'id'
        });

        Daster.select2('.select2-university', {
            url: '{{ route('web.select.university') }}',
            placeholder: 'Pilih Fakultas yang dituju',
            minimumInputLength: 0
        });

        Daster.select2('.select2-period', {
            url: '{{ route('web.select.period') }}',
            placeholder: 'Pilih Periode',
            minimumInputLength: 0
        });

    })
</script>

</body>

<!-- end::Body -->
</html>
