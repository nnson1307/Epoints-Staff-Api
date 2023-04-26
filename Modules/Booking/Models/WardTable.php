<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/08/2022
 * Time: 11:17
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class WardTable extends Model
{
    protected $table = "ward";
    protected $primaryKey = "ward_id";

    /**
     * Lấy Option phường/xã
     *
     * @param $districtId
     * @return mixed
     */
    public function getDistricts($districtId)
    {
        return $this
            ->select(
                'ward_id',
                'name'
            )
            ->where('district_id', $districtId)
            ->get();
    }
}