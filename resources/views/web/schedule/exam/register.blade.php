@extends('web.layout.content')

@section('content')

    <!-- begin:: Content Head -->
    <div class="kt-subheader  kt-grid__item" id="kt_subheader">
        <div class="kt-container  kt-container--fluid ">
            <div class="kt-subheader__main">
                <h3 class="kt-subheader__title">{{ $title }}</h3>
                <span class="kt-subheader__separator kt-subheader__separator--v"></span>
                <span class="kt-subheader__desc"></span>

                {!! Daster::breadcrumb($breadcrumbs) !!}

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

        <div class="col-md-12">

            <!--begin::Portlet-->
            <div class="kt-portlet">
                <div class="kt-portlet__head">
                    <div class="kt-portlet__head-label">
                        <h3 class="kt-portlet__head-title">
                            Form Persyaratan Pendaftaran
                        </h3>
                    </div>
                </div>

                <!--begin::Form-->
                <form class="kt-form" id="form-register" action="{{ route('web.schedule.exam.store', $schedule->id) }}" enctype="multipart/form-data" method="POST">
                    <div class="kt-portlet__body">
                        <div class="form-group">
                            <div class="alert alert-secondary" role="alert">
                                <div class="alert-icon"><i class="flaticon-warning kt-font-brand"></i></div>
                                <div class="alert-text">
                                    Isi dengan data yang sesuai. Pendaftaran hanya dapat dilakukan maksimal 2 kali dalam satu periode Ujian.
                                </div>
                            </div>
                        </div>

                        @foreach($requirements as $requirement)

                        <div class="form-group">
                            <label>{{ $requirement->name }}</label>

                            @if($requirement->type === 'Text')

                            <input type="text" name="requirement_{{ $requirement->id }}" class="form-control" @if($requirement->required) required @endif placeholder="{{ $requirement->name }}">

                            @elseif($requirement->type === 'Checkbox')

                            <div>
                                <input data-switch="true" name="requirement_{{ $requirement->id }}" type="checkbox" data-on-text="Ya" data-handle-width="50" data-off-text="Tidak" data-on-color="success">
                            </div>

                            @elseif($requirement->type === 'File')

                            <input type="file" name="requirement_{{ $requirement->id }}" class="form-control" @if($requirement->required) required @endif placeholder="{{ $requirement->name }}">

                            @endif

                            <span class="form-text text-muted"></span>
                        </div>

                        @endforeach

{{--                        <div class="form-group form-group-last">--}}
{{--                            <label class="form-control-label">Tanggal </label>--}}
{{--                            <input type="text" name="event_date" required class="form-control input-datepicker" placeholder="Tanggal">--}}
{{--                        </div>--}}

                    </div>
                    <div class="kt-portlet__foot kt-portlet__foot--solid">
                        <div class="kt-form__actions">
                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-success btn-wide">Daftar</button>
                                    <button type="reset" class="btn btn-secondary">Kosongkan</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
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
                contentReload: '{{ route('web.schedule.exam.submitted', $schedule->id) }}'
            });

        });
    </script>
@endpush
