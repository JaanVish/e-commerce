@extends('account::layouts.app')

@php
$elements = ['datatable', 'nice-select', 'datepicker']
@endphp
@push('styles')
@if(Route::has('_asset.css'))
<link rel="stylesheet" href="{{ route('_asset.css', ['elements' => $elements]) }}">
@endif
@endpush

@push('scripts')
@if(Route::has('_asset.js'))
<script type="text/javascript" src="{{  route('_asset.js', ['elements' => $elements])  }}"></script>
@else
<script src="{{ asset(asset_path('vendor/datatables/buttons.server-side.js')) }}"></script>
@endif
{{$dataTable->scripts()}}

<x-backEnd.delete_modal datatable="expense-table" />

@endpush



@section('mainContent')
<section class="admin-visitor-area up_st_admin_visitor">
    <div class="container-fluid p-0">
        <div class="row justify-content-center">
            <div class="col-12">
                <div class="box_header common_table_header">
                    <div class="main-title d-md-flex">
                        <h3 class="mb-0 mr-30 mb_xs_15px mb_sm_20px">{{ __('expense.Expense') }}</h3>

                        @if (permissionCheck('account.expenses.store'))
                        <ul class="d-flex">
                            <li>
                                <a data-container="expense_modal" data-href="{{ route('account.expenses.create') }}"
                                    class="primary-btn radius_30px mr-10 fix-gr-bg btn-modal">
                                    <i class="ti-plus"></i> {{ __('expense.New Expense') }}
                                </a>
                            </li>
                        </ul>
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-xl-12">
                <div class="QA_section QA_section_heading_custom check_box_table">
                    <div class="QA_table ">
                        <!-- table-responsive -->
                        <div class="">
                            <div id="chart_account_list">

                                {{ $dataTable->table([], true) }}

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="modal fade expense_modal" id="expense_modal">
</div>

@endsection
