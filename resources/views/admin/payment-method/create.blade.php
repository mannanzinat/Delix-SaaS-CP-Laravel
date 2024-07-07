@extends('backend.layouts.master')

@section('title')
    {{__('add').' '.__('payout_method')}}
@endsection

@section('mainContent')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col col-lg-6 col-md-9">
            <div class="header-top d-flex justify-content-between align-items-center">
                    <h3 class="section-title">{{__('add')}} {{__('payout_method')}}</h3>
                <div class="oftions-content-right mb-12">
                    <div class="oftions-content-right">
                        <a href="{{url()->previous()}}" class="d-flex align-items-center btn sg-btn-primary gap-2"><i class="icon las la-arrow-left"></i><span>{{__('back')}}</span></a>
                    </div>
                </div>
            </div>
            <form action="{{ route('admin.payment.method.store')}}" class="form-validate" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card-inner">
                                    <div class="mb-3">
                                        <label class="form-label" for="method">{{ __('name') }} <span
                                                class="text-danger">*</span></label>
                                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" value="{{ old('name') }}" name="name">
                                        @if ($errors->has('name'))
                                            <div class="invalid-feedback help-block">
                                                <p>{{ $errors->first('name') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="method">{{ __('type') }} <span
                                                class="text-danger">*</span></label>
                                            <select class="without_search form-select form-control method @error('type') is-invalid @enderror"
                                                name="type">

                                                <option value="">{{ __('select_type') }}</option>
                                                <option value="{{ \App\Enums\PaymentMethodType::MFS->value }}" {{ old('type') == \App\Enums\PaymentMethodType::MFS->value ? 'selected':'' }}>{{ __('mfs') }}</option>
                                                <option value="{{ \App\Enums\PaymentMethodType::BANK->value }}" {{ old('type') == \App\Enums\PaymentMethodType::BANK->value ? 'selected':'' }}>{{ __('bank') }}</option>
                                                <option value="{{ \App\Enums\PaymentMethodType::CASH->value }}" {{ old('type') == \App\Enums\PaymentMethodType::CASH->value ? 'selected':'' }}>{{ __('cash') }}</option>

                                            </select>
                                        @if ($errors->has('type'))
                                            <div class="invalid-feedback help-block">
                                                <p>{{ $errors->first('type') }}</p>
                                            </div>
                                        @endif
                                    </div>

                                <div class="row">
                                    <div class="d-flex justify-content-left align-items-center mt-30">
                                        <button type="submit" class="btn sg-btn-primary resubmit">{{__('submit') }}</button>
                                        @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
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

