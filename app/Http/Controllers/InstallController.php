<?php

namespace App\Http\Controllers;

use App\Http\Requests\InstallRequest;
use App\Models\Setting;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\URL;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use ZipArchive;

class InstallController extends Controller
{
    public function index()
    {
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            return view('install.index');
        }
        if (config('app.app_installed')) {
            return redirect('/');
        }

        return view('install.index');
    }

    public function getInstall(InstallRequest $request): JsonResponse
    {
        ini_set('max_execution_time', 900);
        try {
            $host                = $request->host;
            $db_user             = $request->db_user;
            $db_name             = $request->db_name;
            $db_password         = $request->db_password;
            $activation_code     = $request->activation_code;
            try {
                $mysqli = @new \mysqli($host, $db_user, $db_password, $db_name);
            } catch (\Exception $e) {
                return response()->json([
                    'error' => __('please_input_valid_database_information.'),
                ]);
            }
            if (mysqli_connect_errno()) {
                return response()->json([
                    'error' => __('please_input_valid_database_information.'),
                ]);
            }
            $mysqli->close();
            $data['DB_HOST']     = $host;
            $data['DB_DATABASE'] = $db_name;
            $data['DB_USERNAME'] = $db_user;
            $data['DB_PASSWORD'] = $db_password;
            $verification        = validate_purchase($activation_code, $data);

            if ($verification === 'success') {
                session()->put('activation_code', $activation_code);

                return response()->json([
                    'success' => 'Activation Code & Database Connection Verified',
                ]);
            } elseif ($verification === 'connection_error') {
                return response()->json([
                    'error' => __('there_is_a_problem_to_connect_with_spaGreen_server.make_sure_you_have_active_internet_connection!'),
                ]);

            } elseif (! $verification) {
                return response()->json([
                    'error' => __('something_went_wrong_please_try_again.'),
                ]);
            } else {
                return response()->json([
                    'error' => $verification,
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }


    public function final(InstallRequest $request): JsonResponse
    {
        try {
            $zip_file    = base_path('public/install/installer.zip');
            if (file_exists($zip_file)) {
                $zip = new ZipArchive;
                if ($zip->open($zip_file) === true) {
                    $zip->extractTo(base_path('/'));
                    $zip->close();
                } else {
                    return response()->json([
                        'type'  => 'error',
                        'error' => 'Installation files Not Found, Please Try Again',
                        'route' => route('install.initialize'),
                    ]);
                }
                unlink($zip_file);
            }
            $config_file = base_path('config.json');
            if (file_exists($config_file)) {
                $config = json_decode(file_get_contents($config_file), true);
            } else {
                return response()->json([
                    'type'  => 'error',
                    'error' => 'Config File Not Found, Please Try Again',
                    'route' => route('install.initialize'),
                ]);
            }
            Artisan::call('migrate:fresh', ['--force' => true]);
            $this->dataInserts($config, $request);
            $this->envUpdates();
            $this->demoDataImport();
            Artisan::call('all:clear');

            return response()->json([
                'type'    => 'success',
                'success' => 'Installation was Successful',
                'route'   => url('/'),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
            ]);
        }
    }

    protected function dataInserts($config, $request): void
    {
        $locale                 ="en";
        $user                   = new User;
        $user->email            = $request->email;
        $user->first_name       = $request->first_name;
        $user->last_name        = $request->last_name;
        $user->dashboard        = 'admin';
        $user->password         = bcrypt($request->password);
        $user->permissions      = \Config::get('permission.admin')
;
        $user->save();

        $activation = Activation::create($user);
        Activation::complete($user, $activation->code);
        $code             = Setting::where('title', 'activation_code')->first();
        if ($code) {
            $code->update([
                'value' => session()->get('activation_code'),
            ]);
        } else {
            Setting::create([
                'title' => 'activation_code',
                'activation_code' => session()->get('activation_code'),
            ]);
        }


        if (isAppMode()) {
            $version      = $config['app_version'];
            $version_code = $config['app_version_code'];
        } else {
            $version      = $config['web_version'];
            $version_code = $config['web_version_code'];
        }

        $code             = Setting::where('title', 'version_code')->first();
        $version_no       = Setting::where('title', 'current_version')->first();

        if ($code) {
            $code->update([
                'value' => $version_code,
            ]);
        } else {
            Setting::create([
                'title' => 'version_code',
                'value' => $version_code,
            ]);
        }

        if ($version_no) {
            $version_no->update([
                'value' => $version,
            ]);
        } else {
            Setting::create([
                'title' => 'current_version',
                'value' => $version,
            ]);
        }

        Setting::create([
            'title' => 'default_language',
            'value' => $locale,
        ]);

        app()->setLocale($locale);
        cache()->put('locale', $locale);

        if (arrayCheck('removed_directories', $config)) {
            foreach ($config['removed_directories'] as $directory) {
                File::deleteDirectory(base_path($directory));
            }
        }
    }

    protected function envUpdates(): void
    {
        envWrite('APP_URL', URL::to('/'));
        envWrite('APP_INSTALLED', true);
        Artisan::call('key:generate');
        Artisan::call('all:clear');
    }
    protected function demoDataImport(): void
    {
        try {
            DB::unprepared(file_get_contents(base_path('public/sql/demo_data.sql')));
        } catch (\Exception $e) {
            // dd($e->getMessage());
        }
    }
}
