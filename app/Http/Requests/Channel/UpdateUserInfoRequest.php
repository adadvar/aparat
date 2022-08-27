<?php

namespace App\Http\Requests\Channel;

use App\Models\User;
use App\Rules\MobileRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateUserInfoRequest extends FormRequest
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
            'email' => 'required_without:mobile|email',
            'mobile' => ['required_without:email', new MobileRule],
        ];
    }
}
