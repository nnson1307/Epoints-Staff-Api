<?php

namespace App\Jobs;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;
use Modules\Order\Http\Api\ApiQueue;


class FunctionSendNotify implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Execute the job.
     *
     * @throws \MyCore\Api\ApiException
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function handle()
    {
        $input = $this->data;

        $mApiQueue = app()->get(ApiQueue::class);

        switch ($input['type']) {
            case 'notify_customer';
                //Gửi thông báo khách hàng
                $mApiQueue->functionSendNotify($input);
                break;
            case 'notify_staff';
                //Gửi thông báo nhân viên
                $mApiQueue->functionSendNotifyStaff($input);
                break;
            case 'email_customer';
                //Gửi email khách hàng
                $mApiQueue->functionSendNotify($input);
                break;
            case 'sms_customer';
                //Lưu log sms khách hàng
                $mApiQueue->functionSendNotify($input);
                break;
            case 'zns_customer';
                //Lưu log zns khách hàng
                $mApiQueue->functionSendNotify($input);
                break;
        }
    }
}
