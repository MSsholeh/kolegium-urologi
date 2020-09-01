<div class="modal-header">
    <h5 class="modal-title">{{ __($title) }}</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
    </button>
</div>
<div class="modal-body">
    <!--begin::Form-->
    <form action="{{ route($route.'.update', $requirement->id) }}" class="kt-form kt-form--label-right" id="form-modal"  method="POST">
        @method('PATCH')
        <input type="hidden" name="id" value="{{ $requirement->id }}">
        <div class="kt-portlet__body">
            <div class="kt-section">
                <div class="kt-section__content">
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Periode <span class="kt-font-danger">*</span> :</label>
                            <select name="period_id" class="select2-periode">
                                <option value="{{ $requirement->period_id }}">{{ $requirement->period ? $requirement->period->name.', '.$requirement->period->started_at . ' s/d ' . $requirement->period->ended_at : '' }}</option>
                            </select>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                    <div class="form-group row">
                        <div class="col-lg-12">
                            <label class="form-control-label">Keterangan <span class="kt-font-danger">*</span> :</label>
                            <textarea name="note" rows="3" placeholder="Deskripsi tentang pendaftaran" class="form-control">{{ $requirement->note }}</textarea>
                            <span class="form-text text-muted"></span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="kt-section">
                <h3 class="kt-section__title">
                    Persyaratan Ujian Nasional
                </h3>
                <div class="kt-section__content">
                    <div id="requirements">
                        <div class="form-group form-group-last row">
                            <div data-repeater-list="requirements" class="col-lg-12">
                                @if($requirement->items->count() === 0)
                                    <div data-repeater-item class="form-group row align-items-center">
                                        <div class="col-md-12 mb-3">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label>Nama Persyaratan:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <input type="text" name="name" class="form-control" placeholder="Persyaratan">
                                                </div>
                                            </div>
                                            <div class="d-md-none kt-margin-b-10"></div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label class="kt-label m-label--single">Tipe:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <select name="type" class="form-control select-select2">
                                                        <option value="Text" selected>Text</option>
                                                        <option value="Checkbox">Checkbox</option>
                                                        <option value="File">File</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-md-none kt-margin-b-10"></div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label>Wajib Diisi:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <input data-switch="true" name="required" type="checkbox" checked="checked" data-on-text="Wajib" data-handle-width="50" data-off-text="Tidak" data-on-color="success">
                                                </div>
                                            </div>
                                            <div class="d-md-none kt-margin-b-10"></div>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <a href="javascript:;" data-repeater-delete="requirements" class="btn-sm btn btn-label-danger btn-bold mt-4">
                                                <i class="la la-trash-o"></i>
                                                Hapus
                                            </a>
                                        </div>
                                    </div>
                                @endif
                                @foreach($requirement->items as $item)
                                    <div data-repeater-item class="form-group row align-items-center">
                                        <input type="hidden" name="requirements[{{ $loop->index }}][id]" value="{{ $item->id }}">
                                        <div class="col-md-12 mb-3">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label>Nama Persyaratan:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <input type="text" name="requirements[{{ $loop->index }}][name]" value="{{ $item->name }}" class="form-control" placeholder="Persyaratan">
                                                </div>
                                            </div>
                                            <div class="d-md-none kt-margin-b-10"></div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label class="kt-label m-label--single">Tipe:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <select name="requirements[{{ $loop->index }}][type]" class="form-control select-select2">
                                                        @foreach($types as $type)
                                                            <option value="{{ $type }}" @if($item->type == $type) selected @endif>{{ $type }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="d-md-none kt-margin-b-10"></div>
                                        </div>
                                        <div class="col-md-5">
                                            <div class="kt-form__group--inline">
                                                <div class="kt-form__label">
                                                    <label>Wajib Diisi:</label>
                                                </div>
                                                <div class="kt-form__control">
                                                    <input data-switch="true" @if($item->required) checked="checked" @endif name="requirements[{{ $loop->index }}][required]" type="checkbox" data-on-text="Wajib" data-handle-width="50" data-off-text="Tidak" data-on-color="success">
                                                </div>
                                            </div>
                                            <div class="d-md-none kt-margin-b-10"></div>
                                        </div>
                                        <div class="col-md-2 text-right">
                                            <a href="javascript:;" data-repeater-delete="requirements" class="btn-sm btn btn-label-danger btn-bold mt-4">
                                                <i class="la la-trash-o"></i>
                                                Hapus
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="form-group form-group-last row">
                            <div class="col-lg-4">
                                <a href="javascript:;" data-repeater-create="requirements" class="btn btn-bold btn-sm btn-label-brand">
                                    <i class="la la-plus"></i> Tambah Persyaratan
                                </a>
                            </div>
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
            datatable: '#table-schedule'
        });

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

        Daster.select2('.select2-periode', {
            url: '{{ route('admin.select.period') }}',
            placeholder: 'Pilih Periode',
            minimumInputLength: 0
        });

    });
</script>
