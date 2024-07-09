@if(hasPermission('cloud_server.edit'))
    <div class="setting-check">
        <input type="checkbox" class="default-change" data-id="{{ $server->id }}"
               {{ $server->default ? 'checked' : '' }} value="server-default-status/{{$server->id}}"
               id="customSwitch1-{{$server->id}}">
        <label for="customSwitch1-{{ $server->id }}"></label>
    </div>
@endif

{{-- <span>
    <div class="setting-check">
            <input type="checkbox"
                class="custom-control-input default-change"
                {{ $shop->default ? 'checked' : '' }}
                value="{{ $shop->id }}"
                data-merchant="{{ Sentinel::getUser()->user_type == 'merchant' ? Sentinel::getUser()->merchant->id : Sentinel::getUser()->merchant_id }}"
                data-url="{{ Sentinel::getUser()->user_type == 'merchant' ? route('merchant.default.shop') : route('merchant.staff.default.shop') }}"
                id="customSwitch2-{{ $shop->id }}">
            <label class="custom-control-label"
                for="customSwitch2-{{ $shop->id }}"></label>
    </div>
</span> --}}
