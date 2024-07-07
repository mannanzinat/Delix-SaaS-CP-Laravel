@extends('backend.layouts.master')
@section('title')
    {{ __('add') }} {{ __('user') }}
@endsection
@section('mainContent')
    <div class="container-fluid">
        <div class="row gx-20">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center mb-12">
                    <h3 class="section-title">{{ __('add') }} {{ __('user') }}</h3>
                    <div class="oftions-content-right">
                        <a href="{{ url()->previous() }}" class="d-flex align-items-center btn sg-btn-primary gap-2">
                            <i class="las la-arrow-left"></i>
                            <span>{{ __('back') }}</span>
                        </a>
                    </div>
                </div>
                <form action="{{ route('user.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="bg-white redious-border p-20 p-sm-30">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card-inner">
                                    <div class="row g-gs">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="fv-full-name">{{ __('first_name') }} <span
                                                        class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="fv-full-name"
                                                        name="first_name" value="{{ old('first_name') }}">
                                                @if ($errors->has('first_name'))
                                                    <div class="invalid-feedback help-block">
                                                        <p>{{ $errors->first('first_name') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="fv-full-name">{{ __('last_name') }} <span
                                                        class="text-danger">*</span></label>
                                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="fv-full-name"
                                                        name="last_name" value="{{ old('last_name') }}">
                                                @if ($errors->has('last_name'))
                                                    <div class="invalid-feedback help-block">
                                                        <p>{{ $errors->first('last_name') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="fv-email">{{ __('email') }} <span
                                                        class="text-danger">*</span></label>
                                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="fv-email" name="email"
                                                     value="{{ old('email') }}">
                                                @if ($errors->has('email'))
                                                    <div class="invalid-feedback help-block">
                                                        <p>{{ $errors->first('email') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="fv-email">{{ __('password') }} <span
                                                        class="text-danger">*</span></label>
                                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="fv-email"
                                                        name="password" value="{{ old('password') }}">
                                                @if ($errors->has('password'))
                                                    <div class="invalid-feedback help-block">
                                                        <p>{{ $errors->first('password') }}</p>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="branch">{{ __('branch') }}</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="without_search form-control" id="branch" name="branch">
                                                            <option value="">{{ __('select') }} {{ __('branch') }}
                                                            </option>
                                                            @foreach ($branchs as $branch)
                                                                <option value="{{ $branch->id }}">
                                                                    {{ $branch->name . ' (' . $branch->address . ')' }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label" for="dashboard">{{ __('dashboard') }}</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="without_search form-control" id="dashboard" name="dashboard">
                                                            <option value="">{{ __('select') }} {{ __('dashboard') }}</option>
                                                            <option value="admin">{{ __('admin') }}</option>
                                                            <option value="branch_manager">{{ __('branch_manager') }}</option>
                                                            <option value="finance">{{ __('finance') }}</option>

                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-lg-12 input_file_div">
                                            <div class="mb-3 mt-2">
                                                <label class="form-label mb-1">{{ __('profile') }}</label>
                                                <input class="form-control file_picker sp_file_input" type="file" id="profilePhoto"
                                                    name="image" accept="image/*">
                                                <div class="invalid-feedback help-block">
                                                    <p class="image_error error">{{ $errors->first('image') }}</p>
                                                </div>
                                            </div>
                                            <div class="selected-files d-flex flex-wrap gap-20">
                                                <div class="selected-files-item">
                                                    <img class="selected-img" src="{{ getFileLink('80X80', []) }}"
                                                        alt="favicon">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="card-inner">
                                    <div class="row g-gs">
                                        <div class="col-md-12">
                                            <div class="pb-3">
                                                <label class="form-label" for="default-06">{{ __('role') }}</label>
                                                <div class="form-control-wrap ">
                                                    <div class="form-control-select">
                                                        <select class="without_search form-control change-role" id="default-06"
                                                            name="role">
                                                            <option value="">{{ __('select') }}
                                                                {{ __('role') }}</option>
                                                            @foreach ($roles as $role)
                                                                <option value="{{ $role->id }}">{{ $role->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <table class="table role-create-table role-permission"
                                        id="permissions-table">
                                        <thead>
                                            <tr>
                                                <th scope="col">{{ __('module') }}/{{ __('sub-module') }}</th>
                                                <th scope="col">{{ __('permissions') }}</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($permissions as $permission)
                                                <tr>
                                                    <td><span
                                                            class="text-capitalize">{{ __($permission->attribute) }}</span>
                                                    </td>
                                                    <td>
                                                        @foreach ($permission->keywords as $key => $keyword)
                                                            <div class="custom-control custom-checkbox">
                                                                @if ($keyword != '')
                                                                <label class="custom-control-label"
                                                                        for="{{ $keyword }}">
                                                                    <input type="checkbox"
                                                                        class="custom-control-input read common-key"
                                                                        id="{{ $keyword }}" name="permissions[]"
                                                                        value="{{ $keyword }}">
                                                                        <span>{{ __($key) }}</span>
                                                                </label>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                    <div class="row">
                                        <div class="col-md-12 text-right mt-4">
                                            <div class="mb-3">
                                                <button type="submit"  class="btn sg-btn-primary resubmit">{{ __('submit') }}</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('admin.roles.script')
@endsection
