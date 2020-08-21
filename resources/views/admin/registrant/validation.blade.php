<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route($route.'.store', $registrant->id) }}" class="kt-form kt-form--label-right" id="form-modal"  method="POST">
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Nama Peserta :</label>
                            <h5>{{ $registrant->user->name }}</h5>
                        </div>
                    </div>

                    <h5>Persyaratan Pendaftaran</h5>

                    @foreach($requirements as $requirement)

                        @php($field = $requirement->item)

                        <div class="form-group">
                            <label>
                                {{ $field->name }}

                            </label>


                            @if($field->type === 'Text')

                                <span class="form-control-plaintext">{{ $requirement->value }}</span>

                            @elseif($field->type === 'Checkbox')

                                <span class="form-control-plaintext">
                                    {{ $requirement->value ? 'Ya' : 'Tidak' }}
                                </span>

                            @elseif($field->type === 'File')

                                <span class="form-control-plaintext">
                                    <a href="{{ route('web.file', ['path' => $requirement->value]) }}" target="_blank" class="btn btn-brand btn-icon"><i class="la la-file"></i></a>
                                </span>

                            @endif

                            <div class="d-flex">
                                <div class="mr-3 pt-2" style="width: 140px;">Hasil validasi</div>
                                <div class="kt-form__control">
                                    <input data-switch="true" name="validate_{{ $requirement->id }}[checklist]" @if($requirement->validation) checked @endif type="checkbox" data-on-text="Sesuai" data-handle-width="230" data-off-text="Tidak Sesuai" data-on-color="success">
                                </div>
                                <input type="text" name="validate_{{ $requirement->id }}[note]" value="{{ $requirement->note }}" class="form-control ml-3" placeholder="Keterangan Validasi">
                            </div>
                        </div>

                    @endforeach


                    <div class="form-group mt-4">
                        <label>
                            Hasil Akhir
                        </label>
                        <div class="kt-form__control">
                            <input data-switch="true" name="result" id="result" @if($registrant->status === 'Approve') checked @endif type="checkbox" data-on-text="Lolos" data-handle-width="50" data-off-text="Gagal" data-on-color="success">
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
