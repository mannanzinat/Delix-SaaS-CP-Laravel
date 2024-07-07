@extends('backend.layouts.base')
@section('base.content')
    @if(Sentinel::getUser()->user_type == 'sas_admin' || Sentinel::getUser()->user_type == 'sas_admin_staff')
        @include('backend.layouts.sas_sidebar')
    @elseif(Sentinel::getUser()->user_type == 'courier_admin' || Sentinel::getUser()->user_type == 'courier_admin_staff')
        @include('backend.layouts.courier_sidebar')
    @elseif(Sentinel::getUser()->user_type == 'merchant' || Sentinel::getUser()->user_type == 'merchant_staff')
        @include('backend.layouts.merchant_sidebar')
    @endif
    <main class="main-wrapper">
        @if(Sentinel::getUser()->user_type == 'sas_admin' || Sentinel::getUser()->user_type == 'sas_admin_staff')
            @include('backend.layouts.header')
        @else
            @include('backend.layouts.header')
        @endif
        <div class="main-content-wrapper">
            @yield('mainContent')
        </div>
    </main>
    @include('backend.layouts.footer')
@endsection
