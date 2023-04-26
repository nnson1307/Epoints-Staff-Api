<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 9:20 AM
 */

namespace Modules\Product\Repositories\ProductCategory;


use Modules\Product\Models\ProductCategoryTable;
use MyCore\Repository\PagingTrait;

class ProductCategoryRepo implements ProductCategoryRepoInterface
{
    use PagingTrait;
    protected $productCategory;

    public function __construct(
        ProductCategoryTable $productCategory
    ) {
        $this->productCategory = $productCategory;
    }

    /**
     * Danh sách loại dịch vụ
     *
     * @param $input
     * @return array|mixed
     * @throws ProductCategoryRepoException
     */
    public function getProductCategories($input)
    {
        try {
            $data = $this->toPagingData($this->productCategory->getServiceCategories($input));

            return $data;
        } catch (\Exception $exception) {
            throw new ProductCategoryRepoException(ProductCategoryRepoException::GET_PRODUCT_CATEGORY_LIST_FAILED);
        }
    }

    /**
     * Lấy option loại sản phẩm
     *
     * @return mixed
     * @throws ProductCategoryRepoException
     */
    public function getOption()
    {
        try {
            $data = $this->productCategory->getOption();

            return $data;
        } catch (\Exception $exception) {
            throw new ProductCategoryRepoException(ProductCategoryRepoException::GET_OPTION_PRODUCT_CATEGORY_FAILED);
        }
    }

    /**
     * Lấy thông tin loại sản phẩm ETL
     *
     * @param $input
     * @return mixed|void
     * @throws ProductCategoryRepoException
     */
    public function getProductCategoryETL($input)
    {
        try {
            $check = $this->productCategory->getCategoryByUuid($input['category_uuid']);

            if ($check == null) {
                //insert
                $productCategoryId = $this->productCategory->add([
                    'category_name' => $input['category_name'],
                    'category_uuid' => $input['category_uuid'],
                    'slug' => str_slug($input['category_name'])
                ]);
            } else {
                $productCategoryId = $check['product_category_id'];
            }

            return [
                'product_category_id' => $productCategoryId
            ];
        } catch (\Exception $exception) {
            throw new ProductCategoryRepoException(ProductCategoryRepoException::GET_PRODUCT_CATEGORY_ETL_FAILED);
        }
    }
}