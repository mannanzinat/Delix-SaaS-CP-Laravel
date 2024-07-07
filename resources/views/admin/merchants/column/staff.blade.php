
<a href="{{route('detail.merchant.personal.info', $merchant->id)}}">
    <div class="user-info-panel d-flex gap-12 align-items-center">
        <div class="user-img">
            <img src="{{ optional($merchant->user->image)->image_small_two ? asset(optional($merchant->user->image)->image_small_two) : getFileLink('80X80', []) }}">
        </div>
        <div class="user-info">
            <h4>{{$merchant->user->first_name.' '.$merchant->user->last_name .'('.$merchant->company.')'}}</h4>
            <span>{{$merchant->user->email}}</span> | <span>{{$merchant->phone_number}}</span>
        </div>
    </div>
</a>
