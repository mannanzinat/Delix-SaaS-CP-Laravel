<div class="d-flex text-center gap-3">
    <div class="custom-checkbox checkbox-column">
        <label for="active_domain-{{ $client->id }}" class="custom-control-label pt-2 pb-4">
            <input type="checkbox" class="custom-control-input active_domain read common-key pb-4" 
                   name="active_domain" 
                   value="1" 
                   id="active_domain-{{ $client->id }}" 
                   data-client-id="{{ $client->id }}" 
                   data-field="custom_domain_active"
                   {{ @$client->domains->custom_domain_active ? 'checked' : '' }}>
            <span>{{ __('active_domain') }}</span>
        </label>
    </div>

    <div class="custom-checkbox checkbox-column">
        <label for="deployed_script-{{ $client->id }}" class="custom-control-label pt-2 pb-4">
            <input type="checkbox" class="custom-control-input read common-key pb-4" 
                   name="deployed_script" 
                   value="1" 
                   id="deployed_script-{{ $client->id }}" 
                   data-client-id="{{ $client->id }}" 
                   data-field="custom_domain_active"
                   {{ @$client->domains->script_deployed ? 'checked' : '' }}>
            <span>{{ __('deployed_script') }}</span>
        </label>
    </div>
    <div class="custom-checkbox checkbox-column">
        <label for="ssl_active-{{ $client->id }}" class="custom-control-label pt-2 pb-4">
            <input type="checkbox" class="custom-control-input read common-key pb-4" 
                   name="ssl_active" 
                   value="1" 
                   id="ssl_active-{{ $client->id }}"
                   data-client-id="{{ $client->id }}" 
                   data-field="custom_domain_active" 
                   {{ @$client->domains->ssl_active ? 'checked' : '' }}>
            <span>{{ __('ssl_active') }}</span>
        </label>
    </div>
</div>
{{-- <button class="btn testpurpose">submit</button> --}}

 <style>
    .custom-checkbox.checkbox-column {
        margin-top: 20px;
    }
    .checkbox-column label span {
      padding-left: 0;
        padding-top: 23px;
        white-space: nowrap;
    }
    .checkbox-column input[type=checkbox] + span::after {
        top: 0;
        left: 50%;
        transform: translateX(-50%);
    }
    .checkbox-column input[type=checkbox]:checked + span::before {
        left: 50%;
        top: 3px;
        transform: translateX(-50%) rotate(45deg);
    }
 </style>

