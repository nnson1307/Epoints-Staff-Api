<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-28
 * Time: 2:36 PM
 * @author SonDepTrai
 */

namespace Modules\User\Repositories\ForgotPassword;


use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\CustomerAccountTable;
use Modules\User\Models\UserTable;
use Modules\User\Repositories\OTP\OtpRepoException;
use Modules\User\Repositories\OTP\OtpRepoInterface;

class ForgotPasswordRepo implements ForgotPasswordRepoInterface
{
    protected $user;

    public function __construct(UserTable $user)
    {
        $this->user = $user;
    }

    /**
     * Gửi otp quên mật khẩu
     *
     * @param $data
     * @return mixed
     * @throws ForgotPasswordRepoException
     */
    public function sendOTP($data)
    {
        // Kiểm tra có user đk với sdt và chưa verify thì trả về khỏi cần tạo
        $mCustomerAccount = app()->get(CustomerAccountTable::class);

        $oUser = $mCustomerAccount->getUserByPhone($data['phone']);

        //nếu chưa có
        if (! $oUser || !$oUser->phone_verified) {
            throw new ForgotPasswordRepoException(__('Số điện thoại không tồn tại, vui lòng sử dụng số điện thoại khác!'));
        }

        // Gửi OTP Verify
        $this->sendVerifyOtp($oUser);

        return $data;
    }

    /**
     * send otp tới sô di động
     * @param $oUser
     * @throws \Modules\User\Repositories\ForgotPassword\ForgotPasswordRepoException
     */
    protected function sendVerifyOtp($oUser)
    {
        $rOtp = app(OtpRepoInterface::class);

        try {
            $rOtp->sendOtp($oUser['customer_id'], $oUser['phone'], 'forget_password');
        }
        catch (OtpRepoException $ex) {
            throw new ForgotPasswordRepoException($ex->getMessage());
        }
    }

    /**
     * Xác thực otp
     *
     * @param $data
     * @return array|mixed
     * @throws ForgotPasswordRepoException
     */
    public function verifyOTP($data)
    {
        // Check OTP Code
        $this->checkOTP(
            $data['phone'],
            $data['otp'],
            __('Mã kích hoạt không đúng. Vui lòng nhập lại'),
            __('Mã kích hoạt hết hiệu lực. Vui lòng yêu cầu gửi lại mã')
        );

        $mCustomerAccount = app()->get(CustomerAccountTable::class);
        // Kiểm tra có user
        $oUser = $mCustomerAccount->getUserByPhone($data['phone']);

        //nếu chưa có
        if (! $oUser) {
            throw new ForgotPasswordRepoException(__('Số điện thoại không tồn tại'));
        }

        // Lưu token vào db để xác thực khi thay đổi mật khẩu
        try {
            // Đăng nhập với user vừa verify
            $token = auth()->login($oUser);

            return [
                'access_token' => $token,
            ];
        } catch (QueryException $ex) {
            throw new ForgotPasswordRepoException($ex->getMessage());
        }
    }

    /**
     * Check mã OTP
     *
     * @param $phone
     * @param $code
     * @param $msgFail
     * @param $msgExpired
     * @throws ForgotPasswordRepoException
     */
    protected function checkOTP($phone, $code, $msgFail, $msgExpired)
    {
        $rOtp = app(OtpRepoInterface::class);

        try {
            $verifyResult = $rOtp->verifyOtp($phone, 'forget_password',$code);
        } catch (OtpRepoException $ex) {
            throw new ForgotPasswordRepoException($ex->getMessage());
        }

        // Kiểm tra mã OTP. Không đúng thì quăng exception thông báo lỗi.
        switch ($verifyResult) {
            case OtpRepoInterface::OTP_ERR_INVALID:
                throw new ForgotPasswordRepoException($msgFail);

            case OtpRepoInterface::OTP_ERR_EXPIRED:
                throw new ForgotPasswordRepoException($msgExpired);
        }
    }

    /**
     * Đổi mật khẩu mới
     *
     * @param $data
     * @return mixed|void
     */
    public function updatePassword($data)
    {
        unset($data['password_confirmation']);

        $mCustomerAccount = app()->get(CustomerAccountTable::class);
        //Lấy thông tin user
        $oUser = $mCustomerAccount->getUserByPhone($data['phone']);
        //Cập nhật mật khẩu mới
        $mCustomerAccount->edit($data, $oUser['customer_account_id']);
    }
}