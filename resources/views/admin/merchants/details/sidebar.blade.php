
<div>
    <div class="user-info-panel d-flex gap-12 align-items-center">
        <div class="user-img">
            <img src="{{ optional($merchant->user->image)->image_small_two ? asset(optional($merchant->user->image)->image_small_two) : getFileLink('80X80', []) }}">
        </div>
        <div class="user-info">
            <h4>{{$merchant->user->first_name.' '.$merchant->user->last_name}}</h4>
            <span>{{$merchant->user->email}}</span>
        </div>
    </div>
</div>

@include('common.script')
@push('css')
<style>
    .default-tab-list.default-tab-list-v2 ul li a.nav-link.active::after {
    border: 0px;
}
</style>
@endpush
