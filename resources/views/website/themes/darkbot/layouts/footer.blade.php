		<!-- Footer Section Start -->
		<footer class="footer__section">  
			<div class="container">
				<div class="row">
					<div class="col-lg-4">
						<div class="footer__wrapper wow fadeInUp" data-wow-delay=".3s">
							<div class="footer__widget">
								<div class="footer__slogun">{{ setting('high_lighted_text', app()->getLocale()) }}</div>
								<div class="footer__toplink">
									<div class="footer__link d-flex align-items-center">
										<div class="icon">
											<img src="{{ static_asset('website/themes/darkbot/assets/images/email.svg')}}" alt="email" />
										</div>
										<a href="mailto:{!! setting('contact_email', app()->getLocale()) !!}">{!! setting('contact_email', app()->getLocale()) !!}</a>
									</div>
									<div class="footer__link d-flex align-items-center">
										<div class="icon">
											<img src="{{ static_asset('website/themes/darkbot/assets/images/phone.svg')}}" alt="phone" />
										</div>
										<a href="mailto:{!! setting('contact_phone', app()->getLocale()) !!}">{!! setting('contact_phone', app()->getLocale()) !!}</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col"></div>
					@if (setting('show_useful_link') &&
                    is_array(setting('footer_useful_link_menu')) &&
                    count(setting('footer_useful_link_menu')) > 0)
					<div class="col-lg-3 col-md-6 col-sm-6">
						<div class="footer__wrapper wow fadeInUp" data-wow-delay=".3s">
							<div class="footer__widget">
								<!-- <h4 class="widget__title">{{ setting('useful_link_title', app()->getLocale()) }}</h4> -->
								@php
									$useful_link_menu =
											headerFooterMenu('footer_useful_link_menu', app()->getLocale()) ?:
											headerFooterMenu('footer_useful_link_menu');
								@endphp
								<div class="widget__wrap">
									<ul class="widget__list">
									@foreach ($useful_link_menu as $usefulLink)
										<li><a href="{{ $usefulLink['url'] }}">{{ $usefulLink['label'] }}</a></li>
									@endforeach
									</ul>
								</div>
							</div>
						</div>
					</div>
					@endif 
					<div class="col"></div>
					<div class="col-lg-3 col-md-6 col-sm-6">
						<div class="footer__wrapper wow fadeInUp" data-wow-delay=".3s">
							<div class="footer__widget">
								<!-- <h4 class="widget__title">Working Time</h4> -->
								<div class="widget__wrap">
									<?php
										$language_switcher = setting('language_switcher');
										if($language_switcher==''){
										  $language_switcher = 1;
										}
									  ?>
									   @php
											$active_locale = '';
											$languages = app('languages');
											$locale_language = $languages->where('locale', app()->getLocale())->first();
											if ($locale_language) {
												$active_locale = $locale_language->name;
											}
										@endphp
									    @if ($language_switcher)
											<div class="language__dropdown">
												<div class="selected">{{ $active_locale }}</div>
												<ul class="list">
													@foreach($languages as $language)
													<li>
														<a href="{{ setLanguageRedirect($language->locale) }}">
														{{ $language->name }}
														</a>
													</li>
													@endforeach
												</ul>
											</div>
										@endif
										@if (setting('show_quick_link') &&
                    is_array(setting('footer_quick_link_menu')) &&
                    count(setting('footer_quick_link_menu')) > 0)
					    @php
							$quick_link_menu = headerFooterMenu('footer_quick_link_menu', app()->getLocale()) ? : headerFooterMenu('footer_quick_link_menu');
						@endphp
									<ul class="widget__list">
										@foreach ($quick_link_menu as $quickLink)
										<li><a href="{{ $quickLink['url'] }}">{{ $quickLink['label'] }}</a></li>
										@endforeach
									</ul>
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="footer__bottom">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="footer__content wow fadeInUp" data-wow-delay=".3s">
								@php
                                    $src = setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : get_media('images/default/logo/logo-green-white.png');
                                @endphp
								<div class="footer__logo">
									<a href="{{ url('/') }}"><img src="{{ $src }}" alt="footer-logo" /></a>
								</div>
								@if(setting('show_payment_method_banner') == 1)
								<div class="footer__apps">
									<a href="#"><img src="{{ setting('payment_method_banner') && @is_file_exists(setting('payment_method_banner')['original_image']) ? get_media(setting('payment_method_banner')['original_image']) : get_media('frontend/img/payment-methods/footer-payment.png') }}" alt="payment" /></a>
								</div>
								@endif
							</div>
						</div>
					</div>
				</div>
			</div>
			@if (setting('show_copyright') != 0)
			<div class="footer__copyright">
				<div class="container">
					<div class="row">
						<div class="col-12">
							<div class="copyright text-center wow fadeInUp" data-wow-delay=".3s">
								<p>
									{!! setting('copyright_title', app()->getLocale()) !!}
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			@endif
		</footer>
		<!-- Footer Section End -->