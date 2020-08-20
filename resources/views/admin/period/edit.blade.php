<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route($route.'.update', $value->id) }}" class="kt-form kt-form--label-right" id="form-modal"  method="POST">
        <input type="hidden" name="_method" value="PATCH" />
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Nama <span class="kt-font-danger">*</span> :</label>
                            <input type="text" name="name" required class="form-control" placeholder="Nama periode" value="{{ $value->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Tanggal Periode <span class="kt-font-danger">*</span> :</label>
                            <input type="text" name="date" required class="form-control input-daterangepicker" placeholder="Tanggal periode" value="{{ Daster::tanggal($value->started_at).' - '.Daster::tanggal($value->ended_at) }}">
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
    });
</script>
