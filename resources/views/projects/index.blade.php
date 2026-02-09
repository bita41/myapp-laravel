@extends('layouts.app')

@section('title', __('Projects'))

@section('content')
<main id="js-page-content" role="main" class="page-content">
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>{{ __('Projects') }}</h2>
                    <div class="panel-toolbar">
                        <button type="button" class="btn btn-primary btn-sm mr-1 btn-modal-remote" data-bs-toggle="modal" data-bs-target="#modal-remote">
                            <svg class="sa-icon me-1"><use href="{{ asset('sa5/img/sprite.svg#plus-circle') }}"></use></svg>
                            {{ __('Add project') }}
                        </button>
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <table id="datatable_projects" class="table table-striped table-bordered table-configuration w-100" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="7%"></th>
                                    <th class="hasinput" width="25%">
                                        <input class="form-control form-control-sm" type="text" id="col_search_1" placeholder="{{ __('Filter') }}" />
                                    </th>
                                    <th class="hasinput" width="15%">
                                        <input class="form-control form-control-sm" type="text" id="col_search_2" placeholder="{{ __('Filter') }}" />
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th width="7%"></th>
                                    <th width="25%">{{ __('Project name') }}</th>
                                    <th width="15%">{{ __('Status') }}</th>
                                    <th width="10%">{{ __('Date') }}</th>
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
