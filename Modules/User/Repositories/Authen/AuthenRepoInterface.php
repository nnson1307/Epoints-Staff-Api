<?php

namespace Modules\User\Repositories\Authen;

/**
 * Interface AuthenRepoInterface
 * @package Modules\User\Repositories\Authen
 * @author DaiDP
 * @since Aug, 2019
 */
interface AuthenRepoInterface
{
    /**
     * Login app
     *
     * @param $data
     * @return mixed
     */
    public function login($data);

    /**
     * Login app
     *
     * @param $data
     * @return mixed
     */
    public function loginV2($data);

    /**
     * refresh token access to app
     *
     * @param $data
     * @return mixed
     */
    public function refreshToken($data);

    /**
     * Logout app
     *
     * @param $input
     * @return mixed
     */
    public function logout($input);


    /**
     * Login Fb, GG, Zalo, AppleId
     *
     * @param $data
     * @return mixed
     */
    public function loginService($data);

    /**
     * Lấy list quyền của app
     *
     * @return mixed
     */
    public function getPermission();

    /**
     * Đăng nhập nhanh
     *
     * @param $input
     * @return mixed
     */
    public function quickLogin($input);

    /**
     * Đăng ký device token khi login từ portal để sử dụng push notify
     *
     * @param $input
     * @return mixed
     */
    public function registerDeviceTokenPortal($input);

    /**
     * Cập nhập avatar user
     *
     * @param array $all
     * @return mixed
     */
    public function uploadAvatar(array $all);

    /**
     * Upload file lên thư viện
     *
     * @param $input
     * @return mixed
     */
    public function uploadFile($input);

    /**
     * Login app
     *
     * @param $data
     * @return \Illuminate\Contracts\Auth\Authenticatable|mixed|null
     * @throws AuthenRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function getInfoPerMission();

    /**
     * Xóa user theo policy của apple
     * @param array $all
     * @return mixed
     */
    public function delete(array $all);
}
