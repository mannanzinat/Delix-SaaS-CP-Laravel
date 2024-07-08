			@if (!empty($partner_logos) && count($partner_logos) > 0)            
            <!-- BRANDS============================ -->
			<div id="brands" class="pt-80 brands-section">
				<div class="container">	
					<!-- BRANDS TITLE -->
					<div class="row justify-content-center">	
						<div class="col-md-10 col-lg-9">
							<div class="brands-title mb-50">
								<h5 class="s-19">{!! setting('client_section_title',app()->getLocale()) !!}</h5>
							</div>
						</div>
					</div>
					<!-- BRANDS CAROUSEL -->				
					<div class="row">
						<div class="col text-center">	
							<div class="owl-carousel brands-carousel-6">
                                @foreach($partner_logos as $key=>$partner_logo)
								<!-- BRAND LOGO IMAGE -->
								<div class="brand-logo" {{ $key }}>
									<a href="#"><img class="img-fluid" src="{{ getFileLink('original_image', $partner_logo->image) }}" alt="{{ $partner_logo->name }}"></a>
								</div>
                                @endforeach
							</div>	
						</div>
					</div>  <!-- END BRANDS CAROUSEL -->
				</div>	<!-- End container -->
			</div>	<!-- END BRANDS -->
            @endif