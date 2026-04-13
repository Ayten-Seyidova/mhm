<?php

namespace App\Jobs;

use App\Http\Helpers\FirebaseHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendGuestNotificationChunk implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 120;
    public $tries = 1;

    public $title;
    public $desc;
    public $tokens;

    public function __construct($title, $desc, $tokens = [])
    {
        $this->title = $title;
        $this->desc = $desc;
        $this->tokens = $tokens;
    }

    public function handle()
    {
        if (empty($this->tokens)) {
            return;
        }

        FirebaseHelper::sendTokens($this->title, $this->desc, $this->tokens);
    }
}
