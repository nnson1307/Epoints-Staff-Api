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

class BrandRegisterTable extends Model
{
    protected $table = "piospa_brand_register";
    protected $primaryKey = "brand_register_id";

    protected $fillable = [
        'brand_name',
        'brand_code',
        'full_name',
        'email',
        'phone',
        'status',
        'created_at',
        'updated_at',
    ];

    /**
     * Lấy thông tin cộng tác viên
     * @param $phone
     * @param $brand_name
     * @return mixed
     */
    public function getBrandRegister($phone, $brand_name)
    {
        $brand_name = strtolower($brand_name);
        $select = $this->where("status", "<>", BrandRegisterStatus::REJECT_STATUS)
                       ->where("phone", $phone)
                       ->whereRaw("LOWER(brand_name) = '{$brand_name}'");
        return $select->first();
    }

    /**
     * Tạo mới cộng tác viên
     * @param array $array
     * @return mixed
     */
    public function createBrandRegister(array $array)
    {
        return $this::create($array);
    }
}