<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-26
 * Time: 4:52 PM
 * @author SonDepTrai
 */

namespace Modules\User\Repositories\OTP;


interface OtpRepoInterface
{
    const TYPE_PHONE_VERIFY = 'otp_verify';
    const TYPE_PASS         = 'otp_pass';
    const TYPE_EMAIL_VERIFY = 'email_verify';

    const OTP_SUCCESS       = 'verify_ok';
    const OTP_ERR_EXPIRED   = 'verify_expired';
    const OTP_ERR_INVALID   = 'verify_invalid';

    /**
     * Gửi OTP
     *
     * @param $userId
     * @param $optType
     * @param $phone
     * @return mixed
     */

    public function sendOtp($userId, $phone, $optType);

    /**
     * Xác thực OTP
     *
     * @param $phone
     * @param $codeType
     * @param $code
     * @return mixed
     */
    public function verifyOtp($phone, $codeType, $code);
}