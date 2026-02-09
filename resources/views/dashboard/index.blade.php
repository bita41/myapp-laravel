@extends('layouts.app')

@section('title', __('Dashboard'))

@section('content')
    <main id="js-page-content" role="main" class="page-content">
        <div class="fs-lg fw-300 p-5 bg-white border-faded rounded mb-g">
            <div id="dashboard_chart" style="min-width: 310px; height: 400px; width: 98%;"></div>
            {{-- Chart placeholder: integrezi Highcharts/JS dupa ce ai date din backend --}}
        </div>

        <div class="row">
            <div class="col-xl-12">
                <div id="panel-1" class="panel">
                    <div class="panel-hdr">
                        <h2>{{ __('Tasks') }}</h2>
                        <div class="panel-toolbar">
                            <a href="{{ route('tasks.index') }}" class="btn btn-primary btn-sm mr-1">
                                <svg class="sa-icon me-1"><use href="{{ asset('sa5/img/sprite.svg#plus-circle') }}"></use></svg>
                                {{ __('Add task') }}
                            </a>
                        </div>
                    </div>
                    <div class="panel-container show">
                        <div class="panel-content">
                            <table id="datatable_tasks" class="table table-striped table-bordered table-configuration w-100" cellspacing="0">
                                <thead>
                                    <tr>
                                        <th width="10%"></th>
                                        <th class="hasinput" width="25%">
                                            <input class="form-control form-control-sm" type="text" id="col_search_1" placeholder="{{ __('Filter') }}" />
                                        </th>
                                        <th class="hasinput" width="15%">
                                            <input class="form-control form-control-sm" type="text" id="col_search_2" placeholder="{{ __('Filter') }}" />
                                        </th>
                                        <th class="hasinput" width="10%">
                                            <input class="form-control form-control-sm" type="text" id="col_search_3" placeholder="{{ __('Filter') }}" />
                                        </th>
                                        <th width="10%"></th>
                                        <th width="10%"></th>
                                        <th width="10%"></th>
                                        <th width="10%"></th>
                                    </tr>
                                    <tr>
                                        <th width="10%"></th>
                                        <th width="25%">{{ __('Task name') }}</th>
                                        <th width="15%">{{ __('Project name') }}</th>
                                        <th width="10%">{{ __('Unit price') }}</th>
                                        <th width="10%">{{ __('Working hours') }}</th>
                                        <th width="10%">{{ __('Total price') }}</th>
                                        <th width="10%">{{ __('Insert date') }}</th>
                                        <th width="10%">{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {{-- Rânduri populat via JS sau Livewire/Datatables --}}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <div class="page-content-overlay" data-action="toggle" data-class="mobile-nav-on"></div>
@endsection

@push('scripts')
<script>
  var datatable_type = 'dashboard';
</script>
@endpush
