<?php
namespace Modules\Chat\Repositories;


interface ChatInterface{


    /**
     * Đăng kí tài khoản mới
     *
     * @param array $all
     * @return mixed
     */
    public function register(array $all);

    public function updateProfile(array $all);



    public function changePassword(array $all);

    public function changeAvatar(array $all);

    /**
     * Xóa user chat
     *
     * @param array $all
     * @return mixed
     */
    public function removeUser(array $all);

    /**
     * Lấy thông tin hồ sơ
     *
     * @param $input
     * @return mixed
     */
    public function getProfile($input);

    /**
     * Lấy thông tin hồ sơ
     *
     * @param $input
     * @return mixed
     */
    public function getProfileWeb($input);

    /**
     * Lấy DS nhân viên có quyền chat
     *
     * @param $input
     * @return mixed
     */
    public function getStaffChat($input);
}
