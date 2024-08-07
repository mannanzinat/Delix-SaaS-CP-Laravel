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
                        <div class="form">
                            @php
                                $now                    = now();
                                $formatted_present_time = $now->format('Y-m-d H:i:s');
                            @endphp
                            @if($user->token_expired_at>=$formatted_present_time)
                                <div class="form-group">
                                    <label for="email">Email Verification</label>
                                    <div class="verify__alert"><i class="fa-solid fa-circle-info"></i>{{ __('your_email_has_been_varified') }}</div>
                                </div>
                            @endif
                            <div class="form-group">
                                <label for="phone">Verify WhatsApp to Get Started</label>
                                <input type="number" class="form-control domain" id="phone" placeholder="Enter Your WhatsApp Number" />
                                <div class="alert__txt invalid-feedback"></div>
                                <!-- <button type="button" class="otp__btn">{{ __('sent_otp') }}</button> -->
                                <a href="javascript:void(0)" class="otp__btn resend">Sent OTP</a>
                                <div class="loading btn otp__btn d-none"><span class="spinner-border"></span>Loading...</div>
                                <div class="send d-none">OTP Sent !</div>
                            </div>
                            <div class="form-group otp-group" style="display: none;">
                                <label for="otp">Enter OTP</label>
                                <input type="number" class="form-control" id="otp" placeholder="Enter Your OTP Number" />
                                <div class="alert__txt invalid-feedback"></div>
                            </div>
                            <div class="btn__submit">
                                <button type="submit" class="btn btn-primary disable sent_otp">Get Started</button>
                                <div class="loading btn btn-primary d-none"><span class="spinner-border"></span>Loading...</div> 
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" value="{{ $user->token }}" class="token" />
@endsection

@push('js')
    <script>
        $(document).ready(function() {

            $('.resend').on('click', function(event) {
                event.preventDefault();
                $('.alert__txt').hide();
                $('.form-control').removeClass('is-invalid');
                $('.otp__btn.btn').removeClass('d-none');
                // $('.otp__btn.btn').addClass('disable');
                var phone = $('#phone').val();
                var token = $('.token').val();
                var route = "{{ route('whatsapp.otp.send') }}";

                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        phone: phone,
                        token: token,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        toastr.success(response.message);
                        $('.otp-group').show();
                        $('.sent_otp').removeClass('disable');
                        $('.otp__btn.btn').addClass('d-none');
                    },
                    error: function(xhr) {
                        $('.form-control').removeClass('is-invalid');
                        $('.alert__txt').hide();
                        $('.otp__btn.btn').addClass('d-none');

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors || {};
                            $.each(errors, function(key, value) {
                                const input = $(`#${key}`);
                                if (input.length > 0) {
                                    input.addClass('is-invalid');
                                    input.siblings('.alert__txt').html('<i class="fa-solid fa-circle-info"></i> ' + value[0]);
                                    input.siblings('.alert__txt').show();
                                }
                            });
                        } else if (xhr.status === 500) {
                            toastr.error(xhr.responseJSON.message || 'An error occurred. Please try again.');
                        } else {
                            toastr.error('An unexpected error occurred. Please try again.');
                        }
                    }
                });
            });

            $('.sent_otp').on('click', function(event) {
                event.preventDefault();
                var token   = $('.token').val();
                var otp     = $('#otp').val();
                var route   = "{{ route('whatsapp.otp.confirm') }}";
                var $button = $(this);

                $button.prop('disabled', true); // Disable the button
                $('.btn__submit .loading').removeClass('d-none');

                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        otp: otp,
                        token: token,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            if (response.url) {
                                window.location.href = response.url;
                            }
                        } else {
                            toastr.info(response.message);
                        }
                        $('.btn__submit .loading').addClass('d-none');
                        $('#phone').val('');
                        $('#otp').val('');
                        $button.prop('disabled', false); // Re-enable the button
                    },
                    error: function(xhr) {
                        console.log(xhr.responseJSON.errors);
                        $('.form-group').removeClass('has-error');
                        $('.invalid-feedback').html('').hide();

                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors || {};
                            $.each(errors, function(key, value) {
                                const input = $(`#${key}`);
                                if (input.length > 0) {
                                    input.addClass('is-invalid');
                                    input.siblings('.invalid-feedback').html('<i class="fa-solid fa-circle-info"></i> ' + value[0]);
                                    input.siblings('.invalid-feedback').show();
                                }
                            });
                        } else {
                            toastr.error(xhr.responseJSON.message || 'something_went_wrong_please_try_again.');
                        }
                        $('.btn__submit .loading').addClass('d-none');
                        $button.prop('disabled', false); // Re-enable the button
                    }
                });
            });
        });

    </script>
@endpush


