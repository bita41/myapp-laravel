@extends('layouts.app')

@section('title', __('Customers'))

@section('content')
<main id="js-page-content" role="main" class="page-content">
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>{{ __('Customers') }}</h2>
                    <div class="panel-toolbar">
                        <button type="button" class="btn btn-primary btn-sm mr-1 btn-modal-remote" data-bs-toggle="modal" data-bs-target="#modal-remote" data-remote="{{ route('customers.index') }}">
                            <svg class="sa-icon me-1"><use href="{{ asset('sa5/img/sprite.svg#plus-circle') }}"></use></svg>
                            {{ __('Add customer') }}
                        </button>
                    </div>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <table id="datatable_customers" class="table table-striped table-bordered table-configuration w-100" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="7%"></th>
                                    <th class="hasinput" width="25%">
                                        <input class="form-control form-control-sm" type="text" id="col_search_1" placeholder="{{ __('Filter') }}" />
                                    </th>
                                    <th class="hasinput" width="15%">
                                        <input class="form-control form-control-sm" type="text" id="col_search_2" placeholder="{{ __('Filter') }}" />
                                    </th>
                                    <th class="hasinput" width="10%">
                                        <input class="form-control form-control-sm" type="text" id="col_search_3" placeholder="{{ __('Filter') }}" />
                                    </th>
                                    <th class="hasinput" width="10%">
                                        <input class="form-control form-control-sm" type="text" id="col_search_4" placeholder="{{ __('Filter') }}" />
                                    </th>
                                </tr>
                                <tr>
                                    <th width="7%"></th>
                                    <th width="25%">{{ __('Customer name') }}</th>
                                    <th width="15%">{{ __('Customer email') }}</th>
                                    <th width="10%">{{ __('Customer phone') }}</th>
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
