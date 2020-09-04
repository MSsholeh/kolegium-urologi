@extends('admin.layout.content')

@section('content')

    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ __($title) }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <div class="kt-subheader__breadcrumbs">
                    {!! Daster::breadcrumb($breadcrumbs) !!}
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        @if ($errors->any())
                            <br>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul style="margin-bottom: 0; padding-left: 5px;">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                        @endif
                        <!--begin::Form-->
                        <form action="{{ route($route.'.store') }}" id="form-register" enctype="multipart/form-data" method="POST">
                            @csrf
                            <div class="kt-portlet__body">
                                <div class="kt-section">
                                    <h3 class="kt-section__title">
                                        Data Diri
                                    </h3>
                                    <div class="kt-section__content">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Nomor NIK<span class="kt-font-danger">*</span> :</label>
                                                <input type="text" name="nik" class="form-control" placeholder="Nomor NIK" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">NPA IDI PUSAT<span class="kt-font-danger">*</span> :</label>
                                                <input type="text" name="npa" class="form-control" placeholder="NPA IDI PUSAT" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Nama Lengkap<span class="kt-font-danger">*</span> :</label>
                                                <input type="text" name="name" class="form-control" placeholder="Nama Lengkap" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Asal Universitas<span class="kt-font-danger">*</span> :</label>
                                                <input type="text" name="university" class="form-control" placeholder="Asal Universitas" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Tempat Lahir<span class="kt-font-danger">*</span> :</label>
                                                <input type="text" name="pob" class="form-control" placeholder="Tempat Lahir" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Tanggal Lahir<span class="kt-font-danger">*</span> :</label>
                                                <input type="date" name="dob" class="form-control" placeholder="Tanggal Lahir" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Alamat <span class="kt-font-danger">*</span> :</label>
                                                <textarea name="address" rows="3" placeholder="Alamat" class="form-control" required></textarea>
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Nomor Handphone<span class="kt-font-danger">*</span> :</label>
                                                <input type="text" name="phone" class="form-control" placeholder="Nomor Handphone" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-section">
                                    <h3 class="kt-section__title">
                                        Data Akun
                                    </h3>
                                    <div class="kt-section__content">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Email<span class="kt-font-danger">*</span> :</label>
                                                <input type="email" name="email" class="form-control" placeholder="Email" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Password<span class="kt-font-danger">*</span> :</label>
                                                <input type="password" name="password" class="form-control" placeholder="Password" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Ulangi Password<span class="kt-font-danger">*</span> :</label>
                                                <input type="password" name="password_confirmation" class="form-control" placeholder="Ulangi Password" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-section">
                                    <h3 class="kt-section__title">
                                        Data PPDS
                                    </h3>
                                    <div class="kt-section__content">
                                        @if(auth()->user()->university_id === null)
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Universitas <span class="kt-font-danger">*</span> :</label>
                                                <select name="university_id" class="select2-university" required>
                                                </select>
                                                <span class="form-text text-muted"></span>
                                            </div>
                                        </div>
                                        @else
                                            <input type="hidden" name="university_id" value="{{ auth()->user()->university_id }}">
                                        @endif
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Tahun Masuk<span class="kt-font-danger">*</span> :</label>
                                                <input type="number" min="1990" max="2020" value="2020" name="tahun_masuk" class="form-control" placeholder="Tahun Masuk" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">NIM<span class="kt-font-danger">*</span> :</label>
                                                <input type="text" name="nim" class="form-control" placeholder="Nomor Induk Mahasiswa" required>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Status Kelulusan<span class="kt-font-danger">*</span> :</label><br>
                                                <input data-switch="true" name="status_lulus" id="status_lulus" type="checkbox" data-on-text="Lulus" data-handle-width="50" data-off-text="Belum Lulus" data-on-color="success">
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <label class="form-control-label">Status Sertifikat Terbit<span class="kt-font-danger">*</span> :</label><br>
                                                <input data-switch="true" name="status_sertifikat" id="status_sertifikat" type="checkbox" data-on-text="Terbit" data-handle-width="50" data-off-text="Belum Terbit" data-on-color="success">
                                            </div>
                                        </div>
                                        <div id="certificate">
                                            <div class="form-group row">
                                                <div class="col-lg-12">
                                                    <label class="form-control-label">Nomor Sertifikat<span class="kt-font-danger">*</span> :</label>
                                                    <input type="text" name="no_sertifikat" class="form-control" placeholder="Nomor Sertifikat">
                                                </div>
                                            </div>
                                            <div class="form-group row">
                                                <div class="col-lg-12">
                                                    <label class="form-control-label">Tanggal Terbit Sertifikat<span class="kt-font-danger">*</span> :</label>
                                                    <input type="date" name="date_sertifikat" class="form-control" placeholder="Tanggal Sertifikat">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="kt-section d-flex justify-content-end">
                                    <div class="kt-section__content">
                                        <div class="form-group row">
                                            <div class="col-lg-12">
                                                <button type="reset" class="btn btn-bold btn-secondary btn-elevate">Kosongkan</button>
                                                <button type="submit" class="btn btn-bold btn-primary btn-elevate" id="submit">Simpan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                        <!--end::Form-->
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script type="text/javascript">

    $(function(){

        $('#requirements').repeater({
            initEmpty: false,

            defaultValues: {
            },

            show: function () {
                $(this).slideDown();

                $(this).find('.select2-container').remove();

                $('.select-select2').select2();
                $(this).find('.select-select2').val('Text').trigger('change');

                $('[data-switch=true]').bootstrapSwitch();

            },

            hide: function (deleteElement) {
                $(this).slideUp(deleteElement);
            }
        });

        $('[data-switch=true]').bootstrapSwitch();

        $(document).ready(function(){
            $("#certificate").hide();
            $('#status_sertifikat').on('switchChange.bootstrapSwitch', function (e, data) {
                var state=$(this).bootstrapSwitch('state');//returns true or false
                if(state){
                    $("#certificate").show();
                }
                else{
                    $("#certificate").hide();
                }
            });
        });

        Daster.select2('.select2-university', {
            url: '{{ route('admin.select.university') }}',
            placeholder: 'Pilih Universitas',
            minimumInputLength: 0
        });

        Daster.select2('.select2-periode', {
            url: '{{ route('admin.select.period') }}',
            placeholder: 'Pilih Periode',
            minimumInputLength: 0
        });

    });
    $(function(){
        function submitForm(){
            $.ajax({
                complete:function(){
                    $('body, html').animate({scrollTop:$('form').offset().top}, 'slow');
                }
            });
        }
        $('#submit').click(function(){
            submitForm();
        });
    });
</script>
@endpush
