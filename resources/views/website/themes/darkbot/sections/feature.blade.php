 <!-- Advantage Section Start -->
 <section class="advantage__section" id="features">
	<div class="container">
		<div class="row"> 
			<div class="col-12">
				<div class="section__title text-center wow fadeInUp" data-wow-delay=".2s">
					<h2 class="title">{!! setting('feature_section_title',app()->getLocale()) !!}</h2>
					<p class="desc">{!! setting('feature_section_subtitle',app()->getLocale()) !!}</p>
				</div>
			</div>
		</div>
		<div class="row">
			@foreach($features as $index => $feature)
				@if (!empty($feature))
				
				@if ($index == 2)
						<div class="col-lg-12">
							<div class="advantage__card grid-2 wow fadeInUp" data-wow-delay=".3s">
								<div class="advantage__content pe-0">
									<h4 class="title">{{ @$feature->language->title, app()->getLocale() }}</h4>

									{{-- @dd($feature) --}}
									<p class="desc">
										@foreach(@$feature->language->description as $description)
											{!! $description !!}
										@endforeach
									</p>
								</div>
								<div class="advantage__thumb">
									<img src="{{  getFileLink('original_image', $feature->image) }}" alt="{{ @$feature->language->title, app()->getLocale() }}" />
								</div>
							</div>
						</div>
				@elseif ($index < 2 || ($index >= 3 && $index < 6))
					<div class="col-lg-{{ $index < 2 ? '6' : '4' }} col-md-6">
						<div class="advantage__card wow fadeInUp" data-wow-delay=".3s">
							<div class="advantage__content">
								<h4 class="title">{{ @$feature->language->title, app()->getLocale() }}</h4>
								{{-- @dd($feature) --}}

								<p class="desc">

									@foreach(@$feature->language->description as $description)

									{!! $description !!}
									@endforeach</p>
							</div>
							<div class="advantage__thumb">
								<img src="{{  getFileLink('original_image', $feature->image) }}" alt="{{ @$feature->language->title, app()->getLocale() }}" />
							</div>
						</div>
					</div>
				@endif
				@endif
			@endforeach
		</div>
	</div>
</section>
<!-- Advantage Section End -->