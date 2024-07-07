<?php


namespace App\Repositories\Admin;

use App\Enums\StatusEnum;
use App\Models\PaymentMethod;
use App\Traits\RepoResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Traits\ApiReturnFormatTrait;
use App\Repositories\Interfaces\Admin\PaymentMethodInterface;

class PaymentMethodRepository implements PaymentMethodInterface
{
    use ApiReturnFormatTrait,RepoResponseTrait;

    public function paginate()
    {
        return PaymentMethod::orderByDesc('id')->paginate(10);
    }

    public function get($id)
    {
        return PaymentMethod::find($id);
    }

    public function store($request)
    {
        DB::beginTransaction();
        try {
            $payment                = new PaymentMethod();
            $payment->name          = $request->name;
            $payment->type          = $request->type;
            $payment->save();

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }

    public function update($request, $id)
    {
        DB::beginTransaction();
        try {
            $payment                = $this->get($id);
            $payment->name          = $request->name;
            $payment->type          = $request->type;
            $payment->save();

            DB::commit();
            return true;
        } catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }


    public function statusChange($request)
    {
        DB::beginTransaction();
        try {

            $row =  $this->get($request['id']);
            if ($row->status == StatusEnum::ACTIVE) {

                $row->status = StatusEnum::INACTIVE;
            } elseif ($row->status == StatusEnum::INACTIVE) {
                $row->status = StatusEnum::ACTIVE;
            }
            $row->save();

            DB::commit();
            return $this->responseWithSuccess(__('updated_successfully'), []);
        } catch (\Throwable $th) {
            dd();
            DB::rollback();
            return $this->responseWithError($th->getMessage(), []);
        }
    }




    public function delete($id)
    {
        DB::beginTransaction();
        try {
            $payment = $this->get($id);

            $payment->delete();

            DB::commit();

            return true;
        }   catch (\Exception $e){
            DB::rollback();
            return false;
        }
    }
}
