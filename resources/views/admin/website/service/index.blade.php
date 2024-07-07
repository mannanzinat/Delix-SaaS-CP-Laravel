
@extends('backend.layouts.master')
@section('title', __('service'))
@section('mainContent')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                @include('admin.website.sidebar_component')
                <div class="col-xxl-9 col-lg-8 col-md-8">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h3 class="section-title">{{ __('service') }}</h3>
                        <div class="oftions-content-right mb-12">
                            <a href="{{ route('services.create') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                                <i class="las la-plus"></i>
                                <span>{{__('add_service') }}</span>
                            </a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12 default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
                            <div class="default-list-table table-responsive yajra-dataTable">
                                {{ $dataTable->table() }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @include('common.delete-script')
    @include('common.change-status-ajax')
@endsection

