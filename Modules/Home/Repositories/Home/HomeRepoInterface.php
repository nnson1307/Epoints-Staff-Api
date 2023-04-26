<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 8/7/2020
 * Time: 3:21 PM
 */

namespace Modules\Home\Repositories\Home;


interface HomeRepoInterface
{
    public function getHome($lang);

    public function getService();

    /**
     * Lấy thông tin khuyến mãi của sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @param $customerId
     * @param $orderSource
     * @param $quantity
     * @param $objectId
     * @param $date
     * @return mixed
     */
    public function getPromotionDetail($objectType, $objectCode, $customerId, $orderSource, $quantity = null, $objectId, $date = null);

    /**
     * Tìm kiếm tất cả home page
     *
     * @param $input
     * @param $lang
     * @return mixed
     */
    public function searchAll($input, $lang);
}