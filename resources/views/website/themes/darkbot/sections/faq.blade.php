
		<!-- Accordion Section Start -->
		<section class="accordion__section py-130" id="faq">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="section__title text-center wow fadeInUp" data-wow-delay=".2s">
							<h2 class="title">{!! setting('faq_section_title',app()->getLocale()) !!}</h2>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-12">
						<div class="accordion__wrapper wow fadeInUp" data-wow-delay=".3s">
							<div class="accordion" id="faqAccordion">
								@foreach($faqs as $_key=> $faq)
								<div class="accordion__item">
									<div class="accordion__header" id="heading_{{ $_key }}">
										<button
											class="accordion-button {{ $_key=="0" ? '':"collapsed" }}"
											type="button"
											data-bs-toggle="collapse"
											data-bs-target="#collapse_{{ $_key }}"
											aria-expanded="true"
											aria-controls="collapse_{{ $_key }}"
										>
										{{ $faq->lang_question }}
										</button>
									</div>
									<div id="collapse_{{ $_key }}" class="accordion-collapse collapse {{ $_key=="0" ? 'show':"" }}" aria-labelledby="heading_{{ $_key }}" data-bs-parent="#faqAccordion">
										<div class="accordion-body">
											<div class="accordion__content">
												<p>
													{!! $faq->lang_answer!!}
												</p>
											</div>
										</div>
									</div>
								</div>
								@endforeach
							</div>
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Accordion Section End -->
