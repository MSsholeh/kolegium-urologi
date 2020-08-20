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
                    <a href="{{ route($route.'.create') }}" class="btn btn-bold btn-twitter btn-sm btn-icon-h kt-margin-l-10 shoot-modal">
                        <i class="flaticon2-plus"></i>
                        Tambah
                    </a>
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
                                <div class="col-lg-12 kt-margin-b-10-tablet-and-mobile">
                                    <label>Universitas</label>
                                    <input type="text" class="form-control kt-input" placeholder="Judul" data-col-index="1">
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
                                <th>Universitas</th>
                                <th>Note</th>
                                <th>Tanggal Dibuat</th>
                                <th>Periode</th>
                                <th>Admin</th>
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
                    { data: 'university' },
                    { data: 'note' },
                    { data: 'created_at' },
                    { data: 'period', name: 'period.name' },
                    { data: 'admin' },
                    { data: 'action', searchable: false, orderable: false, className: 'dt-center' },
                ],
                order: [2, 'desc']
            });

        });
    </script>
@endpush
