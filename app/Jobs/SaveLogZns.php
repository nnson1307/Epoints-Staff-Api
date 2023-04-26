<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\Order\Http\Api\ZnsApi;

class SaveLogZns implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $key;
    protected $customerId;
    protected $objectId;

    /**
     * Create a new job instance.
     *
     * SaveLogZns constructor.
     * @param $key
     * @param $customerId
     * @param $objectId
     */
    public function __construct(
        $key,
        $customerId,
        $objectId
    ) {
        $this->key = $key;
        $this->customerId = $customerId;
        $this->objectId = $objectId;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mZnsApi = app()->get(ZnsApi::class);
        //Lưu log ZNS
        $mZnsApi->saveLogTriggerEvent([
            "key" => $this->key,
            "user_id" => $this->customerId,
            "object_id" => $this->objectId
        ]);
    }
}
