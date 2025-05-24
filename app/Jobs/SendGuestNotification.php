<?php

namespace App\Jobs;

use App\Http\Helpers\FirebaseHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendGuestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title;
    public $desc;
    public $guestId;

    public function __construct($title, $desc, $guestId)
    {
        $this->title = $title;
        $this->desc = $desc;
        $this->guestId = $guestId;
    }

    public function handle()
    {
        FirebaseHelper::sendGuest($this->title, $this->desc, $this->guestId);
    }
}

