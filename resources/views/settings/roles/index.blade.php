@extends('layouts.app')

@section('title', __('Roles'))

@section('content')
<main id="js-page-content" role="main" class="page-content">
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>{{ __('Roles') }}</h2>
                    <div class="panel-toolbar">
                        <button type="button" class="btn btn-primary btn-sm btn-modal-remote" data-bs-toggle="modal" data-bs-target="#modal-remote">
                            <svg class="sa-icon me-1"><use href="{{ asset('sa5/img/sprite.svg#plus-circle') }}"></use></svg>
                            {{ __('Add role') }}
                        </button>
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <table id="datatable_roles" class="table table-striped table-bordered table-configuration w-100" cellspacing="0">
                            <thead>
                                <tr>
                                    <th></th>
                                    <th class="hasinput" width="30%">
                                        <input id="col_search_1" class="form-control form-control-sm" placeholder="{{ __('Filter name') }}" type="text">
                                    </th>
                                    <th class="hasinput" width="30%">
                                        <input type="text" id="col_search_2" class="form-control form-control-sm" placeholder="{{ __('Filter description') }}" />
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th width="7%"></th>
                                    <th width="30%">{{ __('Role name') }}</th>
                                    <th width="30%">{{ __('Role description') }}</th>
                                    <th width="20%">{{ __('Date') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
<div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div>
@endsection
