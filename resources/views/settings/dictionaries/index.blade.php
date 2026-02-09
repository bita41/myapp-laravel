@extends('layouts.app')

@section('title', l('a-dictionary'))

@section('content')
<script>
    window.DICTIONARIES_DATA_URL = @json(route('settings.dictionaries.dictionaries_server_side'));
</script>

@php
    $widthPerLanguage = count($languages) > 0 ? (70 / count($languages)) : 25;
@endphp
<div class="app-content">
    <div class="content-wrapper">
        <div class="main-content">
            <div class="card shadow-0 mb-g">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h2 class="card-title mb-0">{{ l('a-dictionary') }}</h2>

                    <button type="button"
                        class="btn btn-sm btn-info waves-effect waves-themed btn-modal-remote"
                        data-remote="{{ route('settings.dictionaries.add') }}"
                        data-bs-target="#modal-remote"
                        data-bs-toggle="modal">
                        <span class="fal fa-plus-circle me-1"></span>{{ l('a-add-dictionary') }}
                    </button>
                </div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table id="datatable_dictionaries" class="table table-bordered table-hover w-100">
                            <thead>
                                <tr>
                                    <th width="3%" class="no-sort no-search"></th>
                                    <th class="no-sort">{{ l('a-dictionary-parameter') }}</th>
                                    @foreach($languages as $language)
                                        <th width="{{ $widthPerLanguage }}%" class="no-sort">
                                            {{ l('a-dictionary-' . $language['file']) }}
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>

                    <div class="mt-2" id="msg_form_dictionaries"></div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
