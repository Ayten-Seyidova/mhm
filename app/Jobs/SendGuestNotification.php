<?php

namespace App\Jobs;

use App\Models\Guest;
use App\Models\GuestNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendGuestNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 1;

    public $title;
    public $desc;
    public $subdirectionIds;

    public function __construct($title, $desc, $subdirectionIds = [])
    {
        $this->title = $title;
        $this->desc = $desc;
        $this->subdirectionIds = $subdirectionIds;
    }

    public function handle()
    {
        if (empty($this->subdirectionIds)) {
            return;
        }

        GuestNotification::create([
            'title' => $this->title,
            'description' => $this->desc,
            'all' => true
        ]);

        Guest::whereIn('sub_direction_id', $this->subdirectionIds)
            ->with('parameters:id,guest_id,token')
            ->select('id', 'sub_direction_id')
            ->chunkById(200, function ($guests) {
                $tokens = [];

                foreach ($guests as $guest) {
                    $token = $guest->parameters->token ?? null;

                    if (!empty($token)) {
                        $tokens[] = $token;
                    }
                }

                if (!empty($tokens)) {
                    SendGuestNotificationChunk::dispatch(
                        $this->title,
                        $this->desc,
                        $tokens
                    )->onQueue('notifications');
                }
            });
    }
}
