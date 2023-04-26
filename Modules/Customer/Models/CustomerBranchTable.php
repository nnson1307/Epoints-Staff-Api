<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 21/10/2021
 * Time: 10:21
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerBranchTable extends Model
{
    protected $table = "customer_branch";
    protected $primaryKey = "customer_branch_id";
    protected $fillable = [
        "customer_branch_id",
        "customer_id",
        "branch_id",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm chi nhánh của khách hàng
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->customer_branch_id;
    }

    /**
     * Lấy thông tin chi nhánh của khách hàng
     *
     * @param $customerId
     * @param $branchId
     * @return mixed
     */
    public function getBranchByCustomer($customerId, $branchId)
    {
        return $this
            ->select(
                "customer_branch_id",
                "customer_id",
                "branch_id"
            )
            ->where("customer_id", $customerId)
            ->where("branch_id", $branchId)
            ->first();
    }

    /**
     * Lấy tất cả chi nhánh của khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function getAllBranchCustomer($customerId)
    {
        return $this
            ->select(
                "customer_branch_id",
                "customer_id",
                "branch_id"
            )
            ->where("customer_id", $customerId)
            ->get();
    }

    /**
     * Xoá tất cả chi nhánh của khách hàng
     *
     * @param $customerId
     * @return mixed
     */
    public function removeBranchCustomer($customerId)
    {
        return $this->where("customer_id", $customerId)->delete();
    }
}