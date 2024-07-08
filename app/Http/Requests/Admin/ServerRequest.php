<?php

namespace App\Http\Requests\Admin;

use App\Models\Country;
use Illuminate\Foundation\Http\FormRequest;

class ServerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'provider'               => 'required',
            'ip'                     => 'required',
            'user_name'              => 'required',
            'password'               => 'required',
        ];
    }

}
