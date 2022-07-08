<?php
namespace App\Services;

use App\Http\Requests\Playlist\AddVideoToPlaylistRequest;
use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\ListPlaylistRequest;
use App\Http\Requests\Playlist\MyPlaylistRequest;
use App\Http\Requests\Playlist\ShowPlaylistRequest;
use App\Http\Requests\Playlist\SortVideosInPlaylistRequest;
use App\Models\Playlist;
use Illuminate\Support\Facades\DB;

class PlaylistService extends BaseService {

    public static function getAll(ListPlaylistRequest $request){
        return Playlist::all();
    }

    public static function my(MyPlaylistRequest $request){
        return auth()->user()->playlists;
    }

    public static function show(ShowPlaylistRequest $request){
        return Playlist::with('videos')
            ->find($request->playlist->id);
    }


    public static function create(CreatePlaylistRequest $request){
         $data = $request->validated();
         $user = auth()->user();
         $playlist = $user->playlists()->create($data);
         return response($playlist);
    }

    public static function addVideo(AddVideoToPlaylistRequest $request){
        DB::table('playlist_videos')
            ->where('video_id', $request->video->id)
            ->delete();

        $request->playlist
        ->videos()
        ->syncWithoutDetaching($request->video->id);

        return response(['message' => 'video added to playlist successfully!'], 200);
    }

    public static function sortVideos(SortVideosInPlaylistRequest $request){
        $request->playlist
        ->videos()
        ->detach($request->videos);

        $request->playlist
        ->videos()
        ->attach($request->videos);

        return response(['message' => 'sorted playlist successfully!'], 200);

    }
}