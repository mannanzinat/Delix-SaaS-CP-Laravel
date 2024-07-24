<!-- Pricing Section Start -->
<section class="pricing__section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__title v2 text-center" data-aos="fade-up" data-aos-duration="600">
                    <h4 class="subtitle">Secure the Ultimate Deal</h4>
                    <h2 class="title">Our Price Plan</h2>
                    <p class="desc">Enjoy premium features and dedicated support, all structured to fit your budget and deliver the best possible experience.</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="pricing__wrapper">
                    <div class="custom__tabs text-center" data-aos="fade-up" data-aos-duration="600">
                        <ul class="nav nav-pills" id="pills-monthly-tabs" role="tablist">
                            @php($i = 0)
                            @foreach($plans2 as $plan => $value)
                                @if(count($value) > 0)
                                    @php($i++)
                                    <li class="nav-item" role="presentation">
                                        <button
                                            class="nav-link @if($i == 1) active @endif"
                                            id="{{ $plan }}-pills-monthly-tab"
                                            data-bs-toggle="pill"
                                            data-bs-target="#{{ $plan }}-pills-monthly"
                                            type="button"
                                            role="tab"
                                            aria-controls="{{ $plan }}-pills-monthly"
                                            aria-selected="true"
                                        >
                                        {{ __($plan) }}
                                        </button>
                                    </li>
                                @endif
                            @endforeach
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            @php($i = 0)
                            @foreach($plans2 as $plan => $value)
                                @if(count($value) > 0)
                                    @php($i++)
                                    <div class="tab-pane fade @if($i == 1) active show @endif" id="{{ $plan }}-pills-monthly" role="tabpanel" aria-labelledby="{{ $plan }}-pills-monthly-tab">
                                        <div class="pricing__grid">
                                            @php($j = 0)
                                            @foreach($value as $key => $plan_feature)
                                                @php($j++)
                                                <div class="pricing__item @if($j == 2) active @endif">
                                                    <div class="pricing__header">
                                                        <h3 class="title">{{ $plan_feature->name }}</h3>
                                                        <div class="pricing__tag">
                                                            <span class="price">${{ $plan_feature->price }}</span>
                                                            <del>$20</del>
                                                        </div>
                                                        <p class="desc">{{ $plan_feature->description }}</p>
                                                    </div>

                                                    <ul class="pricing__features">
                                                        <li>
                                                            <i class="fas fa-check"></i>
                                                            <span>{{ $plan_feature->active_merchant }} {{ __('active_merchant') }}</span>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-check"></i>
                                                            <span>{{ $plan_feature->monthly_parcel }} {{ __('monthly_parcel') }}</span>
                                                        </li>
                                                        <li>
                                                            <i class="fas fa-check"></i>
                                                            <span>{{ $plan_feature->active_rider }} {{ __('active_rider') }}</span>
                                                        </li>
                                                        <li class="{{ $plan_feature->custom_domain <= 0 ? 'disable' : '' }}">
                                                            <i class="{{ $plan_feature->active_staff > 0 ? 'fas fa-check' : 'fas fa-minus' }}"></i>
                                                            <span>{{ $plan_feature->active_staff }} {{ __('active_staff') }}</span>
                                                        </li>

                                                        <li class="@if ($plan_feature->custom_domain == 0) disable @endif">
                                                            <i class="{{ $plan_feature->custom_domain == 1 ? 'fas fa-check' : 'fas fa-minus' }}"></i>
                                                            <span>{{ __('custom_domain') }}</span>
                                                        </li>
                                                        <li class="@if ($plan_feature->branded_website == 0) disable @endif">
                                                            <i class="{{ $plan_feature->branded_website == 1 ? 'fas fa-check' : 'fas fa-minus' }}"></i>
                                                            <span>{{ __('branded_website') }}</span>
                                                        </li>
                                                        <li class="@if ($plan_feature->white_level == 0) disable @endif">
                                                            <i class="{{ $plan_feature->white_level == 1 ? 'fas fa-check' : 'fas fa-minus' }}"></i>
                                                            <span>{{ __('white_level') }}</span>
                                                        </li>
                                                        <li class="@if ($plan_feature->merchant_app == 0) disable @endif">
                                                            <i class="{{ $plan_feature->merchant_app == 1 ? 'fas fa-check' : 'fas fa-minus' }}"></i>
                                                            <span>
                                                                {{ __('merchant_app') }}
                                                                <small>$200 Installation Charge (Onetime)</small>
                                                            </span>
                                                        </li>
                                                        <li class="@if ($plan_feature->rider_app == 0) disable @endif">
                                                            <i class="{{ $plan_feature->rider_app == 1 ? 'fas fa-check' : 'fas fa-minus' }}"></i>
                                                            <span>
                                                                {{ __('rider_app') }}
                                                                <small>$200 Installation Charge (Onetime)</small>
                                                            </span>
                                                        </li>
                                                    </ul>

                                                    <div class="pricing__btn">
                                                        <a class="btn btn-outline w-100" href="#">{{ __('buy_now') }}</a>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- Pricing Section End -->