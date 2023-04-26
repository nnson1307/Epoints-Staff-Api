<?php

namespace App\Http\Middleware;

use App\Models\PiospaBrandTable;
use Closure;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use function JmesPath\search;
use MyCore\Helper\OpensslCrypt;
use Modules\User\Models\ConfigTable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class SwitchDatabaseTenant
{
    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function handle($request, Closure $next)
    {
        $configDB = $this->configDB($request);

        if ($configDB != 200) {
            abort($configDB);
        }

        // $branchId = $request->header('branch-id');
        // if (!isset($request->all()["branch_id"]) && isset($branchId)) {
        //     $request->merge(["branch_id" => $branchId]);
        // }


        return $next($request);
    }

    /**
     * Cấu hình database
     *
     * @param $request
     * @return int
     * @throws \MyCore\Api\ApiException
     */
    protected function configDB($request)
    {
        // get brand-code tu header
        $brandCode = $request->header('brand-code');

        if ($request->header('client-key') && $brandCode != null) {
            //Lấy ds brand bằng client key
            $getBrand = $this->getBrandByClient([
                'client_key' => $request->header('client-key')
            ]);

            if (count($getBrand) > 0) {
                $brandCode = $getBrand[0]['brand_code'];
            }
        }

        if (isset($request->brand_code)) {
            $brandCode = $request->brand_code;
        }
        // add brand_code de code cu van chay duoc
        // khi nao xoa het code cu thi co the xoa dong nay
        $request->request->add([
            'brand_code' => $brandCode
        ]);

        if (isset($request->brand_code)) {
            //Lấy brand code
            $brandCode = $request->brand_code;

            $domain = $brandCode . env('DOMAIN_PIOSPA');

            $arrConfigConnStr = config('epoint-connstr', []);

            // Kiểm tra không tìm thấy cấu hình của tenant thì trả về lỗi 404
            if (empty($arrConfigConnStr[$domain])) {
                return 404;
            }

            $conStr = $arrConfigConnStr[$domain];

            // Kiểm tra connect string không đủ thông tin bắt buộc thì trả về lỗi 404
            $arrParams = $this->parseConnStr($conStr);

            if (
                empty($arrParams['server'])
                || empty($arrParams['database'])
                || empty($arrParams['user'])
            ) {
                return 404;
            }
            //Switch db
            $this->switchDB($arrParams);

            return 200;
        }

        return 200;
    }


    /**
     * Parse connect string to array
     *
     * @param $str
     * @return array
     */
    protected function parseConnStr($str)
    {
        $arrPart = explode(';', $str);
        $arrParams = [];
        foreach ($arrPart as $item) {
            list($key, $val) = explode('=', $item, 2);
            $key = strtolower($key);

            $arrParams[$key] = $val;
        }

        return $arrParams;
    }

    /**
     * Switch db
     *
     * @param $arrParams
     * @return int
     */
    public function switchDB($arrParams)
    {

        $idTenant = $arrParams['tenant_id'];

        session(['idTenant' => $idTenant]);

        session(['brand_code' => $arrParams['brand_code']]);
        // Thiết lập cấu hình database
        config([
            'database.connections.mysql' => [
                'driver' => 'mysql',
                'host' => $arrParams['server'],
                'port' => $arrParams['port'] ?? 3306,
                'database' => $arrParams['database'],
                'username' => $arrParams['user'],
                'password' => $arrParams['password'] ?? '',
                'unix_socket' => env('DB_SOCKET', ''),
                'charset' => env('DB_CHARSET', 'utf8mb4'),
                'collation' => env('DB_COLLATION', 'utf8mb4_unicode_ci'),
                'prefix' => env('DB_PREFIX', ''),
                'strict' => env('DB_STRICT_MODE', false),
                'engine' => env('DB_ENGINE', null),
                'timezone' => env('DB_TIMEZONE', '+07:00'),
            ]
        ]);
        \DB::purge('mysql'); // Clear cache config. See: https://stackoverflow.com/a/37705096
        $keyCache = 'config_' . $idTenant;
        $value = Cache::remember($keyCache, 360, function () {
            return ConfigTable::all();
        });
        $collectionDetail = collect($value->toArray());

        $oncall_key = $collectionDetail->where('key', 'oncall_key')->first()['value'];
        $oncall_secret = $collectionDetail->where('key', 'oncall_secret')->first()['value'];
        $timezone = $collectionDetail->where('key', 'timezone')->first()['value'];

        session(['key_service' => $oncall_key]);
        session(['secret_service' => $oncall_secret]);
        date_default_timezone_set($timezone);
        //        date_default_timezone_set("Asia/Ho_Chi_Minh");

        // session(['key_service' => DB::table('config')->select('value')->where('key', 'oncall_key')->first()->value]);
        // session(['secret_service' => DB::table('config')->select('value')->where('key', 'oncall_secret')->first()->value]);
        // date_default_timezone_set(DB::table('config')->select('value')->where('key', 'timezone')->first()->value);
    }

    /**
     * Lấy ds brand bằng client key
     *
     * @param array $filter
     * @return mixed
     * @throws \MyCore\Api\ApiException
     */
    public function getBrandByClient($filter = [])
    {
        $oClient = new Client([
            'base_uri' => PIOSPA_QUEUE_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
        ]);

        $rsp = $oClient->post('admin/brand/get-brand-by-client', [
            'json' => $filter
        ]);

        $result = json_decode($rsp->getBody(), true);

        if (($result['ErrorCode'] ?? 1) == 0) {
            return $result['Data'];
        } else {
            return $result;
        }
    }
}
