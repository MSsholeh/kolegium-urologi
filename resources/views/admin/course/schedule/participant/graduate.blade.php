<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route($route.'.graduate.store', [$course->id, $schedule->id, $participant->id]) }}" class="kt-form kt-form--label-right" id="form-modal"  method="POST">
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Nama Peserta :</label>
                            <span class="form-control-plaintext" id="staticEmail">email@example.com</span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Kelulusan :</label>
                            <div class="kt-form__control">
                                <input data-switch="true" name="graduate" type="checkbox" @if($participant->graduate) checked @endif data-on-text="Lulus" data-handle-width="50" data-off-text="Tidak" data-on-color="success">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-6">
                            <label class="form-control-label">Sertifikat :</label>
                            <input type="file" name="certified" class="form-control" placeholder="Sertifikat">
                            @if($participant->certified)
                                <span class="form-text text-muted">Isi untuk mengganti sertifikat sebelunya
                                    <a href="{{ route('web.file', ['path' => $participant->certified]) }}" target="_blank" class="btn btn-twitter btn-sm btn-elevate btn-circle btn-icon"><i class="la la-certificate"></i></a>
                                </span>
                            @else
                                <span class="form-text text-muted">Isi sertifikat jika memenuhi kelulusan</span>
                            @endif
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
            datatable: '#table-main'
        });

        $('[data-switch=true]').bootstrapSwitch();
    });
</script>
