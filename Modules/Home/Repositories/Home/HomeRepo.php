<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 8/7/2020
 * Time: 3:21 PM
 */

namespace Modules\Home\Repositories\Home;


use App\Jobs\GetPromotion;
use Illuminate\Database\QueryException;
use Modules\Home\Models\CustomerAppointmentDetailTable;
use Modules\Home\Models\CustomerAppointmentTable;
use Modules\Home\Models\NewTable;
use Modules\Home\Models\PointHistoryTable;
use Modules\Home\Models\ProductCategoryTable;
use Modules\Home\Models\ProductImageTable;
use Modules\Home\Models\PromotionMasterTable;
use Modules\Home\Models\ServiceBranchPriceTable;
use Modules\Home\Models\ProductChildTable;
use Modules\Home\Models\ProductFavouriteTable;
use Modules\Home\Models\PromotionDetailTable;
use Modules\Home\Models\PromotionObjectApplyTable;
use Modules\Home\Models\CustomerTable;
use Modules\Home\Models\PromotionDailyTimeTable;
use Modules\Home\Models\PromotionWeeklyTimeTable;
use Modules\Home\Models\PromotionMonthlyTimeTable;
use Modules\Home\Models\PromotionDateTimeTable;
use Modules\Home\Models\ServiceCardTable;
use Modules\Home\Models\ServiceTable;
use Modules\Home\Repositories\Home\HomeRepoException;
use Modules\Product\Repositories\Product\ProductRepoException;
use Modules\Home\Models\VoucherTable;
use Modules\Service\Repositories\Service\ServiceRepoException;
use MyCore\Repository\PagingTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HomeRepo implements HomeRepoInterface
{
    use PagingTrait;

    protected $productCategory;
    protected $mVoucher;
    protected $mNews;
    protected $order;

    public function __construct(
        ProductCategoryTable $productCategory,
        VoucherTable $mVoucher,
        NewTable $mNews

    )
    {
        $this->productCategory = $productCategory;
        $this->mVoucher = $mVoucher;
        $this->mNews = $mNews;
    }


    /**
     * Lấy ds Banner
     *
     * @param $lang
     * @return mixed
     * @throws \Modules\Home\Repositories\Home\HomeRepoException
     */
    public function getHome($lang)
    {
        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);
            $mPointHistory = app()->get(PointHistoryTable::class);
            $mProductCategory = app()->get(ProductCategoryTable::class);
            $customerId = Auth()->id();
            $input = [
                'page' => 1,
                'type' => 'current'
            ];
            //Danh sách Banner
            $data['banner'] = [
                [
                    'id' => 1,
                    'image' => 'https://kbeauty.fpt.edu.vn/wp-content/uploads/2018/04/Banner-Spa.png'
                ],
                [
                    'id' => 2,
                    'image' => 'https://phuntheuthammy.vn/wp-content/uploads/2018/06/banner-spa-specials.jpg'
                ]
            ];

            //Danh sách nhắc nhở
            //Danh sách cộng điểm khi đặt lịch
            $getPoint = $mPointHistory->getPointBooking($customerId);
            $dataPoint = [];
            if (count($getPoint) > 0) {
                foreach ($getPoint as $v) {
                    $dataPoint[$v['object_id']] = $v['point'];
                }
            }
            //Danh sách lịch hẹn
            $inputtype = ['type' => "current"];
            $Booking = $this->toPagingData($mAppointment->getAppointments($inputtype, $customerId));
            $dataItem = $Booking['Items'];
            $data['BookingHistory'] = [];
            if (count($dataItem) > 0) {
                unset($Booking['Items']);

                foreach ($dataItem as $item) {
                    if ($input['type'] == "older" && $item['status'] != "finish") {
                        $item['status'] = 'cancel';
                    }
                    //Chi tiết lịch hẹn
                    $item['appointment_detail'] = [];
                    $item['appointment_detail'] = $mAppointmentDetail->getDetailAppointment($item['customer_appointment_id'], $customerId);
                    $item['point'] = isset($dataPoint[$item['customer_appointment_id']]) ? $dataPoint[$item['customer_appointment_id']] : 0;
                    $data['BookingHistory'] [] = $item;
                }
            }
            //!Danh sách nhắc nhở
            // list product caterory
            $ProductCategory = $this->toPagingData($mProductCategory->getServiceCategories($input));
            $dataServiceHot = $ProductCategory['Items'];
            if (count($dataServiceHot) > 0) {
                unset($ProductCategory['Items']);
            }
            foreach ($dataServiceHot as $item) {
                $data['ProductCaterory'] [] = $item;
            }
            // !list product caterory
            $data['Voucher'] = $this->mVoucher->getAllVoucher()->toArray();
            //! vocher
            // ds bài viết
            $arr = [
                'page' => 1,
                'type' => 'home'
            ];
            $dataNews = $this->mNews->getNews($arr, $lang);
            //Lấy hình default nếu là null
            if (count($dataNews) > 0) {
                foreach ($dataNews as $item) {
                    if ($item['image'] == null) {
                        $item['image'] = 'http://' . request()->getHttpHost() . '/static/images/news.png';
                    }
                    $data['news'] [] = $item;
                }
            }
            // ! ds bài viết
            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new HomeRepoException(HomeRepoException::GET_LIST_BANNER_FAILED);
        }

    }


    /**
     * Lấy danh sách Dịch vụ home page
     *
     * @return array
     * @throws ServiceRepoException
     */
    public function getService()
    {
        try {
            //service khuyến mãi
            $type = 'service';
            $customer_id = Auth::id();
            $mServiceBranch = app()->get(ServiceBranchPriceTable::class);
            $dataService = $mServiceBranch->getAllServiceByRepresentative();
            $data = [];

            foreach ($dataService as $item) {
                // lay promotion (neu co): giá cũ là giá chi nhánh, giá mới là giá khuyến mãi
                $getPromotion = $this->getPromotionDetail($type, $item['service_code'], $customer_id, 'app', null, $item['service_id']);
                $promotion = [];
                if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                        // Tinh phan tram
                        if ($getPromotion['price'] < $item['new_price']) {
                            $percent = $getPromotion['price'] / $item['new_price'] * 100;
                            $promotion['price'] = (100 - round($percent, 2)) . '%';
                            $item['old_price'] = $item['new_price'];
                            $item['new_price'] = $getPromotion['price'];
                            $item['is_new'] = 0;
                        }
                    }
                    if (isset($getPromotion['gift'])) {
                        $promotion['gift'] = $getPromotion['gift'];
                        $item['is_new'] = 0;
                    }
                } else {
                    // service new
                    // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                    $item['old_price'] = null;
                    $item['is_new'] = 1;
                    $item['promotion'] = null;
                    $data['new'][] = $item;
                    continue;
                }
                $item['promotion'] = $promotion;

                // Nếu k có promotion thì giá cũ là null
//                $item['old_price'] = isset($getPromotion['price']) ? $item['new_price'] : null;
//                $item['old_price'] = null;
//                $item['new_price'] = intval($item['new_price']);

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
            throw new ServiceRepoException(ServiceRepoException::GET_SERVICE_REPRESENTATIVE_FAILED);
        }
    }

    /**
     * Lấy danh sách tất cả sản phẩm không phân trang
     *
     * @param $input
     * @return array|mixed
     * @throws ProductRepoException
     */
    public function getAllProducts($input)
    {
        try {
            $mProduct = app()->get(ProductChildTable::class);
            $mProductImage = app()->get(ProductImageTable::class);

            $data = [];
            //Sản phảm type_app = new
            $data['new'] = $mProduct->getAllProduct('new', $input);
            foreach ($data['new'] as $item) {
                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                $getPromotion = $this->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);
                $promotion = [];
                if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['gift'] != null) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                        // Tinh phan tram
                        if ($getPromotion['price'] < $item['new_price']) {
                            $percent = $getPromotion['price'] / $item['new_price'] * 100;
                            $promotion['price'] = (100 - round($percent, 2)) . '%';
                            // Tính lại giá khi có khuyến mãi
                            $item['old_price'] = $item['new_price'];
                            $item['new_price'] = floatval($getPromotion['price']);
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
                $item['is_like'] = isset($arrProductLike[$item['product_id']]) ? 1 : 0;
                //Lấy avatar product child
                $imageChild = $mProductImage->getAvatar($item['product_code']);

                if ($imageChild != null) {
                    $item['avatar'] = $imageChild['image'];
                }
            }

            // Sản phẩm theo promotion
            $data['promotion'] = [];
            $count = 0;
            $listProduct = $mProduct->getAllProduct('all', $input);
            foreach ($listProduct as $item) {
                $item['new_price'] = floatval($item['new_price']);

                $getPromotion = $this->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);
                $promotion = [];
                if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                        // Tinh phan tram
                        if ($getPromotion['price'] < $item['new_price']) {
                            $percent = $getPromotion['price'] / $item['new_price'] * 100;
                            $promotion['price'] = (100 - round($percent, 2)) . '%';
                            // Tính lại giá khi có khuyến mãi
                            $item['old_price'] = floatval($item['new_price']);
                            $item['new_price'] = floatval($getPromotion['price']);
                        }
                    }
                    if ($getPromotion['gift'] != null) {
                        $promotion['gift'] = $getPromotion['gift'];
                    }
                }

                if (!empty($promotion)) {
                    $item['promotion'] = $promotion;
                    $data['promotion'][] = $item;
                    $count++;
                }
                if ($count >= 6) {
                    break;
                }
                //Lấy avatar product child
                $imageChild = $mProductImage->getAvatar($item['product_code']);

                if ($imageChild != null) {
                    $item['avatar'] = $imageChild['image'];
                }
            }

            return $data;
        } catch (\Exception | QueryException $e) {
            throw new ProductRepoException(ProductRepoException::GET_ALL_PRODUCT_FAILED, $e->getMessage() . $e->getLine() . $e->getFile());
        }
    }

    /**
     * Tìm kiếm tất cả home page
     *
     * @param $input
     * @param $lang
     * @return mixed|void
     * @throws \Modules\Home\Repositories\Home\HomeRepoException
     */
    public function searchAll($input, $lang)
    {
        try {
            $mProduct = app()->get(ProductChildTable::class);
            $mServiceBranch = app()->get(ServiceBranchPriceTable::class);
            $mVoucher = app()->get(VoucherTable::class);
            $mNews = app()->get(NewTable::class);
            $mPromotion = app()->get(PromotionMasterTable::class);

            $data['product'] = [];
            $data['service'] = [];
            $data['voucher'] = [];
            $data['promotion'] = [];
            $data['news'] = [];

            //Lấy thông tin sản phẩm
            $getProduct = $mProduct->getProductSearch($input)->toArray();

            if (count($getProduct) > 0) {
                foreach ($getProduct as $item) {
                    $item['old_price'] = null;
                    $item['new_price'] = floatval($item['new_price']);
                    // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                    // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                    $getPromotion = $this->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);
                    $promotion = [];
                    if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                        if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                            // Tinh phan tram
                            if ($getPromotion['price'] < $item['new_price']) {
                                $percent = $getPromotion['price'] / $item['new_price'] * 100;
                                $promotion['price'] = (100 - round($percent, 2)) . '%';
                                // Tính lại giá khi có khuyến mãi
                                $item['old_price'] = $item['new_price'];
                                $item['new_price'] = floatval($getPromotion['price']);
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
                    $data['product'] [] = $item;
                }
            }

            //Lấy thông tin dịch vụ
            $getService = $mServiceBranch->getServiceSearch($input)->toArray();
           
            if (count($getService) > 0) {
                foreach ($getService as $item) {
                    $item['old_price'] = null;
                    $item['new_price'] = floatval($item['new_price']);
                    // lay promotion (neu co): giá cũ là giá chi nhánh, giá mới là giá khuyến mãi
                    $getPromotion = $this->getPromotionDetail('service', $item['service_code'], Auth()->id(), 'app', null, $item['service_id']);
                    $promotion = [];
                    if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                        if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                            // Tinh phan tram
                            if ($getPromotion['price'] < $item['new_price']) {
                                $percent = $getPromotion['price'] / $item['new_price'] * 100;
                                $promotion['price'] = (100 - round($percent, 2)) . '%';
                                // Tính lại giá khi có khuyến mãi
                                $item['old_price'] = $item['new_price'];
                                $item['new_price'] = floatval($getPromotion['price']);
                                $item['is_new'] = 0;
                            }
                        }
                        if ($getPromotion['gift'] != null) {
                            $promotion['gift'] = $getPromotion['gift'];
                            $item['is_new'] = 0;
                        }
                    } else {
                        // service new
                        // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                        $item['is_new'] = 1;
                        $item['promotion'] = null;
                    }
                    if (empty($promotion)) {
                        $promotion = null;
                    }
                    $item['promotion'] = $promotion;
                    $data['service'] [] = $item;
                }
            }

            //Lấy thông tin voucher
            $data['voucher'] = $mVoucher->getVoucherSearch($input)->toArray();
            //Lấy thông tin bài viết
            $data['news'] = $mNews->getNewsSearch($input, $lang)->toArray();
            //Lấy thông tin CTKM
            $data['promotion'] = $mPromotion->getPromotionSearch($input)->toArray();

            return $data;
        } catch (\Exception | QueryException $e) {
            throw new HomeRepoException(HomeRepoException::SEARCH_ALL_FAILED, $e->getMessage(). $e->getLine());
        }
    }

    /**
     * Lấy thông tin khuyến mãi của sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @param $customerId
     * @param $orderSource
     * @param $quantity
     * @param $objectId
     * @param $date
     * @return mixed|void
     */
    public function getPromotionDetail($objectType, $objectCode, $customerId, $orderSource, $quantity = null, $objectId, $date = null)
    {
        $mPromotionDetail = new PromotionDetailTable();
        $mDaily = new PromotionDailyTimeTable();
        $mWeekly = new PromotionWeeklyTimeTable();
        $mMonthly = new PromotionMonthlyTimeTable();
        $mFromTo = new PromotionDateTimeTable();
        $mCustomer = new CustomerTable();
        $mPromotionApply = new PromotionObjectApplyTable();

        $currentDate = Carbon::now()->format('Y-m-d H:i:s');
        $currentTime = Carbon::now()->format('H:i');

        if ($date != null) {
            $currentDate = Carbon::createFromFormat('Y-m-d H:i', $date)->format('Y-m-d H:i:s');
            $currentTime = Carbon::createFromFormat('Y-m-d H:i', $date)->format('H:i');
        }

        $price = null;
        $gift = null;

        $promotionQuota = [];
        $promotionPrice = [];
        $promotionLog = [];

        $getDetail = $mPromotionDetail->getPromotionDetail($objectType, $objectCode, null, $currentDate);

        if (isset($getDetail) && count($getDetail) > 0) {
            foreach ($getDetail as $key => $item) {
                $promotionType = $item['promotion_type'];
                //Check thời gian diễn ra chương trình
                if ($currentDate < $item['start_date'] || $currentDate > $item['end_date']) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check chi nhánh áp dụng
                if ($item['branch_apply'] != 'all' &&
                    !in_array(Auth()->user()->branch_id, explode(',', $item['branch_apply']))) {
                    //Kết thúc vòng for
                    continue;
                }
                //Check KM theo time đặc biệt
                if ($item['is_time_campaign'] == 1) {
                    switch ($item['time_type']) {
                        case 'D':
                            $daily = $mDaily->getDailyByPromotion($item['promotion_code']);

                            if ($daily != null) {
                                $startTime = Carbon::createFromFormat('H:i:s', $daily['start_time'])->format('H:i');
                                $endTime = Carbon::createFromFormat('H:i:s', $daily['end_time'])->format('H:i');
                                //Kiểm tra giờ bắt đầu, giờ kết thúc
                                if ($currentTime < $startTime || $currentTime > $endTime) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                        case 'W':
                            $weekly = $mWeekly->getWeeklyByPromotion($item['promotion_code']);
                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['default_start_time'])->format('H:i');
                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['default_end_time'])->format('H:i');

                            switch (Carbon::createFromFormat('Y-m-d H:i:s', $currentDate)->format('l')) {
                                case 'Monday':
                                    if ($weekly['is_monday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_monday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Tuesday':
                                    if ($weekly['is_tuesday'] == 1) {
                                        if ($weekly['is_other_tuesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_tuesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Wednesday':
                                    if ($weekly['is_wednesday'] == 1) {
                                        if ($weekly['is_other_wednesday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_wednesday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Thursday':
                                    if ($weekly['is_thursday'] == 1) {
                                        if ($weekly['is_other_monday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_thursday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Friday':
                                    if ($weekly['is_friday'] == 1) {
                                        if ($weekly['is_other_friday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_friday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Saturday':
                                    if ($weekly['is_saturday'] == 1) {
                                        if ($weekly['is_other_saturday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_saturday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                                case 'Sunday':
                                    if ($weekly['is_sunday'] == 1) {
                                        if ($weekly['is_other_sunday'] == 1) {
                                            $startTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_start_time'])->format('H:i');
                                            $endTime = Carbon::createFromFormat('H:i:s', $weekly['is_other_sunday_end_time'])->format('H:i');
                                        }
                                    } else {
                                        //Kết thúc vòng for
                                        continue 3;
                                    }
                                    break;
                            }
                            //Kiểm tra giờ bắt đầu, giờ kết thúc
                            if ($currentTime < $startTime || $currentTime > $endTime) {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'M':
                            $monthly = $mMonthly->getMonthlyByPromotion($item['promotion_code']);

                            if (count($monthly) > 0) {
                                $next = false;

                                foreach ($monthly as $v) {
                                    $startDate = Carbon::createFromFormat('Y-m-d H:i:s', $v['run_date'] . ' ' . $v['start_time'])->format('Y-m-d H:i');
                                    $endDate = Carbon::createFromFormat('Y-m-d H:i:s', $v['run_date'] . ' ' . $v['end_time'])->format('Y-m-d H:i');

                                    if ($currentDate > $startDate && $currentDate < $endDate) {
                                        $next = true;
                                    }
                                }

                                if ($next == false) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            } else {
                                //Kết thúc vòng for
                                continue 2;
                            }
                            break;
                        case 'R':
                            $fromTo = $mFromTo->getDateTimeByPromotion($item['promotion_code']);

                            if ($fromTo != null) {
                                $startFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['form_date'] . ' ' . $fromTo['start_time'])->format('Y-m-d H:i');
                                $endFrom = Carbon::createFromFormat('Y-m-d H:i:s', $fromTo['to_date'] . ' ' . $fromTo['end_time'])->format('Y-m-d H:i');

                                if ($currentDate < $startFrom || $currentDate > $endFrom) {
                                    //Kết thúc vòng for
                                    continue 2;
                                }
                            }
                            break;
                    }
                }

                //Check KM theo type = discount or gift
                if ($item['promotion_type'] != $promotionType) {
                    //Kết thúc vòng for
                    continue;
                }

                //Check nguồn đơn hàng
                if ($item['order_source'] != 'all' && $item['order_source'] != $orderSource) {
                    //Kết thúc vòng for
                    continue;
                }

                //Check đối tượng áp dụng
                if ($item['promotion_apply_to'] != 1 && $item['promotion_apply_to'] != null) {
                    //Lấy thông tin khách hàng
                    $getCustomer = $mCustomer->getItem($customerId);

                    if ($getCustomer == null || $getCustomer['customer_id'] == 1) {
                        //Kết thúc vòng for
                        continue;
                    }

                    if ($getCustomer['member_level_id'] == null) {
                        $getCustomer['member_level_id'] = 1;
                    }

                    $objectId = '';
                    if ($item['promotion_apply_to'] == 2) {
                        $objectId = $getCustomer['member_level_id'];
                    } else if ($item['promotion_apply_to'] == 3) {
                        $objectId = $getCustomer['customer_group_id'];
                    } else if ($item['promotion_apply_to'] == 4) {
                        $objectId = $item['customer_id'];
                    }

                    $getApply = $mPromotionApply->getApplyByObjectId($item['promotion_code'], $objectId);

                    if ($getApply == null) {
                        //Kết thúc vòng for
                        continue;
                    }
                }

                $item['object_type'] = $objectType;
                $item['object_id'] = $objectId;
                $item['object_code'] = $objectCode;
                $item['quantity'] = $quantity;
                //Check quota (số tiền)
                if ($promotionType == 1) {
                    $promotionPrice [] = $item;
                } else {
                    $item['quota'] = !empty($item['quota']) ? $item['quota'] : 0;
                    //Số quà được tặng
                    $totalGift = intval($item['quantity_gift']);
                    //Quota use sau khi áp dụng promotion
                    $quotaUse = $item['quota_use'] + $totalGift;

                    if ($item['quota'] == 0 || $item['quota'] == '' || $quotaUse <= floatval($item['quota'])) {
                        //Lấy giá trị quà tặng
                        $priceGift = $this->getPriceObject($item['gift_object_type'], $item['gift_object_code']);

                        $item['quantity_gift'] = $totalGift;
                        $item['quota'] = !empty($item['quota']) ? $item['quota'] : 0;
                        $item['quota_use'] = floatval($item['quota_use']);
                        $item['total_price_gift'] = $priceGift * $totalGift;

                        $promotionQuota [] = $item;
                    }
                }
            }
        }

        if (count($promotionPrice) > 0) {
            //Lấy CTKM có giá ưu đãi nhất
            $getPriceMostPreferential = $this->choosePriceMostPreferential($promotionPrice);
            $promotionLog [] = $getPriceMostPreferential;
            //Lấy giá KM
            $price = $getPriceMostPreferential['promotion_price'];
        }

        if (count($promotionQuota) > 0) {
            //Lấy CTKM có quà tặng ưu đãi nhất
            $getGiftMostPreferential = $this->getGiftMostPreferential($promotionQuota);
            $promotionLog [] = $getGiftMostPreferential;
            //Lấy quà tặng KM
            $gift = __('Mua ') . $getGiftMostPreferential['quantity_buy'] . __(' tặng ') . $getGiftMostPreferential['quantity_gift'];
        }

        return [
            'price' => $price,
            'gift' => $gift,
            'promotion_log' => $promotionLog,
        ];
    }

    /**
     * Lấy giá trị khuyến mãi sp, dv, thẻ dv
     *
     * @param $objectType
     * @param $objectCode
     * @return int
     */
    private function getPriceObject($objectType, $objectCode)
    {
        $price = 0;

        switch ($objectType) {
            case 'product':
                $mProduct = app()->get(ProductChildTable::class);
                //Lấy thông tin sp khuyến mãi
                $getProduct = $mProduct->getProductPromotion($objectCode);
                $price = $getProduct['new_price'];

                break;
            case 'service':
                $mService = app()->get(ServiceTable::class);
                //Lấy thông tin dv khuyến mãi
                $getService = $mService->getServicePromotion($objectCode);
                $price = $getService['new_price'];

                break;
            case 'service_card':
                $mServiceCard = app()->get(ServiceCardTable::class);
                //Lấy thông tin thẻ dv khuyến mãi
                $getServiceCard = $mServiceCard->getServiceCardPromotion($objectCode);
                $price = $getServiceCard['new_price'];

                break;
        }

        return floatval($price);
    }

    /**
     * Lấy quà tặng ưu đãi nhất
     *
     * @param $arrGift
     * @return array
     */
    private function getGiftMostPreferential($arrGift)
    {
        $result = [];
        if (count($arrGift) == 1) {
            //Có 1 CTKM quà tặng thì lấy chính nó
            $result [] = [
                'object_type' => $arrGift[0]['object_type'],
                'object_id' => $arrGift[0]['object_id'],
                'object_code' => $arrGift[0]['object_code'],
                'quantity' => $arrGift[0]['quantity'],
                'promotion_type' => $arrGift[0]['promotion_type'],
                'promotion_id' => $arrGift[0]['promotion_id'],
                'promotion_code' => $arrGift[0]['promotion_code'],
                'start_date' => $arrGift[0]['start_date'],
                'end_date' => $arrGift[0]['end_date'],
                'base_price' => $arrGift[0]['base_price'],
                'promotion_price' => $arrGift[0]['promotion_price'],
                'gift_object_type' => $arrGift[0]['gift_object_type'],
                'gift_object_id' => $arrGift[0]['gift_object_id'],
                'gift_object_code' => $arrGift[0]['gift_object_code'],
                'quantity_gift' => $arrGift[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrGift[0]['quantity_buy'],
                'quota' => $arrGift[0]['quota'],
                'quota_use' => $arrGift[0]['quota_use'],
                'total_price_gift' => $arrGift[0]['total_price_gift']
            ];
        } else if (count($arrGift) > 1) {

            //Có nhiều CTKM quà tặng
            //Lấy quà tặng có giá trị cao nhất
            $giftPreferential = $this->chooseGiftPreferential($arrGift);

            $result = $giftPreferential;

            if (count($result) > 1) {
                //Lấy quà tặng có số lượng mua thấp nhất
                $giftMinBuy = $this->chooseGiftMinBuy($result);

                $result = $giftMinBuy;
            }

            if (count($result) > 1) {
                //Lấy quà tặng có quota - quota_use còn nhiều nhất (ưu tiên quota != 0 ko giới hạn)
                $giftQuota = $this->chooseGiftQuota($result);

                $result = $giftQuota;
            }
        }

        return $result[0];
    }

    /**
     * Chọn quà tặng có giá trị cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftPreferential($arrGift)
    {
        $result = [];
        //Lấy giá trị quà tặng có giá trị cao nhất
        $giftPrice = array_column($arrGift, 'total_price_gift');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($giftPrice, SORT_DESC, $arrGift);

        $result [] = [
            'object_type' => $arrGift[0]['object_type'],
            'object_id' => $arrGift[0]['object_id'],
            'object_code' => $arrGift[0]['object_code'],
            'quantity' => $arrGift[0]['quantity'],
            'promotion_id' => $arrGift[0]['promotion_id'],
            'promotion_code' => $arrGift[0]['promotion_code'],
            'promotion_type' => $arrGift[0]['promotion_type'],
            'start_date' => $arrGift[0]['start_date'],
            'end_date' => $arrGift[0]['end_date'],
            'base_price' => $arrGift[0]['base_price'],
            'promotion_price' => $arrGift[0]['promotion_price'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['total_price_gift'] >= $result[0]['total_price_gift']) {
                $result [] = [
                    'object_type' => $v['object_type'],
                    'object_id' => $v['object_id'],
                    'object_code' => $v['object_code'],
                    'quantity' => $v['quantity'],
                    'promotion_id' => $v['promotion_id'],
                    'promotion_code' => $v['promotion_code'],
                    'promotion_type' => $v['promotion_type'],
                    'start_date' => $v['start_date'],
                    'end_date' => $v['end_date'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có lượng mua thấp nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftMinBuy($arrGift)
    {
        //Có nhiều promotion bằng giá trị thì check số lượng mua (lợi ích khách hàng)
        $result = [];
        //Lấy quà tặng có số lượng mua thấp nhất
        $quantityBuy = array_column($arrGift, 'quantity_buy');
        //Sắp xếp lại array có số lượng cần mua thấp nhất
        array_multisort($quantityBuy, SORT_ASC, $arrGift);

        $result [] = [
            'object_type' => $arrGift[0]['object_type'],
            'object_id' => $arrGift[0]['object_id'],
            'object_code' => $arrGift[0]['object_code'],
            'quantity' => $arrGift[0]['quantity'],
            'promotion_id' => $arrGift[0]['promotion_id'],
            'promotion_code' => $arrGift[0]['promotion_code'],
            'promotion_type' => $arrGift[0]['promotion_type'],
            'start_date' => $arrGift[0]['start_date'],
            'end_date' => $arrGift[0]['end_date'],
            'base_price' => $arrGift[0]['base_price'],
            'promotion_price' => $arrGift[0]['promotion_price'],
            'gift_object_type' => $arrGift[0]['gift_object_type'],
            'gift_object_id' => $arrGift[0]['gift_object_id'],
            'gift_object_code' => $arrGift[0]['gift_object_code'],
            'quantity_gift' => $arrGift[0]['quantity_gift'],
            //mới update param thêm
            'quantity_buy' => $arrGift[0]['quantity_buy'],
            'quota' => $arrGift[0]['quota'],
            'quota_use' => $arrGift[0]['quota_use'],
            'total_price_gift' => $arrGift[0]['total_price_gift']
        ];

        unset($arrGift[0]);

        foreach ($arrGift as $v) {
            //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
            if ($v['quantity_buy'] == $result[0]['quantity_buy']) {
                $result [] = [
                    'object_type' => $v['object_type'],
                    'object_id' => $v['object_id'],
                    'object_code' => $v['object_code'],
                    'quantity' => $v['quantity'],
                    'promotion_id' => $v['promotion_id'],
                    'promotion_code' => $v['promotion_code'],
                    'promotion_type' => $v['promotion_type'],
                    'start_date' => $v['start_date'],
                    'end_date' => $v['end_date'],
                    'base_price' => $v['base_price'],
                    'promotion_price' => $v['promotion_price'],
                    'gift_object_type' => $v['gift_object_type'],
                    'gift_object_id' => $v['gift_object_id'],
                    'gift_object_code' => $v['gift_object_code'],
                    'quantity_gift' => $v['quantity_gift'],
                    //mới update param thêm
                    'quantity_buy' => $v['quantity_buy'],
                    'quota' => $v['quota'],
                    'quota_use' => $v['quota_use'],
                    'total_price_gift' => $v['total_price_gift']
                ];
            }
        }

        return $result;
    }

    /**
     * Chọn quà tặng có quota còn lại cao nhất
     *
     * @param $arrGift
     * @return array
     */
    private function chooseGiftQuota($arrGift)
    {
        //Có nhiều promotion bằng giá trị + số lượng mua thì kiểm tra quota_use con lại (ưu tiên promotion có quota != 0)
        $result = [];

        $arrLimited = [];
        $arrUnLimited = [];

        foreach ($arrGift as $v) {
            if ($v['quota'] != 0) {
                $v['quota_balance'] = $v['quota'] - $v['quota_use'];
                $arrLimited [] = $v;
            } else {
                $arrUnLimited [] = $v;
            }
        }

        if (count($arrLimited) > 0) {
            //Ưu tiên lấy quà tặng có giới hạn quota

            //Lấy quà tặng có quota còn lại cao nhất
            $quantityQuota = array_column($arrLimited, 'quota_balance');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuota, SORT_DESC, $arrLimited);

            $result [] = [
                'object_type' => $arrLimited[0]['object_type'],
                'object_id' => $arrLimited[0]['object_id'],
                'object_code' => $arrLimited[0]['object_code'],
                'quantity' => $arrLimited[0]['quantity'],
                'promotion_id' => $arrLimited[0]['promotion_id'],
                'promotion_code' => $arrLimited[0]['promotion_code'],
                'promotion_type' => $arrLimited[0]['promotion_type'],
                'start_date' => $arrLimited[0]['start_date'],
                'end_date' => $arrLimited[0]['end_date'],
                'base_price' => $arrLimited[0]['base_price'],
                'promotion_price' => $arrLimited[0]['promotion_price'],
                'gift_object_type' => $arrLimited[0]['gift_object_type'],
                'gift_object_id' => $arrLimited[0]['gift_object_id'],
                'gift_object_code' => $arrLimited[0]['gift_object_code'],
                'quantity_gift' => $arrLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrLimited[0]['quantity_buy'],
                'quota' => $arrLimited[0]['quota'],
                'quota_use' => $arrLimited[0]['quota_use'],
                'total_price_gift' => $arrLimited[0]['total_price_gift']
            ];

            unset($arrLimited[0]);

            foreach ($arrLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_balance'] == ($result[0]['quota'] - $result[0]['quota_use'])) {
                    $result [] = [
                        'object_type' => $v['object_type'],
                        'object_id' => $v['object_id'],
                        'object_code' => $v['object_code'],
                        'quantity' => $v['quantity'],
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

        if (count($result) == 0 && count($arrUnLimited) > 0) {
            //Lấy quà tặng có quota_use thấp nhất
            $quantityQuotaUse = array_column($arrUnLimited, 'quota_use');
            //Sắp xếp lại array có số lượng cần mua thấp nhất
            array_multisort($quantityQuotaUse, SORT_ASC, $arrUnLimited);

            $result [] = [
                'object_type' => $arrUnLimited[0]['object_type'],
                'object_id' => $arrUnLimited[0]['object_id'],
                'object_code' => $arrUnLimited[0]['object_code'],
                'quantity' => $arrUnLimited[0]['quantity'],
                'promotion_id' => $arrUnLimited[0]['promotion_id'],
                'promotion_code' => $arrUnLimited[0]['promotion_code'],
                'promotion_type' => $arrUnLimited[0]['promotion_type'],
                'start_date' => $arrUnLimited[0]['start_date'],
                'end_date' => $arrUnLimited[0]['end_date'],
                'base_price' => $arrUnLimited[0]['base_price'],
                'promotion_price' => $arrUnLimited[0]['promotion_price'],
                'gift_object_type' => $arrUnLimited[0]['gift_object_type'],
                'gift_object_id' => $arrUnLimited[0]['gift_object_id'],
                'gift_object_code' => $arrUnLimited[0]['gift_object_code'],
                'quantity_gift' => $arrUnLimited[0]['quantity_gift'],
                //mới update param thêm
                'quantity_buy' => $arrUnLimited[0]['quantity_buy'],
                'quota' => $arrUnLimited[0]['quota'],
                'quota_use' => $arrUnLimited[0]['quota_use'],
                'total_price_gift' => $arrUnLimited[0]['total_price_gift']
            ];

            unset($arrUnLimited[0]);

            foreach ($arrUnLimited as $v) {
                //Kiểm tra có promotion nào có giá trị = với promotion cao nhất
                if ($v['quota_use'] <= $result[0]['quota_use']) {
                    $result [] = [
                        'object_type' => $v['object_type'],
                        'object_id' => $v['object_id'],
                        'object_code' => $v['object_code'],
                        'quantity' => $v['quantity'],
                        'promotion_id' => $v['promotion_id'],
                        'promotion_code' => $v['promotion_code'],
                        'promotion_type' => $v['promotion_type'],
                        'start_date' => $v['start_date'],
                        'end_date' => $v['end_date'],
                        'base_price' => $v['base_price'],
                        'promotion_price' => $v['promotion_price'],
                        'gift_object_type' => $v['gift_object_type'],
                        'gift_object_id' => $v['gift_object_id'],
                        'gift_object_code' => $v['gift_object_code'],
                        'quantity_gift' => $v['quantity_gift'],
                        //mới update param thêm
                        'quantity_buy' => $v['quantity_buy'],
                        'quota' => $v['quota'],
                        'quota_use' => $v['quota_use'],
                        'total_price_gift' => $v['total_price_gift']
                    ];
                }
            }
        }

//        if (count($result) > 1) {
//            $result = $result[0];
//        }
        return $result;
    }

    /**
     * Chọn CTKM giảm giá ưu đãi nhất
     *
     * @param $arrPrice
     * @return array
     */
    private function choosePriceMostPreferential($arrPrice)
    {
        //Lấy giá trị quà tặng có giá trị cao nhất
        $minPrice = array_column($arrPrice, 'promotion_price');
        //Sắp xếp lại array có quà tặng giá trị cao nhất
        array_multisort($minPrice, SORT_ASC, $arrPrice);

        //Lấy CTKM có giá ưu đãi nhất
        return [
            'object_type' => $arrPrice[0]['object_type'],
            'object_id' => $arrPrice[0]['object_id'],
            'object_code' => $arrPrice[0]['object_code'],
            'quantity' => $arrPrice[0]['quantity'],
            'promotion_id' => $arrPrice[0]['promotion_id'],
            'promotion_code' => $arrPrice[0]['promotion_code'],
            'promotion_type' => $arrPrice[0]['promotion_type'],
            'start_date' => $arrPrice[0]['start_date'],
            'end_date' => $arrPrice[0]['end_date'],
            'base_price' => $arrPrice[0]['base_price'],
            'promotion_price' => $arrPrice[0]['promotion_price'],
            'gift_object_type' => $arrPrice[0]['gift_object_type'],
            'gift_object_id' => $arrPrice[0]['gift_object_id'],
            'gift_object_code' => $arrPrice[0]['gift_object_code'],
            'quantity_gift' => $arrPrice[0]['quantity_gift'],
        ];
    }

}