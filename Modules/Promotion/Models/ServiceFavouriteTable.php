<?php


namespace Modules\Promotion\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceFavouriteTable extends Model
{
    protected $table = "service_favourite";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "service_code",
        "customer_id",
        "created_at",
        "updated_at"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_REPRESENTATIVE = 1;

    /**
     * Kiểm tra dịch vụ đã like chưa
     *
     * @param $serviceCode
     * @param $userId
     * @return mixed
     */
    public function checkFavourite($serviceCode, $userId)
    {
        return $this
            ->where("service_code", $serviceCode)
            ->where("customer_id", $userId)
            ->first();
    }
}