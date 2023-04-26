<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 11:23 AM
 * @author SonDepTrai
 */

namespace Modules\Service\Repositories\Service;


interface ServiceRepoInterface
{
    /**
     * Lấy danh sách dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function getServices($input);

    /**
     * Danh sách dịch vụ đã sử dụng
     *
     * @param $input
     * @return mixed
     */
    public function getHistoryServices($input);

    /**
     * Danh sách dịch vụ theo chi nhánh chính
     *
     * @param $input
     * @return mixed
     */
    public function getServiceRepresentative($input);

    /**
     * Chi tiết dịch vụ
     *
     * @param $serviceId
     * @param $lang
     * @return mixed
     */
    public function getDetail($serviceId, $lang);

    /**
     * Lay thong tin chung (banner + dich vu noi bat + dich vu khuyen mai)
     *
     * @return mixed
     */
    public function getGeneralInfo();

    /**
     * Like / Unlike thích dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function likeUnlikeService($input);

    /**
     * Danh sách dịch vụ yêu thích
     *
     * @param $input
     * @return mixed
     */
    public function getListServiceLikes($input);
}