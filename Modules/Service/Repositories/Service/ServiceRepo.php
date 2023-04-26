<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 11:23 AM
 * @author SonDepTrai
 */

namespace Modules\Service\Repositories\Service;


//use Modules\Service\Models\OrderDetailTable;
use Carbon\Carbon;
use Illuminate\Database\QueryException;

use Modules\Home\Repositories\Home\HomeRepoInterface;
use Modules\Service\Models\CustomerPotentialLogTable;
use Modules\Service\Models\CustomerTable;
use Modules\Service\Models\PromotionDailyTimeTable;
use Modules\Service\Models\PromotionDateTimeTable;
use Modules\Service\Models\PromotionDetailTable;
use Modules\Service\Models\PromotionMonthlyTimeTable;
use Modules\Service\Models\PromotionObjectApplyTable;
use Modules\Service\Models\PromotionWeeklyTimeTable;


use Modules\Content\Models\NewTable;
use Modules\Product\Models\RatingLogTable;
use Modules\Service\Models\FeedbackQuestionTable;
use Modules\Service\Models\OrderDetailTable;
use Modules\Service\Models\ServiceBranchPriceTable;
use Modules\Service\Models\ServiceFavouriteTable;
use Modules\Service\Models\ServiceImageTable;
use MyCore\Repository\PagingTrait;


class ServiceRepo implements ServiceRepoInterface
{
    use PagingTrait;

    /**
     * Danh sách dịch vụ
     *
     * @param $input
     * @return array|mixed
     * @throws ServiceRepoException
     */
    public function getServices($input)
    {
        try {
            $mServiceBranch = app()->get(ServiceBranchPriceTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            //Lấy dịch vụ theo chi nhánh của NV login
            $input['branch_id'] = Auth()->user()->branch_id;

            $data = $this->toPagingData($mServiceBranch->getServices($input));

            $dataItem = $data['Items'];

            if (count($data) > 0) {
                unset($data['Items']);
            }

            foreach ($dataItem as $item) {
                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                // lay promotion (neu co)
                $getPromotion = $mHome->getPromotionDetail('service', $item['service_code'], null, 'app', null, $item['service_id']);
                $promotion = [];
                $item['is_new'] = 1;
                if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                        // Tinh phan tram
                        if ($getPromotion['price'] < $item['new_price']) {
                            $percent = $getPromotion['price'] / $item['new_price'] * 100;
                            $promotion['price'] = (100 - round($percent, 2)) . '%';
                            // Tính lại giá khi có khuyến mãi
                            $item['old_price'] = floatval($item['new_price']);
                            $item['new_price'] = ($item['new_price'] * $percent) / 100;
                            $item['is_new'] = 0;
                        }
                    }
                    if (isset($getPromotion['gift'])) {
                        $promotion['gift'] = $getPromotion['gift'];
                        $item['is_new'] = 0;
                    }
                }

                if (empty($promotion)) {
                    $promotion = null;
                }
                $item['promotion'] = $promotion;

                //Image null thì trả về image default
                if ($item['service_avatar'] == null) {
                    $item['service_avatar'] = 'http://' . request()->getHttpHost() . '/static/images/service.png';
                }
                $data['Items'] [] = $item;
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ServiceRepoException(ServiceRepoException::GET_SERVICE_LIST_FAILED);
        }
    }

    /**
     * Danh sách dịch vụ đã sử dụng
     *
     * @param $input
     * @return array|mixed
     * @throws ServiceRepoException
     */
    public function getHistoryServices($input)
    {
        try {
            $customerId = Auth()->id();
            $mOrderDetail = app()->get(OrderDetailTable::class);

            $data = $mOrderDetail->getDetails($input, $customerId, 'service');

            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new ServiceRepoException(ServiceRepoException::GET_SERVICE_LIST_FAILED);
        }
    }

