<div class="col-xxl-4 col-xl-6 col-lg-6 col-md-12">
    <div class="payment-box">
        <div class="payment-icon">
            <img src="{{ static_asset('images/payment-icon/paypal.svg') }}" alt="Stripe">
            <span class="title">{{ __('paypal') }}</span>
        </div>
        @can('payment_methods.edit')
        <div class="payment-settings">
            <div class="payment-settings-btn">
                <a href="#" class="btn btn-md sg-btn-outline-primary" data-bs-toggle="modal" data-bs-target="#paypal"><i
                        class="las la-cog"></i> <span>{{ __('setting') }}</span></a>
            </div>

            <div class="setting-check">
                <input type="checkbox" id="is_paypal_activated" value="setting-status-change/is_paypal_activated"
                       class="status-change" {{ setting('is_paypal_activated') ? 'checked' : '' }}>
                <label for="is_paypal_activated"></label>
            </div>
        </div>
        @endcan
    </div>
</div>
<!-- End Payment box -->
<div class="modal fade" id="paypal" tabindex="-1" aria-labelledby="paymentMethodLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <h6 class="sub-title">{{ __('paypal') }} {{ __('configuration') }}</h6>
            <button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <form action="{{ route('payment.gateway') }}" method="post" class="form">@csrf
                <div class="row gx-20">
                    <input type="hidden" name="is_modal" class="is_modal" value="0">
                    <input type="hidden" name="payment_method" value="paypal">
                    <div class="col-12">
                        <div class="d-flex gap-12 sandbox_mode_div mb-4">
                            <input type="hidden" name="is_paypal_sandbox_mode_activated" value="{{ setting('is_paypal_sandbox_mode_activated') }}">
                            <label class="form-label" for="is_paypal_sandbox_mode_activated">{{ __('sandbox_mode') }}</label>
                            <div class="setting-check">
                                <input type="checkbox" value="1" id="is_paypal_sandbox_mode_activated" class="sandbox_mode" @checked(setting('is_paypal_sandbox_mode_activated'))>
                                <label for="is_paypal_sandbox_mode_activated"></label>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">{{ __('client_id') }}</label>
                            <input type="text" class="form-control rounded-2" name="paypal_client_id"
                                   placeholder="{{ __('enter_secret_key') }}"
                                   value="{{ isDemoMode() ? '******************' : old('paypal_client_id',setting('paypal_client_id')) }}">
                            <div class="nk-block-des text-danger">
                                <p class="paypal_client_id_error error"></p>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="mb-4">
                            <label class="form-label">{{ __('client_secret') }}</label>
                            <input type="text" class="form-control rounded-2" name="paypal_client_secret"
                                   placeholder="{{ __('client_secret') }}"
                                   value="{{ isDemoMode() ? '******************' : old('paypal_client_secret',setting('paypal_client_secret')) }}">
                            <div class="nk-block-des text-danger">
                                <p class="paypal_client_secret_error error"></p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- END Permissions Tab====== -->
                <div class="d-flex justify-content-end align-items-center mt-30">
                    <button type="submit" class="btn sg-btn-primary">{{ __('save') }}</button>
                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                </div>
            </form>
        </div>
    </div>
</div>
