<?php
namespace App\Services;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Models\Channel;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class VideoService extends BaseService {
    public static function upload(UploadVideoRequest $request){

        try {

           $video = $request->file('video');
           $fileName = time() . Str::random(10);
           $path = public_path('videos/tmp');
           $video->move($path, $fileName);
           
           return response([
               'video' =>$fileName
            ], 200);
        }catch (Exception $e){
            return response(['message' => 'An error has occurred !'], 500);
        }
    }

    public static function create(CreateVideoRequest $request){
        dd($request->validated());
    }

}