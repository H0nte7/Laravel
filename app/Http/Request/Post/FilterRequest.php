<?php

namespace App\Http\Request\Post;

use Illuminate\Foundation\Http\FormRequest;

class FilterRequest extends FormRequest
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
     *@return array
     */
    public function rules()
    {
        return [
          'region_id' => '',
          'city_id' => '',
          'home_type' => 'string',
          'rooms' => 'integer',
          'guest' => 'integer',

        ];
    }
}
