<div class="row">
    <div class="col-12 col-md-12">
        <div class="mb-4">
            <label for="embed_code" class="form-label">{{ __('javascript_embed_code') }}</label>
            <div class="code-container">
                <pre class="language-js"><code class="language-js">&lt;script src="{{ route('chat-widget-script',['id'=>$row->unique_id]) }}"&gt;&lt;/script&gt;</code></pre>
                <div class="toolbar"></div>
            </div>
        </div>
        <div class="staff-role-heigh simplebar">
            <div class="default-list-table table-responsive">
                <table class="table" id="permissions-table">
                    <thead>
                        <tr>
                            <th>{{ __('name') }}</th>
                            <th>{{ __('short_link') }}</th>
                            <th>{{ __('qr_code') }}</th>
                            <th class="text-center">{{ __('active') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($row->contacts as $contact)
                            <?php
                            $contactName = $contact->name ?? '';
                            $contactPhone = $contact->phone ?? '';
                            $contactLabel = $contact->label ?? '';
                            $avatar = getFileLink('80x80', $contact->images) ?? '';
                            $android = stripos($_SERVER['HTTP_USER_AGENT'], 'android');
                            $iphone = stripos($_SERVER['HTTP_USER_AGENT'], 'iphone');
                            $ipad = stripos($_SERVER['HTTP_USER_AGENT'], 'ipad');
                            $initialMessage = $contact->welcome_message;
                            if ($android !== false || $ipad !== false || $iphone !== false) {
                                $value = 'https://api.whatsapp.com/send?phone=' . $contactPhone . '&text=' . urlencode($initialMessage);
                            } else {
                                $value = 'https://web.whatsapp.com/send?phone=' . $contactPhone . '&text=' . urlencode($initialMessage);
                            }
                            ?>
                            <tr>
                                <td>{{ $contact->name }}</td>
                                <td>
                                    {{ $value }}
                                </td> 
                                <td>
                                    {!! QrCode::size(50)->color(74, 74, 74, 90)->generate($value) !!}
                                </td>
                                <td>
                                    <ul class="d-flex gap-30 justify-content-center">
                                        <li>
                                            <a href="javascript:void(0)" class="_js_qr_download" data-text="{{ route('client.chatwidget.contact.qr-download', $contact->unique_id) }}">
                                                <i class="las la-copy"></i>
                                            </a>
                                        </li>
                                        <li>
                                            <a href="{{ route('client.chatwidget.contact.qr-download', $contact->unique_id) }}">
                                                <i class="las la-download"></i>
                                            </a>
                                        </li>
                                    </ul>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
