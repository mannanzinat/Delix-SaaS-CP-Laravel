<ul class="d-flex gap-30 justify-content-center">
    <li>
        <a href="javascript:void(0)" class="__js_get_embed_code" data-url="{{ route('client.chatwidget.embad-code',$query->id) }}" title="{{ __('get_embed_code') }}" data-id="{{ $query->id }}" data-id="{{ $query->id }}">
                <i class="las la-code"></i>
        </a>
    </li>
    <li>
        <a title="{{ __('view') }}" href="{{ route('client.chatwidget.view', $query->id) }}">
                <i class="las la-cog"></i>
        </a>
    </li>
    <li>
        <a href="javascript:void(0)" class="__js_reset_settings" title="{{ __('reset_settings') }}" data-url="{{ route('client.chatwidget.reset-setting', $query->id) }}">
                <i class="las la-sync"></i>
        </a>
    </li>
    <li>
        <a href="javascript:void(0)" class="__js_delete" data-url="{{ route('client.chatwidget.destroy', $query->id) }}"
            data-toggle="tooltip" data-original-title="{{ __('delete') }}">
            <i class="las la-trash-alt"></i>
        </a>
    </li>
</ul>
