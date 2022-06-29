<?php

namespace App\Http\Requests\Channel;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UpdateSocialsRequest extends FormRequest
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
            'cloob' => 'nullable|url',
	        'lenzor' => 'nullable|url',
	        'facebook' => 'nullable|url',
        	'twitter' => 'nullable|url',
	        'telegram' => 'nullable|url',
        ];
    }
}
