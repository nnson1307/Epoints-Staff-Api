<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 9:39 AM
 * @author SonDepTrai
 */

namespace Modules\Product\Repositories\Product;


interface ProductRepoInterface
{
    /**
     * Danh sách lịch sử sản phẩm
     *
     * @param $input
     * @return mixed
     */
    public function getHistoryProducts($input);

    /**
     * Lấy danh sách tất cả sản phẩm
     *
     * @param $input
     * @return mixed
     */
    public function getAllProducts($input);

    /**
     * Lấy danh sách sản phẩm theo type
     *
     * @param $input
     * @return mixed
     */
    public function getProducts($input);

    /**
     * Lấy chi tiết sản phẩm
     *
     * @param $input
     * @param $lang
     * @return mixed
     */
    public function getProductDetail($input, $lang);

    /**
     * Lấy danh sách sản phẩm home page
     *
     * @return mixed
     */
    public function getProductHome();

    /**
     * Từ khóa hot
     *
     * @return mixed
     */
    public function hotKeyword();

    /**
     * Like hoặc unlike sản phẩm
     *
     * @param $input
     * @return mixed
     */
    public function likeUnlike($input);

    /**
     * Danh sách sản phẩm yêu thích
     *
     * @param $input
     * @return mixed
     */
    public function getListProductLike($input);

    /**
     * Lay thong tin chung (banner + san pham noi bat + san pham khuyen mai)
     *
     * @return mixed
     */
    public function getGeneralInfo();

    /**
     * Un active sp chưa có image
     *
     * @return mixed
     */
    public function unActiveProduct();

    /**
     * Scan sản phẩm
     *
     * @param $input
     * @param $lang
     * @return mixed
     */
    public function scanProduct($input, $lang);
}