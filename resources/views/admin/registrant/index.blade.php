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
            <div class="kt-subheader__toolbar">
                <div class="kt-subheader__wrapper">
                    <button type="button" data-size="lg" class="btn btn-secondary" aria-expanded="false" id="l-toggle-filter">
                        <i class="la la-search"></i>
                        Filter
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- end:: Content Head -->

    <!-- begin:: Content -->
    <div class="kt-container  kt-container--fluid  kt-grid__item kt-grid__item--fluid">

        <!--Begin::Dashboard 1-->

        <!--Begin::Row-->
        <div class="row">
            <div class="col-lg-12">

                <div class="kt-portlet">
                    <div class="kt-portlet__body">

                        <form class="kt-form kt-form--fit kt-margin-b-20 hidden" id="kt_filter" style="display: none;">
                            <div class="row kt-margin-b-20">
                                <div class="col-lg-12 kt-margin-b-10">
                                    <label>Nama Pendaftar</label>
                                    <input type="text" class="form-control kt-input" placeholder="Nama Pendaftar" data-col-index="1">
                                </div>
                                <div class="col-lg-4 kt-margin-b-10">
                                    <label>Universitas</label>
                                    <input type="text" class="form-control kt-input" placeholder="Nama Universitas" data-col-index="3">
                                </div>
                                <div class="col-lg-4 kt-margin-b-10">
                                    <label>Periode</label>
                                    <input type="text" class="form-control kt-input" placeholder="Periode" data-col-index="6">
                                </div>
                                <div class="col-lg-4 kt-margin-b-10">
                                    <label>Status</label>
                                    <select class="form-control kt-input" data-col-index="7" placeholder="Status">
                                        <option value="">- Pilih Status -</option>
                                        <option value="Request">Pengajuan Pendaftaran</option>
                                        <option value="Approve">Diterima</option>
                                        <option value="Reject">Ditolak</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn btn-primary btn-brand--icon btn-sm" id="kt_search">
                                <span>
                                    <i class="la la-search"></i>
                                    <span>Cari</span>
                                </span>
                                    </button>
                                    &nbsp;&nbsp;
                                    <button class="btn btn-secondary btn-secondary--icon btn-sm" id="kt_reset">
                                <span>
                                    <i class="la la-close"></i>
                                    <span>Kembalikan</span>
                                </span>
                                    </button>
                                </div>
                            </div>
                            <div class="kt-separator kt-separator--md kt-separator--dashed"></div>
                        </form>

                        <!--begin: Datatable -->
                        <table class="table table-hover table-checkable" id="main-table">
                            <thead>
                            <tr>
                                <th width="35px">No</th>
                                <th>Nama</th>
                                <th>NIK</th>
                                <th>Universitas</th>
                                <th>Tanggal Pendaftaran</th>
                                <th>Pendaftaran Ke</th>
                                <th>Periode</th>
                                <th>Status</th>
                                <th width="120px">Aksi</th>
                            </tr>
                            </thead>
                        </table>
                        <!-- end: Datatable -->
                    </div>
                </div>

            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script type="text/javascript">
        jQuery(document).ready(function () {

            Daster.initTable('#main-table', {
                url: '{{ route($route.'.table') }}',
                columns: [
                    { data: 'DT_RowIndex', searchable: false, orderable: false, className: 'dt-center' },
                    { data: 'name', name: 'user.name' },
                    { data: 'nik', name: 'user.nik' },
                    { data: 'university', name: 'university.name' },
                    { data: 'registered_at', name: 'created_at' },
                    { data: 'submission' },
                    { data: 'period', name: 'requirement.period.name' },
                    { data: 'status' },
                    { data: 'action', searchable: false, orderable: false, className: 'dt-center' },
                ],
                order: [3, 'desc']
            });

        });
    </script>
@endpush
