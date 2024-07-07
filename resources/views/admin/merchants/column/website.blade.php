
<span>
    <span>{{  __('website') }} : @if(!blank($merchant->website))
        <a class="btn btn-link text-success text-right" href="{{ $merchant->website }}" title="{{ $merchant->website }}" style="width: 90px;" target="_blank"><i class="la la-link text-success"></i> {{ __('visit') }}</a>
        @endif
    </span>
    <span>{{  __('license') }} :
        @if(!blank($merchant->trade_license) && file_exists($merchant->trade_license))
            <a href="{{ static_asset($merchant->trade_license) }}" target="_blank"> <i class="la la-link"></i> {{ __('trade_license') }}</a>
        @endif
    </span>
</span>

