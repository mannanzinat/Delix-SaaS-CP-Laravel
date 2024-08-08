@extends('website.layouts.master')
@section('content')
<section class="user__dashboard">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__container">
                    @include('website.client.sidebar')
                    <div class="main__containter">
                        <!-- card Start -->
                        <div class="card">
                            <div class="app__order text-center">
                                <h5 class="app__label">Your subscription plan is free and it will not allow to order our Marchant App.</h5>
                                <div class="verify__warning"><i class="fa-solid fa-circle-info"></i>Enable for order Marchant App</div>
                                <p class="desc">For Marchant App need to upgrade your plan.</p>
                                <div class="app__btn">
                                    <a href="#" class="btn btn-gray btn-sm">Upgrade Now</a>
                                </div>
                            </div>
                        </div>

                        <!-- card Start -->
                        <div class="card">
                            <div class="app__order text-center">
                                <h5 class="app__label">You are eligible to order your Merchant App!</h5>
                                <div class="verify__success"><i class="fa-solid fa-circle-info"></i>You are allow for order Marchant App</div>
                                <p class="desc">For Marchant App need to order here.</p>
                                <div class="app__btn">
                                    <a href="{{ route('client.merchant-app.details') }}" class="btn btn-gray btn-sm">Order Now</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
