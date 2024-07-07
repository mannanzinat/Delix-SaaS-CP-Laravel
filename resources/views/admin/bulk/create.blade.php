@extends('backend.layouts.master')

@section('title')
    {{ __('assign_delivery') }}
@endsection

@section('mainContent')
    <div class="container-fluid">
        <div class="row gx-20">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center mb-12">
                    <h3 class="section-title">{{ __('assign_delivery') }}</h3>
                    <div class="oftions-content-right">
                        <a href="{{ route('parcel') }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                            <i class="las la-arrow-left"></i>
                            <span>{{ __('back') }}</span>
                        </a>
                    </div>
                </div>
                <form action="{{ route('bulk.assigning.parcel.save') }}" class="form-validate" id="parcel-form"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-inner">
                                    <div class="row g-gs">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="barcode">{{ __('scan_barcode') }}
                                                </label>
                                                <input type="text" class="form-control barcode" id="barcode"
                                                    data-url="admin/add-parcel-row/" value="{{ old('barcode') }}"
                                                    name="barcode">
                                                @if ($errors->has('barcode'))
                                                    <div class="invalid-feedback help-block">
                                                        <p>{{ $errors->first('barcode') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label">{{ __('delivery_man') }} <span
                                                        class="text-danger">*</span></label>
                                                <select id="delivery-man-live-search" name="delivery_man" data-url="{{ route('get-delivery-man-live') }}"
                                                    class="form-control delivery-man-live-search" required> </select>
                                                @if ($errors->has('delivery_man'))
                                                    <div class="invalid-feedback help-block">
                                                        <p>{{ $errors->first('delivery_man') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table mt-5" id="parcels" data-val="0">
                                        <tr>
                                            <th>#</th>
                                            <th>{{ __('parcel_id') }}
                                            </th>
                                            <th>{{ __('customer_name') }}
                                            </th>
                                            <th>{{ __('customer_address') }}
                                            </th>
                                            <th>{{ __('action') }}</th>
                                        </tr>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12 text-right mt-3">
                                            <div class="custom-checkbox mb-3">
                                                <label class="custom-control-label" for="customCheck1">
                                                    <input type="checkbox" class="custom-control-input" id="customCheck1"
                                                        name="notify_customer" value="notify">
                                                    <span class="text-capitalize">{{ __('notify_customer') }}</span>
                                                </label>
                                            </div>
                                            <button type="submit"
                                                class="btn sg-btn-primary d-md-inline-flex resubmit">{{ __('print') }}</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('script')
    <script>
        $('#delivery-man-live-search').select2(
            getLiveSearch(
                $('#delivery-man-live-search').data('url'),
                'Select delivery hero'
            )
        )
    </script>
@endpush
@include('live_search.delivery-man')
@include('admin.bulk.bulk-script')
