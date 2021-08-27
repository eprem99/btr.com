<?php

namespace App\Http\Requests\Admin\Client;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreCountry extends CoreRequest
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
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required',
            'nicename' => 'required',
            'iso' => 'required',
            'iso3' => 'required',
            'phonecode' => 'required',
            'numcode' => 'required',
        ];
    }
}
