<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 9:21 AM
 */

namespace Modules\Product\Repositories\ProductCategory;


interface ProductCategoryRepoInterface
{
    /**
     * Danh sách loại sản phẩm
     *
     * @param $input
     * @return mixed
     */
    public function getProductCategories($input);

    /**
     * Lấy option loại sản phẩm
     *
     * @return mixed
     */
    public function getOption();

    /**
     * Lấy thông tin loại sản phẩm ETL
     *
     * @param $input
     * @return mixed
     */
    public function getProductCategoryETL($input);
}