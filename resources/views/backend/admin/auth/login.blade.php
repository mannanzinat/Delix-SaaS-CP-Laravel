{{-- <style>
  #html_element div {
      width: 100% !important;
  }

  #html_element #rc-anchor-container {
      width: auto !important;
  }
  #html_element iframe {
      width: 100% !important;
  }
</style> --}}
@extends('website.layouts.master')
  @section('content')
    <section class="login__section">
      <div class="container">
        <div class="row">
          <div class="col-lg-6 col-md-10 m-auto">
            <div class="login__content text-center">
              <h2 class="title"><span>Login </span> & Started</h2>
              <p class="desc">Create an account and experience how Delix works.</p>
              @if(request()->has('registration_success'))
                  <div class="form-group mt-5">
                      <div class="verify__alert">
                          <i class="fa-solid fa-circle-info"></i>
                          {{ __('registration_successfully_please_check_your_mail_to_verify') }}
                      </div>
                  </div>
              @elseif(request()->has('registration_error'))
                  <div class="form-group mt-5">
                      <div class="verify__alert">
                          <i class="fa-solid fa-circle-info"></i>
                          {{ __('something_went_wrong_please_try_again') }}
                      </div>
                  </div>
              @endif
            </div>
            <div class="form__wrapper">
              <div class="bgPattern__right MoveTopBottom">
                <img src="{{ static_asset('website') }}/assets/images/bg-pattern-01.png" alt="pattern" />
              </div>
              <div class="bgPattern__right MoveTopBottom">
                <img src="{{ static_asset('website') }}/assets/images/bg-pattern-01.png" alt="pattern" />
              </div>
              <div class="bgPattern__leftBottom MoveLeftRight">
                <img src="{{ static_asset('website') }}/assets/images/bg-pattern-01.png" alt="pattern" />
              </div>
              <form class="loginForm" method="POST" action="{{ route('postlogin') }}">
                @csrf
                <div class="form-group">
                  <label for="email">{{ __('email') }}</label>
                  <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Enter Your Email Address" />
                  @if ($errors->has('email'))
                      <div class="alert__txt"><i class="fa-solid fa-circle-info"></i>{{ $errors->first('email') }}</div>
                  @endif
                </div>
                <div class="form-group">
                  <label for="password">{{ __('password') }}</label>
                  <input type="password" class="form-control" id="password" name="password" placeholder="Enter Your Password" />
                  <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                  <div id="password-error" class="alert__txt"></div>
                  @if ($errors->has('password'))
                      <div class="alert__txt"><i class="fa-solid fa-circle-info"></i>{{ $errors->first('password') }}</div>
                  @endif
                </div>
                @if (setting('is_recaptcha_activated') && setting('recaptcha_site_key'))
                  <div class="my-4">
                    <div id="html_element" class="g-recaptcha" data-sitekey="{{setting('recaptcha_site_key')}}"></div>
                  </div>
                @endif
                <div class="flex__input">
                  <div class="form-group mb-0">
                    <div class="custom__checkbox">
                      <input type="checkbox" class="form-check-input" id="policyCheck" name="policy_check" />
                      <label class="form-check-label mb-0" for="policyCheck">I agree Privacy Policy & Terms</label>
                    </div>
                    @if ($errors->has('policy_check'))
                        <div class="alert__txt"><i class="fa-solid fa-circle-info"></i>{{ $errors->first('policy_check') }}</div>
                    @endif
                  </div>
                  <a href="{{ route('password.forgot') }}" class="forget">Forgot password?</a>
                </div>
                <div class="btn__submit">
                  <button type="submit" class="btn btn-primary">{{ __('login') }}</button>
                </div>
                <p class="account text-center">
                  Already have an account?
                  <a href="{{ route('register') }}">Sign up</a>
                  in instead
                </p>
                <div class="devider text-center">or</div>
                <div class="instant__login text-center">
                  You Can Sign up Using
                  <div class="login__icon">
                    <a href="#">
                      <img src="{{ static_asset('website') }}/assets/images/login-whatsapp.png" alt="whatsapps" />
                    </a>
                    <a href="{{ route('login.google') }}">
                      <img src="{{ static_asset('website') }}/assets/images/login-email.png" alt="email" />
                    </a>
                  </div>
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
    </section>
  @endsection
  @push('js')
    <!--====== ReCAPTCHA ======-->
    <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script>

    @if ($errors->any())
    <script>
            @foreach ($errors->all() as $error)
                toastr.error('{{ $error }}');
            @endforeach
    </script>
    @endif
    @if (setting('is_recaptcha_activated') && setting('recaptcha_site_key'))
    <script type="text/javascript">
        var onloadCallback = function() {
        grecaptcha.render('html_element', {
            'sitekey' : '{{setting('recaptcha_site_key')}}',
            'size' : 'lg'
        });
        };
    </script>
    @endif
  @endpush
