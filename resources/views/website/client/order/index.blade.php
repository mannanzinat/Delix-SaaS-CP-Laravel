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
                            <h4 class="title">Order</h4>
                        </div>
                        <div class="support__wrapper">
                            <!-- Dashboard Search Bar Start -->
                            <div class="dashboard__search-filter">
                                <div class="filter__dropdown">
                                    <select class="form__dropdown form-control" data-width="100%" data-minimum-results-for-search="Infinity">
                                        <option value="1">01</option>
                                        <option value="2">02</option>
                                        <option value="3">03</option>
                                        <option value="4">04</option>
                                        <option value="5">05</option>
                                        <option value="6">06</option>
                                        <option value="7">07</option>
                                        <option value="8">08</option>
                                        <option value="9">09</option>
                                        <option value="10">10</option>
                                    </select>
                                    <div class="title">Orders per page</div>
                                </div>

                                <form action="#" method="post" class="search__form">
                                    <input type="search" class="form-control" name="search" placeholder="Search" />
                                    <button type="submit"><i class="fa-solid fa-magnifying-glass"></i></button>
                                </form>
                            </div>
                            <!-- Dashboard Search Bar End -->
                            <div class="custom__table table-responsive mt-0">
                                <table class="table dashboard__table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="">ID/Subject</th>
                                            <th>Date/Time</th>
                                            <th>Amount</th>
                                            <th>Payment Status</th>
                                            <th class="text-center">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>140246</td>
                                            <td>22 July</td>
                                            <td>$200</td>
                                            <td>
                                                <div class="status btn__danger">Unpaid</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn__group">
                                                    <a href="{{ route('client.order.details') }}" class="copy__btn">
                                                        <img src="{{ asset('website') }}/assets/images/view.svg" alt="view" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>140246</td>
                                            <td>22 July</td>
                                            <td>$200</td>
                                            <td>
                                                <div class="status btn__success">paid</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn__group">
                                                    <a href="{{ route('client.order.details') }}" class="copy__btn">
                                                        <img src="{{ asset('website') }}/assets/images/view.svg" alt="view" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>140246</td>
                                            <td>22 July</td>
                                            <td>$200</td>
                                            <td>
                                                <div class="status btn__danger">Unpaid</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn__group">
                                                    <a href="{{ route('client.order.details') }}" class="copy__btn">
                                                        <img src="{{ asset('website') }}/assets/images/view.svg" alt="view" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>140246</td>
                                            <td>22 July</td>
                                            <td>$200</td>
                                            <td>
                                                <div class="status btn__warning">Partially Paid</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn__group">
                                                    <a href="{{ route('client.order.details') }}" class="copy__btn">
                                                        <img src="{{ asset('website') }}/assets/images/view.svg" alt="view" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>140246</td>
                                            <td>22 July</td>
                                            <td>$200</td>
                                            <td>
                                                <div class="status btn__success">paid</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn__group">
                                                    <a href="{{ route('client.order.details') }}" class="copy__btn">
                                                        <img src="{{ asset('website') }}/assets/images/view.svg" alt="view" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>1</td>
                                            <td>140246</td>
                                            <td>22 July</td>
                                            <td>$200</td>
                                            <td>
                                                <div class="status btn__warning">Partially Paid</div>
                                            </td>
                                            <td class="text-center">
                                                <div class="btn__group">
                                                    <a href="{{ route('client.order.details') }}" class="copy__btn">
                                                        <img src="{{ asset('website') }}/assets/images/view.svg" alt="view" />
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Start -->
                            <nav class="pagination__wrapper">
                                <p class="pagination__show">Showing 1 to 6 of 6 entries</p>
                                <ul class="pagination">
                                    <li class=""><a href="#" class="btn">Previous</a></li>
                                    <li class=""><a href="#">1</a></li>
                                    <li class="active"><a href="#">2</a></li>
                                    <li class=""><a href="#">3</a></li>
                                    <li class=""><a href="#" class="btn">Next</a></li>
                                </ul>
                            </nav>
                            <!-- Pagination End -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

