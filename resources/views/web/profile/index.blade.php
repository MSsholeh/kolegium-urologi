@extends('web.layout.content')

@section('content')

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
                            <h3>Kelola Data Akun</h3>
                        </div>

                        <div class="stepwizard">
                            <div class="stepwizard-row">
                              <div class="stepwizard-step">
                                <a href="{{route('web.profile.index')}}" type="button" class="btn btn-primary btn-circle" style="color:white">1</a>
                                <p>Data Akun</p>
                              </div>
                              <div class="stepwizard-step ">
                                <a href="{{route('web.registration.index')}}" hrtype="button" class="btn btn-dark btn-circle" style="color:white">2</a>
                                <p>Pendaftaran PPDS</p>
                              </div>
                                @php
                                    $graduation = App\Models\RegistrantGraduation::where('user_id',auth()->user()->id)->where('status','Approve')->first();
                                    if(!empty($graduation)) { $lulus = App\Models\ExamParticipant::where('registrant_graduation_id', $graduation->id)->where('graduate','Lulus')->first(); }
                                @endphp

                                @if(!empty($lulus))
                                <div class="stepwizard-step ">
                                   <a href="{{route('web.certificate.index')}}" type="button" class="btn btn-dark btn-circle" style="color:white">3</a>
                                   <p>Pengajuan Sertifikat</p>
                                </div>
                                @endif
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

                        <!--begin::Form-->
                        <form class="kt-form" id="form-profile" action="{{ route('web.profile.update') }}" method="POST">
                            @csrf
                            <h4 class="font-weight-bold">Data Akun</h4>
                            <input type="hidden" name="id" value="{{$user->id}}">
                            <div class="form-group mt-3">
                                <label>Email<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="email" required placeholder="Email" name="email" value="{{ $user->email }}" autocomplete="off" readonly>
                            </div>
                            <div class="form-group mt-3">
                                <label>Password<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="password" value="***************************" readonly>
                                <br><a class="kt-link" href="{{url('/change-password')}}">Ganti Password</a>
                            </div><br>
                            <h4 class="font-weight-bold mt-4">Peserta Didik</h4>

                            <div class="form-group mt-3">
                                <label>Nomor KTP<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Nomor KTP" name="nik" value="{{ $user->nik }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>NPA IDI PUSAT<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="NPA IDI PUSAT" name="npa" value="{{ $user->npa }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Asal Universitas<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Asal Universitas" name="university" value="{{ $user->university }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Nama Lengkap<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Nama Lengkap" name="name" value="{{ $user->name }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Tempat Lahir<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Tempat Lahir" name="pob" value="{{ $user->pob }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Tanggal Lahir<span style="color:red">*</span></label>
                                <input class="form-control mt-0 input-datepicker-dob" type="text" required placeholder="Tanggal Lahir" name="dob" value="{{ $user->dob }}" autocomplete="off">
                            </div>
                            <div class="form-group mt-3">
                                <label>Alamat<span style="color:red">*</span></label>
                                <textarea class="form-control mt-0" type="text" required placeholder="Alamat" name="address">{{ $user->address }}</textarea>
                            </div>
                            <div class="form-group mt-3">
                                <label>Nomor Handphone<span style="color:red">*</span></label>
                                <input class="form-control mt-0" type="text" required placeholder="Nomor Handphone" name="phone" value="{{ $user->phone }}" autocomplete="off">
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
                                </span>
                                <button type="submit" id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Simpan</button>
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
    <script>
        $(function(){
            $('[data-switch=true]').bootstrapSwitch();

            Daster.initValidate("#form-profile", {
                rules: {},
            });

        });
    </script>
@endpush
