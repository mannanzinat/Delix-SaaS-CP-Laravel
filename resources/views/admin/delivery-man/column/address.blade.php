
<div>
    {{$delivery_man->address}}
    <div>
        Driving License:
        @if(!blank($delivery_man->driving_license) && file_exists($delivery_man->driving_license))
            <a href="{{ static_asset($delivery_man->driving_license) }}" target="_blank"> <i class="icon  las la-external-link-alt"></i> {{ __('driving_license') }}</a>
        @else
            {{ __('not_available') }}
        @endif
    </div>
</div>
