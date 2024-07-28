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
                            <div class="verify__alert"><i class="fa-solid fa-circle-info"></i>{{ __('your_email_has_been_varified') }}</div>
                        </div>
                        <div class="form-group">
                            <label for="phone">Verify WhatsApp to Get Started</label>
                            <input type="number" class="form-control domain" id="phone" placeholder="Enter Your WhatsApp Number" />
                            @if ($errors->has('phone'))
                                <div class="nk-block-des text-danger pt-2">
                                    <p class="text-danger">{{ $errors->first('phone') }}</p>
                                </div>
                            @endif
                            <button type="button" class="otp__btn">{{ __('sent_otp') }}</button>
                        </div>
                        <div class="form-group otp-group" style="display: none;">
                            <label for="otp">Enter OTP</label>
                            <input type="number" class="form-control" id="otp" placeholder="Enter Your OTP Number" />
                            @if ($errors->has('otp'))
                                <div class="nk-block-des text-danger pt-2">
                                    <p class="text-danger">{{ $errors->first('otp') }}</p>
                                </div>
                            @endif
                        </div>
                        <div class="btn__submit">
                            <button type="submit" class="btn submit_otp sent_otp" disabled>Get Started</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <input type="hidden" value="{{ $token }}" class="token" />
@endsection

@push('js')
    <script>
        $(document).ready(function() {
            //otp send
            $('.otp__btn').on('click', function(event) {
                event.preventDefault();
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
                        $('#phone').val('');
                        $('#token').val('');
                        $('.text-danger').remove();
                        $('.otp-group').show();
                        $('.sent_otp').removeAttr('disabled');
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $('.text-danger').remove();
                            if (errors.phone) {
                                $('#phone').after('<div class="nk-block-des text-danger pt-2"><p class="text-danger">' + errors.phone[0] + '</p></div>');
                            }
                            if (errors.token) {
                                $('#token').after('<div class="nk-block-des text-danger pt-2"><p class="text-danger">' + errors.token[0] + '</p></div>');
                            }
                        } else {
                            toastr.error(xhr.responseJSON.message || 'An error occurred');
                        }
                    }
                });
            });

            //confirm otp
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.sent_otp').on('click', function(event) {
                event.preventDefault();
                var token = $('.token').val();
                var otp   = $('#otp').val();
                var route = "{{ route('whatsapp.otp.confirm') }}";

                $.ajax({
                    url: route,
                    type: 'POST',
                    data: {
                        otp: otp,
                        token: token
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success(response.message);
                            if (response.url) {
                                window.location.href = response.url;
                            }
                        } else {
                            toastr.success(response.message);
                        }
                        $('#otp').val('');
                        $('#token').val('');
                        $('.text-danger').remove();
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            $('.text-danger').remove();
                            if (errors.otp) {
                                $('#otp').after('<div class="nk-block-des text-danger pt-2"><p class="text-danger">' + errors.otp[0] + '</p></div>');
                            }
                            if (errors.token) {
                                $('#token').after('<div class="nk-block-des text-danger pt-2"><p class="text-danger">' + errors.token[0] + '</p></div>');
                            }
                        } else {
                            toastr.error(xhr.responseJSON.message || 'An error occurred');
                        }
                    }
                });
            });
        });
    </script>
@endpush
