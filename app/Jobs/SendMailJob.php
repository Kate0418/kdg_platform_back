<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Exception;
use App\Models\User;

class SendMailJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    protected $user;
    protected $mailClass;

    /**
     * Create a new job instance.
     *
     * @param \Illuminate\Database\Eloquent\Collection $users メールを送信するユーザーのコレクション
     * @param string $mailClass メールクラスの名前
     * @return void
     */
    public function __construct(User $user, $mailClass)
    {
        $this->user = $user;
        $this->mailClass = $mailClass;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        $user = $this->user;
        try {
            Mail::to($user->email)->send(new $this->mailClass($user));
            Log::debug($user->email);
        } catch (Exception $e) {
            Log::error($e);
        }
    }
}
