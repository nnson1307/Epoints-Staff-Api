<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-20
 * Time: 3:16 PM
 * @author SonDepTrai
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class SpaInfoTable extends Model
{
    protected $table = 'spa_info';
    protected $primaryKey = 'id';

    /**
     * Lấy thông tin cấu hình spa
     *
     * @param $id
     * @return mixed
     */
    public function getInfo($id)
    {
        return $this
            ->select(
                "id",
                "name",
                "code",
                "phone",
                "email",
                "hot_line",
                "provinceid",
                "districtid",
                "address",
                "slogan",
                "bussiness_id",
                "logo",
                "is_actived",
                "fanpage",
                "zalo",
                "instagram_page",
                "website",
                "tax_code",
                "is_part_paid",
                "introduction",
                "branch_apply_order",
                "total_booking_time"
            )
            ->where('id', $id)
            ->first();
    }
}