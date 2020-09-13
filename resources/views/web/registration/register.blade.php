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
                            <h3>Persyaratan Pendaftaran</h3>
                        </div>

                        <div class="stepwizard">
                            <div class="stepwizard-row">
                              <div class="stepwizard-step">
                                <a href="{{route('web.profile.index')}}" type="button" class="btn btn-dark btn-circle" style="color:white">1</a>
                                <p>Data Akun</p>
                              </div>
                              <div class="stepwizard-step ">
                                <a href="{{route('web.registration.index')}}" type="button" class="btn btn-primary btn-circle" style="color:white">2</a>
                                <p>Pengisian Data</p>
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

                        <form class="kt-form" id="form-register" action="{{ route('web.registration.store', $requirement->id) }}" enctype="multipart/form-data" method="POST">
                        <div class="kt-portlet__body">
                            <div class="form-group form-group-last">
                                <div class="alert alert-secondary" role="alert">
                                    <div class="alert-icon"><i class="flaticon-information kt-font-brand"></i></div>
                                    <div class="alert-text">
                                        {{ $requirement->note }}
                                    </div>
                                </div>
                            </div>

                            @foreach($requirement->items as $item)

                                <div class="form-group">
                                    <label>{{ $item->name }}</label>

                                    @if($item->type === 'Text')

                                        <input type="text" name="requirement_{{ $item->id }}" class="form-control" @if($item->required) required @endif placeholder="{{ $item->name }}">

                                    @elseif($item->type === 'Checkbox')

                                        <div>
                                            <input data-switch="true" name="requirement_{{ $item->id }}" type="checkbox" data-on-text="Ya" data-handle-width="50" data-off-text="Tidak" data-on-color="success">
                                        </div>

                                    @elseif($item->type === 'File')

                                        <input type="file" name="requirement_{{ $item->id }}" class="form-control" @if($item->required) required @endif placeholder="{{ $item->name }}">

                                    @endif

                                    <span class="form-text text-muted"></span>
                                </div><br>

                            @endforeach

                            </div>
                            <div class="kt-login__actions">
                                <button type="submit" id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Daftar</button>
                                <button type="reset" class="btn btn-secondary btn-elevate kt-login__btn-secondary">Kosongkan</button>
                                <a href="{{ route('web.registration.index') }}"class="btn btn-secondary btn-elevate kt-login__btn-secondary">Kembali</a>
                            </div>
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
            $('[data-switch=true]').bootstrapSwitch();

            Daster.initValidate("#form-register", {
                rules: {},
                contentReload: "{{ route('web.registration.index') }}"
            });

        });
    </script>
@endpush
