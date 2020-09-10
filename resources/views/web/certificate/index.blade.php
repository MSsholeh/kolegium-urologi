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
                            <h3>Pengajuan Sertifikat</h3>
                        </div>

                        <div class="stepwizard">
                            <div class="stepwizard-row">
                              <div class="stepwizard-step">
                                <a href="{{route('web.profile.index')}}" type="button" class="btn btn-dark btn-circle" style="color:white">1</a>
                                <p>Data Akun</p>
                              </div>
                              <div class="stepwizard-step ">
                                <a href="{{route('web.registration.index')}}" type="button" class="btn btn-dark btn-circle" style="color:white">2</a>
                                <p>Pendaftaran PPDS</p>
                              </div>
                              <div class="stepwizard-step ">
                                <a href="{{route('web.certificate.index')}}" type="button" class="btn btn-primary btn-circle" style="color:white">3</a>
                                <p>Pengajuan Sertifikat</p>
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

                        <form class="kt-form" action="{{route('web.certificate.register')}}" method="POST">
                            @csrf
                            <div class="form-group mt-3">
                                <label>Tipe Pengajuan Sertifikat<span style="color:red">*</span></label>
                                <select name="certificate_type" class="select2-certificate">
                                </select>
                                <span class="form-text text-muted"></span>
                            </div><br>

                            <div class="kt-login__actions">
                                <button type="submit" id="kt_login_signin_submit" class="btn btn-primary btn-elevate kt-login__btn-primary">Lanjut</button>
                            </div>
                        </form>

                        <h4 class="font-weight-bold mt-4">Riwayat Pengajuan Sertifikat</h4>
                          <table class="table table-striped">
                            <thead>
                              <tr>
                                <th>#</th>
                                <th>Tipe</th>
                                <th>Tanggal Daftar</th>
                                <th>Status</th>
                              </tr>
                            </thead>
                            <tbody>
                                @if(!$certificates)
                                    <tr>
                                        <td colspan="3" class="text-center">Belum ada Pengajuan</td>
                                    </tr>
                                @endif
                                @foreach($certificates as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>Pengajuan {{ $item->requirement_certificate->type }}</td>
                                        <td>{{ Daster::tanggal($item->created_at, 1, true) }}</td>
                                        <td>
                                            @if($item->status == "Request")
                                                <a href="#" class="btn btn-sm btn-label-warning btn-bold">Menunggu Konfirmasi</a>
                                            @elseif($item->status == "Approve")
                                                <a href="#" class="btn btn-sm btn-label-success btn-bold">Terbit</a>
                                            @else
                                                <a href="#" class="btn btn-sm btn-label-danger btn-bold">Ditolak</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                          </table>

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
            Daster.select2('.select2-certificate', {
                url: '{{ route('web.select.certificate') }}',
                placeholder: 'Pilih Tipe Pengajuan',
                minimumInputLength: 0
            });


        })
    </script>
    <script>
        $(function(){
            $('[data-switch=true]').bootstrapSwitch();

            Daster.initValidate("#form-certificate", {
                rules: {},
                contentReload: "{{ route('web.certificate.index') }}"
            });

        });
    </script>
@endpush
