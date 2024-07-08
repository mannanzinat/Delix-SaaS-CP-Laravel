<!-- FOOTER-3============================================= -->
<footer id="footer-3" class="pt-100 footer">
    <div class="container">
        <!-- FOOTER CONTENT -->
        <div class="row">
            <!-- FOOTER LOGO -->
            <div class="col-xl-3">
                <div class="footer-info">
                    @php
                        $src =
                            setting('light_logo') && @is_file_exists(setting('light_logo')['original_image'])
                                ? get_media(setting('light_logo')['original_image'])
                                : get_media('images/default/logo/logo-green-white.png');
                    @endphp
                    <img class="footer-logo" src="{{ $src }}" alt="{!! setting('company_name',app()->getLocale()) !!}">
                </div>
                @if (setting('high_lighted_text'))
                    {!! setting('high_lighted_text',app()->getLocale()) !!}
                @endif
            </div>

            <!-- FOOTER LINKS -->
            @if (setting('show_useful_link') &&
                    is_array(setting('footer_useful_link_menu')) &&
                    count(setting('footer_useful_link_menu')) > 0)

                <div class="col-sm-4 col-md-3 col-xl-3">
                    <div class="footer-links fl-1">
                        <!-- Title -->
                        <h6 class="s-17 w-700">{{ setting('useful_link_title', app()->getLocale()) }}</h6>
                        @php
                            $useful_link_menu =
                                headerFooterMenu('footer_useful_link_menu', app()->getLocale()) ?:
                                headerFooterMenu('footer_useful_link_menu');
                        @endphp
                        <!-- Links -->
                        <ul class="foo-links clearfix">
                            @foreach ($useful_link_menu as $usefulLink)
                                <li>
                                    <p><a href="{{ $usefulLink['url'] }}">{{ $usefulLink['label'] }}</a></p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div> <!-- END FOOTER LINKS -->
            @endif
            @if (setting('show_resource_link') &&
                    is_array(setting('footer_resource_link_menu')) &&
                    count(setting('footer_resource_link_menu')) > 0)
                {{-- <!-- FOOTER LINKS -->
                <div class="col-sm-4 col-md-3 col-xl-3">
                    <div class="footer-links fl-2">
                        <!-- Title -->
                        <h6 class="s-17 w-700">{{ setting('resource_link_title', app()->getLocale()) }}</h6>
                        @php
                            $resource_link_menu =
                                headerFooterMenu('footer_resource_link_menu', app()->getLocale()) ?:
                                headerFooterMenu('footer_resource_link_menu');
                        @endphp
                        <!-- Links -->
                        <ul class="foo-links clearfix">
                            @foreach ($resource_link_menu as $resourceLink)
                                <li>
                                    <p><a href="{{ $resourceLink['url'] }}">{{ $resourceLink['label'] }}</a></p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div> <!-- END FOOTER LINKS --> --}}
            @endif
            @if (setting('show_quick_link') &&
                    is_array(setting('footer_quick_link_menu')) &&
                    count(setting('footer_quick_link_menu')) > 0)
                <!-- FOOTER LINKS -->
                <div class="col-sm-4 col-md-3 col-xl-3">
                    <div class="footer-links fl-3">
                        <!-- Title -->
                        <h6 class="s-17 w-700">{{ setting('quick_link_title', app()->getLocale()) }}</h6>
                        @php
                            $quick_link_menu =
                                headerFooterMenu('footer_quick_link_menu', app()->getLocale()) ?:
                                headerFooterMenu('footer_quick_link_menu');
                        @endphp
                        <!-- Links -->
                        <ul class="foo-links clearfix">
                            @foreach ($quick_link_menu as $quickLink)
                                <li>
                                    <p><a href="{{ $quickLink['url'] }}">{{ $quickLink['label'] }}</a></p>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div> <!-- END FOOTER LINKS -->
            @endif
            <!-- FOOTER LINKS -->
            <div class="col-sm-6 col-md-3">
                <div class="footer-links fl-4">
                    <!-- Title -->
                    <h6 class="s-17 w-700">{{ __('connect_with_us') }}</h6>
                    <!-- Mail Link -->
                    <p class="footer-mail-link ico-25">
                        <a href="mailto:{!! setting('contact_email', app()->getLocale()) !!}">{!! setting('contact_email', app()->getLocale()) !!}</a>
                    </p>
                    @if (setting('show_social_links') != 0)
                        <ul class="footer-socials ico-25 text-center clearfix">
                            @if (setting('facebook_link') != '')
                                <li>
                                    <a href="{{ setting('facebook_link') }}">
                                        <span class="flaticon-facebook"></span>
                                    </a>
                                </li>
                            @endif
                            @if (setting('twitter_link') != '')
                                <li>
                                    <a href="{{ setting('twitter_link') }}">
                                        <span class="flaticon-twitter"></span>
                                    </a>
                                </li>
                            @endif
                            @if (setting('linkedin_link') != '')
                                <li>
                                    <a href="{{ setting('linkedin_link') }}">
                                        <span class="flaticon-linkedin"></span>
                                    </a>
                                </li>
                            @endif
                            @if (setting('instagram_link') != '')
                                <li>
                                    <a href="{{ setting('instagram_link') }}">
                                        <span class="flaticon-instagram"></span>
                                    </a>
                                </li>
                            @endif
                            @if (setting('youtube_link') != '')
                                <li> 
                                    <a href="{{ setting('youtube_link') }}">
                                        <span class="flaticon-youtube"></span>
                                    </a>
                                </li>
                            @endif
                        </ul>
           
                    @endif
                </div>
            </div> <!-- END FOOTER LINKS -->
        </div> <!-- END FOOTER CONTENT -->
        <hr> <!-- FOOTER DIVIDER LINE -->
        <!-- BOTTOM FOOTER -->
        <div class="bottom-footer">
            <div class="row row-cols-1 row-cols-md-2 d-flex align-items-center">
                <!-- FOOTER COPYRIGHT -->
                <div class="col">
                    <div class="footer-copyright">
                        <p class="p-sm">{!! setting('copyright_title', app()->getLocale()) !!}</p>
                    </div>
                </div>
            </div> <!-- End row -->
        </div> <!-- END BOTTOM FOOTER -->
    </div> <!-- End container -->
</footer> <!-- END FOOTER-3 -->
