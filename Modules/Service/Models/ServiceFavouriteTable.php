<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 9/16/2020
 * Time: 5:46 PM
 */

namespace Modules\Service\Models;


use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
     * Like dịch vụ
     *
     * @param array $data
     * @return mixed
     */
    public function like(array $data)
    {
        return $this->create($data);
    }

    /**
     * Unlike dịch vụ
     *
     * @param $serviceCode
     * @param $customerId
     * @return mixed
     */
    public function unlike($serviceCode, $customerId)
    {
        return $this->where("service_code", $serviceCode)->where("customer_id", $customerId)->delete();
    }

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

    /**
     * Danh sách dịch vụ yêu thích
     *
     * @param $filter
     * @param $customerId
     * @return mixed
     */
    public function getListFavourite($filter, $customerId)
    {
        $imageDefault = 'http://' . request()->getHttpHost() . '/static/images/service.png';

        $ds = $this
            ->select(
                "branches.branch_name as branch_name",
                "services.service_id as service_id",
                "services.service_name",
                "services.service_code",
                "service_branch_prices.old_price",
                "service_branch_prices.new_price",
                DB::raw("(CASE
                    WHEN  services.service_avatar = '' THEN '$imageDefault'
                    WHEN  services.service_avatar IS NULL THEN '$imageDefault'
                    ELSE  services.service_avatar 
                    END
                ) as service_avatar"),
                "services.detail_description",
                "services.description",
                "services.time",
                "service_categories.name as category_name"
            )
            ->join("services", "services.service_code", "=", "{$this->table}.service_code")
            ->join("service_categories", "service_categories.service_category_id", "=", "services.service_category_id")
            ->join("service_branch_prices", "service_branch_prices.service_id", "=", "services.service_id")
            ->join("branches", "branches.branch_id", "=", "service_branch_prices.branch_id")
            ->where("services.is_actived", self::IS_ACTIVE)
            ->where("services.is_deleted", self::NOT_DELETE)
            ->where("service_categories.is_deleted", self::NOT_DELETE)
            ->where("service_categories.is_actived", self::IS_ACTIVE)
            ->where("service_branch_prices.is_deleted", self::NOT_DELETE)
            ->where("service_branch_prices.is_actived", self::IS_ACTIVE)
            ->where("branches.is_representative", self::IS_REPRESENTATIVE)
            ->where("{$this->table}.customer_id", $customerId)
            ->orderBy("{$this->table}.id", "desc");

        // get số trang
        $page = (int)($filter['page'] ?? 1);

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }
}