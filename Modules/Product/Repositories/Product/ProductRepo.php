<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-09
 * Time: 9:39 AM
 * @author SonDepTrai
 */

namespace Modules\Product\Repositories\Product;


use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Home\Repositories\Home\HomeRepoInterface;
use Modules\Product\Models\CustomerPotentialLogTable;
use Modules\Product\Models\CustomerTable;
use Modules\Product\Models\PromotionDailyTimeTable;
use Modules\Product\Models\PromotionDateTimeTable;
use Modules\Product\Models\PromotionDetailTable;
use Modules\Product\Models\PromotionMonthlyTimeTable;
use Modules\Product\Models\PromotionObjectApplyTable;
use Modules\Product\Models\PromotionWeeklyTimeTable;
use Modules\Product\Models\ConfigTable;
use Modules\Product\Models\NewTable;
use Modules\Product\Models\OrderDetailTable;
use Modules\Product\Models\ProductAttributeGroupsTable;
use Modules\Product\Models\ProductAttributesTable;
use Modules\Product\Models\ProductCategoryTable;
use Modules\Product\Models\ProductChildTable;
use Modules\Product\Models\ProductFavouriteTable;
use Modules\Product\Models\ProductImageTable;
use Modules\Product\Models\ProductModel;
use Modules\Product\Models\ProductTable;
use Modules\Product\Models\RatingLogTable;
use Modules\Product\Models\SuppliersTable;
use MyCore\Repository\PagingTrait;
use Carbon\Carbon;

class ProductRepo implements ProductRepoInterface
{
    use PagingTrait;

