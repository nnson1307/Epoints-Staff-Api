<?php


namespace Modules\Promotion\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceBranchPriceTable extends Model
{
    protected $table = 'service_branch_prices';
    protected $primaryKey = 'service_branch_price_id';

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_REPRESENTATIVE = 1;

    /**
     * Chi tiết dịch vụ
     *
     * @param $serviceCode
     * @return mixed
     */
    public function getDetail($serviceCode)
    {
        return $this
            ->select(
                "branches.branch_name as branch_name",
                "{$this->table}.branch_id",
                "{$this->table}.service_id as service_id",
                "services.service_name",
                "services.service_code",
                "services.service_avatar",
                "{$this->table}.old_price",
                "{$this->table}.new_price",
                "services.detail_description",
                "services.description",
                "services.time",
                "services.service_category_id"
            )
            ->join("services", "services.service_id", "=", "{$this->table}.service_id")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("services.is_actived", self::IS_ACTIVE)
            ->where("services.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("services.service_code", $serviceCode)
            ->first();
    }
}