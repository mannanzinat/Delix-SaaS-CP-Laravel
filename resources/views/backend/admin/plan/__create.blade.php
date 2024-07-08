@extends('backend.layouts.master')
@section('title', __('create_plan'))
@section('content')
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <h3 class="section-title">{{__('create_plan') }}</h3>
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <form action="{{ route('plans.store') }}" method="POST" class="form">@csrf
                            <div class="row gx-20">
                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="planName" class="form-label">{{__('plan_name')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="text" class="form-control rounded-2" id="planName" name="name"
                                               placeholder="{{__('plan_name')}}">
                                        <div class="nk-block-des text-danger">
                                            <p class="name_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Package Name -->

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="description" class="form-label">{{__('description')}}<span
                                                    class="text-danger">*</span></label>
                                        <textarea class="form-control" name="description"
                                                  placeholder="{{__('description')}}" id="description"></textarea>
                                        <div class="nk-block-des text-danger">
                                            <p class="description_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <div class=col-lg-3>
                                    <div class="mb-4">
                                        <label for="planPrice" class="form-label">{{__('plan_price')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="planPrice"
                                               name="price" placeholder="{{__('plan_price')}}">
                                        <div class="nk-block-des text-danger">
                                            <p class="price_error error"></p>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Package Price -->

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="planValidity" class="form-label">{{__('plan_validity')}}<span
                                                    class="text-danger">*</span></label>
                                        <div class="select-type-v2">
                                            <select id="planValidity" name="validity"
                                                    class="form-select form-select-lg mb-3 without_search">
                                                <option value="monthly">{{__('monthly')}}</option>
                                                <option value="yearly">{{__('yearly')}}</option>
                                                <option value="life_time">{{__('life_time')}}</option>
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="validity_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Package Validity -->

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="active_merchant" class="form-label">{{__('active_merchant')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="active_merchant"
                                               name="active_merchant" placeholder="{{__('active_merchant')}}">
                                        <div class="nk-block-des text-danger">
                                            <p class="active_merchant_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="monthly_parcel" class="form-label">{{__('monthly_parcel')}}<span
                                                    class="text-danger">*</span></label>
                                        <div class="select-type-v2">
                                            <input type="number" class="form-control rounded-2" id="monthly_parcel"
                                                   name="monthly_parcel" placeholder="{{__('monthly_parcel')}}">
                                            <div class="nk-block-des text-danger">
                                                <p class="monthly_parcel_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="active_rider" class="form-label">{{__('active_rider')}}<span
                                                    class="text-danger">*</span></label>
                                        <div class="select-type-v2">
                                            <input type="number" class="form-control rounded-2" id="active_rider"
                                                   name="active_rider" placeholder="{{__('active_rider')}}">
                                            <div class="nk-block-des text-danger">
                                                <p class="active_rider_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="active_staff" class="form-label">{{__('active_staff')}}<span
                                                    class="text-danger">*</span></label>
                                        <div class="select-type-v2">
                                            <input type="number" class="form-control rounded-2" id="active_staff"
                                                   name="active_staff" placeholder="{{__('active_staff')}}">
                                            <div class="nk-block-des text-danger">
                                                <p class="active_staff_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="featured" class="form-label">{{__('featured')}}</label>
                                        <div class="select-type-v2">
                                            <select id="featured" class="form-select form-select-lg mb-3 without_search"
                                                    name="featured">
                                                <option value="1">{{__('yes')}}</option>
                                                <option value="0">{{__('no')}}</option>
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="featured_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Live Class Facilities -->

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="conversation_limit" class="form-label">{{__('conversation_limit')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="conversation_limit"
                                               placeholder="{{__('conversation_limit')}}" name="conversation_limit">
                                        <div class="nk-block-des text-danger">
                                            <p class="conversation_limit_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-3">
                                    <div class="mb-4">
                                        <label for="team_limit" class="form-label">{{__('team_limit')}}<span
                                                    class="text-danger">*</span></label>
                                        <input type="number" class="form-control rounded-2" id="team_limit"
                                               name="team_limit" placeholder="{{__('team_limit')}}">
                                        <div class="nk-block-des text-danger">
                                            <p class="team_limit_error error"></p>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-lg-6 custom-control custom-checkbox contacts-list">
                                    <div class="mb-4">
                                        <label class="custom-control-label  pb-4"
                                               for="telegram_access">
                                            <input type="checkbox"
                                                   class="custom-control-input read common-key pb-4"
                                                   name="telegram_access"
                                                   value="1"
                                                   id="telegram_access">
                                            <span>{{__('telegram_access')}}</span>
                                        </label>
                                    </div>
                                </div>
                                <!-- End Course Upload Limit -->

                                <div class="col-lg-12">
                                    <div class="mb-4">
                                        <label for="planStatus" class="form-label">{{__('plan_status')}}</label>
                                        <div class="select-type-v2">
                                            <select id="planStatus"
                                                    class="form-select form-select-lg mb-3 without_search"
                                                    name="status">
                                                <option value="1" selected>{{__('active')}}</option>
                                                <option value="0">{{__('inactive')}}</option>
                                            </select>
                                            <div class="nk-block-des text-danger">
                                                <p class="status_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- End Package Status -->

                                @if(setting('is_stripe_activated') && setting('stripe_secret') && setting('stripe_key'))
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="stripe_plan_key" class="form-label">{{__('stripe_plan_key')}}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="stripe_plan_key"
                                                   name="stripe" placeholder="{{__('stripe_plan_key')}}">
                                            <div class="nk-block-des text-danger">
                                                <p class="stripe_plan_key_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                @if(setting('paypal_client_id') && setting('paypal_client_secret') && setting('is_paypal_activated'))
                                    <div class="col-lg-12">
                                        <div class="mb-4">
                                            <label for="paypal_plan_id" class="form-label">{{__('paypal_plan_id')}}
                                                <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="paypal_plan_id"
                                                   name="paypal" placeholder="{{__('paypal_plan_id')}}">
                                            <div class="nk-block-des text-danger">
                                                <p class="paypal_plan_id_error error"></p>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-end align-items-center mt-30">
                                    <button type="submit" class="btn sg-btn-primary">{{__('submit') }}</button>
                                    @include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection