@extends('website.layouts.master')
@section('content')
    <section class="login__section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-10 m-auto">
                    <div class="login__content text-center">
                        <h2 class="title"><span>Verification </span> Process</h2>
                        <p class="desc">Verify Your Email & WhatsApp Here</p>
                    </div>
                    <div class="form__wrapper">
                        <div class="bgPattern__right MoveTopBottom">
                            <img src="{{ asset('website') }}/assets/images/bg-pattern-01.png" alt="pattern" />
                        </div>
                        <div class="bgPattern__right MoveTopBottom">
                            <img src="{{ asset('website') }}/assets/images/bg-pattern-01.png" alt="pattern" />
                        </div>
                        <div class="bgPattern__leftBottom MoveLeftRight">
                            <img src="{{ asset('website') }}/assets/images/bg-pattern-01.png" alt="pattern" />
                        </div>
                        <div class="form-group">
                            <label for="email">Email Verification</label>
                            <div class="verify__alert"><i class="fa-solid fa-circle-info"></i>Your email has been verified</div>
                        </div>
                        <div class="form-group">
                            <label for="whatsapp_number">Verify WhatsApp to Get Started</label>
                            <input type="number" class="form-control domain" id="whatsapp_number" placeholder="Enter Your WhatsApp Number" />
                            <button type="button" class="otp__btn">{{ __('sent_otp') }}</button>
                        </div>
                        <div class="form-group">
                            <label for="whatsapp_otp">Enter OTP</label>
                            <input type="number" class="form-control" id="whatsapp_otp" placeholder="Enter Your OTP Number" />
                        </div>
                        <div class="btn__submit">
                            <button type="submit" class="btn btn-primary sent_otp">Get Started</button>
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
            $('.otp__btn').on('click', function(event) {
                var number = $('#whatsapp_number').val();
                var route  = "{{ route('whatsapp.otp.send') }}";

                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        number: number,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                    },
                    error: function(xhr, status, error) {

                    }
                });
            });
        });

        $(document).ready(function() {
            $('.sent_otp').on('click', function(event) {
                var otp     = $('#whatsapp_otp').val();
                var route   = "{{ route('whatsapp.store.otp') }}";

                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        otp: otp,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {

                    },
                    error: function(xhr, status, error) {

                    }
                });
            });
        });
    </script>
@endpush
