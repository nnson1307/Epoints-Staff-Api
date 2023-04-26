<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-06
 * Time: 2:09 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ProvinceTable extends Model
{
    protected $table = 'province';
    protected $primaryKey = 'provinceid';

    /**
     * Lấy option tỉnh thành của chi nhánh
     *
     * @param $provinceBranch
     * @return mixed
     */
    public function getProvinces($provinceBranch)
    {
        return $this
            ->select(
                'provinceid',
                'name'
            )
            ->whereIn("provinceid", $provinceBranch)
            ->get();
    }

    /**
     * Lấy option tỉnh thành full
     *
     * @return mixed
     */
    public function getProvinceFull()
    {
        return $this
            ->select(
                'provinceid',
                'name'
            )
            ->get();
    }
}