<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-06
 * Time: 2:10 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DistrictTable extends Model
{
    protected $table = 'district';
    protected $primaryKey = 'districtid';

    /**
     * Lấy Option Quận Huyện
     *
     * @param $province_id
     * @return mixed
     */
    public function getDistricts($province_id)
    {
        return $this
            ->select(
                'districtid',
                'name'
            )
            ->where('provinceid', $province_id)
            ->get();
    }
}