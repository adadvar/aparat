<?php

namespace App\Listeners;

use App\Events\VisitVideo;
use App\Models\VideoView;
use Exception;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;

class AddVisitedVideoLogToVideoViewsTable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\VisitVideo  $event
     * @return void
     */
    public function handle(VisitVideo $event)
    {
        try{
            $video = $event->getVideo();
            $userId = auth('api')->id();
            $clietIp = client_ip();
            $condistions = [
                'user_id' => $userId,
                'video_id' => $video->id,
                ['created_at', '>', now()->subDays(1)],
            ];

            if(!auth('api')->check()){
                $condistions['user_ip'] = $clietIp;
            }

            if(!VideoView::where($condistions)->count()){
            VideoView::create([
                'user_id' => $userId,
                'video_id' => $video->id,
                'user_ip' => $clietIp,
            ]);
        }

        }catch(Exception $e){
            Log::error($e);
        }
    }
}
