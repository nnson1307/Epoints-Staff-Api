<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/14/2020
 * Time: 10:31 AM
 */

namespace Modules\Promotion\Repositories\Promotion;


interface PromotionRepoInterface
{
    /**
     * Danh sách CTKM
     *
     * @param $input
     * @return mixed
     */
    public function getLists($input);

    /**
     * Chi tiết CTKM
     *
     * @param $input
     * @return mixed
     */
    public function getDetail($input);
}