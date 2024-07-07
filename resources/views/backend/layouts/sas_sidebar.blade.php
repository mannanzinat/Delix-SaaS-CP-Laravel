<header class="navbar-dark-v1">
    <div class="header-position">
        <span class="sidebar-toggler">
            <i class="las la-times"></i>
        </span>
        <div class="dashboard-logo d-flex justify-content-center align-items-center py-20">
            <a class="logo" href="{{ route('sas.admin.dashboard') }}">
                <img style="width: 100% !important;max-height: 38px;" src="{{ setting('admin_logo') && @is_file_exists(setting('admin_logo')['original_image']) ? get_media(setting('admin_logo')['original_image']) : get_media('images/default/logo/logo_light.png') }}"
                     alt="Logo">
            </a>
        </div>
        <nav class="side-nav">
            <ul>
                <li class="{{ menuActivation(['dashboard','dashboard/*'], 'active') }}">
                    <a
                        href="{{ route('sas.admin.dashboard') }}">
                        <i class="las la-tachometer-alt"></i>
                        <span>{{ __('dashboard') }}</span>
                    </a>
                </li>

                    <li class="{{ menuActivation(['admin/clients', 'admin/clients*'], 'active') }}">
                        <a href="{{ route('clients.index') }}">
                            <i class="las la-user"></i>
                            <span>{{ __('manage_client') }}</span>
                        </a>
                    </li>
                    <li class="{{ menuActivation('admin/subscriptions', 'active') }}">
                        <a href="">
                            <i class="las la-money-bill"></i>
                            <span>{{ __('subscription') }}</span>
                        </a>
                    </li>
                    <li class="{{ menuActivation('admin/plans*', 'active') }}">
                        <a href="">
                            <i class="las la-money-bill-wave"></i>
                            <span>{{ __('price_plans') }}</span>
                        </a>
                    </li>

                    <li class="{{ menuActivation('admin/payment-gateway', 'active') }}">
                        <a href="">
                            <i class="las la-credit-card"></i>
                            <span>{{ __('payment_gateway') }}</span>
                        </a>
                    </li>
                    <li class="{{ menuActivation(['admin/custom-notification', 'admin/custom-notification*'], 'active') }}">
                        <a href="">
                            <i class="las la-bell"></i>
                            <span>{{ __('notification') }}</span>
                        </a>
                    </li>

                @if (hasPermission('user_read') || hasPermission('role_read'))
                    <li
                        class="{{ menuActivation(['admin/roles', 'admin/roles/*', 'admin/users', 'admin/user/*'], 'active') }}">
                        <a href="#staff-menu" class="dropdown-icon" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ menuActivation(['admin/roles', 'admin/roles/*', 'admin/users', 'admin/user/*', 'admin/user-create'], 'true', 'false') }}"
                            aria-controls="staff-menu">
                            <i class="la la-users"></i>
                            <span>{{ __('user_manage') }}</span>
                        </a>
                        <ul id="staff-menu"
                            class="sub-menu collapse {{ menuActivation(['admin/roles', 'admin/roles/*', 'admin/users', 'admin/user/*', 'admin/user-create'], 'show') }}">
                            @if (hasPermission('role_read'))
                                <li>
                                    <a href="{{ route('roles.index') }}"
                                        class="{{ menuActivation(['admin/roles', 'admin/roles/*'], 'active') }}"><span>{{ __('roles') }}</span></a>
                                </li>
                            @endif
                            @if (hasPermission('user_read'))
                                <li>
                                    <a href="{{ route('users') }}"
                                        class="{{ menuActivation(['admin/users', 'admin/user/*'], 'active') }}"><span>{{ __('users') }}</span></a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif

                @if (hasPermission('settings_read'))
                    <li
                        class="{{ menuActivation(['admin/preference-setting', 'admin/setting/pusher-notification', 'admin/cron-setting', 'admin/setting/one-signal-notification', 'admin/custom-notification', 'admin/custom-notification*', 'admin/setting/*', 'admin/otp-setting', 'admin/languages', 'admin/countries', 'admin/payment-method', 'admin/payment-method/*'], 'active') }}">
                        <a href="#setting-menu" class="dropdown-icon" data-bs-toggle="collapse" role="button"
                            aria-expanded="{{ menuActivation(['admin/payment-method', 'admin/payment-method/*'], 'true', 'false') }}"
                            aria-controls="setting-menu">
                            <i class="las la-cogs"></i>
                            <span>{{ __('system_settings') }}</span>
                        </a>
                        <ul id="setting-menu"
                            class="sub-menu collapse {{ menuActivation(['admin/preference-setting', 'admin/cron-setting', 'admin/setting/pusher-notification', 'admin/setting/one-signal-notification', 'admin/custom-notification', 'admin/custom-notification*', 'admin/otp-setting', 'admin/setting/*', 'admin/languages', 'admin/countries', 'admin/payment-method', 'admin/payment-method/*'], 'show') }}">

                            @if (hasPermission('general_setting'))
                                <li><a class="{{ menuActivation('admin/setting/system-setting', 'active') }}"
                                        href="{{ route('general.setting') }}">{{ __('general_setting') }}</a></li>
                            @endif

                            @if (hasPermission('preference'))
                                <li><a href="{{ route('preference.setting') }}"
                                        class="{{ menuActivation(['admin/setting/preference-setting'], 'active') }}">
                                        <span>{{ __('preference') }}</span></a>
                                </li>
                            @endif

                            @if (hasPermission('language_read'))
                                <li><a class="{{ menuActivation(['admin/languages', 'admin/language/*'], 'active') }}"
                                        href="{{ route('languages.index') }}">{{ __('language_settings') }}</a>
                                </li>
                            @endif

                            @if (hasPermission('payment_method_create'))
                                <li><a class="{{ menuActivation(['admin/setting/payment-method*', 'admin/sms/payment-method/*'], 'active') }}"
                                        href="{{ route('admin.payment.method') }}">{{ __('payout_method') }}</a>
                                </li>
                            @endif

                            @if (hasPermission('panel_setting'))
                                <li><a class="{{ menuActivation('admin/setting/panel-setting', 'active') }}"
                                        href="{{ route('admin.panel-setting') }}">{{ __('admin_panel_setting') }}</a>
                                </li>
                            @endif
                            @if (hasPermission('country_read'))
                                <li><a class="{{ menuActivation('admin/setting/countries', 'active') }}"
                                        href="{{ route('countries.index') }}">{{ __('country') }}</a></li>
                            @endif
                            <li>
                                <a href="{{ route('charges.setting') }}"
                                    class="{{ menuActivation('admin/setting/charges-setting', 'active') }}">
                                    <span>{{ __('default_charge') }}</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('packaging.charge.setting') }}"
                                    class="{{ menuActivation(['admin/setting/packaging-charge-setting'], 'active') }}">
                                    <span>{{ __('packaging_type_and_charges') }}</span>
                                </a>
                            </li>

                            <li>
                                <a class="{{ menuActivation('admin/cron-setting', 'active') }}"
                                href="{{ route('cron.setting') }}">{{ __('cron_job') }}</a>
                            </li>

                            <li>
                                <a class="{{ menuActivation('admin/setting/pusher-notification', 'active') }}"
                                    href="{{ route('pusher.notification') }}">{{ __('pusher') }}</a>
                            </li>
                        </ul>
                    </li>
                @endif
                @if (hasPermission('apikeys.index'))
                    <li
                        class="{{ menuActivation(['admin/apikeys*'], 'active') }}">
                        <a href="{{ route('apikeys.index') }}">
                            <i class="las la-mobile"></i>
                            <span>{{ __('mobile_app_setting') }}</span>
                        </a>
                    </li>
                @endif
                @if (hasPermission('email_template_read') || hasPermission('server_configuration_update'))
                    <li
                        class="{{ menuActivation(['admin/email/server-configuration*', 'admin/email/template*'], 'active') }}">
                        <a href="#emailSetting" class="dropdown-icon" data-bs-toggle="collapse"
                            aria-expanded="{{ menuActivation(['admin/email/server-configuration*', 'admin/email/template*'], 'true', 'false') }}"
                            aria-controls="emailSetting">
                            <i class="las la-envelope"></i>
                            <span>{{ __('email_settings') }}</span>
                        </a>
                        <ul class="sub-menu collapse {{ menuActivation(['admin/email/server-configuration*', 'admin/email/template*'], 'show') }}"
                            id="emailSetting">
                            @if (hasPermission('email_template_read'))
                                <li><a class="{{ menuActivation('admin/email/template*', 'active') }}"
                                        href="{{ route('email.template') }}">{{ __('email_template') }}</a></li>
                            @endif
                            @if (hasPermission('server_configuration_update'))
                                <li><a class="{{ menuActivation('admin/email/server-configuration*', 'active') }}"
                                        href="{{ route('email.server-configuration') }}">{{ __('server_configuration') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @if (hasPermission('notice_read'))
                    <li class="{{ menuActivation(['admin/notice', 'admin/notice/*'], 'active') }}">
                        <a href="{{ route('notice') }}">
                            <i class="las la-bell"></i>
                            <span>{{ __('notice') }}</span>
                        </a>
                    </li>
                @endif
                @if (hasPermission('system_update') || hasPermission('server_info'))
                    <li class="{{ menuActivation(['admin/utility/*'], 'active') }}">
                        <a href="#utility" class="dropdown-icon" data-bs-toggle="collapse"
                            aria-expanded="{{ menuActivation(['admin/utility/*'], 'true', 'false') }}"
                            aria-controls="utility">
                            <i class="las la-cogs"></i>
                            <span>{{ __('utility') }}</span>
                        </a>
                        <ul class="sub-menu collapse {{ menuActivation(['admin/utility/*'], 'show') }}"
                            id="utility">
                            @if (hasPermission('system_update'))
                                <li><a class="{{ menuActivation(['admin/utility/system-update'], 'active') }}"
                                        href="{{ route('system.update') }}">{{ __('system_update') }}</a></li>
                            @endif
                            @if (hasPermission('server_info'))
                                <li>
                                    <a class="{{ menuActivation(['admin/utility/server-info', 'admin/utility/system-info', 'admin/utility/extension-library', 'admin/utility/file-system-permission'], 'active') }}"
                                        href="{{ route('server.info') }}">{{ __('server_information') }}</a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
            </ul>
        </nav>
        <div class="footer_copyright">
            <div class="version">{{ __('version') }} <span>{{ setting('version_code') }}</span></div>
            <p>{{ setting('admin_panel_copyright_text', app()->getLocale()) }}</p>
        </div>
    </div>
</header>
