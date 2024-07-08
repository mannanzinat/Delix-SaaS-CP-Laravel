@extends('website.themes.' . active_theme() . '.master')
@section('content')
@push('css')
@endpush
		<!-- Breadcrumb Start -->
		<div class="breadcrumb__area">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="breadcrumb__title text-center">
							<h2 class="title">{!! $page_info->title !!}</h2>
							<p class="desc">{{ __('last_updated') }} {{ \Carbon\Carbon::parse($page_info->created_at)->format('jS F, Y') }}</p>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- Breadcrumb End -->
		<!-- Privacy Policy Start -->
		<section class="privacy__section p-0">
			<div class="container">
				<div class="row">
					<div class="col-12">
						<div class="privacy__content text-white">
                            {!! $page_info->content !!}
						</div>
					</div>
				</div>
			</div>
		</section>
		<!-- Privacy Policy End -->
    @include('website.themes.' . active_theme() . '.sections.cta')
@push('js')
@endpush
@endsection
