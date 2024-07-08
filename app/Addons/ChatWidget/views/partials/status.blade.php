<div class="setting-check">
    <input type="checkbox" class="__js_update_status"
        {{ $query->status == 1 ? 'checked' : '' }}
        data-id="{{ $query->id }}"
        data-url="{{ route('client.chatwidget.status.update',$query->id) }}"
        value="0"
        id="customSwitch2-{{ $query->id }}">
    <label
        for="customSwitch2-{{ $query->id }}"></label>
</div>