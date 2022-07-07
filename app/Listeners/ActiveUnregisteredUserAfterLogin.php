<?php

namespace App\Listeners;

use App\Events\ActiveUnregisteredUser;
use App\Models\User;
use Exception;
use Laravel\Passport\Events\AccessTokenCreated;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ActiveUnregisteredUserAfterLogin
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
     * @param  \App\Events\Laravel\Passport\Events\AccessTokenCreated  $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        $user = User::withTrashed()->find($event->userId);
        if ($user->trashed()) {
            try {
                DB::beginTransaction();
                $user->restore();
                event(new ActiveUnregisteredUser($user));
                Log::info('active unregisterd user', ['user_id' => $user->id]);
                DB::commit();
            } catch (Exception $exception) {
                Db::rollBack();
                Log::error($exception);
                throw $exception;
            }
        }
    }
}
