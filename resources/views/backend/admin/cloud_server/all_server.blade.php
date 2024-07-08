@extends('backend.layouts.master')
@section('title', __('clients'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h3 class="section-title">{{__('server_list') }}</h3>
                        @can('client.create')
                            <div class="oftions-content-right mb-12">
                                <a href="{{ route('cloud-server.create') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                                    <i class="las la-plus"></i>
                                    <span>{{__('add_server') }}</span>
                                </a>
                            </div>
                        @endcan
                    </div>

                    <div class="bg-white redious-border p-20 p-sm-30">
                        <div class="row mb-20">
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
@include('backend.common.change-status-script')
@include('backend.common.delete-script')

@push('js')
    {{ $dataTable->scripts() }}
@endpush


