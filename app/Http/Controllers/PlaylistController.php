<?php

namespace App\Http\Controllers;

use App\Http\Requests\Playlist\AddVideoToPlaylistRequest;
use App\Http\Requests\Playlist\CreatePlaylistRequest;
use App\Http\Requests\Playlist\ListPlaylistRequest;
use App\Http\Requests\Playlist\MyPlaylistRequest;
use App\Http\Requests\Playlist\ShowPlaylistRequest;
use App\Http\Requests\Playlist\SortVideosInPlaylistRequest;
use App\Services\PlaylistService;

class PlaylistController extends Controller
{
    public function index(ListPlaylistRequest $request){
        return PlaylistService::getAll( $request);
    }

    public function my(MyPlaylistRequest $request){
        return PlaylistService::my( $request);
    }

    public function show(ShowPlaylistRequest $request){
        return PlaylistService::show( $request);
    }

    public function create(CreatePlaylistRequest $request){
        return PlaylistService::create( $request);
    }

    public function addVideo(AddVideoToPlaylistRequest $request){
        return PlaylistService::addVideo( $request);
    }

    public function sortVideos(SortVideosInPlaylistRequest $request){
        return PlaylistService::sortVideos($request);
    }
}
