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
        <div class="row">
            <div class="col-md-12">
                <div class="kt-portlet">
                    <div class="kt-portlet__head">
                        <div class="kt-portlet__head-label">
                            <h3 class="kt-portlet__head-title">
                                Status Pendaftaran
                            </h3>
                        </div>
                    </div>
                    <div class="kt-portlet__body">

                        <div class="kt-form">
                            @foreach($registrant->requirements as $item)

                                @php($field = $item->item)

                                <div class="form-group">
                                    <label class="font-weight-bold">{{ $field->name }}</label>

                                    @if($field->type === 'Text')

                                        <span class="form-control-plaintext">{{ $item->value }}</span>

                                    @elseif($field->type === 'Checkbox')

                                        <span class="form-control-plaintext">
                                            {{ $item->value ? 'Ya' : 'Tidak' }}
                                        </span>

                                    @elseif($field->type === 'File')

                                        <span class="form-control-plaintext">
                                            <a href="{{ route('web.file', ['path' => $item->value]) }}" target="_blank" class="btn btn-brand btn-icon"><i class="la la-file"></i></a>
                                        </span>

                                    @endif

                                    <p><i>Catatan Validator</i>: {{ $item->note }}</p>

                                </div>

                            @endforeach
                        </div>

                        <div class="mt-3">
                            <span class="btn btn-sm btn-label-{{ config('constant.registrant_status.badge.'.$registrant->status) }} btn-bold">{{ config('constant.registrant_status.'.$registrant->status) }}</span>
                            <a href="{{ route('web.dashboard.home') }}" class="btn btn-sm btn-secondary">Kembali</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
