<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Console;

use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Modules\Payment\Models\PaymentTransactionTable;
use Modules\Payment\Repositories\PaymentFactory;

class RePayment extends Command
{

    use DispatchesJobs;
    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'payment:re-payment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Thanh toán lại.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $mTransaction = new PaymentTransactionTable();
        $arrTransaction = $mTransaction->getTransactionFail();

        $oPayment = PaymentFactory::getInstance(PaymentFactory::EWAY);

        foreach ($arrTransaction as $item){
            $response = $oPayment->response(['AccessCode' => $item['AccessCode']]);
            $item->Retry = $item->Retry + 1;
            $item->save();
        }
    }
}
