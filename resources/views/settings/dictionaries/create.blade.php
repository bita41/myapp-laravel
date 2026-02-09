{{-- Fragment for modal-remote (Add dictionary) --}}
<div data-modal-title="{{ l('a-add-dictionary') }}">
    <form action="{{ route('settings.dictionaries.add_ajax') }}" method="POST" data-remote-form="true" class="inputs-modal-clean">
        @csrf
        <div class="mb-3">
            <label for="dictionar_parameter" class="form-label">{{ l('a-dictionary-parameter') }}</label>
            <input type="text" name="parameter" id="dictionar_parameter" class="form-control" placeholder="{{ l('a-dictionary-parameter') }}">
        </div>
        @foreach($languages as $lang)
            @php $file = (string) ($lang['file'] ?? ''); @endphp
            @if($file !== '')
                <div class="mb-3">
                    <label for="dictionar_{{ $file }}" class="form-label">{{ e($lang['name'] ?? $file) }}</label>
                    <input type="text" name="dictionar[{{ $file }}]" id="dictionar_{{ $file }}" class="form-control">
                </div>
            @endif
        @endforeach
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fal fa-save"></i> {{ l('a-simple-save') }}</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ l('a-cancel') }}</button>
        </div>
    </form>
</div>
