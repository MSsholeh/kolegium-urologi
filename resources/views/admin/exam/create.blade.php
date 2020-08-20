<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route($route.'.store') }}" class="kt-form kt-form--label-right" id="form-modal"  method="POST">
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Periode <span class="kt-font-danger">*</span> :</label>
                            <select name="period_id" class="select2-period"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Universitas <span class="kt-font-danger">*</span> :</label>
                            <select name="university_id" class="select2-university"></select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Judul Ujian <span class="kt-font-danger">*</span> :</label>
                            <input type="text" name="title" required class="form-control" placeholder="Judul Ujian">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Tanggal Ujian <span class="kt-font-danger">*</span> :</label>
                            <input type="text" name="date" required class="form-control input-daterangepicker" placeholder="Tanggal periode">
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Deskripsi <span class="kt-font-danger">*</span> :</label>
                            <textarea name="description" class="form-control" rows="3"></textarea>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
    <!--end::Form-->
</div>
<div class="modal-footer">
    <button type="submit" class="btn btn-label-brand btn-bold btn-brand--icon btn-label-brand" form="form-modal" id="form-submit">
        <span><i class="la la-save"></i>Simpan</span>
    </button> &nbsp;&nbsp;
    <a href="javascript:;" data-dismiss="modal" class="kt-link kt-font-bold kt-font-danger">Batal</a>
</div>

<script type="text/javascript">
    $(function(){
        Daster.initValidate("#form-modal", {
            rules: {},
            modal: '#shoot-modal',
            datatable: '#main-table'
        });

        Daster.select2('.select2-period', {
            url: '{{ route('admin.select.period', 'exam') }}',
            minimumInputLength: 0
        });

        Daster.select2('.select2-university', {
            url: '{{ route('admin.select.university') }}',
            minimumInputLength: 0
        });
    });
</script>