    /**
     * Danh sách dịch vụ theo chi nhánh chính
     *
     * @param $input
     * @return array|mixed
     * @throws ServiceRepoException
     */
    public function getServiceRepresentative($input)
    {
        try {
            $mServiceBranch = app()->get(ServiceBranchPriceTable::class);
            $mServiceFavourite = app()->get(ServiceFavouriteTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            $data = $this->toPagingData($mServiceBranch->getServiceRepresentative($input));
            $dataItem = $data['Items'];
            if (count($data) > 0) {
                unset($data['Items']);
            }
            foreach ($dataItem as $item) {
                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                //Check dịch vụ đã thích chưa
                $getLike = $mServiceFavourite->checkFavourite($item['service_code'], Auth()->id());
                $item['is_like'] = $getLike != null ? 1 : 0;
                // lay promotion (neu co)
                $getPromotion = $mHome->getPromotionDetail('service', $item['service_code'], Auth()->id(), 'app', null, $item['service_id']);
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
                            $item['is_new'] = 0;
                        }
                    }
                    if ($getPromotion['gift'] != null) {
                        $promotion['gift'] = $getPromotion['gift'];
                        $item['is_new'] = 0;
                    }
                } else {
                    // service new
                    $item['old_price'] = null;
                    $item['is_new'] = 1;
                    $item['promotion'] = null;
                    $data['Items'][] = $item;
                    continue;
                }

                if (empty($promotion)) {
                    $promotion = null;
                }

                $item['promotion'] = $promotion;

                //Image null thì trả về image default
                if ($item['service_avatar'] == null) {
                    $item['service_avatar'] = 'http://' . request()->getHttpHost() . '/static/images/service.png';
                }

                $data['Items'] [] = $item;
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ServiceRepoException(ServiceRepoException::GET_SERVICE_REPRESENTATIVE_FAILED);
        }
    }

    /**
     * Chi tiết dịch vụ
     *
     * @param $serviceId
     * @param $lang
     * @return mixed
     * @throws ServiceRepoException
     */
    public function getDetail($serviceId, $lang)
    {
        try {
            $mServiceBranch = app()->get(ServiceBranchPriceTable::class);
            $mServiceImage = app()->get(ServiceImageTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            //Lấy chi tiết dịch vụ
            $data = $mServiceBranch->getDetail($serviceId);
            $data['old_price'] = null;
            $data['new_price'] = floatval($data['new_price']);
            $data['description_image'] = $data['service_avatar'];
            //Lấy thông tin khuyến mãi
            $getPromotion = $mHome->getPromotionDetail('service', $data['service_code'], null, 'app', null, $data['service_id']);
            $promotion = [];
            if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                    // Tinh phan tram
                    if ($getPromotion['price'] < $data['new_price']) {
                        $percent = $getPromotion['price'] / $data['new_price'] * 100;
                        $promotion['price'] = (100 - round($percent, 2)) . '%';
                        // Tính lại giá khi có khuyến mãi
                        $data['old_price'] = floatval($data['new_price']);
                        $data['new_price'] = ($data['new_price'] * $percent) / 100;
                    }
                }
                if ($getPromotion['gift'] != null) {
                    $promotion['gift'] = $getPromotion['gift'];
                }
            }

            if (empty($promotion)) {
                $promotion = null;
            }

            $data['promotion'] = $promotion;
            // Lấy 5 dịch vụ theo loại dịch vụ
//            $getServiceAttached = $mServiceBranch->getServiceByCategory($data['service_category_id'], $data['service_id']);
//            if (count($getServiceAttached) > 0) {
//                foreach ($getServiceAttached as $v) {
//                    //Lấy giá KM của dịch vụ
//                    $getPromotion = $mHome->getPromotionDetail('service', $v['service_code'], Auth()->id(), 'app', null, $v['service_id']);
//
//                    if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
//                        if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
//                            $v['new_price'] = floatval($getPromotion['price']);
//                        }
//                    }
//                }
//            }
//            $serviceAttached = $getServiceAttached;
            // Service image
            $serviceImage = $mServiceImage->getServiceImage($data['service_id']);

//            $data['service_attached'] = $serviceAttached;
            $data['service_images'] = $serviceImage;

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ServiceRepoException(ServiceRepoException::GET_SERVICE_DETAIL_FAILED, $exception->getMessage() . ' FILE : ' . $exception->getFile() . ' - LINE : ' . $exception->getLine());
        }
    }


    /**
     * Danh sach banner
     *
     * @return array[]
     */
    public function getListBanner()
    {
        return $banner = [
            [
                'id' => 1,
                'image' => 'https://hocvps.com/wp-content/uploads/2015/10/Laravel-5.png'
            ],
            [
                'id' => 2,
                'image' => 'https://hocvps.com/wp-content/uploads/2015/10/Laravel-5.png'
            ]
        ];
    }


    /**
     * Lay thong tin chung (banner + dich vu noi bat + dich vu khuyen mai)
     *
     * @return mixed
     * @throws ServiceRepoException
     */
    public function getGeneralInfo()
    {
        try {
            $mServiceBranch = app()->get(ServiceBranchPriceTable::class);
            $mHome = app()->get(HomeRepoInterface::class);
            // Banner
            $banner = $this->getListBanner();
            $data['banner'] = $banner;
            $dataService = $mServiceBranch->getAllServiceByRepresentative();
            foreach ($dataService as $item) {
                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                // lay promotion (neu co)
                $getPromotion = $mHome->getPromotionDetail('service', $item['service_code'], Auth()->id(), 'app', null, $item['service_id']);
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
                            $item['is_new'] = 0;
                        }
                    }
                    if ($getPromotion['gift'] != null) {
                        $promotion['gift'] = $getPromotion['gift'];
                        $item['is_new'] = 0;
                    }
                } else {
                    $item['is_new'] = 1;
                    $item['promotion'] = null;
                    $data['new'][] = $item;
                    continue;
                }

                if (empty($promotion)) {
                    $promotion = null;
                }
                $item['promotion'] = $promotion;

                //Image null thì trả về image default
                if ($item['service_avatar'] == null) {
                    $item['service_avatar'] = 'http://' . request()->getHttpHost() . '/static/images/service.png';
                }
                if (!empty($promotion)) {
                    $data['promotion'][] = $item;
                }
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ServiceRepoException(ServiceRepoException::GET_GENERAL_INFO_FAILED);
        }
    }

    /**
     * Like / Un like dịch vụ
     *
     * @param $input
     * @return mixed|void
     * @throws ServiceRepoException
     */
    public function likeUnlikeService($input)
    {
        try {
            $mServiceFavourite = app()->get(ServiceFavouriteTable::class);

            if ($input['type'] == 'like') {
                //Kiểm tra dịch vụ đã like chưa
                $check = $mServiceFavourite->checkFavourite($input['service_code'], Auth()->id());

                if ($check == null) {
                    //Like dịch vụ
                    $mServiceFavourite->like([
                        'service_code' => $input['service_code'],
                        'customer_id' => Auth()->id()
                    ]);
                }
            } else if ($input['type'] == 'unlike') {
                //UnLike dịch vụ
                $mServiceFavourite->unlike($input['service_code'], Auth()->id());
            }
        } catch (\Exception | QueryException $exception) {
            throw new ServiceRepoException(ServiceRepoException::LIKE_UNLIKE_FAILED);
        }
    }

    /**
     * Danh sách dịch vụ yêu thích
     *
     * @param $input
     * @return mixed|void
     * @throws ServiceRepoException
     */
    public function getListServiceLikes($input)
    {
        try {
            $mServiceFavourite = app()->get(ServiceFavouriteTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            $data = $this->toPagingData($mServiceFavourite->getListFavourite($input, Auth()->id()));

            foreach ($data['Items'] as $item) {
                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                // lay promotion (neu co)
                $getPromotion = $mHome->getPromotionDetail('service', $item['service_code'], Auth()->id(), 'app', null, $item['service_id']);
                $promotion = [];
                if (isset($getPromotion) && $getPromotion != null && count($getPromotion) > 0) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                        // Tinh phan tram
                        if ($getPromotion['price'] < $item['new_price']) {
                            $percent = $getPromotion['price'] / $item['new_price'] * 100;
                            $promotion['price'] = (100 - round($percent, 2)) . '%';
                            // Tính lại giá khi có khuyến mãi
                            $item['old_price'] = floatval($item['new_price']);
                            $item['new_price'] = ($item['new_price'] * $percent) / 100;
                            $item['is_new'] = 0;
                        }
                    }
                    if (isset($getPromotion['gift'])) {
                        $promotion['gift'] = $getPromotion['gift'];
                        $item['is_new'] = 0;
                    }
                } else {
                    // service new
                    $item['is_new'] = 1;
                    $item['promotion'] = null;
                    continue;
                }

                if (empty($promotion)) {
                    $promotion = null;
                }
                $item['promotion'] = $promotion;
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ServiceRepoException(ServiceRepoException::GET_SERVICE_FAVOURITE_FAILED, $exception->getMessage());
        }
    }

}