    /**
     * Danh sách lịch sử sản phẩm
     *
     * @param $input
     * @return array|mixed
     * @throws ProductRepoException
     */
    public function getHistoryProducts($input)
    {
        try {
            $customerId = Auth()->id();
            $mOrderDetail = app()->get(OrderDetailTable::class);

            $data = $mOrderDetail->getDetails($input, $customerId, 'product');

            return $this->toPagingData($data);
        } catch (\Exception $exception) {
            throw new ProductRepoException(ProductRepoException::GET_PRODUCT_LIST_FAILED);
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
            $mProductImage = app()->get(ProductImageTable::class);
            $mHome = app()->get(HomeRepoInterface::class);
            //Lấy sản phẩm đã like
            $mProductFavourite = app()->get(ProductFavouriteTable::class);
            $getLikeALl = $mProductFavourite->getLikeAll(Auth()->id());
            $arrProductLike = [];
            if (count($getLikeALl) > 0) {
                foreach ($getLikeALl as $v) {
                    $arrProductLike[$v['product_id']] = $v['id'];
                }
            }

            $mProduct = app()->get(ProductChildTable::class);
            $data = [];

            //Sản phẩm theo group new
            $data['new'] = $mProduct->getAllProduct('new', $input);

            if (isset($data['new']) && count($data['new'])) {
                foreach ($data['new'] as $item) {
                    $item['old_price'] = null;
                    $item['new_price'] = floatval($item['new_price']);
                    // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                    // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                    $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);
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


                    $item['is_like'] = isset($arrProductLike[$item['product_id']]) ? 1 : 0;
                    //Lấy avatar product child
                    $imageChild = $mProductImage->getAvatar($item['product_code']);

                    if ($imageChild != null) {
                        $item['avatar'] = $imageChild['image'];
                    }
                }
            }
            //Sản phảm theo group best seller
            $data['best_seller'] = $mProduct->getAllProduct('best_seller', $input);

            if (isset($data['best_seller']) && count($data['best_seller'])) {
                foreach ($data['best_seller'] as $item) {
                    $item['old_price'] = null;
                    $item['new_price'] = floatval($item['new_price']);
                    // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                    // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                    $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);
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

                    $item['is_like'] = isset($arrProductLike[$item['product_id']]) ? 1 : 0;
                    //Lấy avatar product child
                    $imageChild = $mProductImage->getAvatar($item['product_code']);

                    if ($imageChild != null) {
                        $item['avatar'] = $imageChild['image'];
                    }
                }
            }
            //Sản phẩm theo group giảm giá
            $data['is_sale'] = $mProduct->getAllProduct('is_sale', $input);

            if (isset($data['is_sale']) && count($data['is_sale'])) {
                foreach ($data['is_sale'] as $item) {
                    $item['old_price'] = null;
                    $item['new_price'] = floatval($item['new_price']);
                    // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                    // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                    $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);
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

                    $item['is_like'] = isset($arrProductLike[$item['product_id']]) ? 1 : 0;
                    //Lấy avatar product child
                    $imageChild = $mProductImage->getAvatar($item['product_code']);

                    if ($imageChild != null) {
                        $item['avatar'] = $imageChild['image'];
                    }
                }
            }
            //Sản phẩm theo group other
            $data['other'] = $mProduct->getAllProduct('other', $input);

            if (isset($data['other']) && count($data['other'])) {
                foreach ($data['other'] as $item) {
                    $item['old_price'] = null;
                    $item['new_price'] = floatval($item['new_price']);
                    // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                    // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                    $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);
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

                    $item['is_like'] = isset($arrProductLike[$item['product_id']]) ? 1 : 0;
                    //Lấy avatar product child
                    $imageChild = $mProductImage->getAvatar($item['product_code']);

                    if ($imageChild != null) {
                        $item['avatar'] = $imageChild['image'];
                    }
                }
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ProductRepoException(ProductRepoException::GET_ALL_PRODUCT_FAILED);
        }
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
            $mProductImage = app()->get(ProductImageTable::class);
            $mHome = app()->get(HomeRepoInterface::class);


            //Ds sản phẩm theo
            $mProduct = app()->get(ProductChildTable::class);
            
            $data = $this->toPagingData($mProduct->getProducts($input));
            
            foreach ($data['Items'] as $item) {
                //Lấy avatar product child
                $imageChild = $mProductImage->getAvatar($item['product_code']);

                if ($imageChild != null) {
                    $item['avatar'] = $imageChild['image'] ?? "";
                }
                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                //Check khuyến mãi
                try {
                    $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], null, 'app', null, $item['product_id']);
                    $promotion = null;
                    if($getPromotion != null){
                       
                        // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                        // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                        
                        if (isset($getPromotion) && $getPromotion['price'] != null) {
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
                    }
                } catch (\Exception $ex) {
                    
                }
                
               

                // if (empty($promotion)) {
                //     $promotion = null;
                // }

                $item['promotion'] = $promotion;
            }

            return $data;
        } catch (\Exception $exception) {
            throw new ProductRepoException(ProductRepoException::GET_PRODUCT_TYPE_LIST_FAILED, $exception->getMessage());
        }
    }

    /**
     * Lấy chi tiết sản phẩm
     *
     * @param $input
     * @param $lang
     * @return mixed
     * @throws ProductRepoException
     */
    public function getProductDetail($input, $lang)
    {
        try {
            $mProduct = app()->get(ProductChildTable::class);
            $mProductDetail = app()->get(ProductTable::class);
            $mNew = app()->get(NewTable::class);
            $mProductModel = app()->get(ProductModel::class);
            $mProductSupplier = app()->get(SuppliersTable::class);
            $mProductImage = app()->get(ProductImageTable::class);

            $info = $mProduct->getInfo($input['product_id']);
            $info['old_price'] = null;
            $info['new_price'] = floatval($info['new_price']);
            // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
            // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
            $info['is_new'] = 1;

            //Lấy avatar product child, ảnh cho mô tả sản phẩm
            $imageChild = $mProductImage->getAvatar($info['product_code']);

            if ($imageChild != null) {
                $info['avatar'] = $imageChild['image'];
                $info['description_image'] = $imageChild['image'];
            } else {
                $info['description_image'] = $info['avatar'];
            }

            $mHome = app()->get(HomeRepoInterface::class);
            //Lấy thông tin khuyến mãi
            $getPromotion = $mHome->getPromotionDetail('product', $info['product_code'], null, 'app', null, $info['product_id']);
            $promotion = [];

            if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                    // Tinh phan tram
                    if ($getPromotion['price'] < $info['new_price']) {
                        $percent = $getPromotion['price'] / $info['new_price'] * 100;
                        $promotion['price'] = (100 - round($percent, 2)) . '%';
                        // Tính lại giá khi có khuyến mãi
                        $info['old_price'] = floatval($info['new_price']);
                        $info['new_price'] = floatval($getPromotion['price']);
                    }
                }
                if ($getPromotion['gift'] != null) {
                    $promotion['gift'] = $getPromotion['gift'];
                }
                $info['is_new'] = 0;
            }
            if (empty($promotion)) {
                $promotion = null;
            }
            $info['promotion'] = $promotion;
            //thông tin chi tiết sản phẩm (doanh mục, nhà cung cấp, thương hiệu, xuất xứ)
            $dataProductDetail = $mProductDetail->getByID($input['product_id']);
            $info['product_model_name'] = null;
            $info['made_in'] = null;
            $info['supplier_name'] = null;
            if ($dataProductDetail != null) {
                if ($dataProductDetail['product_model_id'] != null) {
                    $dataProductModel = $mProductModel->getBYID($dataProductDetail['product_model_id']);
                    $info['product_model_name'] = $dataProductModel['product_model_name'];//nhãn hiệu detail sp
                    $info['made_in'] = 'Việt Nam'; // xuất xứ
                }
                if ($dataProductDetail['supplier_id'] != null) {
                    $dataProductSupplier = $mProductSupplier->getBYID($dataProductDetail['supplier_id']);
                    $info['supplier_name'] = $dataProductSupplier['supplier_name'];//nhãn hiệu detail sp
                }
            }

            //Lấy 3 sản phẩm theo loại sản phẩm
