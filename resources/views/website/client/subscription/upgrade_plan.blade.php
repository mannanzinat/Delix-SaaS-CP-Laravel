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
													<input type="radio" id="starter-{{ $package->billing_period }}" name="radio-group" />
													<label for="starter-{{ $package->billing_period }}">
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
												<!-- <div class="custom__radio">
													<input type="radio" id="professional-{{ $package->billing_period }}" name="radio-group" />
													<label for="professional-{{ $package->billing_period }}">
														<div class="package__left">
															<span class="titles">Professional</span>
															<span class="discount">Get 30% Off</span>
														</div>
														<div class="package__right">
															<span class="price"><del>$130</del>$99</span>
															<span class="duration">/Month</span>
														</div>
													</label>
												</div>
												<div class="custom__radio">
													<input type="radio" id="business-{{ $package->billing_period }}" name="radio-group" />
													<label for="business-{{ $package->billing_period }}">
														<div class="package__left">
															<span class="titles">Business</span>
															<span class="discount">Get Premium Combo Plan</span>
														</div>
														<div class="package__right">
															<span class="price"><del>$220</del>$199</span>
															<span class="duration">/Month</span>
														</div>
													</label>
												</div> -->
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
										<ul class="nav nav-pills" id="pills-tab" role="tablist">
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
										<div class="tab-content" id="">
											<div
												class="tab-pane fade active show"
												id="cards"
												role="tabpanel"
												aria-labelledby="cards-tab"
											>
												<div class="card__details">
													<div class="form__wrapper">
														<div class="flex__input">
															<div class="form-group">
																<label for="name">Card Holder Name</label>
																<input
																	type="text"
																	class="form-control"
																	id="name"
																	placeholder="Card Holder Name"
																/>
															</div>
															<div class="form-group">
																<label for="dateAction">Date</label>
																<input
																	type="text"
																	name="text"
																	class="form-control"
																	id="dateAction"
																	placeholder="MM / YY / DD"
																/>
															</div>
														</div>
														<div class="flex__input">
															<div class="form-group">
																<label for="number">Card Number</label>
																<input
																	type="number"
																	class="form-control"
																	id="number"
																	placeholder="Card Number"
																/>
															</div>
															<div class="form-group">
																<label for="cvc">CVC</label>
																<input type="text" class="form-control" id="cvc" placeholder="CVC" />
															</div>
														</div>
														<div class="upload__btn text-end">
															<a href="subscription-details.html" class="btn btn-gray btn-sm"
																>Subscribe Now</a
															>
														</div>
													</div>
												</div>
											</div>
											<div class="tab-pane fade" id="banks" role="tabpanel" aria-labelledby="banks-tab">
												<div class="bank__details">
													<ul class="list__item">
														<li>
															<span>Bank Name</span>
															Islamia Bank Bangladesh Limited
														</li>
														<li>
															<span>Branch Name</span>
															Mirpur
														</li>
														<li>
															<span>Bank A/C Owner Name</span>
															Foyshal Ahmed
														</li>
														<li>
															<span>Bank A/C Number</span>
															31478547889
														</li>
														<li>
															<span>Routing Number</span>
															31478547889
														</li>
													</ul>
													<div class="upload__input">
														<span>Payment Slip/Proof</span>
														<div class="avatar-upload form-control">
															<label for="fileUpload"> No file uploaded </label>
															<input type="file" class="fileUpload" id="fileUpload" />
															<div class="btn"><i class="fa-solid fa-upload fa-fw"></i>Upload</div>
														</div>
													</div>
													<div class="upload__btn text-end mt-15">
														<a href="#" class="btn btn-gray btn-sm">Submit For Approval </a>
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




