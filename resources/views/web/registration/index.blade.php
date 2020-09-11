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
                            <h3>Pendaftaran PPDS</h3>
                        </div>

                        <div class="stepwizard">
                            <div class="stepwizard-row">
                              <div class="stepwizard-step">
                                <a href="{{route('web.profile.index')}}" type="button" class="btn btn-dark btn-circle" style="color:white">1</a>
                                <p>Data Akun</p>
                              </div>
                              <div class="stepwizard-step ">
                                <a href="{{route('web.registration.index')}}" type="button" class="btn btn-primary btn-circle" style="color:white">2</a>
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

                        @if($registered)
                            <div class="alert alert-solid-info alert-bold" role="alert">
                                <div class="alert-text">Anda telah terdaftar di {{ $registered->university->name }}.</div>
                            </div>
                            @if(!empty($lulus))
                            <div class="alert alert-solid-warning alert-bold" role="alert">
                                <div class="alert-text">Anda sudah dapat melakukan pengajuan sertifikat.</div>
                            </div>
                            @endif
                            <h4 class="font-weight-bold mt-4">Riwayat Pendaftaran</h4>
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Universitas</th>
                                    <th>Tanggal Daftar</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrants as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $item->university->name }}</td>
                                            <td>{{ Daster::tanggal($item->created_at, 1, true) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              </table>
                        @elseif($submission < 2 && empty($progress))
                            <br>
                            <div class="alert alert-solid-warning alert-bold" role="alert">
                                <div class="alert-text">Anda mempunyai kesempatan <b>{{ 2 - $submission }} kali</b> lagi untuk mendaftar.</div>
                            </div>
                            <h4 class="font-weight-bold mt-4">Pilih Fakultas Yang Dituju</h4>

                            <form class="kt-form" action="{{route('web.registration.register')}}" method="POST">
                                @csrf
                                <div class="form-group mt-3">
                                    <label>Fakultas<span style="color:red">*</span></label>
                                    <select name="university_id" class="select2-university">
                                    </select>
                                    <span class="form-text text-muted"></span>
                                </div><br>

                                <div class="kt-login__actions">
                                    <button type="submit" id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Lanjut</button>
                                </div>
                            </form>

                            @if($submission==1)
                            <h4 class="font-weight-bold mt-4">Riwayat Pendaftaran</h4>
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Universitas</th>
                                    <th>Tanggal Daftar</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrants as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $item->university->name }}</td>
                                            <td>{{ Daster::tanggal($item->created_at, 1, true) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              </table>
                            @endif
                        @elseif($progress)
                            <div class="alert alert-solid-brand alert-bold" role="alert">
                                @if($submission < 2)
                                <div class="alert-text">Anda mempunyai 1 pendaftaran dalam proses, Anda belum bisa mendaftar ke pendaftaran lain sampai proses selesai.</div>
                                @else
                                <div class="alert-text">Anda mempunyai 1 pendaftaran dalam proses.</div>
                                @endif
                            </div>
                            <h4 class="font-weight-bold mt-4">Riwayat Pendaftaran</h4>
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Universitas</th>
                                    <th>Tanggal Daftar</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrants as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $item->university->name }}</td>
                                            <td>{{ Daster::tanggal($item->created_at, 1, true) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              </table>

                        @else
                            <div class="alert alert-solid-danger alert-bold" role="alert">
                                <div class="alert-text">Anda sudah tidak mempunyai kesempatan untuk mendaftar.</div>
                            </div>
                            <h4 class="font-weight-bold mt-4">Riwayat Pendaftaran</h4>
                              <table class="table table-striped">
                                <thead>
                                  <tr>
                                    <th>#</th>
                                    <th>Universitas</th>
                                    <th>Tanggal Daftar</th>
                                  </tr>
                                </thead>
                                <tbody>
                                    @foreach($registrants as $item)
                                        <tr>
                                            <td>{{ $loop->iteration }}.</td>
                                            <td>{{ $item->university->name }}</td>
                                            <td>{{ Daster::tanggal($item->created_at, 1, true) }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                              </table>
                        @endif
                        @if(!empty($lulus))
                            <div class="kt-login__actions">
                                <span class="">
                                </span>
                                <a href="{{route('web.certificate.index')}}" type="submit" id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary d-flex align-items-center">Lanjut</a>
                            </div>
                        @endif
<!--
                            <h4 class="font-weight-bold mt-4">Klik checkbox captcha untuk verifikasi di bawah</h4>
                            <div class="form-group mt-3">
                                {!! app('captcha')->display($attributes = [], $options = ['lang'=> 'id']) !!}
                            </div>
-->

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
            placeholder: 'Pilih Fakultas',
            minimumInputLength: 0
        });


    })
</script>
@endpush
