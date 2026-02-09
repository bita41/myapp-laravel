{{-- Fragment for modal-remote (Edit dictionary) --}}
<div data-modal-title="{{ l('a-edit-dictionary') }}">
    <form action="{{ route('settings.dictionaries.edit_ajax') }}" method="POST" data-remote-form="true" class="inputs-modal-clean">
        @csrf
        <input type="hidden" name="dictionar_id" value="{{ $row->dictionar_id }}">
        <div class="mb-3">
            <label for="edit_dictionar_parameter" class="form-label">{{ l('a-dictionary-parameter') }}</label>
            <input type="text" name="parameter" id="edit_dictionar_parameter" class="form-control" value="{{ old('parameter', $row->parameter ?? '') }}">
        </div>
        @foreach($languages as $lang)
            @php $file = (string) ($lang['file'] ?? ''); @endphp
            @if($file !== '')
                <div class="mb-3">
                    <label for="edit_dictionar_{{ $file }}" class="form-label">{{ e($lang['name'] ?? $file) }}</label>
                    <input type="text" name="dictionar[{{ $file }}]" id="edit_dictionar_{{ $file }}" class="form-control" value="{{ old("dictionar.{$file}", $row->{$file} ?? '') }}">
                </div>
            @endif
        @endforeach
        <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary"><i class="fal fa-save"></i> {{ l('a-simple-save') }}</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ l('a-cancel') }}</button>
        </div>
    </form>
</div>
