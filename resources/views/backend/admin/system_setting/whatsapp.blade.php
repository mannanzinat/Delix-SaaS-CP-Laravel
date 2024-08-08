@extends('backend.layouts.master')
@section('title', __('whatsApp_settings'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <h3 class="section-title">{{ __('whatsApp_settings') }}</h3>
                <div class="default-tab-list default-tab-list-v2  bg-white redious-border p-20 p-sm-30 mb-4">
                    <form action="{{ route('admin.whatsapp.settings.update') }}" method="POST" enctype="multipart/form-data">
                        @csrf

                        <div class="row mb-3">
                            <div class="col-lg-12">
                                <div class="card h-100">
                                    <div class="card-header">
                                        <h5><i class="las la-key"></i> {{ __('whatsApp_access_token') }}</h5>
                                    </div>
                                    <div class="card-body">
                                        <div class="mb-4">
                                            <label for="access_token" class="form-label"><i class="las la-lock"></i>
                                                {{ __('access_token') }} <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="access_token"
                                                name="access_token"
                                                value="{{ isDemoMode() ? '******************' : old('access_token', setting('access_token')) }}"
                                                placeholder="{{ __('enter_access_token') }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="access_token_error error">{{ $errors->first('access_token') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="phone_number_id"
                                                   class="form-label">{{ __('phone_number_id') }}</label>
                                            <input type="text" class="form-control rounded-2" id="phone_number_id"
                                                   name="phone_number_id"
                                                   value="{{ isDemoMode() ? '******************' : old('phone_number_id', setting('phone_number_id')) }}"
                                                   placeholder="{{ __('enter_phone_number_id') }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="phone_number_id_error error">
                                                    {{ $errors->first('phone_number_id') }}</p>
                                            </div>
                                        </div>
                                        <div class="mb-4">
                                            <label for="business_account_id" class="form-label"><i
                                                    class="las la-briefcase"></i> {{ __('business_account_id') }} <span
                                                    class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="business_account_id"
                                                name="business_account_id"
                                                value="{{ isDemoMode() ? '******************' : old('business_account_id', setting('business_account_id')) }}"
                                                placeholder="{{ __('enter_business_account_id') }}">
                                            <div class="nk-block-des text-danger">
                                                <p class="business_account_id_error error">
                                                    {{ $errors->first('business_account_id') }}</p>
                                            </div>
                                        </div>
                                        <div class="p-2 border rounded bg-light">
                                            @if (
                                                !empty(setting('access_token')) &&
                                                    !empty(setting('scopes')))

                                                <div class="mb-2">
                                                    <strong>{{ __('name') }}:</strong>
                                                    {{ setting('name') }}
                                                    <a class="text-success" target="_blank"
                                                        href="https://business.facebook.com/wa/manage/home/?business_id=&waba_id={{ setting('business_account_id') }}"
                                                        class="small">
                                                        <small>
                                                            <i class="las la-pen"></i>
                                                            {{ __('manage') }}
                                                        </small>
                                                    </a>
                                                </div>

                                                <div class="mb-2">
                                                    <strong>{{ __('expires_at') }}:</strong>
                                                    {{ setting('expires_at') }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>{{ __('app_id') }}:</strong>
                                                    {{ setting('app_id') }}
                                                </div>
                                                <div class="mb-2">
                                                    <strong>{{ __('token_verified') }}:</strong>
                                                    @if (setting('token_verified'))
                                                        <i class="las la-check-circle text-success"></i>
                                                    @else
                                                        <i class="las la-times-circle text-danger"></i>
                                                    @endif
                                                </div>


                                                <?php
                                                $scopes = @setting('scopes') ?? [];
                                                $requiredScopes = config('static_array.whatsapp_required_scopes');
                                                ?>
                                                <ul class="list-inline">
                                                    @foreach ($scopes as $scope)
                                                        <li class="list-inline-item">
                                                            @if (in_array($scope, $requiredScopes))
                                                                <i class="las la-check-circle text-success"></i>
                                                            @else
                                                                <i class="las la-check-circle text-success"></i>
                                                            @endif
                                                            {{ $scope }}
                                                        </li>
                                                    @endforeach
                                                    @foreach ($requiredScopes as $requiredScope)
                                                        @if (!in_array($requiredScope, $scopes))
                                                            <li class="list-inline-item">
                                                                <i class="las la-times-circle text-danger"></i>
                                                                {{ $requiredScope }}
                                                            </li>
                                                        @endif
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </div>
                                        <div class="d-flex justify-content-end align-items-center mt-30 gap-2">
                                            <button type="submit" class="btn sg-btn-primary"><i class="las la-save"></i>
                                                {{ __('save') }}</button>
                                            @include('backend.common.loading-btn', [
                                                'class' => 'btn sg-btn-primary',
                                            ])
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.copy-text').click(function() {
                var inputField = $(this).closest('.input-group').find('input');
                inputField.select();
                document.execCommand("copy");
                toastr.success("{{ __('copied') }}");
            });
        });
        $(document).on('click', '.__js_delete', function() {
            confirmationAlert(
                $(this).data('url'),
                $(this).data('id'),
                'Yes, Delete It!'
            )
        })
        const confirmationAlert = (url, data_id, button_test = 'Yes, Confirmed it!') => {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: button_test,
                confirmButtonColor: '#ff0000'
            }).then((confirmed) => {
                if (confirmed.isConfirmed) {
                    axios.post(url, {
                            data_id: data_id
                        })
                        .then(response => {
                            console.log(response);
                            Swal.fire(
                                response.data.message,
                                '',
                                response.data.status == true ? 'success' : 'error',
                            ).then(() => {
                                if (response.data.status == true) {
                                    location.reload();
                                }
                            });
                        })
                        .catch(error => {
                            console.log(error);
                            Swal.fire(...error.response.data);
                        })
                }
            });
        };
        $('#sync_button').click(function() {
            var button = $(this);
            var url = button.data('url');
            button.html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> ' +
                '{{ __('syncing') }}');
            $.ajax({
                url: url,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    id: button.data('id')
                },
                success: function(data) {
                    button.html('<i class="las la-sync-alt"></i> {{ __('sync') }}');
                    if (data.status) {
                        toastr.success(data.message);
                        location.reload();
                    } else {
                        toastr.error(data.message);
                    }
                },
                error: function(xhr, status, error) {
                    button.html('<i class="las la-sync-alt"></i> {{ __('sync') }}');
                    console.error('Error:', error);
                }
            });
        });
    </script>
@endpush
