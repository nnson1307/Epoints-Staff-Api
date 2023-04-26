<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Notification\Repositories\Notification\NotificationRepoInterface;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data = [];
    protected $notification;

    public function __construct(
        $data
    ) {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @param NotificationRepoInterface $notification
     */
    public function handle(
        NotificationRepoInterface $notification
    ) {
        //Send notification
        $notification->sendNotification($this->data);
    }
}
