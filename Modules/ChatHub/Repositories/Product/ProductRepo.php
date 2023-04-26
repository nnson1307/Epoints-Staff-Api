<?php

namespace Modules\ChatHub\Repositories\Product;

use Carbon\Carbon;
use Modules\ChatHub\Repositories\Product\ProductRepoInterface;
use Modules\ChatHub\Repositories\Product\ProductRepoException;
use Modules\Home\Repositories\Home\HomeRepoInterface;
use Modules\ChatHub\Models\ProductChildTable;
use Modules\ChatHub\Models\ProductImageTable;
use MyCore\Repository\PagingTrait;


class ProductRepo implements ProductRepoInterface
{
    use PagingTrait;
    protected $productChild;
    protected $productImage;

    public function __construct(
        ProductChildTable $productChild,
        ProductImageTable $productImage
    )
    {
        $this->productChild = $productChild;
        $this->productImage = $productImage;
    }

    /**
     * Lấy danh sách sản phẩm theo type
     *
     * @param $input
     * @return mixed
     * @throws ProductRepoException
     */
    public function getProducts($input)
    {
        try {
            $mHome = app()->get(HomeRepoInterface::class);

            $data = $this->toPagingData($this->productChild->getProducts($input));

            foreach ($data['Items'] as $item) {
                //Lấy avatar product child
                $imageChild = $this->productImage->getAvatar($item['product_code']);

                if ($imageChild != null) {
                    $item['avatar'] = $imageChild['image'];
                }

                //Check khuyến mãi
                $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], null, 'app', null, $item['product_id']);

                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                $promotion = [];
                if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                        // Tinh phan tram
                        if ($getPromotion['price'] < $item['new_price']) {
                            $percent = $getPromotion['price'] / $item['new_price'] * 100;
                            $promotion['price'] = (100 - round($percent, 2)) . '%';
                            // Tính lại giá khi có khuyến mãi
                            $item['old_price'] = floatval($item['new_price']);
                            $item['new_price'] = ($item['new_price'] * $percent) / 100;
                        }
                    }
                    if ($getPromotion['gift'] != null) {
                        $promotion['gift'] = $getPromotion['gift'];
                    }
                }

                if (empty($promotion)) {
                    $promotion = null;
                }

                $item['promotion'] = $promotion;
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ProductRepoException(ProductRepoException::GET_PRODUCT_TYPE_LIST_FAILED, $exception->getMessage());
        }
    }
}