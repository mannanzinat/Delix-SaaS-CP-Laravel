@extends('website.layouts.master')
@section('content')
<section class="user__dashboard">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="dashboard__container">
                    @include('website.client.sidebar')
                    <div class="main__containter">
                        <div class="dashboard__wrapper">
                            <!-- card Start -->
                            <div class="card">
                                <div class="card__header">
                                    <h4 class="title">Account Information</h4>
                                </div>
                                <div class="inform__list">
                                    <ul>
                                        <li><span>Name:</span>Jack Anderson</li>
                                        <li><span>Phone Number:</span>01400-620055</li>
                                        <li><span>Email:</span>info@spagreen.net</li>
                                    </ul>
                                </div>
                                <div class="card__btn mt-25">
                                    <a href="#" class="btn btn-gray w-100">Edit Information</a>
                                </div>
                            </div>
                            <!-- card Start -->
                            <div class="card">
                                <div class="card__header">
                                    <h4 class="title">You are connected with</h4>
                                </div>
                                <div class="inform__list">
                                    <ul>
                                        <li>
                                            <span>Email:</span>
                                            <div class="success__btn">Connected</div>
                                        </li>
                                        <li>
                                            <span>WhatsApp:</span>
                                            <div class="warning__btn">Disconnect</div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- card Start -->
                            <div class="card">
                                <div class="card__header">
                                    <h4 class="title">Action Activation</h4>
                                    <p class="desc">Last Login: <span>25 July, 2024</span></p>
                                    <!-- <span class="custom__btn">Set Custom Domain</span> -->
                                </div>
                                <div class="custom__table table-responsive mt-0">
                                    <table class="table dashboard__table">
                                        <thead>
                                            <tr>
                                                <th class="w-100">Date/Time:</th>
                                                <th>IP/Browser:</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="date">21 July</div>
                                                    <div class="time">10:20:10 AM</div>
                                                </td>
                                                <td>
                                                    <div class="date">192.16.15</div>
                                                    <div class="time">Firefox</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="date">21 July</div>
                                                    <div class="time">10:20:10 AM</div>
                                                </td>
                                                <td>
                                                    <div class="date">192.16.15</div>
                                                    <div class="time">Google Crome</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="date">21 July</div>
                                                    <div class="time">10:20:10 AM</div>
                                                </td>
                                                <td>
                                                    <div class="date">192.16.15</div>
                                                    <div class="time">Safari</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="date">21 July</div>
                                                    <div class="time">10:20:10 AM</div>
                                                </td>
                                                <td>
                                                    <div class="date">192.16.15</div>
                                                    <div class="time">Internet Explorer</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="date">21 July</div>
                                                    <div class="time">10:20:10 AM</div>
                                                </td>
                                                <td>
                                                    <div class="date">192.16.15</div>
                                                    <div class="time">Opera Mini</div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="date">21 July</div>
                                                    <div class="time">10:20:10 AM</div>
                                                </td>
                                                <td>
                                                    <div class="date">192.16.15</div>
                                                    <div class="time">Firefox</div>
                                                </td>
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
</section>
@endsection

