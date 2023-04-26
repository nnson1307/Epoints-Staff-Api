<?php


namespace Modules\Brand\Http\Api;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Log;

class StaffApi
{
    /**
     * Đăng kí tài khoản chat mới
     *
     * @param array $array
     */
    public function registerCustomerLead($branch_code, array $array)
    {
        $oClient = $this->getHttpClient($branch_code);
        $rsp = $oClient->request('POST', 'customer-lead/add-brand-lead', [
            'json' => $array
        ]);
        if ($rsp->getStatusCode() !== 200) {
            $data = json_decode($rsp->getBody()->getContents(), true);
            $message = "";
            if(isset($data['username'])){
                $message = $data['username'];
            } if(isset($data['email'])){
                $message = $data['email'];
            }if(isset($data['firstName'])){
                $message = $data['firstName'];
            }if(isset($data['password'])){
                $message = $data['password'];
            }if(isset($data['lastName'])){
                $message = $data['lastName'];
            }
            throw new Exception(__($message));
        }

        $data = json_decode($rsp->getBody()->getContents(), true);
        return $data;
    }

    /**
     * Lấy client request api
     *
     * @return Client
     */
    protected function getHttpClient($branchCode)
    {
        return new Client([
            'base_uri'    => sprintf(REGISTER_BRAND_URL_DEFAULT, $branchCode),
            'http_errors' => false,
            'headers' => [
                'brand-code' => $branchCode
            ],
        ]);
    }
}
