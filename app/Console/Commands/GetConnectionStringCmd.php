<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Api\Service;
use MyCore\Helper\OpensslCrypt;
use MyCore\FileManager\Stub;
use Illuminate\Support\Facades\Artisan;

class GetConnectionStringCmd extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'epoint:connection_string';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Lấy connection string từ Azure cache về máy.';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $oApi = new Service();
        $arrBrand = $oApi->getAllBrand();

        if(!$arrBrand) return 'not ok';

        $oConstr = new OpensslCrypt(env('OP_SECRET'), env('OP_SALT'));
        $arrConnStr = [];
        foreach ($arrBrand as $brand){
            $domain = $brand['brand_code'].env('DOMAIN_PIOSPA','branddev.retailpro.io');
            $arrConnStr[ $domain ] = $oConstr->decode($brand['brand_contr']).';tenant_id='.$brand['tenant_id'].';brand_code='.$brand['brand_code'];
            if(isset($brand['brand_domain']) && $brand['brand_domain'] != ''){
                $arrConnStr[ $brand['brand_domain'] ] = $oConstr->decode($brand['brand_contr']).';tenant_id='.$brand['tenant_id'].';brand_code='.$brand['brand_code'];
            }
        }

        // Lay check sum
        $beforeMd5 = $this->getMd5();

        // Ghi ra file cấu hình và save vào config
        $oStub = new Stub(resource_path('stubs/epoint-connstr.stub'), [
            'CONN_STR' => var_export($arrConnStr, true)
        ]);
        $oStub->saveTo(config_path(), 'epoint-connstr.php');

        $afterMd5 = $this->getMd5();

        if ($beforeMd5 != $afterMd5) {
            $this->line('Restart Queue.');
            Artisan::call('queue:restart');
        }

        $this->line('ok');
    }

    /**
     * Lay check sum
     *
     * @return string
     */
    protected function getMd5()
    {
        $path = config_path('epoint-connstr.php');

        return md5_file($path);
    }
}
