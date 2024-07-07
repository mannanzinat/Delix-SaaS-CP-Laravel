@extends('backend.layouts.base')
@section('base.content')
    @if(Sentinel::getUser()->user_type == 'merchant' || Sentinel::getUser()->user_type == 'merchant_staff')
        @include('backend.layouts.merchant_sidebar')
    @else
        @include('backend.layouts.sidebar')
    @endif
    <main class="main-wrapper">
        @include('backend.layouts.header')
        <div class="main-content-wrapper">
            @yield('mainContent')
        </div>
    </main>
    @include('backend.layouts.footer')
@endsection
