@extends('backend.layouts.master')
@section('title', __('contacts'))
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="header-top d-flex justify-content-between align-items-center">
                    <h3 class="section-title">{{ __('subscriber_management') }}</h3>
                    <div class="oftions-content-right mb-12 gap-2">
                        <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary" id="filterBTN">
                            <i class="las la-filter"></i>
                        </a>
   
                    </div>
                </div>
                <div class="row col-lg-12">
                    <div class="col-lg-12" id="filterSection">
                        <div class="hidden-filter bg-white redious-border p-20 p-sm-30 mb-2">
                            <div class="row">

                                <div class="col-lg-6">
                                    <div class="mb-4">
                                        <label for="group_id" class="form-label">{{ __('group') }}<span
                                                class="text-danger">*</span></label>
                                        <select id="group_id" name="group_id"
                                            class="multiple-select-1 form-select-lg rounded-0 mb-3 filterable"
                                            aria-label=".form-select-lg example">
                                            <option value="">{{ __('select_group') }}</option>
                                            @if (isset($groups))
                                            @foreach ($groups as $key => $group)
                                                <option value="{{ $key }}">{{ $group }}</option>
                                            @endforeach
                                            @endif
                                        </select>
                                        @if ($errors->has('group_id'))
                                            <div class="nk-block-des text-danger">
                                                <p>{{ $errors->first('group_id') }}</p>
                                            </div>
                                        @endif

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-2">
                                <button type="submit" class="btn sg-btn-primary w-80 mt-10 d-flex justify-end"
                                    id="filter">{{ __('filter') }}</button>
                                <button type="submit" class="btn sg-btn-primary  w-80 mt-10 d-flex justify-end"
                                    id="reset">{{ __('reset') }}</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div
                    class="default-tab-list table-responsive default-tab-list-v2 activeItem-bd-md bg-white redious-border p-20 p-sm-30">
                    <div class="default-list-table yajra-dataTable">
                        {{ $dataTable->table() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@include('backend.common.delete-script')
@push('js')
    {{ $dataTable->scripts() }}
    <script>
        $(document).ready(function() {
            $('.dropdown-submenu').on('click', function(event) {
                $('.dropdown-submenu ul').removeClass('show');
                $(this).find('ul').toggleClass('show');
                event.stopPropagation();
            });
            $(document).on('click', function(event) {
                if (!$(event.target).closest('.dropdown-submenu').length) {
                    $('.dropdown-submenu ul').removeClass('show');
                }
            });
        });




        $(document).ready(function() {
            $('#filterBTN').click(function() {
                $('#filterSection').toggleClass('show');
            });

            const advancedSearchMapping = (attribute) => {
                $('#dataTableBuilder').on('preXhr.dt', function(e, settings, data) {
                    data[attribute.key] = attribute.value;
                });
            }

            $(document).on('change', '.filterable', function() {
                advancedSearchMapping({
                    key: $(this).attr('id'),
                    value: $(this).val(),
                });
            });

            $(document).on('click', '#reset', () => {
                $('.filterable').val('').trigger('change');
                $('#dataTableBuilder').DataTable().ajax.reload();
            });

            $(document).on('click', '#filter', () => {
                $('#checkAll').prop('checked', false).trigger('change');
                $('#dataTableBuilder').DataTable().ajax.reload();
            });
        });

        $(document).on('click', '.common-key', function() {
            var anyChecked = false;

            $('.custom-control-input').each(function() {
                if ($(this).prop('checked')) {
                    anyChecked = true;
                    return false;
                }
            });

            if (anyChecked) {
                $('.custom-dropdown').removeClass('d-none');
            } else {
                $('.custom-dropdown').addClass('d-none');
            }
        });

        $(document).on('click','.blacklist',function (){
            let selector = $('.common-key:checked');
            let ids=[];
            $.each(selector,function (){
                let val = $(this).val();
                ids.push(val);
            });
            $.ajax({
                url: '{{ url(localeRoutePrefix().'/client/contact/blacklist') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids,
                    is_blacklist: 1
                },
                success: function (response) {
                    if (response.status === 200) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        })

        $(document).on('click','.remove_blacklist',function (){
            let selector = $('.common-key:checked');
            let ids=[];
            $.each(selector,function (){
                let val = $(this).val();
                ids.push(val);
            });
            $.ajax({
                url: '{{ url(localeRoutePrefix().'/client/remove-blacklist') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids,
                    is_blacklist: 0
                },
                success: function (response) {
                    if (response.status === 200) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        })

        $(document).on('click','.remove_list',function (){
            let selector = $('.common-key:checked');
            let ids=[];
            $.each(selector,function (){
                let val = $(this).val();
                ids.push(val);
            });
            $.ajax({
                url: '{{ url(localeRoutePrefix().'/client/remove-list') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids,
                },
                success: function (response) {
                    if (response.status === 200) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        })


        $(document).ready(function() {
            $('.dropdown-menu').on('click', '.add_list', function() {
                let listId = $(this).data('list-id');
                let selector = $('.common-key:checked');
                let ids = [];
                $.each(selector, function() {
                    let val = $(this).val();
                    ids.push(val);
                });
                $.ajax({
                    url: '{{ url(localeRoutePrefix().'/client/add-list') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids,
                        contact_list_id: listId ,
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        toastr.error('An error occurred while processing your request.');
                    }
                });
            });
        });


        $(document).ready(function() {
            $('.dropdown-menu').on('click', '.add_segment', function() {
                let segmentId = $(this).data('segment-id');
                let selector = $('.common-key:checked');
                let ids = [];
                $.each(selector, function() {
                    let val = $(this).val();
                    ids.push(val);
                });
                $.ajax({
                    url: '{{ url(localeRoutePrefix().'/client/add-segment') }}',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        ids: ids,
                        segment_id: segmentId ,
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            setTimeout(function() {
                                window.location.reload();
                            }, 2000);
                            toastr.success(response.message);
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        console.log(xhr.responseText);
                        toastr.error('An error occurred while processing your request.');
                    }
                });
            });
        });




        $(document).on('click','.remove_segment',function (){
            let selector = $('.common-key:checked');
            let ids=[];
            $.each(selector,function (){
                let val = $(this).val();
                ids.push(val);
            });
            $.ajax({
                url: '{{ url(localeRoutePrefix().'/client/remove-segment') }}',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    ids: ids,
                },
                success: function (response) {
                    if (response.status === 200) {
                        setTimeout(function() {
                            window.location.reload();
                        }, 2000);
                        toastr.success(response.message);
                    } else {
                        toastr.error(response.message);
                    }
                },
                error: function (xhr) {
                    console.log(xhr.responseText);
                }
            });
        })
        $(document).ready(function() {
            $('.js-example-basic-multiple').select2();
        });
    </script>
@endpush
