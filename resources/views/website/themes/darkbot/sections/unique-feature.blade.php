		<!-- Feature Section Start -->
		<section class="feature__section p-0" id="feature">
			<div class="container">
				<div class="featureBox__wrapper">
					<div class="row"> 
						<div class="col-12">
							<div class="section__title text-center wow fadeInUp" data-wow-delay=".2s">
								<h2 class="title">{!! setting('unique_feature_section_title',app()->getLocale()) !!}</h2>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-12">
							<div class="featureBox__inner">
								@foreach($unique_features as $key => $unique_feature)
								<!-- FeatureBox Start -->
								<div class="featureBox wow fadeInUp" data-wow-delay=".3s">
									<div class="featureBox__icon">
										<i class="fa-solid fa-circle-check"></i>
									</div>
									<div class="featureBox__content">
										<h4 class="title">{{ @$unique_feature->language->title, app()->getLocale() }}</h4>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Feature Section End -->
