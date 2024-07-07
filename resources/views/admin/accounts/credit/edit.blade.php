@extends('backend.layouts.master')

@section('title')
    {{ __('edit') . ' ' . __('credit_from_merchant') }}
@endsection

@section('mainContent')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-12 col-lg-6 col-md-6">
                <div class="header-top d-flex justify-content-between align-items-center mb-12">
                    <h3 class="section-title">{{ __('edit') }} {{ __('credit_from_merchant') }}</h3>
                    <div class="oftions-content-right">
                        <a href="{{ url()->previous() }}" class="d-flex align-items-center btn sg-btn-primary gap-2"><i
                                class="icon las la-arrow-left"></i><span>{{ __('back') }}</span></a>
                    </div>
                </div>
                <form action="{{ route('incomes.receive.from.merchant.update') }}"  method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" value="{{ $company_account->id }}" name="id">
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-inner">
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('merchants') }} <span
                                                class="text-danger">*</span></label>
                                        <select id="merchant-live-search" name="merchant"
                                            class="form-control select-merchant-for-credit merchant-live-search @error('merchant') is-invalid @enderror"
                                            data-url="{{ route('admin.merchant.parcel') }}">
                                            {{--                                                        <select class=" form-control select-merchant-for-credit" data-url="{{ route('admin.merchant.parcel') }}" name="merchant"> --}}
                                            <option value="{{ $company_account->merchant_id }}">
                                                {{ $company_account->merchant->company }}</option>
                                        </select>
                                        @if ($errors->has('merchant'))
                                            <div class="invalid-feedback help-block">
                                                <p>{{ $errors->first('merchant') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('parcel') }}</label>
                                        <select class="without_search  form-control" name="parcel" id="parcel_select"
                                            data-search="on">
                                            @if (!blank($company_account->parcel_id))
                                                <option value="{{ $company_account->parcel_id }}">
                                                    {{ $company_account->parcel->parcel_no . ' (' . $company_account->parcel->merchant->company . ')' }}
                                                </option>
                                            @endif
                                            <option value=""></option>
                                        </select>
                                        @if ($errors->has('parcel'))
                                            <div class="invalid-feedback help-block">
                                                <p>{{ $errors->first('parcel') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">{{ __('account') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="without_search  form-control @error('account') is-invalid @enderror" name="account">
                                            <option value="">{{ __('select_account') }}</option>
                                            @foreach ($accounts as $account)
                                                <option value="{{ $account->id }}"
                                                    {{ $account->id == $company_account->account_id ? 'selected' : '' }}>
                                                    ({{ __($account->method) }})
                                                    @if ($account->method == 'bank')
                                                        {{ $account->account_holder_name . ', ' . $account->account_no . ', ' . __($account->bank_name) . ',' . $account->bank_branch }}.
                                                    @elseif($account->method == 'cash')
                                                        {{ $account->user->first_name . ' ' . $account->user->last_name }}
                                                    @else
                                                        {{ $account->account_holder_name . ', ' . $account->number . ', ' . __($account->type) }}
                                                    @endif
                                                </option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('account'))
                                            <div class="invalid-feedback help-block">
                                                <p>{{ $errors->first('account') }}</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="fv-full-name">{{ __('amount')}} ({{ (setting('default_currency'))  }}) <span
                                            class="text-danger">*</span></label>
                                    <input type="number" class="form-control @error('amount') is-invalid @enderror" id="fv-full-name"
                                        value="{{ old('amount') != '' ? old('amount') : $company_account->amount }}"
                                        name="amount">
                                    @if ($errors->has('amount'))
                                        <div class="invalid-feedback help-block">
                                            <p>{{ $errors->first('amount') }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">{{ __('date') }} <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control date-picker @error('date') is-invalid @enderror" name="date"
                                        autocomplete="off"
                                        value="{{ old('date') != '' ? old('date') : date('Y-m-d', strtotime($company_account->date)) }}">
                                    @if ($errors->has('date'))
                                        <div class="invalid-feedback help-block">
                                            <p>{{ $errors->first('date') }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="mb-3">
                                    <label class="form-label" for="details">{{ __('details') }}</label>
                                    <textarea class="form-control" id="details" placeholder="{{ __('details') }}" name="details">{{ old('details') != '' ? old('details') : $company_account->details }}</textarea>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 text-right mt-4">
                                        <div class="mb-3">
                                            <button type="submit"
                                                class="btn sg-btn-primary resubmit">{{ __('update') }}</button>
                                        </div>
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
@endpush
@include('live_search.merchants')
