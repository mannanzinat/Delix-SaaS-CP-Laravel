@php

    $str = Str::random(21);
    session()->put('hash_token', $str);

    $php_version_success = false;
    $mysql_success = false;
    $curl_success = false;
    $gd_success = false;
    $allow_url_fopen_success = false;
    $timezone_success = true;

    $php_version_required_min = '8.0.2';
    $php_version_required_max = '9.0';
    $current_php_version = phpversion();

    //check required php version
    if (floatval($current_php_version) >= floatval($php_version_required_min) && floatval($current_php_version) < $php_version_required_max):
        $php_version_success = true;
    endif;

    //check mySql
    if (function_exists('mysqli_connect')):
        $mysql_success = true;
    endif;

    //check curl
    if (function_exists('curl_version')):
        $curl_success = true;
    endif;

    //check gd
    if (extension_loaded('gd') && function_exists('gd_info')):
        $gd_success = true;
    endif;

    //check allow_url_fopen
    if (ini_get('allow_url_fopen')):
        $allow_url_fopen_success = true;
    endif;

    //check allow_url_fopen
    $timezone_settings = ini_get('date.timezone');
    if ($timezone_settings):
        $timezone_success = true;
    endif;

    //check if all requirement is success
    if ($php_version_success && $mysql_success && $curl_success && $gd_success && $allow_url_fopen_success):
        $all_requirement_success = true;
    else:
        $all_requirement_success = false;
    endif;

    if (strpos(php_sapi_name(), 'cli') !== false || defined('LARAVEL_START_FROM_PUBLIC')):
        $writeable_directories = ['../app', '../config', '../routes', '../resources', '../public', '../storage', '../.env', '../bootstrap/cache'];
    else:
        $writeable_directories = ['./app', './config', './routes', './resources', './public', './storage', '.env', './bootstrap/cache'];
    endif;

    foreach ($writeable_directories as $value):
        if (!is_writeable($value)):
            $all_requirement_success = false;
        endif;
    endforeach;

@endphp
        <!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="SpaGreen">

    <title>SaleBot | Installation</title>

    <link rel="shortcut icon" href="{{ static_asset('images/default/favicon/favicon-96x96.png') }}">

    <link rel='stylesheet' type='text/css' href="{{ static_asset('install/bootstrap/css/bootstrap.min.css') }}"/>
    <link rel='stylesheet' type='text/css'
          href="{{ static_asset('install/js/font-awesome/css/font-awesome.min.css') }}"/>

    <link rel='stylesheet' type='text/css' href="{{ static_asset('install/css/install.css?ver=1.0.0') }}"/>
    <!--====== Color CSS ======-->
    <link rel='stylesheet' type='text/css' href="{{ static_asset('install/css/salebot.css?ver=1.0.0') }}"/>
</head>

