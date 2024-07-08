@extends('backend.layouts.master')
@section('title', __('servers'))
@section('content')
	<div class="main-content-wrapper">
		<section class="oftions">
			<div class="container-fluid">
				<div class="row">
					<div class="col-lg-12">
						<h3 class="section-title">{{__('add_server') }}</h3>
						<form action="{{ route('cloud-server.store') }}" method="post" enctype="multipart/form-data">
							@csrf
							<div class="bg-white redious-border p-20 p-sm-30">
								<h6 class="sub-title">{{__('server_information')  }}</h6>
								<div class="row gx-20">
									<div class="col-lg-4">
										<div class="select-type-v2 mb-4 list-space">
											<label for="provider" class="form-label">{{__('provider') }}<span
														class="text-danger">*</span></label>
											<div class="select-type-v1 list-space">
												<select class="form-select form-select-lg rounded-0 mb-3 with_search"
														aria-label=".form-select-lg example" name="provider" required>
													<option value="" selected>{{ __('select_provider') }}</option>
													<option value="aws" selected>{{ __('aws') }}</option>
													<option value="vuttr" selected>{{ __('vuttr') }}</option>
													<option value="digitalization" selected>{{ __('digitalization') }}</option>
													<option value="allnet" selected>{{ __('allnet') }}</option>
												</select>
												@if ($errors->has('provider'))
													<div class="nk-block-des text-danger">
														<p>{{ $errors->first('provider') }}</p>
													</div>
												@endif
											</div>
										</div>
									</div>
                                    <div class="col-lg-4">
                                        <div class="mb-4">
                                            <label for="ip"
                                                   class="form-label">{{__('ip') }}<span
                                                        class="text-danger">*</span></label>
                                            <input type="text" class="form-control rounded-2" id="ip"
                                                   name="ip" value="{{ old('ip') }}" placeholder="{{ __('ip') }}" required>
                                            @if ($errors->has('ip'))
                                                <div class="nk-block-des text-danger">
                                                    <p>{{ $errors->first('ip') }}</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
									<div class="col-lg-4">
										<div class="mb-4">
											<label for="organisationName"
											       class="form-label">{{__('user_name') }}<span
														class="text-danger">*</span></label>
											<input type="text" class="form-control rounded-2" id="user_name"
											       name="user_name" value="{{ old('user_name') }}" placeholder="{{ __('user_name') }}" required>
											@if ($errors->has('user_name'))
												<div class="nk-block-des text-danger">
													<p>{{ $errors->first('user_name') }}</p>
												</div>
											@endif
										</div>
									</div>

									<div class="col-lg-4">
										<div class="mb-4">
											<label for="password"
											       class="form-label">{{__('password') }}<span
														class="text-danger">*</span></label>
											<input type="password" class="form-control rounded-2" id="password"
											       name="password" value="{{ old('password') }}"
											       autocomplete="off" placeholder="{{ __('password') }}" required>
											@if ($errors->has('password'))
												<div class="nk-block-des text-danger">
													<p>{{ $errors->first('password') }}</p>
												</div>
											@endif
										</div>
									</div>

									<div class="d-flex justify-content-between align-items-center mt-30">
										<button type="submit" class="btn sg-btn-primary">{{ __('submit') }}</button>
										@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</section>
	</div>
	@include('backend.common.gallery-modal')
@endsection
@push('js')
	<script src="{{ static_asset('admin/js/countries.js') }}"></script>

@endpush
@push('css_asset')
	<link rel="stylesheet" href="{{ static_asset('admin/css/dropzone.min.css') }}">
@endpush
