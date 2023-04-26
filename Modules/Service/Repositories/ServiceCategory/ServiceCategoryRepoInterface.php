<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:20 PM
 */

namespace Modules\Service\Repositories\ServiceCategory;


interface ServiceCategoryRepoInterface
{
    /**
     * Danh sách loại dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function getServiceCategories($input);

    /**
     * Lấy option loại dịch vụ
     *
     * @return mixed
     */
    public function getOption();
}