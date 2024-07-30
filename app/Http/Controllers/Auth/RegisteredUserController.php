<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\SignUpRequest;
use App\Providers\RouteServiceProvider;
use App\Repositories\RegistrationRepository;
use App\Repositories\EmailTemplateRepository;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Services\TimeZoneService;
use App\Traits\SendMailTrait;
use App\Traits\SendNotification;
use App\Models\Activation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\ClientStaff;
use App\Models\Client;
use App\Models\Domain;
use App\Models\User;
use Carbon;

class RegisteredUserController extends Controller
{
    use SendMailTrait, SendNotification;

    protected $repo;
    protected $emailTemplate;


    public function __construct(RegistrationRepository $repo, EmailTemplateRepository $emailTemplate)
    {
        $this->repo = $repo;
        $this->emailTemplate = $emailTemplate;
    }

    public function create()
    {


        if (Auth::check()) {

            if (Auth::user()->role_id == 1) {
                $url = RouteServiceProvider::ADMIN;

            } else {
                $url = RouteServiceProvider::CLIENT;
            }

            return redirect(url($url));
        }

        return view('backend.admin.auth.register');
    }
    public function store(SignUpRequest $request)
    {
        
        $result                     = $this->repo->signUp($request);

        if ($result['success']):
            $user                   = $result['user'];
            $link                   = route('user.verified', $user->token);
            $template_data          = $this->emailTemplate->emailConfirmation();
            $data = [
                'confirmation_link' => $link,
                'user'              => $user,
                'subject'           => $template_data->subject ?? __('welcome'),
                'email_templates'   => $template_data,
                'template_title'    => 'Email Confirmation',
            ];

            try {
                $this->sendmail($request->email, 'emails.template_mail', $data);
            } catch (\Exception $e) {
                \Log::error('Error sending email: ' . $e->getMessage());
            }

            $message                = __('registration_successful_please_check_your_email');

            if ($request->ajax()):
                return response()->json(['success' => true, 'message' => $message]);
            else:
                return redirect()->back()->with('success', $message);
            endif;
        else:
            if ($request->ajax()):
                return response()->json(['error' => $result['message']], 500);
            else:
                Toastr::error($result['message']);
                return redirect()->back()->withErrors([$result['message']]);
            endif;
        endif;
    }


    public function emailConfirmation(Request $request)
    {
        $user = User::where('email', $request->email)->first();
        if (setting('disable_email_confirmation') != 1) {
            Auth::guard('web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $data['user_id'] = $user->id;
            $data['code']    = Str::random(32);
            $activation      = Activation::create($data);
            $data            = [
                'user'              => $user,
                'user_id'           => $user->id,
                'code'              => $activation->code,
                'confirmation_link' => url('/').'/activation/'.$request->email.'/'.$activation->code,
                'template_title'    => 'email_confirmation',
            ];
            $this->sendmail($request->email, 'emails.template_mail', $data);
            Toastr::success(__('user_register_hints'));

            return redirect()->route('login');
        } else {
            return redirect()->route('login');
        }
    }
}
