<?php

namespace App\Rules;

use App\Models\Playlist;
use Illuminate\Contracts\Validation\Rule;

class SortablePlaylistVideosRule implements Rule
{

    private $playlist;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(Playlist $playlist)
    {
        $this->playlist = $playlist;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if(is_array($value)){
            $videos = $this->playlist->videos()->pluck('videos.id')->toArray();
            sort($videos);
            $value = array_map('intval', $value);
            sort($value);
            return $videos === $value;
        }
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'video list is not valid for this playlist.';
    }
}
