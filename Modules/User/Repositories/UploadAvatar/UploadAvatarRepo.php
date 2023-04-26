<?php

namespace Modules\User\Repositories\UploadAvatar;

use App\Auth\MyAuthNotFoundException;
use App\Jobs\SendNotification;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Database\QueryException;
use Modules\User\Models\StaffTable;
use Modules\User\Models\UserCarrierTable;
use Modules\User\Repositories\User\UserRepoException;

use Modules\User\Models\OrderImageTable;




/**
 * Interface UploadAvatarRepoInterface
 * @package Modules\User\Repositories\UploadAvatar
 * @author todh
 * @since April, 2023
 */

class UploadAvatarRepo implements UploadAvatarRepoInterface
{
    /**
     * upload avatar by links from app
     *
     * @param array $all
     * @return mixed
     * @throws UploadAvatarRepoException
     */
    public function uploadAvatarByAppLinks(array $all)
    {
        try {
            $mOrderImage = app()->get(OrderImageTable::class);
            $mStaffsTable= app()->get(StaffTable::class);

            if(isset($all['action'])){

                if ($all['action'] == 'staff_avatar') {
                    if(!isset($all['staff_avatar']) || $all['staff_avatar'] == null || $all['staff_avatar'] == ''){
                        throw new UploadAvatarRepoException(__('Vui lòng chọn ảnh'));
                    }
                    $mStaffsTable->edit([
                        'staff_avatar' => isset($all['staff_avatar']) ? $all['staff_avatar'] : null,
                        'updated_at' => Carbon::now()
                    ], Auth()->id());

                    return [
                        'error' => false,
                        'message' => __('Thêm ảnh thành công')
                    ];

                }elseif($all['action'] == 'order_image'){


                    if(!isset($all['order_code']) || $all['order_code'] == null || $all['order_code'] == ''){
                         throw new UploadAvatarRepoException(__('Mã đơn hàng không được trống.'));
                     }

                    if(!isset($all['brand_code']) || $all['brand_code'] == null || $all['brand_code'] == ''){
                        throw new UploadAvatarRepoException(__('Brand code là thông tin bắt buộc'));
                    }

                    if(!isset($all['order_image']) || $all['order_image'] == null || $all['order_image'] == ''){
                        throw new UploadAvatarRepoException(__('Vui lòng chọn ảnh đơn hàng'));
                    }

                    if(isset($all['type']) || $all['type'] == null || $all['type'] == ''){
                        if(in_array($all['type'],['before','after']) == false){
                            throw new UploadAvatarRepoException( __('Type không đúng định dạng, phải là trước hoặc sau.'));
                        }
                    }
                    //Insert order image
                    $mOrderImage->add([
                        'order_code' => $all['order_code'] ?? null,
                        'type' => $all['type'],
                        'link' => $all['order_image'],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id()
                    ]);
                    return [
                        'error' => false,
                        'message' => __('Thêm ảnh thành công')
                    ];

                }
            }
        } catch (\Exception  $ex) {
            throw new UploadAvatarRepoException('',UploadAvatarRepoException::GET_UPLOAD_FILE_FAILED);
        }
    }
}
