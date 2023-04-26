<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:20 PM
 */

namespace Modules\Support\Repositories\Support;


interface SupportRepoInterface
{
    /**
     * Danh sách loại dịch vụ
     *
     * @param $input
     * @return mixed
     */
    public function getListFaq();
}