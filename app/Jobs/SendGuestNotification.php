<?php

namespace App\Jobs;

use App\Http\Helpers\FirebaseHelper;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class SendGuestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $title;
    public $desc;
    public $guestId;

//    public function __construct($title, $desc, $guestId)
//    {
//        $this->title = $title;
//        $this->desc = $desc;
//        $this->guestId = $guestId;
//    }
    public function __construct($title, $desc)
    {
        $this->title = $title;
        $this->desc = $desc;
    }

    public function handle()
    {
       // FirebaseHelper::sendGuest($this->title, $this->desc, $this->guestId);
        Log::info('send guest notification job called');
        FirebaseHelper::sendAll($this->title, $this->desc);
//        FirebaseHelper::testGuest($this->title, $this->desc);
    }
}

