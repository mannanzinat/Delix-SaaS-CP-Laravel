<div class="user-info-panel d-flex gap-12 align-items-center">
    <div class="user-img">
        <img
        src="{{ optional($query->image)->image_small_two ? asset(optional($query->image)->image_small_two) : getFileLink('80X80', []) }}"
        alt="favicon">
    </div>
    <div class="user-info">
        <h4>{{ @$query->first_name . ' ' . @$query->last_name . '(' . @$query->branch->name . ')' }}</h4>
        <span>{{ @$query->email }}</span>
        @if(!empty($query->phone_number))
        | <span>{{ $query->phone_number }}</span>
        @endif
    </div>
</div>
