<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Video;
use App\Models\VideoFavourite;
use App\Models\VideoRepublish;
use Illuminate\Auth\Access\HandlesAuthorization;

class VideoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function changeState(User $user, Video $video = null){
        return $user->isAdmin();
   }

   public function republish(User $user, Video $video = null){
       return $video && $video->isAccepted() &&
       (
            $video->user_id != $user->id &&
            VideoRepublish::where([
                'user_id' => $user->id,
                'video_id' => $video->id
            ])->count() < 1
       );
   }

   public function like(User $user = null, Video $video = null){

        if($video && $video->isAccepted()){
            $conditions = [
                'user_id' =>  $user ? $user->id : null,
                'video_id' => $video->id,
            ];

            if(empty($user)){
                $conditions['user_ip'] = client_ip();
            }
            return VideoFavourite::where($conditions)->count() == 0;
        }

        return false;
   }

   public function unlike(User $user = null, Video $video = null){
        $conditions = [
                    'user_id' =>  $user ? $user->id : null,
                    'video_id' => $video->id,
                ];
        
        if(empty($user)){
            $conditions['user_ip'] = client_ip();
        }
        return VideoFavourite::where($conditions)->count();
 }

   public function seeLikedList(User $user, Video $video = null){
        return true;
   }
}
