<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-06
 * Time: 2:13 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Repositories\Address;


interface AddressRepoInterface
{
    /**
     * Lấy Option Tỉnh Thành của chi nhánh
     *
     * @return mixed
     */
    public function getProvinces();

    /**
     * Lấy Option Quận Huyện
     *
     * @param $provinceId
     * @return mixed
     */
    public function getDistricts($provinceId);

    /**
     * Lấy option tỉnh thành full
     *
     * @return mixed
     */
    public function getProvinceFull();

    /**
     * Lấy option phường xã
     *
     * @param $input
     * @return mixed
     */
    public function getWard($input);
}