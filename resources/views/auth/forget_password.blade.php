@extends('website.layouts.master')
@section('content')
<section class="login__section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 col-md-10 m-auto">
                <div class="login__content text-center">
                    <h2 class="title"><span>Forget </span> Password</h2>
                    <p class="desc">You can reset your password here</p>
                    <div class="form-group check-mail mt-5 d-none">
                        <div class="verify__alert"><i class="fa-solid fa-circle-info"></i>{{ __('please_check_your_mail_to_recovery_password') }}</div>
                    </div>
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
                    <form id="forgotPasswordForm" action="{{ route('forgot.password-email') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" />
                            <div id="emailError" class="alert__txt"></div>
                        </div>
                        <div class="btn__submit">
                            <button type="submit" class="btn btn-primary">Send</button>
                            <div class="loading btn btn-primary d-none"><span class="spinner-border"></span>Loading...</div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
  @push('js')
    <script>
        $(document).ready(function() {
            $('#forgotPasswordForm').on('submit', function(event) {
                event.preventDefault();

                var form          = $(this);
                var url           = form.attr('action');
                var formData      = form.serialize();
                $('.btn__submit .loading').removeClass('d-none');
                $('.btn__submit button').prop('disabled', true);
                $('#emailError').html('');
    
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success(response.message);
                        $('#forgotPasswordForm')[0].reset();
                        $('.btn__submit .loading').addClass('d-none');
                        $('.btn__submit button').prop('disabled', false);
                        $('.check-mail').removeClass('d-none');
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            var errors = xhr.responseJSON.errors;
                            if (errors && errors.email) {
                                $('#emailError').html('<i class="fa-solid fa-circle-info"></i>' + errors.email[0]);
                            }
                        } else if (xhr.status === 403) {
                            toastr.error(xhr.responseJSON.message || 'Forbidden');
                        } else if (xhr.status === 500) {
                            toastr.error(xhr.responseJSON.message || 'An error occurred while processing your request.');
                        } else {
                            toastr.error('An unexpected error occurred.');
                        }
                        $('.btn__submit .loading').addClass('d-none');
                        $('.btn__submit button').prop('disabled', false);

                    }
                });
            });
        });
    </script>
  @endpush

