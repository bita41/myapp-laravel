{{-- Fragment for modal-remote (Add dictionary) --}}
<form action="{{ route('settings.dictionaries.add_ajax') }}" method="POST" data-async-modal id="form_adddictionary" class="inputs-modal-clean">
    @csrf

    <div class="modal-header">
        <h5 class="modal-title">{{ l('a-add-dictionary') }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <div class="modal-body">
        <div class="mb-3">
            <label for="dictionar_parameter" class="form-label">{{ l('a-dictionary-parameter') }} <span class="text-danger">*</span></label>
            <input type="text" name="parameter" id="dictionar_parameter" class="form-control required" placeholder="{{ l('a-dictionary-parameter') }}">
        </div>

        @foreach($languages as $lang)
            @php $file = (string) ($lang['file'] ?? ''); @endphp
            @if($file !== '')
                <div class="mb-3">
                    <label for="dictionar_{{ $file }}" class="form-label">
                        {{ e($lang['name'] ?? $file) }}
                        @if(($lang['code'] ?? '') === 'ro')
                            <span class="text-danger">*</span>
                        @endif
                    </label>
                    <input type="text"
                           name="dictionar[{{ $file }}]"
                           id="dictionar_{{ $file }}"
                           class="form-control {{ ($lang['code'] ?? '') === 'ro' ? 'required' : '' }}">
                </div>
            @endif
        @endforeach

        <div id="msg_form_adddictionary"></div>
    </div>

    <div class="modal-footer">
        <input type="hidden" name="datatable_id" value="datatable_dictionaries">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ l('a-cancel') }}</button>
        <button type="submit" class="btn btn-primary"><i class="fal fa-save"></i> {{ l('a-simple-save') }}</button>
    </div>
</form>

