<?php
namespace App\Services;

use App\Http\Requests\Channel\FollowChannelRequest;
use App\Http\Requests\Channel\UnFollowChannelRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Models\Channel;
use App\Models\User;
use Exception;
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
           $channel->banner = Storage::disk('channel')->path($fileName);
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

    
}