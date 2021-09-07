<?php

namespace App\Http\Requests\Admin\TaskLabel;

use App\Http\Requests\CoreRequest;
use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;

class StoreRequest extends CoreRequest
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
            "label_name" => 'required',
            "site_address" => 'required',
            "site_phone" => 'required',
            "site_city" => 'required',
            "site_state" => 'required',
            "site_zip" => 'required',
            "site_timezone" => 'required',
            "site_country" => 'required',
            "site_pname" => 'required',
            "site_pphone" => 'required',
            "site_pemail" => 'required|email',
        ];
    }
}
