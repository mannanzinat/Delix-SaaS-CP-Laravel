@extends('backend.layouts.master')
@section('title', __('segments'))
@section('content')
<section class="oftions">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col col-lg-8 col-md-9">
                <div class="header-top d-flex justify-content-between align-items-center">
                    <h3 class="section-title">{{__('segments') }}</h3>
                    <div class="oftions-content-right mb-12">
                        <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary gap-2" data-bs-toggle="modal" data-bs-target="#segments">
                            <i class="las la-plus"></i>
                            <span>{{__('add_new_segments') }}</span>
                        </a>
                    </div>
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
    <div class="modal fade" id="segments" tabindex="-1" aria-labelledby="segments" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-md">
            <div class="modal-content">
                <h6 class="sub-title create_sub_title">{{__('add_new_segments') }}</h6>
                <button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                @include('website.clientwhatsapp.segments.create')
            </div>
        </div>
    </div>
@endsection
@include('backend.common.delete-script')
@push('js')
    {{ $dataTable->scripts() }}
@endpush

