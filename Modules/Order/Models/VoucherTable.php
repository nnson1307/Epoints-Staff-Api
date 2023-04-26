<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class VoucherTable extends Model
{
    protected $table = 'vouchers';
    protected $primaryKey = 'voucher_id';
    protected $casts = [
        'cash' => 'float',
        'max_price' => 'float',
        'required_price' => 'float'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_SPECIAL = 1;
    /**
     * Lấy chi tiết voucher
     *
     * @param $code
     * @return mixed
     */
    public function getInfo($code)
    {
        return $this->select(
            "voucher_id",
            "code",
            "is_all",
            "type",
            "percent",
            "cash",
            "max_price",
            "required_price",
            "object_type",
            "object_type_id",
            "expire_date",
            "branch_id",
            "quota",
            "total_use",
            "sale_special",
            "voucher_title",
            "voucher_img as image",
            "description",
            "detail_description",
            "member_level_apply",
            "created_at as start_date",
            "point"
        )
            ->where("code", $code)
            ->where("type_using", "public")
            ->where("is_actived", 1)
            ->where("is_deleted", 0)
            ->first();
    }

    public function getItemByCode($code)
    {
        $oSelect = $this
            ->select("voucher_id",
                "code", "is_all",
                "type", "percent",
                "cash", "max_price",
                "required_price", "object_type",
                "object_type_id", "expire_date",
                "quota", "total_use", "is_actived", "branch_id", "total_use")
            ->where("code", $code)
            ->where("is_deleted", 0);

        return $oSelect->first();
    }

    public function editVoucherOrder($data, $code)
    {
        return $this->where("code", $code)->update($data);
    }

    /**
     * Lấy thông tin voucher
     *
     * @param $voucherCode
     * @param $memberLevelId
     * @return mixed
     */
    public function getVoucher($voucherCode, $memberLevelId)
    {
        return $this
            ->select(
                "voucher_id",
                "voucher_title",
                "code",
                "is_all",
                "type",
                "percent",
                "cash",
                "max_price",
                "required_price",
                "object_type",
                "object_type_id",
                "expire_date",
                "branch_id",
                "quota",
                "total_use",
                "voucher_img as image",
                "created_at as start_date",
                "point",
                "customer_group_apply"
            )
            ->where("code", $voucherCode)
            ->where(function ($query) use ($memberLevelId) {
                $query->where("member_level_apply", "all")
                    ->orWhereNull("member_level_apply")
                    ->orWhereIn("member_level_apply", $memberLevelId);
            })
            ->where("is_actived", 1)
            ->where("is_deleted", 0)
//            ->where("type_using", "public")
            ->first();
    }
}