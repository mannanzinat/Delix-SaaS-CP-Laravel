@extends('website.layouts.master')
@section('content')
<section class="user__dashboard">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__container">
                    @include('website.client.sidebar')
                    <div class="main__containter">
                        <div class="dashboard__header">
                            <h4 class="title">Order Details</h4>
                        </div>
                        <div class="support__wrapper">
                            <div class="order__detsils">
                                <div class="flex__input grid-3">
                                    <!-- Card Start -->
                                    <div class="card">
                                        <div class="card__header">
                                            <h4 class="title">Customer Info</h4>
                                        </div>
                                        <div class="inform__list">
                                            <ul>
                                                <li><span>Name:</span>Jack Anderson</li>
                                                <li><span>Phone Number:</span><a href="tel:01400620055">01400-620055</a></li>
                                                <li><span>Email:</span><a href="mailto:info@spagreen.net">info@spagreen.net</a></li>
                                                <li><span>Address:</span>Banani, Dhaka</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Card Start -->
                                    <div class="card">
                                        <div class="card__header">
                                            <h4 class="title">Company Info</h4>
                                        </div>
                                        <div class="inform__list">
                                            <ul>
                                                <li><span>Name:</span>Jack Anderson</li>
                                                <li><span>Phone Number:</span><a href="tel:01400620055">01400-620055</a></li>
                                                <li><span>Email:</span><a href="mailto:info@spagreen.net">info@spagreen.net</a></li>
                                                <li><span>Address:</span>Mirpur DOHS, Dhaka</li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Card Start -->
                                    <div class="card">
                                        <div class="card__header">
                                            <h4 class="title">Invoice Info</h4>
                                        </div>
                                        <div class="inform__list">
                                            <ul>
                                                <li><span>Order Number:</span>SL_1406</li>
                                                <li>
                                                    <span>Payment Status:</span>
                                                    <div class="btn__success">paid</div>
                                                </li>
                                                <li>
                                                    <span>Status:</span>
                                                    <div class="btn__success">Completed</div>
                                                </li>
                                                <li>&nbsp;</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="custom__table table-responsive mt-0">
                                <table class="table dashboard__table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="">Item</th>
                                            <th>Unit Price</th>
                                            <th>Discount</th>
                                            <th>Tax</th>
                                            <th class="text-center">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>Subscription Plan (Business)</td>
                                            <td>$260</td>
                                            <td>$20</td>
                                            <td>$0</td>
                                            <td class="text-center">$200</td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>Marchant App</td>
                                            <td>$260</td>
                                            <td>$20</td>
                                            <td>$0</td>
                                            <td class="text-center">$200</td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>Rider App</td>
                                            <td>$260</td>
                                            <td>$20</td>
                                            <td>$0</td>
                                            <td class="text-center">$200</td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>Rider App</td>
                                            <td>$260</td>
                                            <td>$20</td>
                                            <td>$0</td>
                                            <td class="text-center">$200</td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>Rider App</td>
                                            <td>$260</td>
                                            <td>$20</td>
                                            <td>$0</td>
                                            <td class="text-center">$200</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="row">
                                <div class="col-lg-6"></div>
                                <div class="col-lg-6">
                                    <div class="custom__table table-responsive">
                                        <table class="table dashboard__table">
                                            <thead>
                                                <tr>
                                                    <th class="">Details</th>
                                                    <th class="text-end">Amount</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Tax</td>
                                                    <td>$20</td>
                                                </tr>
                                                <tr>
                                                    <td>Discount</td>
                                                    <td>$20</td>
                                                </tr>
                                                <tr>
                                                    <td>Grand Total</td>
                                                    <td>$4000</td>
                                                </tr>
                                                <tr>
                                                    <td>Paid</td>
                                                    <td>$4000</td>
                                                </tr>
                                                <tr>
                                                    <td>Due</td>
                                                    <td>$0</td>
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
</section>
@endsection

