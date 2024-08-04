@extends('website.layouts.master')
@section('content')
<section class="user__dashboard">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__container">
                    @include('website.client.sidebar')
                    <div class="main__containter">
                        <div class="subscription__wrapper">
                            <!-- Card Start -->
                            <div class="card">
                                <div class="card__header">
                                    <h4 class="title">Subscription</h4>
                                </div>
                                <div class="information">
                                    <h3 class="inform__title">Welcome! Jack</h3>
                                    <p class="desc">Here is your personal information</p>
                                </div>
                                <div class="inform__list">
                                    <ul>
                                        <li><span>Company Name:</span>Demo Name</li>
                                        <li><span>Next Billing:</span>2024-12-10</li>
                                        <li><span>Active Package:</span>Business</li>
                                    </ul>
                                </div>
                                <div class="subscription__btn mt-25">
                                    <a href="#" class="btn btn-gray">Enable Recurring</a>
                                    <a href="#" class="btn btn-gray">Change Plan</a>
                                    <a href="#" class="btn btn-gray">Cancel Now</a>
                                </div>
                            </div>

                            <div class="card">
                                <div class="custom__tabs text-center">
                                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                                        <li class="nav-item" role="presentation">
                                            <button
                                                class="nav-link active"
                                                id="package-plan-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#package-plan"
                                                type="button"
                                                role="tab"
                                                aria-controls="package-plan"
                                                aria-selected="true"
                                            >
                                                Plan Details
                                            </button>
                                        </li>
                                        <li class="nav-item" role="presentation">
                                            <button
                                                class="nav-link"
                                                id="package-subscription-tab"
                                                data-bs-toggle="pill"
                                                data-bs-target="#package-subscription"
                                                type="button"
                                                role="tab"
                                                aria-controls="package-subscription"
                                                aria-selected="false"
                                            >
                                                Subscription Log
                                            </button>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="">
                                        <div class="tab-pane fade active show" id="package-plan" role="tabpanel" aria-labelledby="package-plan-tab">
                                            <div class="subscription__detail">
                                                <div class="subscription__heading">
                                                    <h4 class="title">Plan Details</h4>
                                                    <p class="desc">
                                                        The table below is the details of your current plan. Including plan name, quote, permission and information.
                                                    </p>
                                                </div>
                                                <div class="custom__table table-responsive mt-0 text-start">
                                                    <table class="table dashboard__table">
                                                        <thead>
                                                            <tr>
                                                                <th class="w-100">Plan Name</th>
                                                                <th class="text-end">Business</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td><div class="date mb-0">Price</div></td>
                                                                <td>$199</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">Active Merchant</div></td>
                                                                <td>500</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">Monthly Parcel</div></td>
                                                                <td>20000</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">Active Rider</div></td>
                                                                <td>20</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">Active Staff</div></td>
                                                                <td>20</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">Custom Domain</div></td>
                                                                <td>yes</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">Branded Website</div></td>
                                                                <td>yes</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">White Level</div></td>
                                                                <td>yes</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">Marchant App</div></td>
                                                                <td>yes</td>
                                                            </tr>
                                                            <tr>
                                                                <td><div class="date mb-0">Rider App</div></td>
                                                                <td>yes</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="package-subscription" role="tabpanel" aria-labelledby="package-subscription-tab">
                                            <div class="subscription__detail">
                                                <div class="subscription__heading">
                                                    <h4 class="title">Subscription Log</h4>
                                                    <p class="desc">Your subscription activity log</p>
                                                </div>
                                                <div class="custom__table table-responsive mt-0 text-start">
                                                    <table class="table dashboard__table">
                                                        <thead>
                                                            <tr>
                                                                <th>#</th>
                                                                <th class="w-100">Date</th>
                                                                <th class="text-end">Business</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>1</td>
                                                                <td>
                                                                    <div class="date mb-0">16 July, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin Active Your Plan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>2</td>
                                                                <td>
                                                                    <div class="date mb-0">10 July, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin Active Your Plan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>3</td>
                                                                <td>
                                                                    <div class="date mb-0">7 July, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin Active Your Plan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>4</td>
                                                                <td>
                                                                    <div class="date mb-0">11 June, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin Active Your Plan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>5</td>
                                                                <td>
                                                                    <div class="date mb-0">20 June, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin Active Your Plan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>6</td>
                                                                <td>
                                                                    <div class="date mb-0">10 May, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>You Cancel Your Subscription</td>
                                                            </tr>
                                                            <tr>
                                                                <td>7</td>
                                                                <td>
                                                                    <div class="date mb-0">11 May, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin Active Your Plan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>8</td>
                                                                <td>
                                                                    <div class="date mb-0">16 May, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin has purchased Advance package for you</td>
                                                            </tr>
                                                            <tr>
                                                                <td>9</td>
                                                                <td>
                                                                    <div class="date mb-0">10 April, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin Active Your Plan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>10</td>
                                                                <td>
                                                                    <div class="date mb-0">07 April, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>Admin Active Your Plan</td>
                                                            </tr>
                                                            <tr>
                                                                <td>11</td>
                                                                <td>
                                                                    <div class="date mb-0">01 April, 2024, <span>11:21:51 AM</span></div>
                                                                </td>
                                                                <td>You Cancel Your Subscription</td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
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




