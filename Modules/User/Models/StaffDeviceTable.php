<?php

/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 06-04-02020
 * Time: 6:02 PM
 */

namespace Modules\User\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StaffDeviceTable extends Model
{
    public $timestamps = false;
    protected $table = "staff_device";
    protected $primaryKey = "staff_device_id";
    protected $fillable = [
        "staff_device_id",
        "staff_id",
        "imei",
        "model",
        "platform",
        "os_version",
        "app_version",
        "token",
        "date_created",
        "last_access",
        "date_modified",
        "modified_by",
        "created_by",
        "endpoint_arn",
        "is_actived",
        "is_deleted",
    ];

    /**
     * Thêm customer device
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_device_id;
    }

    /**
     * Kiểm tra thông tin imei
     *
     * @param $staffId
     * @param $imei
     * @return mixed
     */
    public function checkImei($staffId, $imei)
    {
        return $this
            ->where('staff_id', $staffId)
            ->where('imei', $imei)
            ->where('is_actived', 1)
            ->first();
    }

    /**
     * Cập nhật login
     *
     * @param $id
     * @return mixed
     */
    public function updateAccess($id, $device_token = null, $enpointArn = null)
    {
        $data = ['last_access' => Carbon::now()];

        if ($device_token) {
            $data['token'] = $device_token;
        }

        if ($enpointArn) {
            $data['endpoint_arn'] = $enpointArn;
        }

        return $this->where($this->primaryKey, $id)
            ->update($data);
    }

    /**
     * Cập nhật thông tin thiết bị
     *
     * @param $imei
     * @param $data
     */
    public function edit($imei, $data)
    {
        $this->where('imei', $imei)
            ->update($data);
    }

    /**
     * Xóa thông tin theo imei
     * @param $imei
     * @return mixed
     */
    public function removeByImei($imei)
    {
        return $this->where('imei', $imei)->delete();
    }

    /**
     * Xoá device của nhân viên
     *
     * @param $staffDeviceId
     * @return mixed
     */
    public function removeById($staffDeviceId)
    {
        return $this->where('staff_device_id', $staffDeviceId)->delete();
    }

    public function deleteDevice($staffId)
    {
        $this->where('staff_id', $staffId)
            ->update(['is_deleted' => 1]);
    }
}
