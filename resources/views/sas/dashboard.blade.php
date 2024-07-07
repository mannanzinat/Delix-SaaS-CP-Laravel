@extends('backend.layouts.master')
@section('title', __('dashboard'))
@push('css')
    <link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
@section('mainContent')
    <section class="oftions">

        <div class="container-fluid">
            <div class="row">
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6">
                    <div class="statistics-card bg-white color-success redious-border mb-20 p-20 p-md-20">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="statistics-info mb-3">
                                    <h6>{{ __('total_cod') }}</h6>
                                    <h4>0</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="statistics-gChart mb-20 mb-lg-0">
                                    <canvas id="statisticsChart1"></canvas>
                                </div>
                            </div>
                            <div class="statistics-footer d-flex align-items-center gap-3">
                                <p class="sales-price">54</p>
                                <h6>{{ __('since_last_month') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- End Statistics Chart1 -->
                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6">
                    <div class="statistics-card bg-white color-warning redious-border mb-20 p-20 p-sm-20">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="statistics-info mb-3">
                                    <h6>{{ __('total_earning') }}</h6>

                                    <h4>56</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="statistics-gChart mb-20 mb-lg-0">
                                    <canvas id="statisticsChart2"></canvas>
                                </div>
                            </div>
                            <div class="statistics-footer d-flex align-items-center gap-3">
                                <p class="sales-price">78</p>
                                <h6>{{ __('since_last_month') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Statistics Chart2 -->

                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6">
                    <div class="statistics-card bg-white color-danger redious-border mb-20 p-20 p-sm-20">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="statistics-info mb-3">
                                    <h6>{{ __('total_merchant') }}</h6>
                                    <h4>679</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="statistics-gChart mb-20 mb-lg-0">
                                    <canvas id="statisticsChart3"></canvas>
                                </div>
                            </div>
                            <div class="statistics-footer d-flex align-items-center gap-3">
                                <p class="sales-price">67</p>
                                <h6>{{ __('since_last_month') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="col-xxl-3 col-xl-6 col-lg-6 col-md-6">
                    <div class="statistics-card bg-white color-blue redious-border mb-20 p-20 p-sm-20">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="statistics-info mb-3">
                                    <h6>{{ __('total_parcel') }}</h6>
                                    <h4>67</h4>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="statistics-gChart mb-20 mb-lg-0">
                                    <canvas id="statisticsChart4"></canvas>
                                </div>
                            </div>
                            <div class="statistics-footer d-flex align-items-center gap-3">
                                <p class="sales-price">6</p>
                                <h6>{{ __('since_last_month') }}</h6>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Statistics Chart4 -->
            </div>
            <div class="row">
                <div class="col-xl-6 col-md-12">
                    <div class="row">
                        <div class="col-xxl-6 col-md-6">
                            <div class="statistics-card bg-white color-primary redious-border mb-4 p-20 p-sm-20">
                                <div class="income-icon">
                                    <i class="las la-credit-card"></i>
                                </div>
                                <div class="statistics-gChart">
                                    <canvas id="statisticsChart5"></canvas>
                                </div>
                                <div class="statistics-info income-content mt-1">
                                    <h6>{{ __('total_income') }}</h6>
                                    <h4>56</h4>
                                </div>
                            </div>
                        </div>

                        <div class="col-xxl-6 col-md-6">
                            <div class="statistics-card bg-white color-danger redious-border mb-4 p-20 p-sm-20">
                                <div class="statistics-icon">
                                    <svg width="32" height="32" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg"><path d="M318.072 561.493H215.037c-16.962 0-30.72-13.758-30.72-30.72V71.683c0-16.968 13.754-30.72 30.72-30.72h586.598c16.966 0 30.72 13.752 30.72 30.72v459.09c0 16.962-13.758 30.72-30.72 30.72h-96.143c-11.311 0-20.48 9.169-20.48 20.48s9.169 20.48 20.48 20.48h96.143c39.583 0 71.68-32.097 71.68-71.68V71.683c0-39.591-32.094-71.68-71.68-71.68H215.037c-39.586 0-71.68 32.089-71.68 71.68v459.09c0 39.583 32.097 71.68 71.68 71.68h103.035c11.311 0 20.48-9.169 20.48-20.48s-9.169-20.48-20.48-20.48z"/><path d="M291.917 259.95h432.845c11.311 0 20.48-9.169 20.48-20.48s-9.169-20.48-20.48-20.48H291.917c-11.311 0-20.48 9.169-20.48 20.48s9.169 20.48 20.48 20.48z"/><path d="M367.155 250.006c5.819-9.699 2.673-22.279-7.026-28.098s-22.279-2.673-28.098 7.026l-91.709 152.863c-16.887 28.125-.345 57.353 32.471 57.353h36.905c11.311 0 20.48-9.169 20.48-20.48s-9.169-20.48-20.48-20.48h-31.445l88.902-148.184zm237.42 148.184c-11.311 0-20.48 9.169-20.48 20.48s9.169 20.48 20.48 20.48H743.88c32.816 0 49.358-29.229 32.468-57.36l-91.706-152.857c-5.819-9.699-18.399-12.845-28.098-7.026s-12.845 18.399-7.026 28.098l88.902 148.184H604.575zm21.643 604.647V827.59a10.25 10.25 0 014.469-8.456l63.156-43.138a51.182 51.182 0 0022.323-42.286v-95.805c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48v95.805c0 3.394-1.667 6.553-4.459 8.459l-63.166 43.145a51.21 51.21 0 00-22.323 42.276v175.247c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48zM298.036 623.641v108.851a51.18 51.18 0 0024.96 43.967l72.952 43.556a10.243 10.243 0 014.99 8.794v174.029c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V828.809a51.204 51.204 0 00-24.945-43.958l-72.967-43.565a10.225 10.225 0 01-4.99-8.794V623.641c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48z"/><path d="M716.068 678.681V465.003c0-36.757-29.803-66.56-66.56-66.56h-2.222c-36.757 0-66.56 29.803-66.56 66.56v126.638c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V465.003c0-14.136 11.464-25.6 25.6-25.6h2.222c14.136 0 25.6 11.464 25.6 25.6v213.678c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48z"/><path d="M621.689 545.519V418.881c0-36.757-29.803-66.56-66.56-66.56h-2.222c-36.757 0-66.56 29.803-66.56 66.56v165.55c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48v-165.55c0-14.136 11.464-25.6 25.6-25.6h2.222c14.136 0 25.6 11.464 25.6 25.6v126.638c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48z"/><path d="M527.876 586.436V372.758c0-36.757-29.803-66.56-66.56-66.56h-2.222c-36.757 0-66.56 29.803-66.56 66.56V536.26c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V372.758c0-14.136 11.464-25.6 25.6-25.6h2.222c14.136 0 25.6 11.464 25.6 25.6v213.678c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48z"/><path d="M433.444 582.383V418.881c0-36.757-29.803-66.56-66.56-66.56h-2.222c-36.757 0-66.56 29.803-66.56 66.56v213.678c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V418.881c0-14.136 11.464-25.6 25.6-25.6h2.222c14.136 0 25.6 11.464 25.6 25.6v163.502c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48z"/></svg>
                                </div>
                                <div class="statistics-gChart">
                                    <canvas id="statisticsChart6"></canvas>
                                </div>
                                <div class="statistics-info">
                                    <h6>{{ __('total_expense') }}</h6>
                                    <h4 class="statistics-info">568</h4>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

