@extends('backend.layouts.master')
@section('title')
    {{ __('add') . ' ' . __('account') }}
@endsection
@section('mainContent')
    <div class="container-fluid">
        <div class="row justify-content-md-center">
            <div class="col-12 col-lg-6 col-md-8">
                <div class="header-top d-flex justify-content-between align-items-center mb-12">
                    <h3 class="section-title">{{ __('add') }} {{ __('account') }}</h3>
                    <div class="oftions-content-right">
                        <a href="{{ url()->previous() }}" class="d-flex align-items-center btn sg-btn-primary gap-2"><i
                                class="icon las la-arrow-left"></i><span>{{ __('back') }}</span></a>
                    </div>
                </div>
                <form action="{{ route('admin.account.store') }}" class="formSubmit" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="card-inner">
                                    <div class="mb-3">
                                        <label class="form-label" for="method">{{ __('user') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="user without_search form-select form-control @error('user') is-invalid @enderror"
                                            name="user">
                                            <option value="">{{ __('select_user') }}</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->first_name . ' ' . $user->last_name }}(
                                                    {{ $user->email }})</option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger mt-2 error-message user-error"></span>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="method">{{ __('method') }} <span
                                                class="text-danger">*</span></label>
                                        <select class="method without_search form-select form-control method @error('method') is-invalid @enderror"
                                            name="method">
                                            <option data-type="" value="">{{ __('select_type') }}</option>
                                            @foreach ($methods as $account_method)
                                                <option data-type="{{ $account_method->type }}"
                                                    value="{{ $account_method->id }}" {{ $account_method->id == old('method') ? 'selected' : '' }}>
                                                    {{ $account_method->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <span class="text-danger mt-2 error-message method-error"></span>
                                    </div>
                                    <div class="g-gs account-holder">
                                        <div class="mb-3">
                                            <label class="form-label"
                                                for="account_holder_name">{{ __('account_holder_name') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text"
                                                class="account_holder_name form-control @error('account_holder_name') is-invalid @enderror"
                                                value="{{ old('account_holder_name') }}" name="account_holder_name">
                                            <span class="text-danger mt-2 error-message account-holder-name-error"></span>
                                        </div>
                                    </div>
                                    <div class="g-gs method-bank d-none">
                                        <div class="mb-3">
                                            <label class="form-label" for="account_no">{{ __('account_no') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control account_no" id="account_no"
                                                value="{{ old('account_no') }}" name="account_no">
                                            <span class="text-danger mt-2 error-message account-no-error"></span>
                                        </div>
                                    </div>
                                    <div class="g-gs method-bank d-none">
                                        <div class="mb-3">
                                            <label class="form-label" for="branch">{{ __('branch') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control branch"
                                                value="{{ old('branch') }}" name="branch">
                                            <span class="text-danger mt-2 error-message branch-error"></span>
                                        </div>
                                    </div>

                                    <div class="g-gs method-mobile d-none">
                                        <div class="mb-3">
                                            <label class="form-label" for="number">{{ __('number') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control number"
                                                value="{{ old('number') }}" name="number">
                                            <span class="text-danger mt-2 error-message number-error"></span>
                                        </div>
                                    </div>
                                    <div class="g-gs method-mobile d-none">
                                        <div class="mb-3">
                                            <label class="form-label" for="account_type">{{ __('account_type') }}
                                                <span class="text-danger">*</span></label>
                                            <select class="without_search form-select form-control account_type"
                                                name="account_type">
                                                <option value="">{{ __('select_account_type') }}</option>
                                                <option value="merchant">{{ __('merchant') }}</option>
                                                <option value="personal">{{ __('personal') }}</option>
                                            </select>
                                            <span class="text-danger mt-2 error-message account_type-error"></span>
                                        </div>
                                    </div>
                                    <div class="g-gs">
                                        <div class="mb-3">
                                            <label class="form-label" for="fv-full-name">{{ __('opening_balance') }}({{ setting('default_currency') }})
                                                <span class="text-danger">*</span></label>
                                            <input type="text"
                                                class="form-control @error('opening_balance') is-invalid @enderror opening_balance"
                                                id="fv-full-name" value="{{ old('opening_balance') }}"
                                                name="opening_balance">
                                            <span class="text-danger mt-2 error-message opening_balance-error"></span>

                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 text-right mt-4">
                                            <div class="mb-3">
                                                <button type="submit" class="btn sg-btn-primary resubmit">{{ __('submit') }}</button>
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
    <script>
        $(document).ready(function() {
            $('.method').on('change', function() {
                var selectedOption = $(this).find(':selected');
                var dataType = selectedOption.data('type');

                if (dataType == 'bank') {
                    $('.method-mobile').addClass('d-none');
                    $('.method-bank').removeClass('d-none');
                    $('.account-holder').removeClass('d-none');
                } else if ($(this).val() == 'cash') {
                    $('.method-mobile').addClass('d-none');
                    $('.method-bank').addClass('d-none');
                    $('.account-holder').addClass('d-none');
                } else {
                    $('.method-mobile').removeClass('d-none');
                    $('.method-bank').addClass('d-none');
                    $('.account-holder').removeClass('d-none');
                }
            });

            $('.formSubmit').on('submit', function(event) {
                var dataType = $('.method').find(':selected').data('type');
                var formValid = true;

                $('.error-message').text('');

                if (dataType == 'bank') {
                    if ($('.account_no').val() == '') {
                        $('.account-no-error').text('Account no is required');
                        formValid = false;
                    }
                    if ($('.branch').val() == '') {
                        $('.branch-error').text('Branch is required');
                        formValid = false;
                    }
                } else if (dataType == 'mfs') {
                    if ($('.number').val() == '') {
                        $('.number-error').text('Number is required');
                        formValid = false;
                    }
                    if ($('.account_type').val() == '') {
                        $('.account_type-error').text('Account type is required');
                        formValid = false;
                    }
                }

                if ($('.opening_balance').val() == '') {
                    $('.opening_balance-error').text('Opening balance is required');
                    formValid = false;
                }
                if ($('.user').val() == '') {
                    $('.user-error').text('User is required');
                    formValid = false;
                }
                if ($('.method').val() == '') {
                    $('.method-error').text('Method is required');
                    formValid = false;
                }

                if ($('.account_holder_name').val() == '') {
                    $('.account_holder_name-error').text('Account holder name is required');
                    formValid = false;
                }

                if (!formValid) {
                    event.preventDefault();
                }
            });
        });

    </script>
@endpush
