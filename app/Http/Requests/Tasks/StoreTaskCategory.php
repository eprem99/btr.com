<?php

namespace App\Http\Requests\Tasks;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreTaskCategory extends CoreRequest
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
            'category_name' => 'required|unique:task_category',
            'category_phone' => 'required',
            'category_email' => 'required|email',
            'category_address' => 'required',
            'category_country' => 'required',
            'category_visibility' => 'unique:task_category'
        ];
    }
}
