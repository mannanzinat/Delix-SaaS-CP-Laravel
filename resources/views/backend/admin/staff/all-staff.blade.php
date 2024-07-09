@extends('backend.layouts.master')
@section('title', __('all_staff'))
@section('content')
<section class="oftions">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center">
                    <h3 class="section-title">{{__('manage_staff') }}</h3>
                    @can('staffs.create')
                    <div class="oftions-content-right mb-12">
                        <a href="{{ route('staffs.create') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                            <i class="las la-plus"></i>
                            <span>{{__('add_staff') }}</span>
                        </a>
                    </div>
                    @endcan
                </div>
                <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="default-list-table table-responsive yajra-dataTable">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
@include('backend.common.delete-script')
@include('backend.admin.staff.staff-script')
@push('js')
    {{ $dataTable->scripts() }}
@endpush
