<?php

namespace App\Http\Controllers;

use App\Http\Requests\Channel\InfoRequest;
use App\Http\Requests\Channel\StatisticsRequest;
use App\Http\Requests\Channel\UpdateChannelRequest;
use App\Http\Requests\Channel\UpdateSocialsRequest;
use App\Http\Requests\Channel\UploadBannerForChannelRequest;
use App\Services\ChannelService;

class ChannelController extends Controller
{
    public function update(UpdateChannelRequest $request){
        return ChannelService::updateChannelInfo($request);
    }

    public function uploadBanner(UploadBannerForChannelRequest $request){
        return ChannelService::uploadBannerForChannel($request);
    }

    public function updateSocials(UpdateSocialsRequest $request){
        return ChannelService::updateSocials($request);
    } 

    public function statistics(StatisticsRequest $request){
        return ChannelService::statistics($request);
    }

    public function info(InfoRequest $request){
      return ChannelService::info($request);
  }
    
} 
 