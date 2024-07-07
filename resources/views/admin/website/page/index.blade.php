@extends('backend.layouts.master')
@section('title', __('all_pages'))
@section('mainContent')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-12 col-lg-12 col-md-12">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h3 class="section-title">{{ __('all_pages') }}</h3>
                        <div class="oftions-content-right mb-12">
                            @if (hasPermission('pages.create'))
                                <a href="{{ route('pages.create') }}"
                                    class="d-flex align-items-center btn sg-btn-primary gap-2">
                                    <i class="las la-plus"></i>
                                    <span>{{__('add_page') }}</span>
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30">
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
    @include('common.delete-script')
@endsection

