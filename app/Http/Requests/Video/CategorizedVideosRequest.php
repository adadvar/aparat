<?php

namespace App\Http\Requests\Video;

use App\Models\Video;
use App\Rules\CanChangeVideoStateRule;
use App\Rules\CategoryIdRule;
use App\Rules\OwnPlaylistIdRule;
use App\Rules\UploadedVideoBannerIdRule;
use App\Rules\UploadedVideoIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class CategorizedVideosRequest extends FormRequest
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
        ];
    }
}
