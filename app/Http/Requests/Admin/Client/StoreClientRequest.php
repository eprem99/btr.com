<?php

namespace App\Http\Requests\Admin\Client;

use App\Http\Requests\CoreRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends CoreRequest
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
        $rules = [
            "name"     => "required",
            "email"    => "required|email|unique:users",
            "password" => "required|min:6",
            'country'  => 'required',
            'state'    => 'required',
            'category_id'    => 'required',
//            'facebook' => 'nullable|regex:/http(s)?:\/\/(www\.)?(facebook|fb)\.com\/(A-z 0-9)?/',
//            'twitter' => 'nullable|reg  ex:/http(s)?://(.*\.)?twitter\.com\/[A-z 0-9 _]+\/?/',
//            'linkedin' => 'nullable|regex:/((http(s?)://)*([www])*\.|[linkedin])[linkedin/~\-]+\.[a-zA-Z0-9/~\-_,&=\?\.;]+[^\.,\s<]/',

        ];


        return $rules;
    }

    public function messages()
    {
        return [
            // 'website.url' => 'The website format is invalid. Add https:// or http to url'
        ];
    }
}
