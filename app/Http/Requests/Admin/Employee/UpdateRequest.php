<?php

namespace App\Http\Requests\Admin\Employee;

use App\EmployeeDetails;
use App\Rules\CheckDateFormat;
use App\Rules\CheckEqualAfterDate;
use Froiden\LaravelInstaller\Request\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends CoreRequest
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
        $detailID = EmployeeDetails::where('user_id', $this->route('employee'))->first();
        $setting = global_setting();
        $rules = [
            'employee_id' => 'required|unique:employee_details,employee_id,'.$detailID->id,
            'email' => 'required|unique:users,email,'.$this->route('employee'),
            'name'  => 'required',
            'hourly_rate' => 'nullable|numeric',
            'department' => 'required',
            'last_date' => ['nullable', new CheckDateFormat(null,$setting->date_format), new CheckEqualAfterDate('joining_date',$setting->date_format)],
            'country' => 'required',
            'state' => 'required',
           // 'phone_code' => 'required_with:mobile',
        ];


        return $rules;
    }
}
