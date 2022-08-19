<?php
namespace App\Services;

use App\Http\Requests\Comment\ChangeCommentStateRequest;
use App\Http\Requests\Comment\CreateCommentRequest;
use App\Http\Requests\Comment\DeleteCommentRequest;
use App\Http\Requests\Comment\ListCommentRequest;
use App\Models\Comment;
use App\Models\Video;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CommentService extends BaseService {

  public static function index(ListCommentRequest $request)
  {
      $comments = Comment::channelComments($request->user()->id);

      if ($request->has('state')) {
          $comments = $comments->where('comments.state', $request->state);
      }

      return $comments
      ->with('user:id,avatar,name')
      ->orderBy('comments.id')
      ->get();
  }

    public static function create(CreateCommentRequest $request){
        $user = $request->user();
        $video = Video::find($request->video_id);
        $comment = $user->comments()->create([
            'video_id' => $request->video_id,
            'parent_id' => $request->parent_id,
            'body' => $request->body,
            'state' => $video->user_id == $user->id
            ? Comment::STATE_ACCEPTED
            : Comment::STATE_PENDING,
        ]);

        return $comment;
    }
 
    public static function changeState(ChangeCommentStateRequest $request){
        $comment = $request->comment;
        $comment->state = $request->state;
        $comment->save();

        return response(['message' => 'state changed successfully!']);  
    }

    public static function delete(DeleteCommentRequest $request){
        try{
            DB::beginTransaction();
            $request->comment->delete();
            DB::commit();
            return response(['message' => 'delete comment successfully!'], 200);  
        }catch(Exception $e){
            DB::rollBack();
            Log::error($e);
            return response(['message' => 'delete comment failed!'], 500);  
        }
    }
}