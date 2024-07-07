<?php

namespace App\Repositories;

use Image;
use App\Models\User;
use App\Models\Merchant;
use App\Enums\StatusEnum;
use App\Traits\RepoResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Image as ImageModel;
use App\Traits\ApiReturnFormatTrait;
use Cartalyst\Sentinel\Laravel\Facades\Activation;
use App\Repositories\Interfaces\MerchantStaffInterface;

class MerchantStaffRepository implements MerchantStaffInterface{

    use RepoResponseTrait, ApiReturnFormatTrait;

    private $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function get($id)
    {
        $user = User::find($id);;
        if ($user->user_type == 'merchant_staff'):
            return $user;
        else:
            return abort(403, 'Access Denied');
        endif;
    }
    public function getMerchant($id)
    {
        return Merchant::find($id);
    }

    public function paginate($merchant)
    {
        return $merchant->staffs()->paginate(\Config::get('parcel.paginate'));
    }


    public function store($request)
    {
        DB::beginTransaction();
        try{
            $merchant = $this->getMerchant($request->merchant);


            if (!blank($request->file('image'))) {

                $requestImage           = $request->file('image');
                $fileType               = $requestImage->getClientOriginalExtension();

                $originalImage      = date('YmdHis') . "_original_" . rand(1, 50) . '.' . $fileType;
                $imageSmallOne      = date('YmdHis') . "image_small_one" . rand(1, 50) . '.' . $fileType;
                $imageSmallTwo      = date('YmdHis') . "image_small_two" . rand(1, 50) . '.' . $fileType;
                $imageSmallThree    = date('YmdHis') . "image_small_three" . rand(1, 50) . '.' . $fileType;

                $directory              = 'admin/profile-images/';

                $storagePath            = public_path($directory);
                if (!is_dir($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }

                $originalImageUrl       = $storagePath . $originalImage;
                $imageSmallOneUrl       = $storagePath . $imageSmallOne;
                $imageSmallTwoUrl       = $storagePath . $imageSmallTwo;
                $imageSmallThreeUrl     = $storagePath . $imageSmallThree;

                Image::make($requestImage)->save($originalImageUrl, 80);
                Image::make($requestImage)->fit(32, 32)->save($imageSmallOneUrl, 80);
                Image::make($requestImage)->fit(40, 40)->save($imageSmallTwoUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallThreeUrl, 80);

                $image                          = new ImageModel();
                $image->original_image          =  static_asset($directory . $originalImage);
                $image->image_small_one         =  static_asset($directory . $imageSmallOne);
                $image->image_small_two         =  static_asset($directory . $imageSmallTwo);
                $image->image_small_three       =  static_asset($directory . $imageSmallThree);;
                $image->save();

            }


            $user = new User();
            $user->first_name    = $request->first_name;
            $user->last_name     = $request->last_name;
            $user->email         = $request->email;
            $user->phone_number  = $request->phone_number;
            $user->password      = bcrypt($request->password);
            $user->permissions   = isset($request->permissions) ? $request->permissions : [];
            $user->image_id      = $image->id ?? null;
            $user->merchant_id   = $merchant->id;
            $user->shops         = $request->shops;
            $user->user_type     = 'merchant_staff';
            $user->save();

            $activation = Activation::create($user);
            Activation::complete($user, $activation->code);

            DB::commit();
            return true;

        } catch (\Exception $e) {
            dd($e->getMessage());
            DB::rollback();
            return false;
        }
    }
    public function update($request)
    {
        DB::beginTransaction();
        try{

            $merchant = $this->getMerchant($request->merchant);
            $user     = User::find($request->id);
            if (!blank($request->file('image'))) {

                $image           = ImageModel::find($user->image_id);

                if(!blank($image)):
                    if($image->original_image != "" && file_exists($image->original_image)):
                        unlink($image->original_image);
                    endif;
                    if($image->image_small_one != "" && file_exists($image->image_small_one)):
                        unlink($image->image_small_one);
                    endif;
                    if($image->image_small_two != "" && file_exists($image->image_small_two)):
                        unlink($image->image_small_two);
                    endif;
                    if($image->image_small_three != "" && file_exists($image->image_small_three)):
                        unlink($image->image_small_three);
                    endif;
                else:
                    $image     = new ImageModel();
                endif;

                $requestImage           = $request->file('image');
                $fileType               = $requestImage->getClientOriginalExtension();

                $originalImage          = date('YmdHis') . "_original_" . rand(1, 50) . '.' . $fileType;
                $imageSmallOne          = date('YmdHis') . "image_small_one" . rand(1, 50) . '.' . $fileType;
                $imageSmallTwo          = date('YmdHis') . "image_small_two" . rand(1, 50) . '.' . $fileType;
                $imageSmallThree        = date('YmdHis') . "image_small_three" . rand(1, 50) . '.' . $fileType;

                $directory              = 'admin/profile-images/';

                $storagePath = public_path($directory);
                if (!is_dir($storagePath)) {
                    mkdir($storagePath, 0755, true);
                }

                $originalImageUrl       = $storagePath . $originalImage;
                $imageSmallOneUrl       = $storagePath . $imageSmallOne;
                $imageSmallTwoUrl       = $storagePath . $imageSmallTwo;
                $imageSmallThreeUrl     = $storagePath . $imageSmallThree;

                Image::make($requestImage)->save($originalImageUrl, 80);
                Image::make($requestImage)->fit(32, 32)->save($imageSmallOneUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallTwoUrl, 80);
                Image::make($requestImage)->fit(80, 80)->save($imageSmallThreeUrl, 80);

                $image->original_image          =  static_asset($directory . $originalImage);
                $image->image_small_one         =  static_asset($directory . $imageSmallOne);
                $image->image_small_two         =  static_asset($directory . $imageSmallTwo);
                $image->image_small_three       =  static_asset($directory . $imageSmallThree);;
                $image->save();

                $user->image_id    = $image->id;

            }

            $user->first_name    = $request->first_name;
            $user->last_name     = $request->last_name;
            $user->email         = $request->email;
            $user->phone_number  = $request->phone_number;
            $user->shops         = $request->shops;
            $user->merchant_id   = $merchant->id;
            if($request->password != ""):
                $user->password  = bcrypt($request->password);
            endif;
            $user->permissions   = isset($request->permissions) ? $request->permissions : [];

            $user->save();

            DB::commit();
            return true;

        } catch (\Exception $e) {
            DB::rollback();
            return false;
        }
    }

    public function statusChange($request)
    {
        try {
            $row = $this->model->find($request->id);
            if ($row->status == StatusEnum::ACTIVE) {
                $row->status = StatusEnum::INACTIVE;
            } elseif ($row->status == StatusEnum::INACTIVE) {
                $row->status = StatusEnum::ACTIVE;
            }
            $row->save();

            return $this->responseWithSuccess(__('updated_successfully'), []);
        } catch (\Throwable $th) {
            return $this->responseWithError($th->getMessage(), []);
        }
    }



}
