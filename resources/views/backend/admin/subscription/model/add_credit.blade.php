<div class="modal fade" id="credit" tabindex="-1" aria-labelledby="credit" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-md">
		<div class="modal-content">
			<h6 class="sub-title create_sub_title">{{__('add_extra_credit') }}</h6>
			<button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
			<form action="{{ route('add.credit') }}" method="post" enctype="multipart/form-data">
				@csrf
				<div class="">
					<div class="row gx-20 add-coupon">
						<input type="hidden" name="subscription_id" id="subscription_id">
						<div class="col-lg-12">
							<div class="mb-4">
								<label for="new_active_merchant" class="form-label">{{ __('active_merchant') }}</label>
								<input type="number" class="form-control rounded-2" id="new_active_merchant" name="new_active_merchant">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mb-4">
								<label for="new_monthly_parcel" class="form-label">{{ __('monthly_parcel') }}</label>
								<input type="number" class="form-control rounded-2" id="new_monthly_parcel" name="new_monthly_parcel">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mb-4">
								<label for="new_active_rider" class="form-label">{{ __('active_rider') }}</label>
								<input type="number" class="form-control rounded-2" id="new_active_rider" name="new_active_rider">
								<div class="invalid-feedback"></div>
							</div>
						</div>
						<div class="col-lg-12">
							<div class="mb-4">
								<label for="new_active_staff" class="form-label">{{ __('active_staff') }}</label>
								<input type="number" class="form-control rounded-2" id="new_active_staff" name="new_active_staff">
								<div class="invalid-feedback"></div>
							</div>
						</div>

						<div class="d-flex justify-content-end align-items-center mt-30">
							<button type="submit" class="btn sg-btn-primary">{{__('submit') }}</button>
							@include('backend.common.loading-btn',['class' => 'btn sg-btn-primary'])
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

