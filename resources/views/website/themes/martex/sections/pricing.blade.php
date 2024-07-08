  <!-- PRICING-3
            ============================================= -->
            <section id="pricing" class="gr--whitesmoke inner-page-hero pb-60 pricing-section">
                <div class="container">
                    <!-- SECTION TITLE -->	
                    <div class="row justify-content-center">	
                        <div class="col-md-10 col-lg-8">
                            <div class="section-title text-center mb-60">	
                                <h2 class="s-52 w-700">{!! setting('pricing_section_title',app()->getLocale()) !!}</h2>
                                <!-- TOGGLE BUTTON -->
                                <div class="toggle-btn ext-toggle-btn toggle-btn-md mt-30">
                                    <span class="toggler-txt">{{ __('billed_monthly') }}</span>
                                    <label class="switch-wrap">
                                      <input type="checkbox" class="billed_checkbox" id="_checbox" onclick="checkPlans()">
                                      <span class="switcher bg--grey switcher--theme">
                                            <span class="show-annual"></span>
                                            <span class="show-monthly"></span>
                                      </span>
                                    </label>
                                    <span class="toggler-txt">{{ __('billed_yearly') }}</span>
                                    <!-- Text -->	
                                    <p class="color--theme">{!! setting('pricing_section_subtitle',app()->getLocale()) !!}</p>
                                </div>
                            </div>	
                        </div>
                    </div>	<!-- END SECTION TITLE -->	
                    <!-- PRICING TABLES -->
                    <div class="pricing-3-wrapper text-center">
                        <div class="row row-cols-1 row-cols-md-3">
                            @php($i=0)
                            @foreach($plans as $plan=>$value)
                            @php($i++)
                            @if ($value->is_free==1)
                            <div class="col is_free">
                                <div id="pt-3-1" class="p-table pricing-3-table bg--white-100 block-shadow r-12 wow fadeInUp" >
                                    <!-- TABLE HEADER -->
                                    <div class="pricing-table-header">
                                        <!-- Title -->
                                        <h4 class="s-32">{{ $value->name }}</h4>
                                        <!-- Text -->
                                        <p class="color--grey">{!! $value->description !!}</p>
                                        <!-- Price -->	
                                        <div class="price mt-25">
                                            <sup class="color--black">{{get_symbol()}}</sup>								
                                            <span class="color--black">{{$value->price}}</span>
                                            @if ($value->billing_period=="yearly")
                                            <sup class="validity color--grey">{{ __('per_year') }}</sup>
                                            @elseif ($value->billing_period=="monthly")
                                            <sup class="validity color--grey">{{ __('per_month') }}</sup>
                                            @else
                                            <sup class="validity color--grey">{{ $value->billing_period }}</sup>
                                            @endif
                                        </div>
                                    </div>	<!-- END TABLE HEADER -->
                                    <!-- BUTTON -->
                                    <a href="{{ route('client.upgrade.plan', $value->id) }}" class="pt-btn btn btn--theme hover--theme">{{ __('get_started_its_free') }}</a>
                                    <p class="p-sm btn-txt color--grey">{{ __('no_credit_card_required') }}</p>
                                </div>
                            </div> 
                            @else
                            <div class="col plan_{{ $value->billing_period }} {{$value->billing_period=="monthly" ? "d-block":"d-none"  }}">
                           
                                <div id="pt-3-2" class="p-table pricing-3-table bg--white-100 block-shadow r-12 wow fadeInUp " >
                                    <!-- TABLE HEADER -->
                                    <div class="pricing-table-header">
                                        <!-- Title -->
                                        <h4 class="s-32">{{ $value->name }}</h4>
                                        <!-- Text -->	
                                        <p class="color--grey">{!! $value->description !!}</p>
                                        <!-- Price -->	
                                        <div class="price mt-25">
                                            <sup class="color--black">{{get_symbol()}}</sup>								
                                            <span class="color--black">{{$value->price}}</span>
                                            @if ($value->billing_period=="yearly")
                                            <sup class="validity color--grey">{{ __('per_year') }}</sup>
                                            @elseif ($value->billing_period=="monthly")
                                            <sup class="validity color--grey">{{ __('per_month') }}</sup>
                                            @else
                                            <sup class="validity color--grey">{{ $value->billing_period }}</sup>
                                            @endif
                                        </div>
                                    </div>	<!-- END TABLE HEADER -->
                                    <!-- BUTTON -->
                                    <a href="{{ route('client.upgrade.plan', $value->id) }}" class="pt-btn btn btn--theme hover--theme">
                                        {{__('buy_now')}}
                                    </a>
                                    <p class="p-sm btn-txt">{{ __('7_day_money_back_guarantee') }}</p>	
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>	<!-- PRICING TABLES -->
                </div>	   <!-- End container -->
            </section>	<!-- END PRICING-3 -->
