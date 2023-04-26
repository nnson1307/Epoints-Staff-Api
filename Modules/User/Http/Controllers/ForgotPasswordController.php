<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-28
 * Time: 2:28 PM
 * @author SonDepTrai
 */

namespace Modules\User\Http\Controllers;


use Modules\User\Http\Requests\ForgotPassword\SendOtpRequest;
use Modules\User\Http\Requests\ForgotPassword\UpdatePasswordRequest;
use Modules\User\Http\Requests\ForgotPassword\VerifyOtpRequest;
use Modules\User\Repositories\ForgotPassword\ForgotPasswordRepoException;
use Modules\User\Repositories\ForgotPassword\ForgotPasswordRepoInterface;

class ForgotPasswordController extends Controller
{
    protected $forgot;

    public function __construct(
        ForgotPasswordRepoInterface $forgot
    ) {
        $this->forgot = $forgot;
    }

    /**
     * Gửi otp quên mật khẩu
     *
     * @param SendOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function sendOtpAction(SendOtpRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->forgot->sendOTP($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ForgotPasswordRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Xác thực otp
     *
     * @param VerifyOtpRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function verifyOtpAction(VerifyOtpRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->forgot->verifyOTP($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (ForgotPasswordRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Đổi mật khẩu mới
     *
     * @param UpdatePasswordRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePasswordAction(UpdatePasswordRequest $request)
    {
        $input = $request->all();

        try {
            $data = $this->forgot->updatePassword($input);

            return $this->responseJson(CODE_SUCCESS, null, $data);
        }
        catch (ForgotPasswordRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}