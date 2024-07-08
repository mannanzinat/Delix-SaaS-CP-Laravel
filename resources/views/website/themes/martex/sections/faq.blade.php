  <!-- FAQs-3
            ============================================= -->
            <section id="faqs" class="pt-100 faqs-section">
                <div class="container">
                    <!-- SECTION TITLE -->
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-9">
                            <div class="section-title mb-70">
                                <!-- Title -->
                                <h2 class="s-48 w-700">{!! setting('faq_section_title',app()->getLocale()) !!}</h2>
                                <!-- Text -->
                                <p class="s-21 color--grey">{!! setting('faq_section_subtitle',app()->getLocale()) !!}</p>
                            </div>
                        </div>
                    </div>
                    <!-- FAQs-3 QUESTIONS -->
                    <div class="faqs-3-questions">
                        <div class="row">
                           <!-- QUESTIONS HOLDER -->
                            <div class="col-lg-6">
                                <div class="questions-holder">
                                    @foreach($faqs as $key=>$faq)
                                    <!-- QUESTION #1 -->
                                    <div class="question mb-35 wow fadeInUp">
                                        <!-- Question -->
                                        <h5 class="s-22 w-700"><span>{{ $key+1 }}.</span>  {{ $faq->lang_question }}</h5>
                                        <!-- Answer -->
                                        <p class="color--grey">{!! $faq->lang_answer!!}
                                        </p>
                                    </div>
                                    @if($key == 2)
                                        @break
                                    @endif
                                    @endforeach
                                </div>
                            </div> <!-- END QUESTIONS HOLDER -->
                          <!-- QUESTIONS WRAPPER -->
                        <div class="col-lg-6">
                            <div class="questions-holder">
                                @foreach($faqs as $key => $faq)
                                    @if($key >= 3 && $key <= 5)
                                        <!-- QUESTION #{{ $key + 1 }} -->
                                        <div class="question mb-35 wow fadeInUp">
                                            <!-- Question -->
                                            <h5 class="s-22 w-700"><span>{{ $key + 1 }}.</span> {{ $faq->lang_question }}</h5>
                                            <!-- Answer -->
                                            <p class="color--grey">{!! $faq->lang_answer!!}</p>
                                        </div>
                                    @endif
                                    @if($key == 5)
                                        @break
                                    @endif
                                @endforeach
                            </div>
                        </div> <!-- END QUESTIONS HOLDER -->
                        </div> <!-- End row -->
                    </div> <!-- END FAQs-3 QUESTIONS -->
                    <!-- MORE QUESTIONS LINK -->
                   <!-- <div class="row">
                        <div class="col">
                            <div class="more-questions mt-40">
                                <div class="more-questions-txt bg--white-400 r-100">
                                    <p class="p-lg">Have any questions?
                                        <a href="contacts.html" class="color--theme">Get in Touch</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>-->
                </div> <!-- End container -->
            </section> <!-- END FAQs-3 -->