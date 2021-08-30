<?php

namespace App\Http\Requests\Admin\Client;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreState extends CoreRequest
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
            'names' => 'required',
            'slug' => 'required',
            'country_id' => 'required',
            'timezone' => 'required',
        ];
    }
}
