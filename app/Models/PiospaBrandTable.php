<?php


namespace App\Models;


use Illuminate\Database\Eloquent\Model;

class PiospaBrandTable extends Model
{
    protected $connection = "mysql2";
    protected $table = "piospa_brand";
    protected $primaryKey = "brand_id";

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Lấy thông tin brand
     *
     * @param $brandCode
     * @return mixed
     */
    public function getBrand($brandCode)
    {
        return $this
            ->select(
                "brand_id",
                "parent_id",
                "tenant_id",
                "brand_name",
                "brand_code",
                "brand_url",
                "brand_avatar",
                "brand_banner",
                "brand_about",
                "brand_contr",
                "company_name",
                "company_code",
                "position",
                "display_name"
            )
            ->where("is_activated", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->where("brand_code", $brandCode)
            ->first();
    }
}