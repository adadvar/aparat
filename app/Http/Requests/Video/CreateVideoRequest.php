<?php

namespace App\Http\Requests\Video;

use App\Rules\CategoryIdRule;
use App\Rules\OwnPlaylistIdRule;
use App\Rules\UploadedVideoBannerIdRule;
use App\Rules\UploadedVideoIdRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateVideoRequest extends FormRequest
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
            'video_id' => ['required', new UploadedVideoIdRule] ,
            'title' => 'required|string|max:255' ,
            'category' => ['required', new CategoryIdRule(CategoryIdRule::PUBLIC_CATEGORIES)] ,
            'info' => 'nullable|string' ,
            'tags' => 'nullable|array' ,
            'tags.*' => 'exists:tags,id' ,
            'playlist' => ['nullable', new OwnPlaylistIdRule] ,
            'channel_category' => ['required', new CategoryIdRule(CategoryIdRule::PRIVATE_CATEGORIES)] ,
            'banner' => ['nullable', new UploadedVideoBannerIdRule] ,
            'publish_at' => 'nullable|date_format:Y-m-d H:i:s|after:now' ,
        ];
    }
}
