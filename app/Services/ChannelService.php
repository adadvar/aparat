<?php
namespace App\Services;

use App\Http\Requests\Channel\FollowChannelRequest;
use App\Http\Requests\Channel\InfoRequest;
use App\Http\Requests\Channel\StatisticsRequest;
use App\Http\Requests\Channel\UnFollowChannelRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UpdateUserInfoRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Models\Channel;
use App\Models\User;
use App\Models\Video;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ChannelService extends BaseService {
   public static function updateChannelInfo(UpdateChannelRequest $request){

    try{
        $channelId = $request->route('id');
        if($channelId){
            $channel = Channel::findOrFail($channelId);
            $user = $channel->user;
        } else {
            $user = auth()->user();
            $channel = $user->channel;
        }

        DB::beginTransaction();
        
        $channel->name = $request->name;
        $channel->info = $request->info;
        $channel->save();
    
        $user->website = $request->website;
        $user->save();

        DB::commit();
        return response(['message' => 'udate channel is done!'],200);
    }
    catch(Exception $e){
        DB::rollBack();
        Log::error($e);

        return  response(['message' => 'An error has occurred!'], 500);
    }

    
   }

    public static function uploadBannerForChannel(UploadBannerForChannelRequest $request){
       try {

           $banner = $request->file('banner');
           $fileName = md5(auth()->id()) . '-' . Str::random(15);
            Storage::disk('channel')->put($fileName, $banner->get());

           $channel = auth()->user()->channel;
           if($channel->banner){
               Storage::disk('channel')->delete($channel->banner);
           }
           $channel->banner = Storage::disk('channel')->url($fileName);
           $channel->save();    
           
           return response([
               'banner' => Storage::disk('channel')->url($fileName)
            ], 200);
        }catch (Exception $e){
            return response(['message' => 'An error has occurred !'], 500);
        }
    }

    public static function updateSocials(UpdateSocialsRequest $request){
        try {

            $socials = [
                'cloob' => $request->input('cloob'),
                'lenzor' => $request->input('lenzor'),
                'facebook' => $request->input('facebook'),
                'twitter' => $request->input('twitter'),
                'telegram' => $request->input('telegram'),
            ];
            
            auth()->user()->channel->update(['socials' => $socials]);

            return response([
                'message' => 'saveed successfully!'
            ], 200);

        }catch (Exception $e){
            return response(['message' => 'An error has occurred !'], 500);
        }

    }

    public static function statistics(StatisticsRequest $request) {

        $topVideos = $request->user()
            ->channelVideos()
            ->select([
                'videos.id', 'videos.slug', 'videos.title', 'videos.duration',
                 DB::raw('count(video_views.id) as views'),
            ])
            ->leftJoin('video_views', 'videos.id', 'video_views.video_id')
            ->groupBy('videos.id')   
            ->orderBy('views', 'desc')
            ->take(5)
            ->get();

        $fromDate = now()->subDays($request->get('last_n_days', 7))->toDateString();
        
        $data = [  
            'views' => [],
            'total_views' => 0,
            'top_videos' => $topVideos,
            'total_followers' => $request->user()->followers()->count(),
            'total_videos' => $request->user()->channelVideos()->count(),
            'total_comments' => Video::channelComments($request->user()->id)
            ->selectRaw('comments.*')
            ->count(),
        ];

        Video::views($request->user()->id)
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

    public static function info(InfoRequest $request) {
      $videos = $request->channel->user
        ->channelVideos()
        ->with(['playlist'])
        ->where('state', Video::STATE_ACCEPTED)
        ->get();
        
        $playlists = [];
        foreach($videos as $video) {
            if(count($video->playlist) > 0 ) {
                if(empty($playlists[$video->playlist[0]->id])) {
                    $playlists[$video->playlist[0]->id] = Arr::only($video->playlist[0]->toArray(), ['id', 'title', 'created_at']);
                    $playlists[$video->playlist[0]->id]['size'] = 1;
                    $playlists[$video->playlist[0]->id]['video'] = Arr::only($video->toArray(), ['id', 'slug', 'title', 'banner_link']);

                }else {
                    $playlists[$video->playlist[0]->id]['size'] ++;
                }
            }
        }

      return [
        'channel' => [
          'name' => $request->channel->name,
          'banner' => $request->channel->banner,
          'info' => $request->channel->info,
          'created_at' => $request->channel->created_at,
          'videos_count' => count($videos),
          'views_count' => $request->channel->user->views()->count(),
        ],
        'user' => [
          'avatar' => $request->channel->user->avatar,
          'playlists' => array_values($playlists),
        ],
        'videos' => $videos,
      ];

    }  

    
    public static function updateUserInfo(UpdateUserInfoRequest $request) {
        return $request->all();
      } 
    
}