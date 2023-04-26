<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/14/2020
 * Time: 10:30 AM
 */

namespace Modules\Promotion\Repositories\Promotion;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\Home\Repositories\Home\HomeRepoInterface;
use Modules\Product\Models\CustomerTable;
use Modules\Product\Models\PromotionDailyTimeTable;
use Modules\Product\Models\PromotionDateTimeTable;
use Modules\Product\Models\PromotionMonthlyTimeTable;
use Modules\Product\Models\PromotionObjectApplyTable;
use Modules\Product\Models\PromotionWeeklyTimeTable;
use Modules\Promotion\Models\ProductFavouriteTable;
use Modules\Promotion\Models\ProductImageTable;
use Modules\Promotion\Models\ServiceFavouriteTable;
use Modules\Promotion\Models\ProductChildTable;
use Modules\Promotion\Models\PromotionDetailTable;
use Modules\Promotion\Models\PromotionMasterTable;
use Modules\Promotion\Models\ServiceBranchPriceTable;
use Modules\Promotion\Models\ServiceCardTable;
use MyCore\Repository\PagingTrait;

class PromotionRepo implements PromotionRepoInterface
{
    use PagingTrait;
    protected $promotion;

    public function __construct(
        PromotionMasterTable $promotion
    ) {
        $this->promotion = $promotion;
    }

    /**
     * Danh sách CTKM
     *
     * @param $input
     * @return mixed|void
     * @throws PromotionRepoException
     */
    public function getLists($input)
    {
        try {
            return $this->toPagingData($this->promotion->getLists($input));

        } catch (\Exception $exception) {
            throw new PromotionRepoException(PromotionRepoException::GET_PROMOTION_LIST_FAILED);
        }
    }

