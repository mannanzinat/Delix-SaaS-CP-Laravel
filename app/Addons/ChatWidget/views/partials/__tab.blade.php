<ul class="nav pb-12 mb-20" id="pills-tab" role="tablist">
    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.primary-content') }}" class="nav-link ps-0 {{ request()->routeIs('footer.primary-content') ? 'active' : '' }}">
            <span>{{ __('button') }}</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.primary-content') }}" class="nav-link ps-0 {{ request()->routeIs('footer.primary-content') ? 'active' : '' }}">
            <span>{{ __('button') }}</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.useful-links') }}" class="nav-link ps-0 {{ request()->routeIs('footer.useful-links') ? 'active' : '' }}">
            <span>{{ __('box') }}</span>
        </a>
    </li>

    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.quick-links') }}" class="nav-link ps-0 {{ request()->routeIs('footer.quick-links') ? 'active' : '' }}">
            <span>{{ __('contacts') }}</span>
        </a>
    </li>

    <li class="nav-item" role="presentation">
        <a href="{{ route('footer.payment-banner-settings') }}" class="nav-link ps-0 {{ request()->routeIs('footer.payment-banner-settings') ? 'active' : '' }}">
            <span>{{ __('settings') }}</span>
        </a>
    </li>

</ul>
