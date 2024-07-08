
		<!-- Marketing Advantage Section Start -->
		@if (!empty($advantages) && count($advantages) > 0)
		<section class="marketing__advantage pt-80" id="advantage"> 
			<div class="container">
				<div class="row">   
					<div class="col-lg-10 m-auto">
						<div class="row">
							@foreach($advantages as $index=>$advantage) 
							@if ($index < 2)
							<div class="col-lg-6 col-md-6">
								<div class="advantage__card wow fadeInUp" data-wow-delay=".3s">
									<div class="advantage__content">
										<h4 class="title">{{ @$advantage->language->title}}</h4>
										<p class="desc">{!! @$advantage->language->description!!}</p>
									</div>
									<div class="advantage__thumb">
										<img src="{{ static_asset(@$advantage->image['original_image'])}}" alt="{{ @$advantage->language->title}}" />
									</div>
								</div>
							</div>
							@else
								{{-- @break --}}
								<div class="col-lg-12">
									<div class="advantage__card grid-2 wow fadeInUp" data-wow-delay=".3s">
										<div class="advantage__content pe-0">
											<h4 class="title">{{ @$advantage->language->title}}</h4>
										</div>
										<div class="advantage__thumb">
											<img src="{{ static_asset(@$advantage->image['original_image'])}}" alt="{{ @$advantage->language->title}}" />
										</div>
									</div>
								</div>
							@endif
							@endforeach
						
							
						</div>
					</div>
				</div>
			</div>
		</section>
		@endif
		<!-- Marketing Advantage Section End -->