    /**
     * Chi tiết CTKM
     *
     * @param $input
     * @return mixed|void
     * @throws PromotionRepoException
     */
    public function getDetail($input)
    {
        try {
            $data = $this->promotion->getDetail($input['promotion_code']);
            // lay chi tiet (product, service, service_card)
            $mPromotionDetail = new PromotionDetailTable();
            $mProductChild = new ProductChildTable();
            $mServiceBranchPrice = new ServiceBranchPriceTable();
            $mProductImage = new ProductImageTable();
            $mHome = app()->get(HomeRepoInterface::class);

            $mServiceCard = new ServiceCardTable();
            $detail = [
                'product' => [],
                'service' => [],
                'service_card' => [],
            ];
            $listDetail = $mPromotionDetail->getListByPromotionCode($data['promotion_code'])->toArray();
            if ($listDetail != null && count($listDetail) > 0) {
                foreach ($listDetail as $item) {
                    if ($item['object_type'] == 'product') {
                        $getChild = $mProductChild->getProductByCode($item['object_code']);
                        if ($getChild != null) {
                            // check image product child
                            $imageChild = $mProductImage->getImageChild($item['object_code']);
                            if ($imageChild != null) {
                                $getChild['avatar'] = $imageChild['image'];
                            }

                            // check san pham da like chua
                            $mProductFavourite = new ProductFavouriteTable();
                            $checkLike = $mProductFavourite->checkFavourite($getChild['product_id'], Auth()->id());
                            $getChild['is_like'] = $checkLike != null ?  1 : 0;

                            $getChild['old_price'] = null;
                            $getChild['new_price'] = floatval($getChild['new_price']);
                            // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                            // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                            $getPromotion = $mHome->getPromotionDetail('product', $getChild['product_code'], Auth()->id(), 'app', null, $getChild['product_id']);
                            $promotion = [];
                            if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['gift'] != null) {
                                if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                                    // Tinh phan tram
                                    if ($getPromotion['price'] < $getChild['new_price']) {
                                        $percent = $getPromotion['price'] / $getChild['new_price'] * 100;
                                        $promotion['price'] = (100 - round($percent, 2)) . '%';
                                        // Tính lại giá khi có khuyến mãi
                                        $getChild['old_price'] = $getChild['new_price'];
                                        $getChild['new_price'] = floatval($getPromotion['price']);
                                    }
                                }
                                if ($getPromotion['gift'] != null) {
                                    $promotion['gift'] = $getPromotion['gift'];
                                }

                            }
                            if (empty($promotion)) {
                                $promotion = null;
                            }
                            $getChild['promotion'] = $promotion;

                            $detail['product'][] = $getChild;
                        }
                    } else if ($item['object_type'] == 'service') {
                        $getService = $mServiceBranchPrice->getDetail($item['object_code']);

                        if ($getService != null) {
                            if ($getService['service_avatar'] == null) {
                                $getService['service_avatar'] = 'http://' . request()->getHttpHost() . '/static/images/service.png';
                            }
                            // check dich vu da like chua
                            $mServiceFavourite = new ServiceFavouriteTable();
                            $checkLike = $mServiceFavourite->checkFavourite($item['object_code'], Auth()->id());
                            $getService['is_like'] = $checkLike != null ?  1 : 0;
                            //Lấy giá KM
                            $getService['old_price'] = null;
                            $getService['new_price'] = floatval($getService['new_price']);
                            // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                            // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                            $getPromotion = $mHome->getPromotionDetail('service', $getService['service_code'], Auth()->id(), 'app', null, $getService['service_id']);
                            $promotion = [];
                            if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['gift'] != null) {
                                if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                                    // Tinh phan tram
                                    if ($getPromotion['price'] < $getService['new_price']) {
                                        $percent = $getPromotion['price'] / $getService['new_price'] * 100;
                                        $promotion['price'] = (100 - round($percent, 2)) . '%';
                                        // Tính lại giá khi có khuyến mãi
                                        $getService['old_price'] = $getService['new_price'];
                                        $getService['new_price'] = floatval($getPromotion['price']);
                                    }
                                }
                                if ($getPromotion['gift'] != null) {
                                    $promotion['gift'] = $getPromotion['gift'];
                                }

                            }
                            if (empty($promotion)) {
                                $promotion = null;
                            }
                            $getService['promotion'] = $promotion;

                            $detail['service'][] = $getService;
                        }
                    } else if ($item['object_type'] == 'service_card') {
                        $getServiceCard = $mServiceCard->getDetail($item['object_code']);

                        if ($getServiceCard != null) {
                            if ($getServiceCard['image'] == null) {
                                $getServiceCard['image'] = 'http://' . request()->getHttpHost() . '/static/images/service-card.png';
                            }
                            //Lấy giá KM
                            $getServiceCard['old_price'] = null;
                            $getServiceCard['price'] = floatval($getServiceCard['price']);
                            // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                            // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                            $getPromotion = $mHome->getPromotionDetail('service_card', $getServiceCard['code'], Auth()->id(), 'app', null, $getServiceCard['service_card_id']);
                            $promotion = [];
                            if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['gift'] != null) {
                                if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                                    // Tinh phan tram
                                    if ($getPromotion['price'] < $getServiceCard['price']) {
                                        $percent = $getPromotion['price'] / $getServiceCard['price'] * 100;
                                        $promotion['price'] = (100 - round($percent, 2)) . '%';
                                        // Tính lại giá khi có khuyến mãi
                                        $getServiceCard['old_price'] = $getServiceCard['price'];
                                        $getServiceCard['price'] = floatval($getPromotion['price']);
                                    }
                                }
                                if ($getPromotion['gift'] != null) {
                                    $promotion['gift'] = $getPromotion['gift'];
                                }

                            }
                            if (empty($promotion)) {
                                $promotion = null;
                            }
                            $getServiceCard['promotion'] = $promotion;

                            $detail['service_card'][]  = $getServiceCard;
                        }
                    }
                }
            }

            $data['detail'] = $detail;

            return $data;
        } catch (\Exception $exception) {
            throw new PromotionRepoException(PromotionRepoException::GET_PROMOTION_DETAIL_FAILED, $exception->getMessage().$exception->getLine());
        }
    }
}