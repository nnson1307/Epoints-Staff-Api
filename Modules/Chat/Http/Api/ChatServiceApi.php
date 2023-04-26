<?php


namespace Modules\Chat\Http\Api;


use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7;
use Illuminate\Support\Facades\Log;

class ChatServiceApi
{
    /**
     * Đăng kí tài khoản chat mới
     *
     * @param array $array
     */
    public function register($branch_code, array $array)
    {
        $oClient = $this->getHttpClient($branch_code, null);
        $rsp = $oClient->request('POST', 'api/register', [
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
    protected function getHttpClient($branchCode, $access_token)
    {
        Log::error(sprintf(DOMAIN_CHAT_EPOINTS, $branchCode));
        if(empty($access_token)){

            return new Client([
                'base_uri'    => sprintf(DOMAIN_CHAT_EPOINTS, $branchCode),
                'http_errors' => false,
                'headers' => [
                    'brand-code' => $branchCode
                ],
            ]);
        }
        return new Client([
            'base_uri'    => sprintf(DOMAIN_CHAT_EPOINTS, $branchCode),
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $access_token,
                'brand-code' => $branchCode
            ]
        ]);
    }

    /**
     * Đăng kí tài khoản chat mới
     *
     * @param array $array
     */
    public function updateProfile($branch_code, $access_token, array $array)
    {
        $oClient = $this->getHttpClient($branch_code, $access_token);
        $rsp = $oClient->request('POST', 'api/token/update-user-profile', [
            'json' => $array
        ]);
        $data = json_decode($rsp->getBody()->getContents(), true);


        if ($rsp->getStatusCode() !== 200) {

            $message = "";
            if(isset($data['email'])){
                $message = $data['email'];
            }
            throw new Exception(__($message));
        }

        $data = json_decode($rsp->getBody()->getContents(), true);
        return $data;
    }

    /**
     * Cập nhật mật khẩu
     *
     * @param $access_token
     * @param $password
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function changePassword($branch_code, $access_token, $password)
    {
        $oClient = $this->getHttpClient($branch_code, $access_token);
        $rsp = $oClient->request('POST', 'api/users/change-password', [
            'json' => [
                "password" => $password
            ]
        ]);
        if ($rsp->getStatusCode() !== 200) {
            $data = json_decode($rsp->getBody()->getContents(), true);
            $message = "";
            if(isset($data['password'])){
                $message = $data['password'];
            }
            throw new Exception(__($message));
        }
        $data = json_decode($rsp->getBody()->getContents(), true);
        return $data;
    }

    /**
     * Cập nhật avatar
     * @param $chat_token
     * @param $password
     */
    public function changeAvatar($branch_code, $access_token, $imageId)
    {
        $oClient = $this->getHttpClient($branch_code, $access_token);
        $rsp = $oClient->request('POST', 'api/picture/change', [
            'json' => [
                "imageID" => $imageId
            ]
        ]);
        if ($rsp->getStatusCode() !== 200) {
            $data = json_decode($rsp->getBody()->getContents(), true);
            $message = __('Upload file thất bại.');
            if(isset($data['error']) && $data['error'] == true){
                $message = __('Upload file thất bại.');
            }
            throw new Exception(__($message));
        }
        $data = json_decode($rsp->getBody()->getContents(), true);
        return $data;
    }

    /**
     * Upload file
     * @param $chat_token
     * @param $password
     */
    public function uploadImage($branch_code, $access_token, $image)
    {
        $oClient = $this->getHttpClient($branch_code, $access_token);

        $rsp = $oClient->request('POST', 'api/upload', [
            'multipart' => [
                [
                    'name'     => 'image',
                    'contents' => Psr7\Utils::tryFopen($image->getRealPath(), 'r'),
                    'filename' => $image->getClientOriginalName()
                ]
            ]
        ]);
        if ($rsp->getStatusCode() !== 200) {
            $data = json_decode($rsp->getBody()->getContents(), true);
            $message = __('Upload file thất bại.');
            throw new Exception(__($message));
        }
        $data = json_decode($rsp->getBody()->getContents(), true);
        return $data['image'];
    }

    public function removeUser($branch_code, $access_token, $staffId)
    {
        $oClient = $this->getHttpClient($branch_code, $access_token);

        $rsp = $oClient->request('POST', 'api/token/remove-user', [
            'json' => [
                "staffId" => $staffId,
            ]
        ]);
        $data = json_decode($rsp->getBody()->getContents(), true);
        if ($rsp->getStatusCode() !== 200) {

            $message = __('Xóa tài khoản thất bại.');
            if(isset($data['error']) && $data['error'] == true){
                $message = __('Xóa tài khoản thất bại.');
            }
            throw new Exception(__($message));
        }

        return $data;
    }

    public function profile($branch_code, $access_token)
    {
        $oClient = $this->getHttpClient($branch_code, $access_token);
        $rsp = $oClient->request('POST', 'api/token/user-profile', [
            'json' => [
                "token" => $access_token
            ]
        ]);
        if ($rsp->getStatusCode() !== 200) {
            $data = json_decode($rsp->getBody()->getContents(), true);
            $message = __('Lấy thông tin hồ sơ thất bại.');
            if(isset($data['error']) && $data['error'] == true){
                $message = __('Lấy thông tin hồ sơ thất bạii.');
            }
            throw new Exception(__($message));
        }
        $data = json_decode($rsp->getBody()->getContents(), true);
        return $data['data'];
    }

    public function profileWeb($branch_code, $access_token)
    {
        $oClient = $this->getHttpClient($branch_code, $access_token);
        $rsp = $oClient->request('POST', 'api/token/user-profile');

        $data = json_decode($rsp->getBody()->getContents(), true);

        if ($rsp->getStatusCode() !== 200) {

            $message = __('Lấy thông tin hồ sơ thất bại.');
            if(isset($data['error']) && $data['error'] == true){
                $message = __('Lấy thông tin hồ sơ thất bạii.');
            }
            throw new Exception(__($message));
        }
        return $data['data'];
    }
}
