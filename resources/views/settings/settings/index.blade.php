@extends('layouts.app')

@section('title', __('Settings'))

@section('content')
<main id="js-page-content" role="main" class="page-content">
    <div class="row">
        <div class="col-xl-12">
            <div id="panel-1" class="panel">
                <div class="panel-hdr">
                    <h2>{{ __('Settings') }}</h2>
                </div>
                <div class="panel-container show">
                    <div class="panel-content">
                        <table id="datatable_settings" class="table table-striped table-bordered table-configuration w-100" cellspacing="0">
                            <thead>
                                <tr>
                                    <th width="5%"></th>
                                    <th class="hasinput" width="20%"></th>
                                    <th class="hasinput" width="65%">
                                        <input type="text" id="col_search_2" class="form-control form-control-sm" placeholder="{{ __('Filter') }}" />
                                    </th>
                                    <th></th>
                                </tr>
                                <tr>
                                    <th width="5%"></th>
                                    <th width="20%">{{ __('Settings name') }}</th>
                                    <th width="66%">{{ __('Settings value') }}</th>
                                    <th width="10%">{{ __('Created date') }}</th>
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
