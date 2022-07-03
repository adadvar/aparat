<?php
namespace App\Services;

use App\Events\UploadNewVideo;
use App\Http\Requests\Video\ChangeStateVideoRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\likedByCurrentUserVideoRequest;
use App\Http\Requests\Video\LikeVideoRequest;
use App\Http\Requests\Video\listVideRequest;
use App\Http\Requests\Video\RepublishVideoRequest;
use App\Http\Requests\Video\UnLikeVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Models\Playlist;
use App\Models\User;
use App\Models\Video;
use App\Models\VideoFavourite;
use App\Models\VideoRepublish;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class   VideoService extends BaseService {

    public static function list(listVideRequest $request){
        $user = auth('api')->user();

        if ($request->has('republished')) {
            if ($user) {
                $videos = $request->republished ? $user->republishedVideos() : $user->channelVideos();
            } else {
                $videos = $request->republished ? Video::whereRepublished() : Video::whereNotRepublished();
            }
        } else {
            $videos = $user ? $user->videos() : Video::query();
        }

        $result = $videos
            ->orderBy('id')
            ->paginate(10);

        return $result;
    }

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
            

            DB::beginTransaction();
            
            $video = Video::create([
                'title' => $request->title,
                'user_id' => auth()->id(),
                'category_id' => $request->category,
                'channel_category_id' => $request->channel_category,
                'slug' => '',
                'info' => $request->info,
                'duration' => 0,
                'banner' => null,
                'enable_comments' => $request->enable_comments,
                'publish_at' => $request->publish_at,
                'state' => Video::STATE_PENDING,
            ]); 

            $video->slug = uniqueId($video->id);
            $video->banner = $video->slug . '-banner';;
            $video->save();

            event(new UploadNewVideo($video, $request));
            // Storage::disk('videos')->delete($uploadedVideoPath);
            // Storage::disk('videos')->move('/tmp/'. $request->video_id ,auth()->id() . '/' . $video->slug);

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

            return response($video, 200);

        }catch (Exception $e){
            DB::rollBack();

            Log::error($e);
            return response(['message' => 'An error has occurred !'], 500);
        }

    }

    public static function changeState(ChangeStateVideoRequest $request){
        $video = $request->video;
        $video->state = $request->state;
        $video->save();

        return response($video);
    }

    public static function republish(RepublishVideoRequest $request){
        try{
            $videoRepublish = VideoRepublish::create([
                'user_id' => auth()->id,
                'video_id' => $request->video->id,
            ]);
       
            return response(['message' => 'republish is successfully!'], 200);
        }catch(Exception $e){

            return response(['message' => 'republish is successfully!'], 500);
        }
    }

    public static function like(LikeVideoRequest $request){

           VideoFavourite::create([
            'user_id' =>  auth('api')->id(),
            'user_ip' => client_ip(),
            'video_id' =>  $request->video->id,
        ]); 
        
        return  response(['message' => 'Done successfully'], 200);

    }

    public static function unlike(UnLikeVideoRequest $request){
        $user = auth('api')->user();
        $conditions = [
            'user_id' =>  $user ? $user->id : null,
            'video_id' => $request->video->id,
        ];

        if(empty($user)){
            $conditions['user_ip'] = client_ip();
        }
        VideoFavourite::where($conditions)->delete();
        
        return  response(['message' => 'Done successfully'], 200);

    }

    public static function likedByCurrentUser(likedByCurrentUserVideoRequest $request){
        dd('unlike');
        $user = $request->user();
        $videos = $user->favouriteVideos()
            ->paginate();
        return $videos;    
    }
}