@extends('website.layouts.master')
@section('content')
    <section class="signup__section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="signUp__wrapper">
                        <div class="signUp__content">
                            <h2 class="title"><span>Sign Up</span> For Free.</h2>
                            <h4 class="subtitle">Create an account and experience how Delix works.</h4>
                            <p class="desc">Set up your Delix checkout, add your SaaS, software, or online content, and configure your subscriptions easily.</p>
                        </div>
                        <div class="process__content">
                            <p class="desc">Integrate with your existing website in minutes, and you're ready to go.</p>
                            <ul class="process__list">
                                <li>
                                    <div class="icon"><img src="{{ static_asset('website') }}/assets/images/signup/icon-01.png" alt="icon" /></div>
                                    Emphasizes that the process of integrating the service with an existing
                                </li>
                                <li>
                                    <div class="icon"><img src="{{ static_asset('website') }}/assets/images/signup/icon-02.png" alt="icon" /></div>
                                    Suggests that the service is designed to work smoothly with any existing website, highlighting its compatibility
                                </li>
                                <li>
                                    <div class="icon"><img src="{{ static_asset('website') }}/assets/images/signup/icon-03.png" alt="icon" /></div>
                                    Sell globally with localized currencies and payments
                                </li>
                                <li>
                                    <div class="icon"><img src="{{ static_asset('website') }}/assets/images/signup/icon-04.png" alt="icon" /></div>
                                    Manage complex subscription models
                                </li>
                                <li>
                                    <div class="icon"><img src="{{ static_asset('website') }}/assets/images/signup/icon-05.png" alt="icon" /></div>
                                    Stop worrying about VAT and tax jurisdictions
                                </li>
                            </ul>
                        </div>
                        <div class="company__partner">
                            <p class="desc">You’re in good company</p>
                            <div class="partner__wrapper">
                                <div class="partner__item">
                                    <img src="{{ static_asset('website') }}/assets/images/signup/partner-01.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ static_asset('website') }}/assets/images/signup/partner-02.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ static_asset('website') }}/assets/images/signup/partner-03.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ static_asset('website') }}/assets/images/signup/partner-04.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ static_asset('website') }}/assets/images/signup/partner-05.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ static_asset('website') }}/assets/images/signup/partner-06.png" alt="partner" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form__wrapper">
                        <form id="signupForm" action="{{ route('register.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="company_name">{{ __('company_name') }}</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="{{ __('enter_your_company_name') }}" value="{{ old('company_name') }}" />
                                <div class="alert__txt invalid-feedback"></div>
                            </div>
                            <div class="flex__input">
                                <div class="form-group">
                                    <label for="first_name">{{ __('first_name') }}</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="{{ __('enter_your_first_name') }}" value="{{ old('first_name') }}" />
                                    <div class="alert__txt invalid-feedback"></div>
                                </div>
                                <div class="form-group">
                                    <label for="last_name">{{ __('last_name') }}</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="{{ __('enter_your_last_name') }}" value="{{ old('last_name') }}" />
                                    <div class="alert__txt invalid-feedback"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('email') }}</label>
                                <input type="text" class="form-control" id="email" name="email" placeholder="{{ __('enter_your_email') }}" value="{{ old('email') }}" />
                                <div class="alert__txt invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('enter_your_password') }}" />
                                <div class="alert__txt invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="domain">{{ __('domain') }}</label>
                                <input type="text" class="form-control domain" id="domain" name="domain" placeholder="{{ __('write_here') }}" value="{{ old('domain') }}" />
                                <small>.delix.cloud</small>
                                <div class="alert__txt invalid-feedback"></div>
                            </div>
                            <div class="form-group">
                                <label for="hear_about_delix">{{ __('where_did_you_hear_about_delix?') }}</label>
                                <select class="form__dropdown form-control" data-width="100%" data-minimum-results-for-search="Infinity" name="hear_about_delix">
                                    <option value="google_ads" {{ old('hear_about_delix') == 'google_ads' ? 'selected' : '' }}>{{ __('google_ads') }}</option>
                                    <option value="facebook" {{ old('hear_about_delix') == 'facebook' ? 'selected' : '' }}>{{ __('facebook') }}</option>
                                    <option value="youtube" {{ old('hear_about_delix') == 'youtube' ? 'selected' : '' }}>{{ __('youtube') }}</option>
                                    <option value="email" {{ old('hear_about_delix') == 'email' ? 'selected' : '' }}>{{ __('email') }}</option>
                                    <option value="friend" {{ old('hear_about_delix') == 'friend' ? 'selected' : '' }}>{{ __('friend') }}</option>
                                </select>
                                <div class="alert__txt invalid-feedback"></div>
                            </div>
                            <div class="">
                                <div class="custom__checkbox">
                                    <input type="checkbox" class="form-check-input" id="policyCheck" name="policy_check" {{ old('policy_check') ? 'checked' : '' }} />
                                    <label class="form-check-label text-dark" for="policyCheck">{{ __('i_agree_privacy_policy_&_terms') }}</label><br>
                                </div>
                                <div id="policyCheckError" class="alert__txt invalid-feedback"></div>
                            </div>
                            <div class="btn__submit">
                                <button type="submit" class="btn btn-primary">Register</button>
                                <div class="loading btn btn-primary d-none"><span class="spinner-border"></span>Loading...</div>
                            </div>
                        </form>
                        <p class="account text-center">
                            {{ __('already_have_an_account?') }}
                            <a href="{{ route('login') }}">{{ __('login') }}</a>
                            {{ __('in_instead') }}
                        </p>
                        <div class="devider text-center">{{ __('or') }}</div>
                        <div class="instant__login text-center">
                            {{ __('you_can_sign_up_using') }}
                            <div class="login__icon">
                                <a href="#">
                                    <img src="{{ static_asset('website') }}/assets/images/login-whatsapp.png" alt="whatsapps" />
                                </a>
                                <a href="{{ route('login.google') }}">
                                    <img src="{{ static_asset('website') }}/assets/images/login-email.png" alt="email" />
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            $('#domain').on('input', function() {
                this.value = this.value.toLowerCase();
            });

            $('#signupForm').on('submit', function(event) {
                event.preventDefault();
                $('.alert__txt').hide();
                $('.form-control').removeClass('is-invalid');
                $('.form-check-input').removeClass('is-invalid');
                $('.btn__submit .loading').removeClass('d-none');
                $('.btn__submit').prop('disabled', true);

                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        toastr.success(response.message);
                        $('#signupForm')[0].reset();
                        $('.btn__submit .loading').addClass('d-none');
                        $('.btn__submit').prop('disabled', false);
                    },
                    error: function(xhr) {
                        const errors = xhr.responseJSON.errors || {};
                        if (xhr.status === 422) {
                            $('.form-control').removeClass('is-invalid');
                            $('.form-check-input').removeClass('is-invalid');
                            $('.invalid-feedback').hide();

                            $.each(errors, function(key, value) {
                                if (key === 'policy_check') {
                                    const checkbox = $(`[name="${key}"]`);
                                    checkbox.addClass('is-invalid');
                                    $('#policyCheckError').html('<i class="fa-solid fa-circle-info"></i> ' + value[0]);
                                    $('#policyCheckError').show();
                                } else {
                                    const input = $(`#${key}`);
                                    if (input.length > 0) {
                                        input.addClass('is-invalid');
                                        input.siblings('.invalid-feedback').html('<i class="fa-solid fa-circle-info"></i> ' + value[0]);
                                        input.siblings('.invalid-feedback').show();
                                    }
                                }
                            });
                        } else {
                            toastr.error(xhr.responseJSON.message || 'Something went wrong. Please try again.');
                        }
        
                        $('.btn__submit .loading').addClass('d-none');
                        $('.btn__submit').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush


