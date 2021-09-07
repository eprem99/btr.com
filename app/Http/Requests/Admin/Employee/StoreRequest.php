<?php

namespace App\Http\Requests\Admin\Employee;

use App\Http\Requests\CoreRequest;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
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
        $setting = global_setting();
        $rules = [
            "employee_id" => "required|unique:employee_details",
            "name" => "required",
            "email" => "required|email|unique:users",
            "password" => "required|min:6",
            'hourly_rate' => 'nullable|numeric',
            'last_date' => ['nullable', new CheckDateFormat(null,$setting->date_format), new CheckEqualAfterDate('joining_date',$setting->date_format)],
            'department' => 'required',
            'country' => 'required',
            'state' => 'required',
           // 'phone_code' => 'required_with:mobile',
        ];

        return $rules;
    }

}
