@extends('website.layouts.master')
@section('content')
    <section class="login__section">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-10 m-auto">
                    <div class="login__content text-center">
                        <h2 class="title">Create <span>New </span> Password</h2>
                        <p class="desc">Your new password must be different from previously used password</p>
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
                        <form id="reset-password-form" action="{{ route('reset-password.post') }}" method="POST">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" placeholder="Enter New Password" />
                                <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                                <div id="password-error" class="alert__txt"></div>
                            </div>
                            <div class="form-group">
                                <label for="confirm-password">Confirm Password</label>
                                <input type="password" class="form-control" id="confirm-password" name="password_confirmation" placeholder="Confirm New Password" />
                                <i class="toggle-password fa fa-fw fa-eye-slash"></i>
                                <div id="confirm-password-error" class="alert__txt"></div>
                            </div>
                            <div class="btn__submit">
                                <button type="submit" class="btn btn-primary">Reset Password</button>
                                <div class="loading btn btn-primary d-none"><span class="spinner-border"></span>Loading...</div>
                            </div>
                            <div id="form-messages"></div>
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
            $('#reset-password-form').on('submit', function(e) {
                e.preventDefault();

                var form = $(this);
                var formData = form.serialize();
                $('.btn__submit .loading').removeClass('d-none');
                $('.btn__submit button').prop('disabled', true);

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    success: function(response) {
                        toastr.success(response.success);
                        form[0].reset();
                        $('.btn__submit .loading').addClass('d-none');
                        $('.btn__submit button').prop('disabled', false);
                        window.location.href = '/login';
                    },
                    error: function(xhr) {
                        $('.btn__submit .loading').addClass('d-none');
                        const errors = xhr.responseJSON.errors || {};
                        $.each(errors, function(key, value) {
                            const input = $(`#${key}`);
                            if (input.length > 0) {
                                input.addClass('is-invalid');
                                input.siblings('.alert__txt').html('<i class="fa-solid fa-circle-info"></i> ' + value[0]);
                                input.siblings('.alert__txt').show();
                            }
                        });
                        if (xhr.status === 403) {
                            toastr.error(xhr.responseJSON.message || 'Forbidden');
                        } else if (xhr.status === 500) {
                            toastr.error(xhr.responseJSON.message || 'An error occurred while processing your request.');
                        }
                        $('.btn__submit button').prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
