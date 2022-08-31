<?php
namespace App\Services;

use App\Events\DeleteVideo;
use App\Events\UploadNewVideo;
use App\Events\VisitVideo;
use App\Http\Requests\Video\CategorizedVideosRequest;
use App\Http\Requests\Video\ChangeStateVideoRequest;
use App\Http\Requests\Video\CreateVideoRequest;
use App\Http\Requests\Video\DeleteVideoRequest;
use App\Http\Requests\Video\FavouritesVideoListRequest;
use App\Http\Requests\Video\likedByCurrentUserVideoRequest;
use App\Http\Requests\Video\LikeVideoRequest;
use App\Http\Requests\Video\listVideRequest;
use App\Http\Requests\Video\RepublishVideoRequest;
use App\Http\Requests\Video\ShowStatisticsVideoRequest;
use App\Http\Requests\Video\ShowVideoCommentsRequest;
use App\Http\Requests\Video\ShowVideoRequest;
use App\Http\Requests\Video\UnLikeVideoRequest;
use App\Http\Requests\Video\UpdateVideoRequest;
use App\Http\Requests\Video\UploadVideoBannerRequest;
use App\Http\Requests\Video\UploadVideoRequest;
use App\Models\Category;
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

class VideoService extends BaseService {

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
            ->get();

        return $result;
    }

    public static function show(ShowVideoRequest $request){

        event(new VisitVideo($request->video));
        $videoData = $request->video->toArray(); 
        
        $conditions = [
            'video_id' => $request->video->id,
            'user_id' => auth('api')->check() ? auth('api')->id() : null,
        ];
        
        if (!auth('api')->check()){
            $conditions['user_ip'] = client_ip();
        }
        $videoData['liked'] = VideoFavourite::where($conditions)->count();
        $videoData['tags'] = $request->video->tags;


        $comments = $request->video->comments;
        $videoData['comments'] = sort_comments($comments);

        $videoData['related_videos'] = $request->video->related()->take(5)->get();

        $videoData['playlist'] = $request->video->playlist()->with('videos')->first();

        $user = $request->video->user;
        $videoData['channel'] = $user->channel->toArray();

        if($currentUser = $request->user('api')) {
            $videoData['channel']['is_followed'] = (bool)$currentUser->followings()->where('user_id2', $user->id)->count();
        } else {
            $videoData['channel']['is_followed'] = false;
        }

        $videoData['channel']['followers_count'] = $user->followers()->count();

        return $videoData;
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
            $video->banner = $request->banner ? $video->slug . '-banner' : null;
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
            VideoRepublish::create([
                'user_id' => auth()->id(),
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
        $user = $request->user();  
        $videos = $user->favouriteVideos()
            ->paginate();
        return $videos;    
    }

    public static function delete(DeleteVideoRequest $request)
    {
        try {
            DB::beginTransaction();  
            if($request->video->user_id === $request->user()->id){

                $request->video->forceDelete();
                event(new DeleteVideo($request->video));
            }else {
                $request->video
                ->republishes()
                ->where('user_id', $request->user()->id)
                ->delete();
            }
            DB::commit();
            return response(['message' => 'حذف با موفقیت انجام شد'], 200);
        } catch (Exception $exception) {
            DB::rollBack();
            Log::error($exception);
            return response(['message' => 'حذف انجام نشد'], 500);
        }

    }

    public static function statistics(ShowStatisticsVideoRequest $request){

        $fromDate = now()->subDays($request->get('last_n_days', 7 ))->toDateString();
        $data = [  
            'views' => [],
            'total_views' => 0,
        ];


        Video::views($request->user()->id)
            ->where('videos.id', $request->video->id)
            ->whereRaw("date(video_views.created_at ) >= '$fromDate'")
            ->selectRaw('date(video_views.created_at ) as date, count(*) as views ')
            ->groupBy(DB::raw('date(video_views.created_at )'))
            ->get()
            ->each(function($item) use(&$data){
            $data['total_views'] += $item->views ;
            $data['views'][$item->date] = $item->views;
        });
        return $data;
    }

    public static function update(UpdateVideoRequest $request){
        $video = $request->video;

        try {
            
            DB::beginTransaction();
            
            if ($request->has('title')) $video->title = $request->title;
            if ($request->has('info')) $video->info = $request->info;
            if ($request->has('category')) $video->category_id = $request->category;
            if ($request->has('chanel_category')) $video->chanel_category_id = $request->chanel_category;
            if ($request->has('enable_comments')) $video->enable_comments = $request->enable_comments;

            
            if($request->banner){
              Storage::disk('videos')->delete(auth()->id() . '/' . $video->banner);
              Storage::disk('videos')->move('/tmp/'. $request->banner ,auth()->id() . '/' . $video->banner);
            }


            if(!empty($request->tags)){
                $video->tags()->sync($request->tags);
            }

            $video->save();

            DB::commit();

            return response($video, 200);

        }catch (Exception $e){
            DB::rollBack();

            Log::error($e);
            return response(['message' => 'An error has occurred !'], 500);
        }

    }

    public static function favourites(FavouritesVideoListRequest $request){
        $videos = $request->user()
            ->favouriteVideos()
            ->selectRaw('videos.* ,channels.name channel_name')
            ->leftJoin('channels', 'channels.user_id', '=', 'videos.user_id')
            ->get();

            return [
                'videos' => $videos,
                'total_fav_videos' => count($videos),
                'total_videos' => $request->user()->channelVideos()->count(),
                'total_comments' => Video::channelComments($request->user()->id)
                    ->selectRaw('comments.*')
                    ->count(),
                'total_views' => Video::views($request->user()->id)->count()
            ];

    }

    public static function categorizedVideos(CategorizedVideosRequest $request){
        
        $categories = Category::whereNull('user_id')
            ->whereHas('videos') 
            ->with(['videos' => function ($q) {
                $q->where('state', Video::STATE_ACCEPTED)
                ->orderBy('id', 'desc')
                ->take(5)
                ->with('user:id,name', 'user.channel:id,user_id,name');
            }])
            ->get();

        return response($categories);
    }
}