//            $productAttached = $mProduct->getProductByCategory($info['product_category_id'], $info['product_id']);
//            $dataProductAttached = [];
//            if (count($productAttached) > 0) {
//                foreach ($productAttached as $item) {
//                    $item['old_price'] = null;
//                    $item['new_price'] = floatval($item['new_price']);
//                    // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
//                    // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
//                    $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth()->id(), 'app', null, $item['product_id']);
//                    $promotion = [];
//                    if (isset($getPromotion) && $getPromotion != null) {
//                        if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
//                            // Tinh phan tram
//                            if ($getPromotion['price'] < $item['new_price']) {
//                                $percent = $getPromotion['price'] / $item['new_price'] * 100;
//                                $promotion['price'] = (100 - round($percent, 2)) . '%';
//                                // Tính lại giá khi có khuyến mãi
//                                $item['old_price'] = floatval($item['new_price']);
//                                $item['new_price'] = ($item['new_price'] * $percent) / 100;
//                            }
//                        }
//                        if ($getPromotion['gift'] != null) {
//                            $promotion['gift'] = $getPromotion['gift'];
//                        }
//                    }
//                    if (empty($promotion)) {
//                        $promotion = null;
//                    }
//                    $item['promotion'] = $promotion;
//                    $item['is_like'] = isset($arrProductLike[$item['product_id']]) ? 1 : 0;
//
//                    $dataProductAttached [] = $item;
//                }
//            }
//            $info['product_attached'] = $dataProductAttached;
            //Danh sách hình ảnh kèm theo
            $listImage = $mProductImage->getImageChild($info['product_code']);
            $arrListImage = [];
            if (count($listImage) > 0) {
                foreach ($listImage as $v) {
                    $arrListImage[] = $v;
                }
            }
            $info['list_image'] = $arrListImage;
            //Option thuộc tính sản phẩm
            $arrAttribute = [];
            $optionAttr = $mProduct->getOptionAttribute($info['product_parent_id']);
            if (count($optionAttr) > 0) {
                foreach ($optionAttr as $v) {
                    $arrAttribute [] = $v;
                }
            }
            $info['attribute'] = $arrAttribute;
            //list bài viết liên quan
