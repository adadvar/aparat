<?php

namespace App\Http\Requests\Channel;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class UploadBannerForChannelRequest extends FormRequest
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
            'banner' => 'required|image|max:1024',
        ];
    }
}
