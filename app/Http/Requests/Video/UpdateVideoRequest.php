<?php

namespace App\Http\Requests\Video;

use App\Rules\CategoryIdRule;
use App\Rules\OwnPlaylistIdRule;
use App\Rules\UploadedVideoBannerIdRule;
use App\Rules\UploadedVideoIdRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

class UpdateVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return Gate::allows('update', $this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'title' => 'string|max:255' ,
            'info' => 'nullable|string' ,
            'tags' => 'nullable|array' ,
            'tags.*' => 'exists:tags,id' ,
            'category' => ['nullable', new CategoryIdRule(CategoryIdRule::PUBLIC_CATEGORIES)] ,
            'channel_category' => ['nullable', new CategoryIdRule(CategoryIdRule::PRIVATE_CATEGORIES)] ,
            'enable_comments' => 'nullable|boolean',
            'banner' => ['nullable', new UploadedVideoBannerIdRule] ,
        ];
    }
}
