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
                            <h4 class="title">Resource/APK</h4>
                            <a href="add-add-ticket.html" class="btn btn-gray"><i class="fas fa-plus"></i>Order History</a>
                        </div>
                        <div class="support__wrapper">
                            <div class="custom__table table-responsive mt-0">
                                <table class="table dashboard__table">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th class="">Date</th>
                                            <th>Title</th>
                                            <th>Version</th>
                                            <th>Platform</th>
                                            <th class="text-center">Option</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>2</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>3</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable disable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>4</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable disable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>5</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable disable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>6</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable disable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>7</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable disable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>8</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable disable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>9</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable disable">Download</div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>10</td>
                                            <td>22-05-2024</td>
                                            <td>Rider App</td>
                                            <td>1.0.0</td>
                                            <td>Android/IOS</td>
                                            <td>
                                                <div class="btn__enable disable">Download</div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!-- Pagination Start -->
                            <nav class="pagination__wrapper">
                                <p class="pagination__show">Showing 1 to 10 of 10 entries</p>
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
