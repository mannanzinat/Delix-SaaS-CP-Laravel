		<!-- CAll To Action Start -->
		<section class="call__to__action p-0"> 
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="ctaBox__wrapper wow fadeInUp" data-wow-delay=".2s">
							<div class="ctaBox__inner wow fadeInUp" data-wow-delay=".3s">
								<div class="ctaBox__icon">
									<img src="{{ static_asset('website/themes/darkbot/assets/images/union-icon.png')}}" alt="union-icon" />
								</div>
								<div class="ctaBox__content">
									<h2 class="ctaBox__title">{!! setting('cta_title', app()->getLocale()) !!}</h2>
									@if (setting('cta_subtitle', app()->getLocale()))
									<p class="ctaBox__desc">{!! setting('cta_subtitle', app()->getLocale()) !!}</p>
									@endif
								</div>
							</div>
							<a href="#" class="btn btn-primary">{!! setting('cta_main_action_btn_label', app()->getLocale()) !!}<i class="fa-solid fa-arrow-right"></i></a>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- CAll To Action End -->