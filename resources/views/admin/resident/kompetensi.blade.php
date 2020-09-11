<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route($route.'.update', $user->id) }}" class="kt-form kt-form--label-right" id="form-modal"  method="POST">
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Nama :</label>
                            <h5>{{ $user->name }}</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">NPA IDI :</label>
                            <h5>{{ $user->npa }}</h5>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Tahap Kompetensi :</label>
                            <select name="tahap_kompetensi" required class="select-select2">
                                <option value=""></option>
                                <option value="Tahap Pembekalan">Tahap Pembekalan</option>
                                <option value="Tahap Magang">Tahap Magang</option>
                                <option value="Tahap Mandiri">Tahap Mandiri</option>
                            </select>
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
    $(function() {
        Daster.initValidate("#form-modal", {
            rules: {},
            modal: '#shoot-modal',
            datatable: '#main-table'
        });

        $('[data-switch=true]').bootstrapSwitch();
    });


</script>
