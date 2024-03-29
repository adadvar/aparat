<?php

namespace App\Rules;

use App\Models\Playlist;
use Illuminate\Contracts\Validation\Rule;

class OwnPlaylistIdRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        return Playlist::where(['id' => $value, 'user_id' => auth()->id()   ])->count();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid playlist id';
    }
}
