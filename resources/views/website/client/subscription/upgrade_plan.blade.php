@extends('website.layouts.master')
@section('content')
<section class="user__dashboard">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__container">
                    @include('website.client.sidebar')
                    <div class="main__containter">
                        <div class="subscription__wrapper">
                            <div class="dashboard__header">
                                <h4 class="title">Subscription Package</h4>
                            </div>
                            @php
                                $packagesByPeriod = $packages->groupBy('billing_period');
                            @endphp

                            <div class="card">
                                <div class="custom__tabs text-center">
                                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                        @foreach($packagesByPeriod as $billingPeriod => $packages)
                                            <li class="nav-item" role="presentation">
                                                <button
                                                    class="nav-link @if($loop->first) active @endif"
                                                    id="{{ $billingPeriod }}-tab"
                                                    data-bs-toggle="pill"
                                                    data-bs-target="#{{ $billingPeriod }}"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="{{ $billingPeriod }}"
                                                    aria-selected="@if($loop->first) true @else false @endif"
                                                >
                                                    {{ __($billingPeriod) }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">
                                        @foreach($packagesByPeriod as $billingPeriod => $packages)
                                            <div
                                                class="tab-pane fade @if($loop->first) active show @endif"
                                                id="{{ $billingPeriod }}"
                                                role="tabpanel"
                                                aria-labelledby="{{ $billingPeriod }}-tab"
                                            >
                                                @foreach($packages as $package)
                                                    <div class="package__category">
                                                        <div class="custom__radio">
                                                            <input type="radio" id="starter-{{ $package->id }}" data-package-id="{{ $package->id }}" class="package" name="radio-group" value="{{ $package->id }}" />
                                                            <label for="starter-{{ $package->id }}">
                                                                <div class="package__left">
                                                                    <span class="titles">{{ __($package->name) }}</span>
                                                                    <span class="discount">Get 20% Off</span>
                                                                </div>
                                                                <div class="package__right">
                                                                    <span class="price">
                                                                        <del>{{ setting('default_currency') }} 70</del>{{ setting('default_currency') }} {{ $package->price }}
                                                                    </span>
                                                                    <span class="duration">/{{ $package->name }}</span>
                                                                </div>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach

                                                <div class="free__plan">
                                                    <div class="plan__left">
                                                        <span class="titles">Free Plan</span>
                                                        <span class="discount">Get free forever</span>
                                                    </div>
                                                    <div class="plan__right">
                                                        <a href="#" class="btn btn-primary">Start Free</a>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="dashboard__header">
                                <h4 class="title">Payment Method</h4>
                            </div>
                            <div class="card">
                                <div class="custom__tabs text-center">
                                    <ul class="nav nav-pills" id="payment-method-tabs" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button
                                                class="nav-link active"
                                                id="cards-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#cards"
                                                type="button"
                                                role="tab"
                                                aria-controls="cards"
                                                aria-selected="true"
                                            >
                                                Card
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button
                                                class="nav-link"
                                                id="banks-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#banks"
                                                type="button"
                                                role="tab"
                                                aria-controls="banks"
                                                aria-selected="false"
                                            >
                                                Bank
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div
                                            class="tab-pane fade active show"
                                            id="cards"
                                            role="tabpanel"
                                            aria-labelledby="cards-tab"
                                        >
                                            <form 
                                                role="form" 
                                                action="{{ route('client.stripe.redirect') }}" 
                                                method="post" 
                                                class="require-validation"
                                                data-cc-on-file="false"
                                                data-stripe-publishable-key="{{ setting('stripe_key') }}"
                                                id="payment-form"
                                            >
                                                @csrf
                                                <!-- Hidden input for package ID -->
                                                <input type="hidden" name="package_id" id="package-id" value="" />
                                                <input type="hidden" name="trx_id" id="trx_id" value="{{ $trx_id }}" />
                                                <div class="card__details">
                                                    <div class="form__wrapper">
                                                        <div class="flex__input">
                                                            <div class='form-group required'>
                                                                <label class='control-label'>Name on Card</label>
                                                                <input class='form-control' size='4' type='text' required>
                                                            </div>
                                                            <div class='form-group required'>
                                                                <label class='control-label'>Card Number</label>
                                                                <input autocomplete='off' class='form-control card-number' size='20' type='text' required>
                                                            </div>
                                                        </div>
                                                        <div class="flex__input">
                                                            <div class='form-group'>
                                                                <label for="cvc">CVC</label>
                                                                <input autocomplete='off' class='form-control card-cvc' placeholder='ex. 311' size='4' type='text' required>
                                                            </div>
                                                        </div>
                                                        <div class="flex__input">
                                                            <div class='form-group expiration required'>
                                                                <label class='control-label'>Expiration Month</label>
                                                                <input class='form-control card-expiry-month' placeholder='MM' size='2' type='text' required>
                                                            </div>
                                                            <div class='form-group expiration required'>
                                                                <label class='control-label'>Expiration Year</label>
                                                                <input class='form-control card-expiry-year' placeholder='YYYY' size='4' type='text' required>
                                                            </div>
                                                        </div>
                                                        <div class='form-row row'>
                                                            <div class='col-md-12 error form-group hide'>
                                                                <div class='alert-danger alert'>Please correct the errors and try again.</div>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-xs-12">
                                                                <button class="btn btn-primary btn-lg btn-block" type="submit">Pay Now ($100)</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                        <div class="tab-pane fade" id="banks" role="tabpanel" aria-labelledby="banks-tab">
                                            <form action="{{ route('client.offline.claim') }}" class="form-validate offline_form"
                                              method="POST" enctype="multipart/form-data">
                                                @csrf
                                                    <input type="hidden" name="plan_id" value="">
                                                    <input type="hidden" name="trx_id" id="trx_id" value="{{ $trx_id }}" />
                                                    <div class="bank__details">
                                                        <div class="list__item wrapper">
                                                            {!! setting('offline_payment_instruction') !!}
                                                        </div>

                                                        <div class="upload__input">
                                                            <div class="text-start mb-2">Payment Slip/Proof</div>
                                                            <div class="avatar-upload form-control">
                                                                <label for="fileUpload">No file uploaded</label>
                                                                <input type="file" class="fileUpload" name="document" id="fileUpload" />
                                                                <div class="btn"><i class="fa-solid fa-upload fa-fw"></i>Upload</div>
                                                            </div>
                                                        </div>
                                                        <div class="upload__btn text-end mt-15">
                                                            <button type="submit" class="btn btn-gray btn-sm">Submit For Approval</button>
                                                        </div>
                                                    </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@push('js')
<script src="https://js.stripe.com/v2/"></script>
<script type="text/javascript">
    // $(function() {
    //     var $form = $(".require-validation");

    //     // Handle form submission
    //     $form.on('submit', function(e) {
    //         var $form = $(this);
    //         var $inputs = $form.find('input[type=text], input[type=password], input[type=email], textarea');
    //         var $errorMessage = $form.find('div.error');
    //         var valid = true;

    //         $errorMessage.addClass('hide');
    //         $('.has-error').removeClass('has-error');

    //         // Validate required fields
    //         $inputs.each(function() {
    //             var $input = $(this);
    //             if ($input.val() === '') {
    //                 $input.parent().addClass('has-error');
    //                 $errorMessage.removeClass('hide');
    //                 valid = false;
    //             }
    //         });

    //         if (!valid) {
    //             e.preventDefault();
    //             return;
    //         }

    //         if (!$form.data('cc-on-file')) {
    //             e.preventDefault();

    //             Stripe.setPublishableKey($form.data('stripe-publishable-key'));

    //             Stripe.createToken({
    //                 number: $form.find('.card-number').val(),
    //                 cvc: $form.find('.card-cvc').val(),
    //                 exp_month: $form.find('.card-expiry-month').val(),
    //                 exp_year: $form.find('.card-expiry-year').val()
    //             }, function(status, response) {
    //                 if (response.error) {
    //                     // Show error in the form
    //                     $form.find('.error').removeClass('hide').find('.alert').text(response.error.message);
    //                 } else {
    //                     // Get the token ID
    //                     var token = response.id;
    //                     $form.append($('<input type="hidden" name="stripeToken"/>').val(token));
    //                     $form.get(0).submit();
    //                 }
    //             });
    //         }
    //     });

    //     // Update package ID on tab click
    //     $('#pills-tab button').on('click', function() {
    //         var packageId = $(this).data('package-id');
    //         $('#package-id').val(packageId);
    //     });
    // });

    $(document).ready(function(){
        $.fn.serializeFormJSON = function () {
            var o = {};
            var a = this.serializeArray();
            $.each(a, function () {
                if (o[this.name] !== undefined) {
                    if (!o[this.name].push) {
                        o[this.name] = [o[this.name]];
                    }
                    o[this.name].push(this.value || '');
                } else {
                    o[this.name] = this.value || '';
                }
            });
            return o;
        };

        // Click event handler for radio buttons
        $('input[type="radio"][name="radio-group"]').on('change', function() {
            var selectedPlanId = $(this).data('package-id');
            $('input[name="plan_id"]').val(selectedPlanId);
        });

        $('form.offline_form').on('submit', function (e) {
            e.preventDefault();
            var button = $(this).find('button[type="submit"]');
        
            // Prevent multiple submissions
            if (button.hasClass('loading_button')) {
                return;
            }
        
            button.addClass('loading_button');
        
            var formData = new FormData(this); // Create FormData object
        
            // Debugging: Log FormData content
            for (var pair of formData.entries()) {
                console.log(pair[0]+ ', '+ pair[1]);
            }

            $.ajax({
                type: 'POST',
                url: $(this).attr('action'),
                data: formData,
                contentType: false, // Important: set contentType to false
                processData: false, // Important: set processData to false
                beforeSend: function () {
                    $('.loading-btn').addClass('loading');
                },
                success: function (response) {
                    console.log(response);
                    if (response.status === true) {
                        window.location.href = response.redirect_to;
                    }
                },
                error: function (xhr, status, error) {
                    console.error(xhr.responseText);
                },
                complete: function () {
                    button.removeClass('loading_button');
                    $('.loading-btn').removeClass('loading');
                }
            });
        });
    });


</script>
@endpush
