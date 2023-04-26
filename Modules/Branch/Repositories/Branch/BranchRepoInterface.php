<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\Branch\Repositories\Branch;


interface BranchRepoInterface
{
    /**
     * Lấy thông tin chi nhánh ETL
     *
     * @param $input
     * @return mixed
     */
    public function getBranchETL($input);

     /**
     * Lấy danh sách chi nhánh
     *
     * @return mixed|void
     * @throws ReportRevenueOrderRepoException
     */
    public function getBranch();
}