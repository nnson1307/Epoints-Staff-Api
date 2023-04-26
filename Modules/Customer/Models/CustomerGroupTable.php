<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 11/05/2021
 * Time: 09:57
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerGroupTable extends Model
{
    protected $table = "customer_groups";
    protected $primaryKey = "customer_group_id";

    const IS_ACTIVE = 1;
    const NOT_DELETED = 0;

    /**
     * Lấy option nhóm khách hàng
     *
     * @return mixed
     */
    public function getCustomerGroup()
    {
        return $this
            ->select(
                "customer_group_id",
                "group_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETED)
            ->get();
    }
}