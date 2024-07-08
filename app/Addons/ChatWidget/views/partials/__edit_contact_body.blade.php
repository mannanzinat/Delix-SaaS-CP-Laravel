<form action="{{ route('client.chatwidget.contact.update', $row->id) }}" id="updateContactForm" class="form-validate"
    method="POST" enctype="multipart/form-data">
    @csrf
    <input type="hidden" name="widget_id" value="{{ $row->widget_id }}">
    <div class="row gx-20">
        <div class="col-lg-12">
            <div class="mb-4">
                <label for="name" class="form-label">{{ __('name') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control rounded-2" id="name" name="name"
                    placeholder="{{ __('enter_name') }}" value="{{ $row->name }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="mb-4">
                <label for="phone" class="form-label">{{ __('phone') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control rounded-2" id="phone" name="phone"
                    placeholder="{{ __('phone') }}" value="{{ $row->phone }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="mb-4">
                <label for="label" class="form-label">{{ __('label') }} <span class="text-danger">*</span></label>
                <input type="text" class="form-control rounded-2" id="label" name="label"
                    placeholder="{{ __('enter_label') }}" value="{{ $row->label }}">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="mb-4">
                <label for="welcome_message" class="form-label">{{ __('message') }} <span
                        class="text-danger">*</span></label>
                <textarea class="form-control rounded-2" name="welcome_message" id="welcome_message" cols="30" rows="10"
                    placeholder="{{ __('enter_welcome_message') }}">{{ $row->welcome_message }}</textarea>
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col-md-12 col-12 mb-4">
            <label for="available_from" class="form-label">{{ __('available') }}</label>
            <div class="input-group" id="">
                <input type="time" name="available_from" id="available_from"
                    placeholder="{{ __('available_from') }}"
                    class="form-control @error('available_from') is-invalid @enderror"
                    value="{{ old('available_from', $row->available_from) }}" />
                <span class="input-group-text">{{ __('to') }}</span>
                <input type="time" name="available_to" id="available_to" placeholder="{{ __('available_to') }}"
                    value="{{ old('available_to', $row->available_to) }}"
                    class="form-control @error('available_to') is-invalid @enderror" />
            </div>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-lg-12 input_file_div">
            <div class="mb-3">
                <label class="form-label mb-1">{{ __('avatar') }}</label>
                <label for="profilePhoto" class="file-upload-text">
                    <p></p>
                    <span class="file-btn">{{ __('choose_file') }}</span>
                </label>
                <input class="d-none file_picker" type="file" id="profilePhoto" name="image" accept=".jpg,.png">
                <div class="invalid-feedback"></div>
            </div>
            <div class="selected-files d-flex flex-wrap gap-20">
                <div class="selected-files-item">
                    <img class="selected-img" src="{{ getFileLink('80x80', $row->images) }}" alt="favicon">
                </div>
            </div>
        </div>
        <div class="d-flex justify-content-end align-items-center mt-30">
            <button id="" class="btn btn-primary d-none preloader" type="button" disabled>
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                Loading...
            </button>
            <button type="submit" class="btn btn-primary save">{{ __('submit') }}</button>
        </div>
    </div>
</form>
