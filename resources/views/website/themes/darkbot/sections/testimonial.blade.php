		<!-- Testimonial Section Start -->
		<section class="testimonial__section" id="testimonial">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="section__title text-center wow fadeInUp" data-wow-delay=".2s">
							<h2 class="title">{!! setting('testimonial_section_title',app()->getLocale()) !!}</h2>
							<p class="desc">
								{!! setting('testimonial_section_subtitle',app()->getLocale()) !!}
							</p>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-lg-12">
						<div class="swiper testimonial__slider wow fadeInUp" data-wow-delay=".3s">
							<div class="swiper-wrapper"> 
								@foreach($testimonials as $tkey=> $testimonial)
								<!-- Swiper Slide -->
								<div class="swiper-slide">
									<div class="testimonial__item">
										<div class="testimonial__avatar">
											<div class="avatar">
												<img src="{{ getFileLink('80x80',  $testimonial->image) }}" alt="{{ @$testimonial->language->name, app()->getLocale() }}" />
											</div>
											<div class="avatar__content">
												<h4 class="title">{{ @$testimonial->language->name, app()->getLocale() }}</h4>
												<div class="designation">{{ @$testimonial->language->designation, app()->getLocale() }}</div>
											</div>
											{{-- <div class="company__logo">
												<img src="{{ static_asset('website/themes/darkbot/assets/images/logo/spagreen.png')}}" alt="logo" />
											</div> --}}
										</div>
										<div class="testimonial__content">
											<div class="rating">
												@switch($testimonial->rating)
												@case('5')
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													@break
												@case('4')
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													@break
												@case('3')
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													@break
												@case('2')
													<a href="#"><i class="fas fa-star"></i></a>
													<a href="#"><i class="fas fa-star"></i></a>
													@break
												@case('1')
													<a href="#"><i class="fas fa-star"></i></a>
													@break
												@default
											@endswitch
											</div>
											<p class="desc">
												{!! @$testimonial->language->description, app()->getLocale() !!}
											</p>
										</div>
									</div>
								</div>
								@endforeach
							</div>
							<div class="testimonial__pagination">
								<div class="swiper-pagination"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Testimonial Section End -->
