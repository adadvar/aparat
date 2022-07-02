<?php

namespace App\Jobs;

use App\Models\Video;
use FFMpeg\Filters\Video\CustomFilter;
use FFMpeg\Format\Video\X264;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use ProtoneMedia\LaravelFFMpeg\Support\FFMpeg;

class ConvertAndAddWaterMarkToUploadedVideoJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    private $video ;
    private $videoId ;
    private $userId ;
    private $addWatermark ;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Video $video,string  $videoId, bool $addWatermark)
    {
        $this->video = $video;
        $this->videoId = $videoId;
        $this->addWatermark = $addWatermark;
        $this->userId = auth()->id();
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $uploadedVideoPath = '/tmp/' . $this->videoId;
        $videoUploaded = FFMpeg::fromDisk('videos')->open($uploadedVideoPath);
        $format = new X264('libmp3lame');

        if($this->addWatermark){
            $filter = new CustomFilter("drawtext=text='http\\://webamooz.net': fontcolor=blue: fontsize=24:
                         box=1: boxcolor=white@0.5: boxborderw=5:
                         x=10: y=(h - text_h - 10)");
            $videoUploaded = $videoUploaded->addFilter($filter);

        }
        
        $videoFile = $videoUploaded->export()
        ->toDisk('videos')
        ->inFormat($format);
        
        $videoFile->save($this->userId . '/' . $this->video->slug . '.mp4');

        $this->video->duration = $videoUploaded->getDurationInSeconds();
        $this->video->state = Video::STATE_CONVERTED;
        $this->video->save();

        Storage::disk('videos')->delete($uploadedVideoPath);
    }
}
