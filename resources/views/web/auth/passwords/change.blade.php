@extends('web.layout.content')

@section('content')

<!-- begin:: Page -->
<div class="kt-grid kt-grid--ver kt-grid--root" id="kt_content" style="margin-top:70px">
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
                            <h3>Ganti Password</h3>
                        </div>

                        <!--begin::Form-->
                        <form class="kt-form" action="{{ route('web.change.password') }}" method="POST" novalidate="novalidate" id="form-change-password">
                            @csrf

                            <div class="form-group">
                                <input id="password" type="password" class="form-control" name="current_password" autocomplete="current-password" placeholder="Password lama">
                                @error('current_password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                @enderror
                            </div>
                            <div class="form-group">
                                <input class="form-control @error('password') is-invalid @enderror" id="new_password" type="password" name="new_password" autocomplete="current-password" placeholder="Password Baru">
                                @error('new_password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                            </div>

                            <div class="form-group">
                                <input id="new_confirm_password" type="password" class="form-control" name="new_confirm_password" autocomplete="current-password" placeholder="Konfirmasi Password Baru">

                            </div>

                            <!--begin::Action-->
                            <div class="kt-login__actions">
                                <button id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Simpan</button>
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

@endsection

@push('scripts')
    <script>
        $(function(){

            Daster.initValidate("#form-change-password", {
                rules: {},
                contentReload: "{{ route('web.profile.index') }}"
            });

        });
    </script>
@endpush
