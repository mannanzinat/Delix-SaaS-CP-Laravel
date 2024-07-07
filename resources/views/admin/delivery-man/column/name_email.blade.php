
<a href="{{route('detail.delivery.man.personal.info', $delivery_man->id)}}">
    <div class="user-info-panel d-flex gap-12 align-items-center">
        <div class="user-img">
            <img src="{{ optional($delivery_man->user->image)->image_small_two ? asset(optional($delivery_man->user->image)->image_small_two) : getFileLink('80X80', []) }}">
        </div>
        <div class="user-info">
            <h4>{{$delivery_man->user->first_name.' '.$delivery_man->user->last_name}}</h4>
            <span>{{$delivery_man->user->email}}<br>{{$delivery_man->phone_number}}
        </div>
    </div>
</a>
