<!DOCTYPE html>

<!--
    DASTER
    Ali Alghozali - alghoza.li
-->
<html lang="en">

<!-- begin::Head -->
<head>
    <meta charset="utf-8" />
    <title>{{ config('app.name') }} - Login</title>
    <meta name="description" content="Login">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!--begin::Fonts -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Barlow:300,400,500,700|Roboto:300,400,500,600,700">

    <!--end::Fonts -->

    <link href="{{ asset('assets/css/app.css') }}" rel="stylesheet" type="text/css" />

    <link rel="shortcut icon" href="{{ asset('assets') }}/media/logo/logo-kui.ico" />
</head>

<!-- end::Head -->

<!-- begin::Body -->
<body class="kt-quick-panel--right kt-demo-panel--right kt-offcanvas-panel--right kt-header--fixed kt-header-mobile--fixed kt-subheader--enabled kt-subheader--fixed kt-subheader--solid kt-aside--enabled kt-aside--fixed kt-page--loading">

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root">
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

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul style="margin-bottom: 0; padding-left: 5px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!--begin::Form-->
                        <form class="kt-form" action="{{ route('web.register') }}" method="POST">
                            @csrf
                            <h4 class="font-weight-bold">Akun</h4>
                            <div class="form-group mt-3">
                                <label>Email</label>
                                <input class="form-control mt-0" type="email" required placeholder="Email" name="email" value="{{ old('email') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Password</label>
                                <input class="form-control mt-0" type="password" required placeholder="Password" name="password" value="{{ old('password') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Ulangi Password</label>
                                <input class="form-control mt-0" type="password" required placeholder="Ulangi Password" name="password_confirmation" value="{{ old('password_confirmation') }}" autocomplete="off">
                            </div>

                            <h4 class="font-weight-bold mt-4">Profil Peserta Didik</h4>

                            <div class="form-group mt-3">
                                <label>Nomor KTP</label>
                                <input class="form-control mt-0" type="text" required placeholder="Nomor KTP" name="nik" value="{{ old('nik') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>NPA IDI</label>
                                <input class="form-control mt-0" type="text" required placeholder="NPA IDI" name="npa" value="{{ old('npa') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Asal Universitas</label>
                                <input class="form-control mt-0" type="text" required placeholder="Asal Universitas" name="university" value="{{ old('university') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Nama Lengkap</label>
                                <input class="form-control mt-0" type="text" required placeholder="Nama Lengkap" name="name" value="{{ old('name') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Tempat Lahir</label>
                                <input class="form-control mt-0" type="text" required placeholder="Tempat Lahir" name="pob" value="{{ old('pob') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Tanggal Lahir</label>
                                <input class="form-control mt-0 input-datepicker-dob" type="text" required placeholder="Tanggal Lahir" name="dob" value="{{ old('dob') }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Alamat</label>
                                <textarea class="form-control mt-0" type="text" required placeholder="Alamat" name="address">{{ old('address') }}</textarea>
                            </div>
                            <div class="form-group mt-3">
                                <label>Nomor Handphone</label>
                                <input class="form-control mt-0" type="text" required placeholder="Nomor Handphone" name="phone" value="{{ old('phone') }}" autocomplete="off">
                            </div>

                            <!--begin::Action-->
                            <div class="kt-login__actions">
                                <span class="">
{{--                                    Gunakan akun Anda yang telah terdaftar di website <a class="kt-link" href="https://kolegium-urologi.id">https://kolegium-urologi.id</a>--}}
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
            placeholder: 'Pilih Fakultas Asal',
            minimumInputLength: 0
        });

    })
</script>

</body>

<!-- end::Body -->
</html>
