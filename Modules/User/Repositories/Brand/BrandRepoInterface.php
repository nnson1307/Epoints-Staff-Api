<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 14:38
 */

namespace Modules\User\Repositories\Brand;


interface BrandRepoInterface
{
    /**
     * Lấy ds brand
     *
     * @param $input
     * @return mixed
     */
    public function getBrand($input);
}