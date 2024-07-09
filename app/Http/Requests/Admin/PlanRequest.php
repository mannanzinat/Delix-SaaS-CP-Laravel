<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }
    
    protected function prepareForValidation()
    {
        if ($this->is_free === null) {
            $this->merge(['is_free' => 0]);
        }

        if ($this->rider_app === null) {
            $this->merge(['rider_app' => 0]);
        }

        if ($this->merchant_app === null) {
            $this->merge(['merchant_app' => 0]);
        }
    }

    public function rules(): array

    {
        $planId = $this->route('plan');
        return [
            'name'                      => 'required|unique:plans,name,'.$planId,
            'description'               => 'required',
            'price'                     => 'required|numeric',
            'billing_period'            => 'required',
            'active_merchant'           => 'required|numeric',
            'monthly_parcel'            => 'required|numeric',
            'active_rider'              => 'required|numeric',
            'active_staff'              => 'required|numeric',
            'is_free'                   => 'nullable|boolean',
            'rider_app'                 => 'nullable|boolean',
            'merchant_app'              => 'nullable|boolean',


        ];
    }


}
