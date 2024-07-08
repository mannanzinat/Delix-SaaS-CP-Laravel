<!-- HEADER====================== -->
<header id="header" class="tra-menu navbar-light white-scroll">
    <div class="header-wrapper">        <!-- MOBILE HEADER -->
        <div class="wsmobileheader clearfix">
            <span class="smllogo"><img
                    src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80', []) }}"
                    alt="{!! setting('company_name',app()->getLocale()) !!}"></span>
            <a id="wsnavtoggle" class="wsanimated-arrow"><span></span></a>
        </div>
        <!-- NAVIGATION MENU -->
        <div class="wsmainfull menu clearfix">
            <div class="wsmainwp clearfix">
                <!-- HEADER BLACK LOGO -->
                <div class="desktoplogo">
                    <a href="{{url('/')}}" class="logo-black">
                        <img src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80', []) }}"
                            alt="{!! setting('company_name',app()->getLocale()) !!}">
                    </a>
                </div>
                <!-- HEADER WHITE LOGO -->
                <div class="desktoplogo">
                    <a href="{{url('/')}}" class="logo-white">
                        <img src="{{ setting('light_logo') && @is_file_exists(setting('light_logo')['original_image']) ? get_media(setting('light_logo')['original_image']) : getFileLink('80x80', []) }}"
                            alt="{!! setting('company_name',app()->getLocale()) !!}">
                    </a>
                </div>
                <!-- MAIN MENU -->
                <nav class="wsmenu clearfix">
                    <ul class="wsmenu-list nav-theme">
                        @if(setting('show_default_menu_link') == 1)
							@if($menu_language && is_array(setting('header_menu')) ? count(setting('header_menu')) : 0 != 0 && setting('header_menu') != [])
								@foreach($menu_language as $key => $value)
									<li class="nl-simple" aria-haspopup="true"><a class="h-link" href="{{ @$value['url']  }}">{{ @$value['label'] }}</a></li>
								@endforeach
							@endif
						@endif 
                        <?php
                          $language_switcher = setting('language_switcher');
                          if($language_switcher==''){
                            $language_switcher = 1;
                          }
                        ?>
                        @if ($language_switcher)
                        <li aria-haspopup="true">
                            @php
                                $active_locale = '';
                                $languages = app('languages');
                                $locale_language = $languages->where('locale', app()->getLocale())->first();
                                if ($locale_language) {
                                    $active_locale = $locale_language->name;
                                }
                            @endphp
                            <a class="h-link" href="#">
                                {{ $active_locale }}
                                <span class="wsarrow"></span>
                            </a>
                            <ul class="sub-menu">
                                @foreach($languages as $language)
                                    <li aria-haspopup="true">
                                        <a href="{{ setLanguageRedirect($language->locale) }}">
                                            {{ $language->name }}
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </li>
                        @endif
                        
                        @if (Auth::check())
                            @if (Auth::user()->role_id == 1)
                                <li class="nl-simple" aria-haspopup="true">
                                    <a href="{{ route('admin.dashboard') }}" class="btn r-04 btn--tra-white hover--theme last-link">
                                        {{ __('dashboard') }}
                                    </a>
                                </li>
                            @else
                                <li class="nl-simple" aria-haspopup="true">
                                    <a href="{{ route('client.dashboard') }}" class="btn r-04 btn--tra-white hover--theme last-link">
                                        {{ __('dashboard') }}
                                    </a>
                                </li>
                            @endif
                        @else
                            <!-- SIGN IN LINK -->
                            <li class="nl-simple reg-fst-link mobile-last-link" aria-haspopup="true">
                                <a href="{{ route('login') }}" class="h-link">
                                    {{ __('sign_in') }}
                                </a>
                            </li>
                            <!-- SIGN UP BUTTON -->
                            <li class="nl-simple" aria-haspopup="true">
                                <a href="{{ route('register') }}" class="btn r-04 btn--tra-white hover--theme last-link">
                                    {{ __('create_free_account') }}
                                </a>
                            </li>
                        @endif
                    </ul>
                </nav> <!-- END MAIN MENU -->
            </div>
        </div> <!-- END NAVIGATION MENU -->
    </div> <!-- End header-wrapper -->
</header> <!-- END HEADER -->
