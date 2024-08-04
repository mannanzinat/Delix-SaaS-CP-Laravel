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
                            <div class="card">
                                @php
                                    $uniquePackages = $packages->unique('billing_period');
                                @endphp
                                <div class="custom__tabs text-center">
                                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                        @php($i = 0)
                                        @foreach($uniquePackages as $package)
                                            @php($i++)
                                            <li class="nav-item" role="presentation">
                                                <button
                                                    class="nav-link @if($i == 1) active @endif"
                                                    id="{{ $package->billing_period }}-tab"
                                                    data-bs-toggle="pill"
                                                    data-bs-target="#{{ $package->billing_period }}"
                                                    type="button"
                                                    role="tab"
                                                    aria-controls="{{ $package->billing_period }}"
                                                    aria-selected="@if($i == 1) true @else false @endif"
                                                    data-package-id="{{ $package->id }}"
                                                >
                                                    {{ __($package->billing_period) }}
                                                </button>
                                            </li>
                                        @endforeach
                                    </ul>
                                    <div class="tab-content">
                                        @foreach($uniquePackages as $package)
                                            <div
                                                class="tab-pane fade @if($loop->first) active show @endif"
                                                id="{{ $package->billing_period }}"
                                                role="tabpanel"
                                                aria-labelledby="{{ $package->billing_period }}-tab"
                                            >
											<div class="package__category">
												<div class="custom__radio">
													<input type="radio" id="starter-{{ $package->id }}" name="radio-group" value="{{ $package->id }}" />
													<label for="starter-{{ $package->id }}">
														<div class="package__left">
															<span class="titles">{{ __($package->name) }}</span>
															<span class="discount">Get 20% Off</span>
														</div>
														<div class="package__right">
															<span class="price"><del>{{ setting('default_currency') }} 70</del>{{ setting('default_currency') }} . {{ $package->price }}</span>
															<span class="duration">/{{ $package->name }}</span>
														</div>
													</label>
												</div>
											</div>

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
											@if (Session::has('success'))
												<div class="alert alert-success text-center">
													<a href="#" class="close" data-dismiss="alert" aria-label="close">×</a>
													<p>{{ Session::get('success') }}</p>
												</div>
											@endif
                                        >
                                            <form 
                                                role="form" 
                                                action="{{ route('client.stripe.redirect') }}" 
                                                method="post" 
                                                class="require-validation"
                                                data-cc-on-file="false"
                                                data-stripe-publishable-key="{{ env('STRIPE_KEY') }}"
                                                id="payment-form"
                                            >
                                                @csrf
                                                <!-- Hidden input for package ID -->
                                                <input type="hidden" name="package_id" id="package-id" value="" />

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
														<!-- <div class='form-row row'>
															<div class='col-md-12 error form-group hide'>
																<div class='alert-danger alert'>Please correct the errors and try again.</div>
															</div>
														</div>

														<div class="upload__btn text-end">
															<button type="submit" class="btn btn-gray btn-sm">Subscribe Now</button>
														</div> -->

														<div class='form-row row'>
																	<div class='col-md-12 error form-group hide'>
																		<div class='alert-danger alert'>Please correct the errors and try
																			again.</div>
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
                                            <div class="bank__details">
                                                <ul class="list__item">
                                                    <li><span>Bank Name</span>Islamia Bank Bangladesh Limited</li>
                                                    <li><span>Branch Name</span>Mirpur</li>
                                                    <li><span>Bank A/C Owner Name</span>Foyshal Ahmed</li>
                                                    <li><span>Bank A/C Number</span>31478547889</li>
                                                    <li><span>Routing Number</span>31478547889</li>
                                                </ul>
                                                <div class="upload__input">
                                                    <span>Payment Slip/Proof</span>
                                                    <div class="avatar-upload form-control">
                                                        <label for="fileUpload">No file uploaded</label>
                                                        <input type="file" class="fileUpload" id="fileUpload" />
                                                        <div class="btn"><i class="fa-solid fa-upload fa-fw"></i>Upload</div>
                                                    </div>
                                                </div>
                                                <div class="upload__btn text-end mt-15">
                                                    <a href="#" class="btn btn-gray btn-sm">Submit For Approval</a>
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
        </div>
    </div>
</section>
@endsection

@push('js')
	<script src="https://js.stripe.com/v2/"></script>
	<script type="text/javascript">
		$(function() {

		/*------------------------------------------

		--------------------------------------------

		Stripe Payment Code

		--------------------------------------------

		--------------------------------------------*/

		var $form = $(".require-validation");

		$('form.require-validation').bind('submit', function(e) {

			var $form = $(".require-validation"),

			inputSelector = ['input[type=email]', 'input[type=password]',

							'input[type=text]', 'input[type=file]',

							'textarea'].join(', '),

			$inputs = $form.find('.required').find(inputSelector),

			$errorMessage = $form.find('div.error'),

			valid = true;

			$errorMessage.addClass('hide');

		

			$('.has-error').removeClass('has-error');

			$inputs.each(function(i, el) {

			var $input = $(el);

			if ($input.val() === '') {

				$input.parent().addClass('has-error');

				$errorMessage.removeClass('hide');

				e.preventDefault();

			}

			});

		

			if (!$form.data('cc-on-file')) {

			e.preventDefault();

			Stripe.setPublishableKey($form.data('stripe-publishable-key'));

			Stripe.createToken({

				number: $('.card-number').val(),

				cvc: $('.card-cvc').val(),

				exp_month: $('.card-expiry-month').val(),

				exp_year: $('.card-expiry-year').val()

			}, stripeResponseHandler);

			}

		

		});

		

		/*------------------------------------------

		--------------------------------------------

		Stripe Response Handler

		--------------------------------------------

		--------------------------------------------*/

		function stripeResponseHandler(status, response) {

			if (response.error) {

				$('.error')

					.removeClass('hide')

					.find('.alert')

					.text(response.error.message);

			} else {

				/* token contains id, last4, and card type */

				var token = response['id'];


					

				$form.find('input[type=text]').empty();

				$form.append("<input type='hidden' name='stripeToken' value='" + token + "'/>");

				$form.get(0).submit();

			}

		}

		// Handle package selection
		$('button[data-package-id]').click(function() {
			var packageId = $(this).data('package-id');
			$('#package-id').val(packageId);
		});
	});

</script>
@endpush
