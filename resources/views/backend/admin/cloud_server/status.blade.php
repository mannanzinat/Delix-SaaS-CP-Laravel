@if(hasPermission('cloud_server.edit'))
    <div class="setting-check">
        <input type="checkbox" class="status-change" data-id="{{ $server->id }}"
               {{ ($server->status == 1) ? 'checked' : '' }} value="server-status/{{$server->id}}"
               id="customSwitch2-{{$server->id}}">
        <label for="customSwitch2-{{ $server->id }}"></label>
    </div>
@endif
