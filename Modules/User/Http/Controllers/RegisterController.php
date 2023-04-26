<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-26
 * Time: 10:28 AM
 * @author SonDepTrai
 */

namespace Modules\User\Http\Controllers;


use Illuminate\Http\Request;
use Modules\User\Http\Requests\Register\RegisterInfoRequest;
use Modules\User\Http\Requests\Register\RegisterVerifyOtpRequest;
use Modules\User\Repositories\User\UserRepoException;
use Modules\User\Repositories\User\UserRepoInterface;

class RegisterController extends Controller
{
    protected $user;

    public function __construct(UserRepoInterface $user)
    {
        $this->user = $user;
    }

    /**
     * Bước 1: Điền thông tin user
     *
     * @param RegisterInfoRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function infoAction(RegisterInfoRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->user->registerInfo($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (UserRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Bước 2: Xác thực OTP
     *
     * @param RegisterVerifyOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtpAction(RegisterVerifyOtpRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->user->registerVerifyOtp($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (UserRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Danh sách đầu số
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getServiceNumList()
    {
        try {
            $data = $this->user->getServiceNumList();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (UserRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

}