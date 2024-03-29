<?php

namespace App\Policies;

use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;
use Illuminate\Auth\Access\HandlesAuthorization;

class PlaylistPolicy
{
    use HandlesAuthorization;

    public function addVideo(User $user, Playlist $playlist, Video $video){
        return $user->id === $playlist->user_id && $user->id === $video->user_id;
    }

    public function sortVideos(User $user, Playlist $playlist){
        return $user->id === $playlist->user_id;
    }

    public function show(User $user, Playlist $playlist){
        return $user->id === $playlist->user_id;
    }
}
