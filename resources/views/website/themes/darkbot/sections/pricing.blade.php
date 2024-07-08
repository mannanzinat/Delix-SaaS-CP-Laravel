<!-- Pricing Section Start -->
<section class="pricing__section pb-130" id="price">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section__title text-center wow fadeInUp" data-wow-delay=".2s">
                    <h2 class="title">{!! setting('pricing_section_title', app()->getLocale()) !!}</h2>
                    <p class="desc">
                        {!! setting('pricing_section_subtitle', app()->getLocale()) !!}
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="pricing__grid wow fadeInUp" data-wow-delay=".2s">
                    <!-- Pricing Item -->
                    @php($i = 0)
                    @foreach ($plans as $value)
                        @php($i++)
                        <div class="pricing__item">
                            <div class="pricing__header">
                                <h3 class="title">{{ $value->name }}</h3>
                                <p class="desc">{!! $value->description !!}</p>
                            </div>
                            <div class="pricing__tag">
                                <span>{{ get_symbol() }}</span>
                                <span class="price">{{ $value->price }} <sub>/{{ $value->billing_period }}</sub></span>
                            </div>
                            <ul class="pricing__features">
                                <li>
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('team_limit') }} {{ $value->team_limit }}</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('campaign_limit') }} {{ $value->campaign_limit }}</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('contact_limit') }} {{ $value->contact_limit }}</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('conversation_limit') }} {{ $value->conversation_limit }}</span>
                                </li>
                                <li>
                                    <i class="fa-solid fa-circle-check"></i>
                                    <span>{{ __('telegram_access') }} {{ $value->telegram_access ? 'Yes' : 'No' }}</span>
                                </li>
                            </ul>
                            <div class="pricing__btn">
                                <a class="btn btn-secondary w-100"
                                    href="{{ route('client.upgrade.plan', $value->id) }}">{{ __('buy_now') }}</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Pricing Section End -->