<body>
<div class="install-box">

    <div class="panel panel-install">
        <div class="panel-heading text-center">
            @if (config('app.mobile_mode'))
                <h2>SaleBot - Installation</h2>
            @else
                <h2>SaleBot - Installation</h2>
            @endif

        </div>
        <div class="panel-body no-padding">
            <div class="tab-container clearfix">
                <div id="pre-installation" class="tab-title col-sm-4 active"><i class="fa fa-circle-o"></i><strong>
                        Pre-Installation</strong></div>
                <div id="configuration" class="tab-title col-sm-4"><i class="fa fa-circle-o"></i><strong>
                        Configuration</strong></div>
                <div id="finished" class="tab-title col-sm-4"><i class="fa fa-circle-o"></i><strong>
                        Finished</strong>
                </div>
            </div>
            <div id="alert-container">

                <div id="error_m" class="alert alert-danger hide">

                </div>

                <div id="success_m" class="alert alert-success hide">

                </div>

            </div>


            <div class="tab-content">
                <div role="tabpanel" class="tab-pane active" id="pre-installation-tab">
                    <div class="section">
                        <p>1. Please configure your PHP settings to match following requirements:</p>
                        <hr/>
                        <div>
                            <table>
                                <thead>
                                <tr>
                                    <th width="25%">PHP Settings</th>
                                    <th width="27%">Current Version</th>
                                    <th>Required Version</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>PHP Version</td>
                                    <td><?php echo phpversion(); ?></td>
                                    <td><?php echo $php_version_required_min; ?>
                                        or Later
                                    </td>
                                    <td class="text-center">
                                        <?php if ($php_version_success) { ?>
                                        <i class="status fa fa-check-circle-o"></i>
                                        <?php } else { ?>
                                        <i class="status fa fa-times-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="section">
                        <p>2. Please make sure the extensions/settings listed below are installed/enabled:</p>
                        <hr/>
                        <div>
                            <table>
                                <thead>
                                <tr>
                                    <th width="25%">Extension/settings</th>
                                    <th width="27%">Current Settings</th>
                                    <th>Required Settings</th>
                                    <th class="text-center">Status</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>MySQLi</td>
                                    <td>
                                        @if($mysql_success)
                                            On
                                        @else
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if ($mysql_success)
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>GD</td>
                                    <td>
                                        @if ($gd_success)
                                            On
                                        @else
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if ($gd_success)
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>cURL</td>
                                    <td>
                                        @if ($curl_success)
                                            On
                                        @else
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if ($curl_success)
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>allow_url_fopen</td>
                                    <td>
                                        @if ($allow_url_fopen_success)
                                            On
                                        @else
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if ($allow_url_fopen_success)
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>zip</td>
                                    <td>
                                        @if (extension_loaded('zip'))
                                            On
                                        @else
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('zip'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>zlip</td>
                                    <td>
                                        @if (extension_loaded('zlib'))
                                            On
                                        @else
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('zlib'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>OpenSSL PHP Extension</td>
                                    <td>
                                        @if (OPENSSL_VERSION_NUMBER < 0x009080bf)
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @else
                                            On
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (OPENSSL_VERSION_NUMBER < 0x009080bf)
                                            <i class="status fa fa-times-circle-o"></i>
                                        @else
                                            <i class="status fa fa-check-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>PDO PHP Extension</td>
                                    <td>
                                        @if (PDO::getAvailableDrivers())
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (PDO::getAvailableDrivers())
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>BCMath PHP Extension</td>
                                    <td>
                                        @if (extension_loaded('bcmath'))
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('bcmath'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Ctype PHP Extension</td>
                                    <td>
                                        @if (extension_loaded('ctype'))
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('ctype'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Fileinfo PHP Extension</td>
                                    <td>
                                        @if (extension_loaded('fileinfo'))
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('fileinfo'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Mbstring PHP Extension</td>
                                    <td>
                                        @if (extension_loaded('mbstring'))
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('mbstring'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>Tokenizer PHP Extension</td>
                                    <td>
                                        @if (extension_loaded('tokenizer'))
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('tokenizer'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>XML PHP Extension</td>
                                    <td>
                                        @if (extension_loaded('xml'))
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('xml'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>JSON PHP Extension</td>
                                    <td>
                                        @if (extension_loaded('json'))
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        @if (extension_loaded('json'))
                                            <i class="status fa fa-check-circle-o"></i>
                                        @else
                                            <i class="status fa fa-times-circle-o"></i>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>PHP ZipArchive Class</td>
                                    <td>
                                        @if (class_exists('ZipArchive'))
                                            On
                                        @else
                                            @php $all_requirement_success = false; @endphp
                                            Off
                                        @endif
                                    </td>
                                    <td>On</td>
                                    <td class="text-center">
                                        <i
                                                class="status fa {{ class_exists('ZipArchive') ? 'fa-check-circle-o' : 'fa-times-circle-o' }}"></i>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="section">
                        <p>3. Please make sure you have set the <strong>writable</strong> permission on the
                            following
                            folders/files:</p>
                        <hr/>
                        <div>
                            <table>
                                <tbody>
                                <?php
                                foreach ($writeable_directories as $value) {
                                    ?>
                                <tr>
                                    <td id="first-td"><?php echo $value; ?></td>
                                    <td class="text-center">
                                            <?php if (is_writeable($value)) { ?>
                                        <i class="status fa fa-check-circle-o"></i>
                                            <?php
                                        } else {
                                            $all_requirement_success = false;
                                            ?>
                                        <i class="status fa fa-times-circle-o"></i>
                                        <?php } ?>
                                    </td>
                                </tr>
                                    <?php
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="panel-footer">
                        <button <?php
                                if (!$all_requirement_success) {
                                    echo 'disabled=disabled';
                                }
                                ?> class="btn btn-info form-next"><i
                                    class='fa fa-chevron-right'></i> Next
                        </button>
                    </div>

                </div>
                <div role="tabpanel" class="tab-pane" id="configuration-tab">
                    <form id="config-form" action="{{ route('install.process') }}" method="POST">
                        @csrf
                        <div class="section clearfix">
                            <p>1. Please enter your database connection details.</p>
                            <hr/>
                            <div>
                                <input type="hidden" name="random_token" value="{{ bcrypt($str) }}">
                                <div class="form-group clearfix">
                                    <label for="host" class=" col-md-3">Database Host</label>
                                    <div class="col-md-9">
                                        <input type="text" value="{{ old('host') ?? 'localhost' }}"
                                               id="host" name="host" autofocus class="form-control"
                                               placeholder="Database Host (usually localhost)"/>
                                        <strong class="text-danger" id="host_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="db_user" class=" col-md-3">Database User</label>
                                    <div class=" col-md-9">
                                        <input type="text" value="{{ old('db_user') ?? '' }}" name="db_user"
                                               class="form-control" autocomplete="off"
                                               placeholder="Database user name"/>
                                        <strong class="text-danger" id="db_user_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="db_password" class=" col-md-3">Password</label>
                                    <div class=" col-md-9">
                                        <input type="password" value="{{ old('db_password') ?? '' }}"
                                               name="db_password" class="form-control" autocomplete="off"
                                               placeholder="Database user password"/>
                                        <strong class="text-danger" id="db_password_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="db_name" class=" col-md-3">Database Name</label>
                                    <div class=" col-md-9">
                                        <input type="text" value="{{ old('db_name') ?? '' }}" name="db_name"
                                               class="form-control" placeholder="Database Name"/>
                                        <strong class="text-danger" id="db_name_error"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="section clearfix">
                            <p>2. Please enter your activation code.</p>
                            <hr/>
                            <h4 class="text-danger">Get activation code from <a href="https://license.spagreen.net/"
                                                                                target="_blank">here.</a></h4>
                            <div>
                                <div class="form-group clearfix">
                                    <label for="activation_code" class="col-md-3">Activation code</label>
                                    <div class="col-md-9">
                                        <input type="text" value="{{ old('activation_code') ?? '' }}"
                                               id="activation_code" name="activation_code" class="form-control"
                                               placeholder="Find in license.spagreen.net"/>
                                        <strong class="text-danger" id="activation_code_error"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="panel-footer">
                            <button type="submit" class="btn btn-info form-next activation_checker">
                                <span class="loader hide"> Installing...</span>
                                <span class="button-text"><i class='fa fa-chevron-right'></i>Next</span>
                            </button>
                        </div>
                    </form>
                    <form action="{{ route('install.finalize') }}" id="user_details_form" class="hide" method="post">@csrf
                        <input type="hidden" name="user_details" value="1">
                        <div class="section clearfix">
                            <p>Please enter your account details for administration.</p>
                            <hr/>
                            <div>
                                <div class="form-group clearfix">
                                    <label for="first_name" class=" col-md-3">First Name</label>
                                    <div class="col-md-9">
                                        <input type="text"
                                               id="first_name" name="first_name" class="form-control"
                                               placeholder="Your first name"/>
                                        <strong class="text-danger" id="first_name_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="last_name" class=" col-md-3">Last Name</label>
                                    <div class=" col-md-9">
                                        <input type="text"
                                               id="last_name" name="last_name" class="form-control"
                                               placeholder="Your last name"/>
                                        <strong class="text-danger" id="last_name_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="email" class=" col-md-3">Email</label>
                                    <div class=" col-md-9">
                                        <input type="text" name="email"
                                               class="form-control" placeholder="Your email"/>
                                        <strong class="text-danger" id="email_error"></strong>
                                    </div>
                                </div>
                                <div class="form-group clearfix">
                                    <label for="password" class=" col-md-3">Password</label>
                                    <div class=" col-md-9">
                                        <input type="password"
                                               name="password" class="form-control" placeholder="Login password"/>
                                        <strong class="text-danger" id="password_error"></strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="panel-footer">
                            <button type="submit" class="btn btn-info form-next user_details_checker">
                                <span class="loader hide"> Installing...</span>
                                <span class="button-text"><i class='fa fa-chevron-right'></i>Finish</span>
                            </button>
                        </div>
                    </form>
                </div>

                <div role="tabpanel" class="tab-pane" id="finished-tab">
                    <div class="section">
                        <div class="clearfix">
                            <i class="status fa fa-check-circle-o pull-left"> </i><span
                                    class="pull-left">Congratulation! You have successfully installed <strong> YOORI -
                                        PWA eCommerce CMS PHP Script</strong></span>
                        </div>

                        <a class="go-to-login-page" href="{{ url('login') }}">
                            <div class="text-center">
                                <div class="font"><i class="fa fa-desktop"></i></div>
                                <div>Login to Admin Dashboard</div>
                            </div>
                        </a>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<script src="{{ static_asset('admin/js/jquery.min.js') }}"></script>

<script>
    $(document).ready(function () {
        $(document).ready(function () {
            var $preInstallationTab = $("#pre-installation-tab");
            var $configurationTab = $("#configuration-tab");

            $(document).on('click', ".form-next", function () {
                if ($preInstallationTab.hasClass("active")) {
                    $preInstallationTab.removeClass("active");
                    $configurationTab.addClass("active");
                    $("#pre-installation").find("i").removeClass("fa-circle-o").addClass("fa-check-circle");
                    $("#configuration").addClass("active");
                    $("#host").focus();
                }
            });

            $(document).on('submit', '#config-form', function (e) {
                e.preventDefault();
                let selector = this;
                $('#error_m').addClass('hide');
                $('#success_m').addClass('hide');
                $("input").removeClass('error_border');
                $("#config-form strong").text('');
                let url = $(selector).attr('action');
                let method = $(selector).attr('method');
                $('.activation_checker .button-text').addClass('hide');
                $('.activation_checker .loader').removeClass('hide');
                $('.activation_checker').addClass('disable_btn');
                let formData = new FormData(selector);

                $.ajax({
                    method: method,
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $(selector).addClass('hide');
                            $('#user_details_form').removeClass('hide');
                            $('#success_m').removeClass('hide').text(response.success);
                        } else {
                            $('.activation_checker .button-text').removeClass('hide');
                            $('.activation_checker .loader').addClass('hide');
                            $('.activation_checker').removeClass('disable_btn');
                            $('#error_m').removeClass('hide').html(response.error);
                        }
                    },
                    error: function (error) {
                        $('.activation_checker .button-text').removeClass('hide');
                        $('.activation_checker .loader').addClass('hide');
                        $('.activation_checker').removeClass('disable_btn');

                        if (error.status == 422) {
                            let errors = error.responseJSON.errors;
                            let error_length = Object.keys(error.responseJSON.errors);

                            for (let i = 0; i < error_length.length; i++) {
                                $('input[name = ' + error_length[i] + ']').addClass(
                                    'error_border');
                                $('#' + error_length[i] + '_error').text(errors[error_length[i]]
                                    [0]);
                            }
                        }
                    }
                })
            });
            $(document).on('submit', '#user_details_form', function (e) {
                e.preventDefault();
                $('#error_m').addClass('hide');
                $('#success_m').addClass('hide');
                $("input").removeClass('error_border');
                $("#user_details_form strong").text('');
                let selector = this;
                let url = $(selector).attr('action');
                let method = $(selector).attr('method');
                $('.user_details_checker .button-text').addClass('hide');
                $('.user_details_checker .loader').removeClass('hide');
                $('.user_details_checker').addClass('disable_btn');
                let formData = new FormData(selector);

                $.ajax({
                    method: method,
                    url: url,
                    data: formData,
                    cache: false,
                    contentType: false,
                    processData: false,
                    success: function (response) {
                        if (response.success) {
                            $('#success_m').removeClass('hide').text(response.success);
                            window.location.href = response.route;
                        } else {
                            $('.user_details_checker .button-text').removeClass('hide');
                            $('.user_details_checker .loader').addClass('hide');
                            $('.user_details_checker').removeClass('disable_btn');
                            $('#error_m').removeClass('hide').text(response.error);
                        }
                    },
                    error: function (error) {
                        $('.user_details_checker .button-text').removeClass('hide');
                        $('.user_details_checker .loader').addClass('hide');
                        $('.user_details_checker').removeClass('disable_btn');

                        if (error.status == 422) {
                            let errors = error.responseJSON.errors;
                            let error_length = Object.keys(error.responseJSON.errors);

                            for (let i = 0; i < error_length.length; i++) {
                                $('input[name = ' + error_length[i] + ']').addClass(
                                    'error_border');
                                $('#' + error_length[i] + '_error').text(errors[error_length[i]]
                                    [0]);
                            }
                        }
                    }
                })
            });
        });
    });
</script>
</body>

</html>