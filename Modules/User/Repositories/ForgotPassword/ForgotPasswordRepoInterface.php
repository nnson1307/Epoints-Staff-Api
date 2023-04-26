<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-28
 * Time: 2:36 PM
 * @author SonDepTrai
 */

namespace Modules\User\Repositories\ForgotPassword;


interface ForgotPasswordRepoInterface
{
    /**
     * Gửi mã otp
     *
     * @param $data
     * @return mixed
     */
    public function sendOTP($data);

    /**
     * Xác thực otp
     *
     * @param $data
     * @return mixed
     */
    public function verifyOTP($data);

    /**
     * Đổi mật khẩu mới
     *
     * @param $data
     * @return mixed
     */
    public function updatePassword($data);
}