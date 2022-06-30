<?php
namespace App\Services;

use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Models\Channel;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class VideoService extends BaseService {
    public static function upload(UploadVideoRequest $request){

        try {

           $video = $request->file('video');
           $fileName = time() . Str::random(10);
           Storage::disk('videos')->put('/tmp/' . $fileName, $video->get());
           
           return response([
               'video' =>$fileName
            ], 200);
        }catch (Exception $e){
            return response(['message' => 'An error has occurred !'], 500);
        }
    }

    public static function uploadBanner(UploadVideoBannerRequest $request){

        try {

           $banner = $request->file('banner');
           $fileName = time() . Str::random(10) . '-banner';
           Storage::disk('videos')->put('/tmp/' . $fileName, $banner->get());
           
           return response([
               'banner' =>$fileName
            ], 200);
        }catch (Exception $e){
            return response(['message' => 'An error has occurred !'], 500);
        }
    }

    public static function create(CreateVideoRequest $request){
        try {
            $myvideo = FFMpeg::fromDisk('videos')->open('/tmp/' . $request->video_id);
            

            DB::beginTransaction();
            
            $video = Video::create([
                'title' => $request->title,
                'user_id' => auth()->id(),
                'category_id' => $request->category,
                'channel_category_id' => $request->channel_category,
                'slug' => '',
                'info' => $request->info,
                'duration' => $myvideo->getDurationInSeconds(),
                'banner' => null,
                'publish_at' => $request->publish_at,
            ]);

            $video->slug = uniqueId($video->id);
            $video->banner = $video->slug . '-banner';;
            $video->save();

            Storage::disk('videos')->move('/tmp/'. $request->video_id ,auth()->id() . '/' . $video->slug);

            if($request->banner){
                Storage::disk('videos')->move('/tmp/'. $request->banner ,auth()->id() . '/' . $video->banner);
            }

            if($request->playlist) {
                $playlist = Playlist::find($request->playlist);
                $playlist->videos()->attach($video->id);
            }

            if($request->tags){
                $video->tags()->attach($request->tags);
            }

            DB::commit();

            return response([
                'data' => $video], 200);

        }catch (Exception $e){
            DB::rollBack();

            Log::error($e);
            return response(['message' => 'An error has occurred !'], 500);
        }

    }

}