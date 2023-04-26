<?php
namespace MyCore\Api;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

/**
 * Created by PhpStorm.
 * User: daidp
 * Date: 11/15/2018
 * Time: 11:08 AM
 */
abstract class ApiAbstract
{
    protected static $instance;
//    protected static $baseUrlApi = BASE_URL_API;


    /**
     * Chỉ cho phép khởi tạo từ chính nó
     */
//    protected function __construct()
//    {
//    }

    /**
     * Singleton
     *
     * @return MyCore\Api\ApiAbstract
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new static();
        }

        return self::$instance;
    }

    /**
     * @return Client
     */
    protected function getClient()
    {
        $client = new Client([
            'base_uri'    => self::$baseUrlApi,
            'http_errors' => false // Do not throw GuzzleHttp exception when status error
        ]);

        return $client;
    }

    /**
     * Hàm cơ bản xử lý gọi api và trã kết quả
     * @param $url
     * @param $params
     * @return mixed
     * @throws ApiException
     */
    public function baseClient($url, $params, $stripTags = true)
    {
        try
        {
            if($stripTags) $params = $this->stripTagData($params);

            $oClient = $this->getClient();

            $rsp = $oClient->post($url, [
                'json' => $params,
            ]);

            $result = json_decode($rsp->getBody(), true);

            if (($result['ErrorCode'] ?? 1) == 0) {
                return $result['Data'];
            }
//            Log::error('PIO ERR | Connection Error By Api: '.$url);
//            Log::error('PIO ERR | Connection Content: ErrorCode = '.$result['ErrorCode']);

            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
        catch (\Exception $ex)
        {
//            Log::error('PIO ERR | Connection Error By Api: '.$url);
//            Log::error('PIO ERR | Connection Content: '.$ex->getMessage());
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }


    /**
     * hỗ trợ striptag data
     * @param $arrData
     * @return array
     */
    public function stripTagData($arrData)
    {
        $arrResult = [];
        foreach ($arrData as $key => $item)
        {
            $arrResult[$key] = strip_tags($item);
        }

        return $arrResult;
    }

    protected function getClientPushNotification()
    {
        $client = new Client([
            'base_uri'    => NAE_SERVICE_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
        ]);

        return $client;
    }

    /**
     * Hàm cơ bản xử lý gọi api và trã kết quả (Queue)
     *
     * @param $url
     * @param $params
     * @param bool $stripTags
     * @return mixed
     * @throws ApiException
     */
    protected function baseClientPushNotification($url, $params, $stripTags = true)
    {
        try
        {
            if ($stripTags) $params = $this->stripTagData($params);

//            Log::info('API:'.$url);
//            Log::info('Input:'.json_encode($params));

            $oClient = $this->getClientPushNotification();

            $rsp = $oClient->post($url, [
                'json' => $params
            ]);

//            Log::info('Output:'.$rsp->getBody());

            $result = json_decode($rsp->getBody(), true);

            if (($result['ErrorCode'] ?? 1) == 0) {
                return $result['Data'];
            } else {
                return $result;
            }
        }
        catch (\Exception $ex)
        {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }

    protected function getClientStaffPushNotification()
    {
        $client = new Client([
            'base_uri'    => STAFF_QUEUE_URL,
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
        ]);

        return $client;
    }

    protected function baseClientStaffPushNotification($url, $params, $stripTags = true)
    {
        try
        {
            if ($stripTags) $params = $this->stripTagData($params);

            // create a log channel
            $oClient = $this->getClientStaffPushNotification();

            $rsp = $oClient->post($url, [
                'json' => $params
            ]);

            $result = json_decode($rsp->getBody(), true);

            if (($result['ErrorCode'] ?? 1) == 0) {
                return $result['Data'];
            } else {
                return $result;
            }
        }
        catch (\Exception $ex)
        {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }

    /**
     *
     *
     * @return Client
     */
    protected function getClientShareService()
    {
        $jwt = session('authen_token');

        $client = new Client([
            'base_uri' => env('BASE_URL_SHARE_SERVICE'),
            'http_errors' => false, // Do not throw GuzzleHttp exception when status error
            'headers' => [
                'Authorization' => 'Bearer ' . $jwt,
                'tenant' => session('brand_code'),
                'key' => session('key_service'),
                'secret' => session('secret_service')
            ]
        ]);

        return $client;
    }

    /**
     * Hàm cơ bản xử lý gọi api và trã kết quả
     *
     * @param $url
     * @param $params
     * @param bool $stripTags
     * @return mixed
     * @throws ApiException
     */
    protected function baseClientShareService($url, $params, $stripTags = true)
    {
        try {
            if ($stripTags) $params = $this->stripTagData($params);

            $oClient = $this->getClientShareService();

            $rsp = $oClient->post($url, [
                'json' => $params
            ]);

            return json_decode($rsp->getBody(), true);
        } catch (\Exception $ex) {
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            throw new ApiException('Đã có lỗi, vui lòng thử lại sau');
        }
    }
}