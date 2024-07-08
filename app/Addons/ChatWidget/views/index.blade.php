@extends('backend.layouts.master')
@section('title', __('chatwidget'))
@section('content')
    @push('css')
        <link href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/themes/prism.min.css" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.15.0/themes/prism-tomorrow.min.css">
        <style>
            .code-container .code-wrapper {
                position: relative;
            }
            .code-container .code-wrapper .copy-button {
                position: absolute;
                top: 0;
                right: 0;
                height: 100%;
                width: 60px;
                border: none;
                background: #ddd;
                display: flex;
                align-items: center;
                justify-content: center;
                transition: all 300ms ease-in-out;
            }
            .code-container .code-wrapper .copy-button svg {
                width: 30px;
            }
            .code-container .code-wrapper .copy-button:hover {
                background: #354abf;
                color: #fff !important;
            }
            .code-container code.language-js {
                text-shadow: none;
            }

            .default-list-table .table td, .default-list-table .table th {
                white-space: normal!important;
                max-width: 500px!important;
            }

        </style>
    @endpush
    <section class="oftions">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-12">
                    <div class="header-top d-flex justify-content-between align-items-center">
                        <h3 class="section-title">{{ __('chatwidget') }}</h3>
                        <div class="oftions-content-right mb-12">
                            <a href="javascript:void(0)" class="d-flex align-items-center btn sg-btn-primary gap-2"
                                data-bs-toggle="modal" data-bs-target="#addChatWidget"><i
                                    class="las la-plus"></i>{{ __('add_new') }}</a>
                        </div>
                    </div>
                    <div class="bg-white redious-border p-20 p-sm-30 pt-sm-30">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="default-list-table table-responsive yajra-dataTable">
                                    {{ $dataTable->table() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <div class="modal fade" id="addChatWidget" tabindex="-1" aria-labelledby="addChatWidgetLabel" aria-hidden="false">
        <div class="modal-dialog">
            <div class="modal-content">
                <h6 class="sub-title">{{ __('add_new_widget') }}</h6>
                <button type="button" class="btn-close modal-close" data-bs-dismiss="modal" aria-label="Close"></button>
                <form action="{{ route('client.chatwidget.store') }}" id="addChatWidgetForm" class="form-validate"
                    method="POST">
                    @csrf
                    <div class="row gx-20">
                        <div class="col-lg-12">
                            <div class="mb-4">
                                <label for="name" class="form-label">{{ __('name') }} <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control rounded-2" id="name" name="name"
                                    placeholder="{{ __('write_here') }}">
                                <div class="nk-block-des text-danger">
                                    <p class="name_error error">{{ $errors->first('name') }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 col-12 mb-4">
                            <label for="box_position" class="form-label">{{ __('box_position') }}</label>
                            <select id="box_position" name="box_position" class="form-select"
                                aria-label=".form-select-lg example" required>
                                <option value="middle-left" {{ old('box_position') == 'none' ? 'selected' : '' }}>
                                    {{ __('middle_left') }}
                                </option>
                                <option value="middle-right" {{ old('box_position') == 'middle-right' ? 'selected' : '' }}>
                                    {{ __('middle_right') }}
                                </option>
                                <option value="bottom-left" {{ old('box_position') == 'bottom-left' ? 'selected' : '' }}>
                                    {{ __('bottom_left') }}
                                </option>
                                <option value="bottom-right"
                                    {{ old('box_position') == 'bottom-right' ? 'selected' : 'selected' }}>
                                    {{ __('bottom_right') }}
                                </option>
                            </select>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="ol-lg-12 mb-4">
                            <label for="welcome_message" class="form-label">{{ __('default_message') }} </label>
                            <textarea class="form-control rounded-2" name="welcome_message" id="welcome_message" cols="30" rows="10"
                                placeholder="{{ __('enter_welcome_message') }}"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="ol-lg-12 mb-4">
                            <label for="offline_message" class="form-label">{{ __('offline_message') }} </label>
                            <textarea class="form-control rounded-2" name="offline_message" id="offline_message" cols="30" rows="10"
                                placeholder="{{ __('enter_offline_message') }}"></textarea>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="d-flex justify-content-end align-items-center mt-30">
                            <button id="chatwidget_preloader" class="btn btn-primary d-none" type="button" disabled>
                                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                                Loading...
                            </button>
                            <button type="submit" class="btn btn-primary save">{{ __('submit') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <span id="sortable-body">
    </span>
    <span id="sb-chat-widget" class="sb-show"></span>
    @include('addon:ChatWidget::partials.embed_code_modal')
@endsection
@push('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/axios/1.6.7/axios.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/plugins/toolbar/prism-toolbar.min.js"></script>
    <script
        src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.25.0/plugins/copy-to-clipboard/prism-copy-to-clipboard.min.js">
    </script>
    <script src="{{ static_asset('admin/js/chatwidget.js') }}"></script>
    {{ $dataTable->scripts() }}
    <script>
        function initCodeCopy() {
            const codeBlocks = document.querySelectorAll('code[class*="language-"]');
            codeBlocks.forEach((block) => {
                const lang = parseLanguage(block);
                const referenceEl = block.parentElement;
                const parent = block.parentElement.parentElement;
                const wrapper = document.createElement('div');
                wrapper.className = 'code-wrapper';
                parent.insertBefore(wrapper, referenceEl);
                wrapper.append(block.parentElement);
                const copyBtn = document.createElement('button');
                copyBtn.setAttribute('class', 'copy-button');
                copyBtn.setAttribute('data-lang', lang);
                copyBtn.innerHTML = `<i class="las la-copy"></i>`;
                wrapper.insertAdjacentElement('beforeend', copyBtn);
            });

            function parseLanguage(block) {
                const className = block.className;
                if (className.startsWith('language')) {
                    const [prefix, lang] = className.split('-');
                    return lang;
                }
            }

            async function fallbackCopyTextToClipboard(text) {
                return new Promise((resolve, reject) => {
                    var textArea = document.createElement('textarea');
                    textArea.value = text;
                    // Avoid scrolling to bottom
                    textArea.style.top = '0';
                    textArea.style.left = '0';
                    textArea.style.position = 'fixed';
                    document.body.appendChild(textArea);
                    textArea.focus();
                    textArea.select();
                    try {
                        var successful = document.execCommand('copy');
                        setTimeout(function() {
                            if (successful) {
                                resolve('success')
                            } else {
                                reject('error')
                            }
                        }, 1);
                    } catch (err) {
                        setTimeout(function() {
                            reject(err)
                        }, 1);
                    } finally {
                        document.body.removeChild(textArea);
                    }
                })
            }
            async function copyTextToClipboard(text) {
                return new Promise((resolve, reject) => {
                    if (navigator.clipboard) {
                        navigator.clipboard.writeText(text).then(
                            resolve(),
                            function() {
                                // try the fallback in case `writeText` didn't work
                                fallbackCopyTextToClipboard(text).then(
                                    () => resolve(),
                                    () => reject()
                                )
                            });
                    } else {
                        fallbackCopyTextToClipboard(text).then(
                            () => resolve(),
                            () => reject()
                        )
                    }
                })
            }
            function copy(e) {
                const btn = e.currentTarget;
                const lang = btn.dataset.lang;
                const text = e.currentTarget.previousSibling.children[0].textContent;
                copyTextToClipboard(text)
                    .then(
                        () => {
                            toastr.success("Copied");

                            btn.innerHTML = `<i class="las la-copy"></i>`;
                            btn.setAttribute('style', 'opacity: 1');

                        },
                        () => alert('failed to copy'),
                    );
                setTimeout(() => {
                    btn.removeAttribute('style');
                    btn.innerHTML = `<i class="las la-copy"></i>`;
                }, 3000);
            }
            const copyButtons = document.querySelectorAll('.copy-button');
            copyButtons.forEach((btn) => {
                btn.addEventListener('click', copy);
            });
        }
        $(document).on('click', '._js_qr_download', async function() {
            var dataValue = $(this).data('text'); // Retrieve data-text value
            console.log(dataValue);
            try {
                await navigator.clipboard.writeText(dataValue);
                toastr.success("Copied");
            } catch (err) {
                toastr.error("Failed to copy text: " + err);
            }
        });
    </script>
@endpush
