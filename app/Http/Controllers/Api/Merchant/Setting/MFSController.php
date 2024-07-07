<?php

namespace App\Http\Controllers\Api\Merchant\Setting;

use App\Http\Controllers\Controller;
use App\Models\Merchant;
use Cartalyst\Sentinel\Checkpoints\NotActivatedException;
use Cartalyst\Sentinel\Checkpoints\ThrottlingException;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Traits\ApiReturnFormatTrait;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use App\Http\Resources\Api\MFSResource;
use Cartalyst\Sentinel\Laravel\Facades\Sentinel;
use App\Models\MerchantPaymentAccount;
use App\Traits\SendMailTrait;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Repositories\Interfaces\WithdrawInterface;
class MFSController extends Controller
{
    use ApiReturnFormatTrait;
    protected $withdrawRepo;

    public function __construct(WithdrawInterface $withdrawRepo)
    {
        $this->withdrawRepo     = $withdrawRepo;
    }
    public function mfs(Request $request)
    {
        try {
            $user = jwtUser();

            if (!$user) {
                return $this->responseWithError('User not authenticated');
            }

            if ($user->user_type == 'merchant') {
                $merchant               = Merchant::where('user_id', $user->id)->first();
            } elseif ($user->user_type == 'merchant_staff') {
                $merchant               = Merchant::where('id', $user->merchant_id)->first();
            } else {
                return $this->responseWithError('Invalid user type');
            }

            $methods                    = PaymentMethod::with('payment')->where('type', 'mfs')->get();
            $payment                    = MerchantPaymentAccount::where('merchant_id', $merchant->id)->where('type', 'mfs')->first();

            if ($payment) {
                $method = PaymentMethod::where('id', $payment->payment_method_id)->with('payment')->first();
            }

            $methods                    = PaymentMethod::with('payment')->where('type', 'mfs')->get();

            $data = [
                'mfs_method'            => MFSResource::collection($methods),

            ];

            return $this->responseWithSuccess('MFS retrieved successfully', [], $data);
        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }



    public function updateMfs(Request $request): \Illuminate\Http\JsonResponse
    {
        $user                       = jwtUser();
        try {
            $data                   = $this->updateOthersAccount($request);
            return $this->responseWithSuccess('MFS updated successfully');

        } catch (\Exception $e) {
            return $this->responseWithError(__('something_went_wrong_please_try_again'), [], 500);
        }
    }

    public function updateOthersAccount($data)
    {
        try {
            $convertedData = [];
            $user          = jwtUser();

            if ($user) {
                if ($user->user_type == 'merchant') {
                    $merchant               = Merchant::where('user_id', $user->id)->first();
                } elseif ($user->user_type == 'merchant_staff') {
                    $merchant               = Merchant::where('id', $user->merchant_id)->first();
                }
            }

            $mfsNumbers                 = str_replace(['[', ']'], '', $data['mfs_number'][0]);
            $mfsAcTypes                 = str_replace(['[', ']'], '', $data['mfs_ac_type'][0]);
            $paymentMethodIds           = str_replace(['[', ']'], '', $data['payment_method_id'][0]);

            $mfsNumbers                 = array_map('trim', explode(',', $mfsNumbers));
            $mfsAcTypes                 = array_map('trim', explode(',', $mfsAcTypes));
            $paymentMethodIds           = array_map('trim', explode(',', $paymentMethodIds));

            $convertedData = [
                "payment_method_id"     => $paymentMethodIds,
                "mfs_number"            => $mfsNumbers,
                "mfs_ac_type"           => $mfsAcTypes,
            ];

            foreach ($convertedData['mfs_number'] as $key => $number) {
                if (isset($convertedData['payment_method_id'][$key])) {
                    $payment_account = MerchantPaymentAccount::where('merchant_id', $merchant->id)
                        ->where('payment_method_id', $convertedData['payment_method_id'][$key])
                        ->first();

                    $type = PaymentMethod::where('id', $convertedData['payment_method_id'][$key])->first();

                    if (!$payment_account) {
                        $payment_account                    = new MerchantPaymentAccount;
                        $payment_account->merchant_id       = $merchant->id;
                        $payment_account->payment_method_id = $convertedData['payment_method_id'][$key];
                    }

                    $payment_account->mfs_number        = $number;
                    $payment_account->mfs_ac_type       = $convertedData['mfs_ac_type'][$key];
                    $payment_account->type              = $type->type;
                    $payment_account->save();
                }
            }
            return true;


        } catch (\Exception $e) {

            return false;
        }
    }

}
