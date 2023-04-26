<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-03-20
 * Time: 3:53 PM
 * @author SonDepTrai
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class MemberLevelTable extends Model
{
    protected $table = 'member_levels';
    protected $primaryKey = 'member_level_id';

    /**
     * Lấy thông tin cấp độ thành viên
     *
     * @param $levelId
     * @return mixed
     */
    public function getInfo($levelId)
    {
        return $this
            ->select(
                "member_level_id",
                "name",
                "slug",
                "code",
                "point",
                "discount"
            )
            ->where("member_level_id", $levelId)
            ->where("is_actived", 1)
            ->where("is_deleted", 0)
            ->first();
    }
}