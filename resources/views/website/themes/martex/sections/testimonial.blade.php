            <!-- TESTIMONIALS-2
            ============================================= -->
            <section id="testimonial" class="pt-100 reviews-section pb-100">
                <div class="container">
                    <!-- SECTION TITLE -->
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-9">
                            <div class="section-title mb-70">
                                <!-- Title -->
                                <h2 class="s-48 w-700">{!! setting('testimonial_section_title',app()->getLocale()) !!}</h2>
                                <!-- Text -->
                                <p class="s-21 color--grey">{!! setting('testimonial_section_subtitle',app()->getLocale()) !!}</p>
                            </div>
                        </div>
                    </div>
                    <!-- TESTIMONIALS-2 WRAPPER -->
                    <div class="reviews-2-wrapper rel shape--02 shape--whitesmoke">
                        <div class="row align-items-center row-cols-1 row-cols-md-2">
                            @foreach($testimonials as $testimonial)
                            <!-- TESTIMONIAL #1 -->
                            <div class="col">
                                <div id="rw-2-1" class="review-2 bg--white-100 block-shadow r-08">
                                    <!-- Quote Icon -->
                                    <div class="review-ico ico-65"><span class="flaticon-quote"></span></div>
                                    <!-- Text -->
                                    <div class="review-txt">
                                        <!-- Text -->
                                        <p>{!! @$testimonial->language->description, app()->getLocale() !!}
                                        </p>
                                        <!-- Author -->
                                        <div class="author-data clearfix">
                                            <!-- Avatar -->
                                            <div class="review-avatar">
                                                <img src="{{ getFileLink('80x80',  $testimonial->image) }}" alt="review-avatar">
                                            </div>
                                            <!-- Data -->
                                            <div class="review-author">
                                                <h6 class="s-18 w-700">{{ @$testimonial->language->name, app()->getLocale() }}</h6>
                                                <p class="p-sm">{{ @$testimonial->language->title }}</p>
                                            </div>
                                        </div> <!-- End Author -->
                                    </div> <!-- End Text -->
                                </div>
                            </div> <!-- END TESTIMONIAL #1 -->
                            @endforeach

                        </div> <!-- End row -->
                    </div> <!-- END TESTIMONIALS-2 WRAPPER -->
                </div> <!-- End container -->
            </section> <!-- END TESTIMONIALS-2 -->