//            $info['newsRelated'] = $mNew->getListByProduct([$input['product_id']], $lang);


            return $info;
        } catch (\Exception | QueryException $exception) {
            throw new ProductRepoException(ProductRepoException::GET_PRODUCT_DETAIL_FAILED, $exception->getMessage() . ' FILE : ' . $exception->getFile() . ' - LINE : ' . $exception->getLine());
        }
    }

    /**
     * Lấy danh sách sản phẩm home page
     *
     * @return mixed|void
     * @throws ProductRepoException
     */
    public function getProductHome()
    {
        try {
            $mProductImage = app()->get(ProductImageTable::class);
            //Lấy ds loại sản phẩm
            $mCategory = app()->get(ProductCategoryTable::class);
            $productCategory = $mCategory->getOption()->toArray();
            //Lấy tất cả sản phẩm
            $mProduct = app()->get(ProductChildTable::class);
            //Lấy sản phẩm đã like
            $mProductFavourite = app()->get(ProductFavouriteTable::class);
            $getLikeALl = $mProductFavourite->getLikeAll(Auth()->id());
            $arrProductLike = [];
            if (count($getLikeALl) > 0) {
                foreach ($getLikeALl as $v) {
                    $arrProductLike[$v['product_id']] = $v['id'];
                }
            }

            $product = $mProduct->getProductHome();
            $dataProduct = [];
            foreach ($product as $item) {
                if ($item['is_sales'] == 0) {
                    $item['old_price'] = null;
                    $item['new_price'] = intval($item['new_price']);
                } else {
                    $item['old_price'] = intval($item['new_price']);
                    $item['new_price'] = round(($item['new_price'] / 100) * (100 - $item['percent_sale']));
                }
                $item['is_like'] = isset($arrProductLike[$item['product_id']]) ? 1 : 0;
                //Lấy avatar product child
                $imageChild = $mProductImage->getAvatar($item['product_code']);

                if ($imageChild != null) {
                    $item['avatar'] = $imageChild['image'];
                }
                $dataProduct [] = $item;
            }
            $grouped = collect($dataProduct)->groupBy('product_category_id')->toArray();
            //Data trả về cho app
            $data = [];
            foreach ($productCategory as $item) {
                $productChild = [];
                $limit = 5;
                if (isset($grouped[$item['product_category_id']]) && count($grouped[$item['product_category_id']]) > 0) {
                    $productChild = array_slice($grouped[$item['product_category_id']], 0, $limit);
                }

                $data [] = [
                    'product_category_id' => $item['product_category_id'],
                    'category_name' => $item['category_name'],
                    'product' => $productChild
                ];
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ProductRepoException(ProductRepoException::GET_PRODUCT_HOME_FAILED);
        }
    }

    /**
     * Từ khóa hot
     *
     * @return mixed|void
     * @throws ProductRepoException
     */
    public function hotKeyword()
    {
        try {
            //Lấy từ khóa hot
            $mConfig = app()->get(ConfigTable::class);
            $hotKeyword = $mConfig->getConfig('hot_search');
            $value = explode(";", $hotKeyword['value']);
            $data = [];
            foreach ($value as $item) {
                if ($item != "") {
                    $data [] = [
                        'keyword' => $item
                    ];
                }
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ProductRepoException(ProductRepoException::GET_HOT_KEYWORD_FAILED);
        }
    }

    /**
     * Like hoặc unlike sản phẩm
     *
     * @param $input
     * @return mixed|void
     * @throws ProductRepoException
     */
    public function likeUnlike($input)
    {
        try {
            $mProductFavourite = app()->get(ProductFavouriteTable::class);

            if ($input['type'] == 'like') {
                //Kiểm tra sản phẩm đã like chưa
                $check = $mProductFavourite->checkFavourite($input['product_id'], Auth()->id());
                if ($check == null) {
                    //Like sản phẩm
                    $mProductFavourite->like([
                        'product_id' => $input['product_id'],
                        'user_id' => Auth()->id()
                    ]);
                }
            } else if ($input['type'] == 'unlike') {
                //UnLike sản phẩm
                $mProductFavourite->unlike($input['product_id'], Auth()->id());
            }
        } catch (\Exception | QueryException $exception) {
            throw new ProductRepoException(ProductRepoException::LIKE_UNLIKE_PRODUCT_FAILED);
        }
    }

    /**
     * Lấy danh sách sản phẩm yêu thích
     *
     * @param $input
     * @return mixed|void
     * @throws ProductRepoException
     */
    public function getListProductLike($input)
    {
        try {
            $mProductFavourite = app()->get(ProductFavouriteTable::class);
            $mProductImage = app()->get(ProductImageTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            $data = $this->toPagingData($mProductFavourite->getListProductLike($input, Auth()->id()));

            $dataItem = $data['Items'];

            if (count($data) > 0) {
                unset($data['Items']);
            }

            foreach ($dataItem as $item) {
                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth()->id(), 'app', null, $item['product_id']);
                $promotion = [];
                if (isset($getPromotion) && $getPromotion != null) {
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

                $data['Items'] [] = $item;
            }

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ProductRepoException(ProductRepoException::GET_LIST_PRODUCT_LIKE_FAILED, $exception->getMessage());
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
                'image' => 'https://kbeauty.fpt.edu.vn/wp-content/uploads/2018/04/Banner-Spa.png'
            ],
            [
                'id' => 2,
                'image' => 'https://phuntheuthammy.vn/wp-content/uploads/2018/06/banner-spa-specials.jpg'
            ]
        ];
    }

    public function getGeneralInfo()
    {
        try {
            $mProduct = app()->get(ProductChildTable::class);
            $mProductImage = app()->get(ProductImageTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            // Banner
            $banner = $this->getListBanner();
            $data['banner'] = $banner;

            // San pham moi
            $data['new'] = $mProduct->getAllProduct('new');

            foreach ($data['new'] as $item) {
                $item['old_price'] = null;
                $item['new_price'] = floatval($item['new_price']);
                // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);
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
                //Lấy avatar product child
                $imageChild = $mProductImage->getAvatar($item['product_code']);

                if ($imageChild != null) {
                    $item['avatar'] = $imageChild['image'];
                }
                $item['is_new'] = 1;
            }

            // San pham khuyen mai
            $data['promotion'] = [];
            $count = 0;
            $listProduct = $mProduct->getAllProduct('all');
            // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
            // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
            foreach ($listProduct as $item) {
                $item['new_price'] = floatval($item['new_price']);
                //Lấy avatar product child
                $imageChild = $mProductImage->getAvatar($item['product_code']);

                if ($imageChild != null) {
                    $item['avatar'] = $imageChild['image'];
                }

                $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth::id(), 'app', null, $item['product_id']);

                $item['old_price'] = $item['new_price'];
                $promotion = [];
                if (isset($getPromotion) && $getPromotion != null) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                        // Tinh phan tram
                        if ($getPromotion['price'] < $item['new_price']) {
                            $percent = $getPromotion['price'] / $item['new_price'] * 100;
                            $promotion['price'] = (100 - round($percent, 2)) . '%';
                            // Tính lại giá khi có khuyến mãi
                            $item['old_price'] = floatval($item['new_price']);
                            $item['new_price'] = $getPromotion['price'];
                        }
                    }
                    if (isset($getPromotion['gift'])) {
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

            }
            return $data;

        } catch (\Exception | QueryException $exception) {
            throw new ProductRepoException(ProductRepoException::GET_GENERAL_INFO_FAILED, $exception->getMessage());
        }
    }

    /**
     * Un active sp không có image
     *
     * @return mixed|void
     * @throws ProductRepoException
     */
    public function unActiveProduct()
    {
        try {
            $mProductChild = app()->get(ProductChildTable::class);

            $getProduct = $mProductChild->getProductNotImage();

            foreach ($getProduct as $v) {
                $mProductChild->edit([
                    "is_actived" => 0
                ], $v['product_code']);
            }

        } catch (\Exception | QueryException $exception) {
            throw new ProductRepoException(ProductRepoException::UN_ACTIVE_PRODUCT_FAILED, $exception->getMessage());
        }
    }

    /**
     * Scan sản phẩm
     *
     * @param $input
     * @param $lang
     * @return mixed|void
     * @throws ProductRepoException
     */
    public function scanProduct($input, $lang)
    {
        try {
            //Lấy sản phẩm đã like
            $mProductFavourite = app()->get(ProductFavouriteTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            $getLikeALl = $mProductFavourite->getLikeAll(Auth()->id());
            $arrProductLike = [];
            if (count($getLikeALl) > 0) {
                foreach ($getLikeALl as $v) {
                    $arrProductLike[$v['product_id']] = $v['id'];
                }
            }

            $mProduct = app()->get(ProductChildTable::class);
            $mProductDetail = app()->get(ProductTable::class);
            $mNew = app()->get(NewTable::class);
            $mProductModel = app()->get(ProductModel::class);
            $mProductSupplier = app()->get(SuppliersTable::class);
            $mRatingLog = app()->get(RatingLogTable::class);
            $mProductImage = app()->get(ProductImageTable::class);

            $info = $mProduct->scanProduct($input['product_code']);

            if ($info == null) {
                throw new ProductRepoException(ProductRepoException::SCAN_PRODUCT_FAILED, __('Không tìm thấy thông tin sản phẩm'));
            }

            $info['old_price'] = null;
            $info['new_price'] = floatval($info['new_price']);
            $info['is_new'] = 1;

            //Lấy avatar product child
            $imageChild = $mProductImage->getAvatar($info['product_code']);

            if ($imageChild != null) {
                $info['avatar'] = $imageChild['image'];
                $info['description_image'] = $imageChild['image'];
            } else {
                $info['description_image'] = $info['avatar'];
            }

            $getPromotion = $mHome->getPromotionDetail('product', $info['product_code'], Auth()->id(), 'app', null, $info['product_id']);
            $promotion = [];

            if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                    // Tinh phan tram
                    if ($getPromotion['price'] < $info['new_price']) {
                        $percent = $getPromotion['price'] / $info['new_price'] * 100;
                        $promotion['price'] = (100 - round($percent, 2)) . '%';
                        // Tính lại giá khi có khuyến mãi
                        $info['old_price'] = $info['new_price'];
                        $info['new_price'] = ($info['new_price'] * $percent) / 100;
                    }
                }
                if ($getPromotion['gift'] != null) {
                    $promotion['gift'] = $getPromotion['gift'];
                }
                $info['is_new'] = 0;
            }
            if (empty($promotion)) {
                $promotion = null;
            }
            $info['promotion'] = $promotion;

            //thông tin chi tiết sản phẩm (doanh mục, nhà cung cấp, thương hiệu, xuất xứ )
            $dataProductDetail = $mProductDetail->getByID($info['product_id']);
            $info['product_model_name'] = null;
            $info['made_in'] = null;
            $info['supplier_name'] = null;
            if ($dataProductDetail != null) {
                if ($dataProductDetail['product_model_id'] != null) {
                    $dataProductModel = $mProductModel->getBYID($dataProductDetail['product_model_id']);
                    $info['product_model_name'] = $dataProductModel['product_model_name'];//nhãn hiệu detail sp
                    $info['made_in'] = 'Việt Nam'; // xuất xứ
                }
                if ($dataProductDetail['supplier_id'] != null) {
                    $dataProductSupplier = $mProductSupplier->getBYID($dataProductDetail['supplier_id']);
                    $info['supplier_name'] = $dataProductSupplier['supplier_name'];//nhãn hiệu detail sp
                }
            }

            //Kiểm tra user đã đánh giá sản phẩm này chưa
            $info['is_review'] = 0;
            $mRatingLog = app()->get(RatingLogTable::class);
            $log = $mRatingLog->getLogByUser("product", $info['product_id'], Auth()->id());
            if ($log != null) {
                $info['is_review'] = 1;
            }
            //Lấy 3 sản phẩm theo loại sản phẩm
            $productAttached = $mProduct->getProductByCategory($info['product_category_id'], $info['product_id']);
            $dataProductAttached = [];
            if (count($productAttached) > 0) {
                foreach ($productAttached as $item) {
                    $item['old_price'] = null;
                    $item['new_price'] = floatval($item['new_price']);
                    $getPromotion = $mHome->getPromotionDetail('product', $item['product_code'], Auth()->id(), 'app', null, $item['product_id']);
                    // Nếu không có promotion thì giá cũ là null, giá mới là giá chi nhánh
                    // Nếu có promotion thì giá cũ là giá chi nhánh, giá mới là giá đã khuyến mãi
                    $promotion = [];
                    if (isset($getPromotion) && $getPromotion != null) {
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
                    $item['is_like'] = isset($arrProductLike[$item['product_id']]) ? 1 : 0;
                    $dataProductAttached [] = $item;
                }
            }
            $info['product_attached'] = $dataProductAttached;
            //Danh sách hình ảnh kèm theo
            $listImage = $mProductImage->getImageChild($info['product_code']);
            $arrListImage = [];
            if (count($listImage) > 0) {
                foreach ($listImage as $v) {
                    $arrListImage[] = $v;
                }
            }
            $info['list_image'] = $arrListImage;
            //Sản phẩm đã được thích chưa
            $info['is_like'] = isset($arrProductLike[$info['product_id']]) ? 1 : 0;
            //Option thuộc tính sản phẩm
            $arrAttribute = [];
            $optionAttr = $mProduct->getOptionAttribute($info['product_parent_id']);
            if (count($optionAttr) > 0) {
                foreach ($optionAttr as $v) {
                    $arrAttribute [] = $v;
                }
            }
            $info['attribute'] = $arrAttribute;
            // Đánh giá sản phẩm
            $mRatingLog = app()->get(RatingLogTable::class);
            $info['rating'] = [];
            $temp = []; // chứa các thành phần của rating
            //Total đánh giá sản phẩm
            $totalRating = $mRatingLog->countRating('product', $info['product_id']);
            $temp['total_rating'] = $totalRating;
            //Điểm đánh giá trung bình
            $avg = $mRatingLog->avgRating('product', $info['product_id']);
            $temp['avg_rating'] = $avg != null ? floatval($avg['rating_avg']) : 0;
            //Danh sach total rating
            $listTotalRating = $mRatingLog->getListTotalRating('product', $info['product_id']);
            if (empty($listTotalRating) || count($listTotalRating) <= 0) {
                $listTotalRating = null;
            }
            $temp['rating_1'] = 0;
            $temp['rating_2'] = 0;
            $temp['rating_3'] = 0;
            $temp['rating_4'] = 0;
            $temp['rating_5'] = 0;
            if ($listTotalRating != null) {
                foreach ($listTotalRating as $key => $item) {
                    $i = $item['rating_value'];
                    $temp['rating_' . $i] = $item['amount'];
                }
            }
            //Danh sach rating
            $listRating = $mRatingLog->getListRating('product', $info['product_id']);
            if (empty($listRating) || count($listRating) <= 0) {
                $listRating = null;
            }
            $temp['ratings'] = $listRating;
            $info['rating'] = $temp;
            //list bài viết liên quan
            $info['newsRelated'] = $mNew->getListByProduct([$info['product_id']], $lang);
            //list câu hỏi câu trả lời
            //Du liêu câu hỏi tạm thời
            $mQuestion = app()->get(FeedbackQuestionTable::class);
            $info['question_answer'] = $mQuestion->getQuestionObject("product", $info['product_code'])->toArray();
            //Du liêu câu hỏi tạm thời

            // ghi log
            $mCustomerPotentialLog = new CustomerPotentialLogTable();
            $dataLog = [
                'customer_id' => Auth()->id(),
                'type' => 'product',
                'obj_id' => $info['product_id'],
                'obj_code' => $info['product_code'],
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ];
            $mCustomerPotentialLog->add($dataLog);

            return $info;
        } catch (\Exception | QueryException $e) {
            throw new ProductRepoException(ProductRepoException::SCAN_PRODUCT_FAILED, $e->getMessage());
        }
    }
}

