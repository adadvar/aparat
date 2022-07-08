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

class ShowStatisticsVideoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {

        return Gate::allows('showStatistics', $this->video);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'last_n_days' =>'nullable|in:7,14,30,90'
        ];
    }
}
