<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/09/2022
 * Time: 10:17
 */

namespace Modules\Warranty\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceTable extends Model
{
    protected $table = 'services';
    protected $primaryKey = 'service_id';

    /**
     * Lấy thông tin dịch vụ
     *
     * @param $serviceCode
     * @return mixed
     */
    public function getService($serviceCode)
    {
        return $this
            ->select(
                "service_id",
                "service_name",
                "price_standard"
            )
            ->where("service_code", $serviceCode)
            ->first();
    }
}