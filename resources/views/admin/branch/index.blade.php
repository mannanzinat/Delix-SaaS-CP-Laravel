@extends('backend.layouts.master')
@section('title')
    {{ __('branch') }} {{ __('lists') }}
@endsection
@section('mainContent')
    <div class="container-fluid">
        <div class="row gx-20">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="section-title">{{ __('lists') }}</h3>
                        <p>{{ __('you_have_total') }} {{ !blank($branches) ? $branches->total() : '0' }} {{ __('branch') }}.</p>
                    </div>
                    <div class="oftions-content-right mb-12">
                        @if (hasPermission('branch_create'))
                            <a href="{{ route('admin.branch.create') }}"
                                class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-plus"></i>
                                <span>{{ __('add') }}</span>
                            </a>
                        @endif
                    </div>
                </div>
                 <section class="oftions">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <div class="default-list-table table-responsive yajra-dataTable">
                                                {{ $dataTable->table(['class' => 'dt-responsive table'], true) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </div>
@endsection
@push('script')
    @include('common.delete-ajax')
@endpush
