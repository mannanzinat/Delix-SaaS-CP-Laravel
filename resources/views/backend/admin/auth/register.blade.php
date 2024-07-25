
<style>
    .text-danger{
        color: #d9534f;
    }
</style>
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
                                    <div class="icon"><img src="{{ asset('website') }}/assets/images/signup/icon-01.png" alt="icon" /></div>
                                    Emphasizes that the process of integrating the service with an existing
                                </li>
                                <li>
                                    <div class="icon"><img src="{{ asset('website') }}/assets/images/signup/icon-02.png" alt="icon" /></div>
                                    Suggests that the service is designed to work smoothly with any existing website, highlighting its compatibility
                                </li>
                                <li>
                                    <div class="icon"><img src="{{ asset('website') }}/assets/images/signup/icon-03.png" alt="icon" /></div>
                                    Sell globally with localized currencies and payments
                                </li>
                                <li>
                                    <div class="icon"><img src="{{ asset('website') }}/assets/images/signup/icon-04.png" alt="icon" /></div>
                                    Manage complex subscription models
                                </li>
                                <li>
                                    <div class="icon"><img src="{{ asset('website') }}/assets/images/signup/icon-05.png" alt="icon" /></div>
                                    Stop worrying about VAT and tax jurisdictions
                                </li>
                            </ul>
                        </div>
                        <div class="company__partner">
                            <p class="desc">You’re in good company</p>

                            <div class="partner__wrapper">
                                <div class="partner__item">
                                    <img src="{{ asset('website') }}/assets/images/signup/partner-01.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ asset('website') }}/assets/images/signup/partner-02.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ asset('website') }}/assets/images/signup/partner-03.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ asset('website') }}/assets/images/signup/partner-04.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ asset('website') }}/assets/images/signup/partner-05.png" alt="partner" />
                                </div>
                                <div class="partner__item">
                                    <img src="{{ asset('website') }}/assets/images/signup/partner-06.png" alt="partner" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6">
                    <div class="form__wrapper">
                        <form id="signupForm" action="{{ route('signup.store') }}" method="post">
                            @csrf
                            <div class="form-group">
                                <label for="company_name">{{ __('company_name') }}</label>
                                <input type="text" class="form-control" id="company_name" name="company_name" placeholder="{{ __('enter_your_company_name') }}" value="{{ old('company_name') }}" required />
                                @if ($errors->has('company_name'))
                                    <div class="nk-block-des text-danger pt-2">
                                        <p class="text-danger">{{ $errors->first('company_name') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="flex__input">
                                <div class="form-group">
                                    <label for="first_name">{{ __('first_name') }}</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="{{ __('enter_your_first_name') }}" value="{{ old('first_name') }}" required />
                                    @if ($errors->has('first_name'))
                                        <div class="nk-block-des text-danger pt-2">
                                            <p class="text-danger">{{ $errors->first('first_name') }}</p>
                                        </div>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <label for="last_name">{{ __('last_name') }}</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="{{ __('enter_your_last_name') }}" value="{{ old('last_name') }}" required />
                                    @if ($errors->has('last_name'))
                                        <div class="nk-block-des text-danger pt-2">
                                            <p class="text-danger">{{ $errors->first('last_name') }}</p>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="email">{{ __('email') }}</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="{{ __('enter_your_email') }}" value="{{ old('email') }}" required />
                                @if ($errors->has('email'))
                                    <div class="nk-block-des text-danger pt-2">
                                        <p class="text-danger">{{ $errors->first('email') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="password">{{ __('password') }}</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="{{ __('enter_your_password') }}" required />
                                @if ($errors->has('password'))
                                    <div class="nk-block-des text-danger pt-2">
                                        <p class="text-danger">{{ $errors->first('password') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="form-group">
                                <label for="domain">{{ __('domain') }}</label>
                                <input type="text" class="form-control domain" id="domain" name="domain" placeholder="{{ __('write_here') }}" value="{{ old('domain') }}" />
                                <small>.delix.cloud</small>
                            </div>
                            <div class="form-group">
                                <label for="hear_about_delix">{{ __('where_did_you_hear_about_delix?') }}</label>
                                <select class="form__dropdown form-control" data-width="100%" data-minimum-results-for-search="Infinity" name="hear_about_delix" required>
                                    <option value="google_ads" {{ old('hear_about_delix') == 'google_ads' ? 'selected' : '' }}>{{ __('google_ads') }}</option>
                                    <option value="facebook" {{ old('hear_about_delix') == 'facebook' ? 'selected' : '' }}>{{ __('facebook') }}</option>
                                    <option value="youtube" {{ old('hear_about_delix') == 'youtube' ? 'selected' : '' }}>{{ __('youtube') }}</option>
                                    <option value="email" {{ old('hear_about_delix') == 'email' ? 'selected' : '' }}>{{ __('email') }}</option>
                                    <option value="friend" {{ old('hear_about_delix') == 'friend' ? 'selected' : '' }}>{{ __('friend') }}</option>
                                </select>
                                @if ($errors->has('hear_about_delix'))
                                    <div class="nk-block-des">
                                        <p class="text-danger">{{ $errors->first('hear_about_delix') }}</p>
                                    </div>
                                @endif
                            </div>
                            <div class="custom__checkbox">
                                <input type="checkbox" class="form-check-input" id="policyCheck" name="policyCheck" {{ old('policyCheck') ? 'checked' : '' }} />
                                <label class="form-check-label" for="policyCheck">{{ __('i_agree_privacy_policy_&_terms') }}</label>
                            </div>
                            <div class="btn__submit">
                                <button type="submit" class="btn btn-primary">{{ __('register') }}</button>
                                {{-- <button class="loading_button  btn-primary" type="submit" disabled>
                                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                    {{ __('loading') }}...
                                </button> --}}
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
                                    <img src="{{ asset('website') }}/assets/images/login-whatsapp.png" alt="whatsapps" />
                                </a>
                                <a href="#">
                                    <img src="{{ asset('website') }}/assets/images/login-email.png" alt="email" />
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
            $('#signupForm').on('submit', function(event) {
                event.preventDefault();
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    success: function(response) {
                        toastr.success('Submitted successfully.');
                        $('.submit__btn .loader').addClass('d-none');
                        $('.signupForm').reset();
                    },
                    error: function(xhr, status, error) {
                        console.error('Submission failed:', error);
                        toastr.error('Submission failed. Please try again later.');
                        $('.submit__btn .loader').addClass('d-none');
                    }
                });
            });
        });
    </script>
@endpush
 

