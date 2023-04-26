<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 17/06/2021
 * Time: 17:17
 */

namespace Modules\Brand\Models;


use Illuminate\Database\Eloquent\Model;
use Modules\Brand\Enum\BrandRegisterStatus;

class BrandTable extends Model
{
    protected $table = "piospa_brand";
    protected $primaryKey = "brand_id";

    protected $fillable = [
        'tenant_id',
        'brand_name',
        'brand_code',
        'brand_url',
        'brand_avatar',
        'brand_banner',
        'brand_about',
        'brand_domain',
        'hotline',
        'company_name',
        'company_code',
        'position',
        'display_name',
        'is_published',
        'is_activated',
        'is_deleted',
        'created_at',
        'updated_at	',
        'created_by',
        'updated_by',
        'sns_firebase_key',
        'sns_p12',
        'sns_success',
        'brand_s3_contr',
        'brand_api_url',
        'brand_noti_url',
        'brand_favicon',
        'client_key',
        'brand_customer_code',
    ];


    /**
     * Scan thông tin cộng tác viên
     *
     * @param $brand_customer_code
     */
    public function scanCode($brand_customer_code)
    {
        $select = $this->select(
                            "brand_id",
                            "brand_name",
                            "brand_code",
                            "client_key"
                        )
                       ->where("brand_customer_code", $brand_customer_code);

        return $select->first();
    }
}