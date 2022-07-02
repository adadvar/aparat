<?php
namespace App\Services;

use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\ListPlaylistRequest;
use App\Http\Requests\Playlist\MyPlaylistRequest;
use App\Models\Playlist;

class PlaylistService extends BaseService {

    public static function getAll(ListPlaylistRequest $request){
        return Playlist::all();
    }

    public static function my(MyPlaylistRequest $request){
        return auth()->user()->playlists;
    }

    public static function create(CreatePlaylistRequest $request){
         $data = $request->validated();
         $user = auth()->user();
         $playlist = $user->playlists()->create($data);
         return response($playlist);
    }
}