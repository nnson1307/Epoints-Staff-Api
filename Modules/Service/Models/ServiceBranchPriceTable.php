<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-18
 * Time: 9:55 AM
 * @author SonDepTrai
 */

namespace Modules\Service\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceBranchPriceTable extends Model
{
    protected $table = 'service_branch_prices';
    protected $primaryKey = 'service_branch_price_id';

    protected $casts = [
        "old_price" => 'float',
        "new_price" => 'float'
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;
    const IS_REPRESENTATIVE = 1;
    const SURCHARGE = 0;

    /**
     * Lấy danh sách dịch vụ
     *
     * @param $filter
     * @return mixed
     */
    public function getServices($filter)
    {
        $ds = $this
            ->select(
                "branches.branch_name as branch_name",
                "{$this->table}.service_id as service_id",
                "services.service_name",
                "services.service_code",
                "services.service_avatar",
                "{$this->table}.old_price",
                "{$this->table}.new_price",
                "services.detail_description",
                "services.description",
                "services.time",
                "service_categories.name as category_name"
            )
            ->join("services", "services.service_id", "=", "{$this->table}.service_id")
            ->join("service_categories", "service_categories.service_category_id", "=", "services.service_category_id")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("services.is_actived", self::IS_ACTIVE)
            ->where("services.is_deleted", self::NOT_DELETE)
            ->where("services.is_surcharge", self::SURCHARGE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("service_categories.is_deleted", self::NOT_DELETE)
            ->where("service_categories.is_actived", self::IS_ACTIVE);
        // get số trang
        $page = (int)($filter['page'] ?? 1);

        // filter branch
        if (isset($filter['branch_id']) && $filter['branch_id'] > 0) {
            $ds->where("{$this->table}.branch_id", $filter['branch_id']);
        }

        // filter service name
        if (isset($filter['service_name']) && $filter['service_name'] != null) {
            $ds->where("services.service_name", 'like', '%' . $filter['service_name'] . '%');
        }

        // filter service category
        if (isset($filter["service_category_id"]) && $filter["service_category_id"] != null) {
            $ds->where("services.service_category_id", "like", "%" . $filter["service_category_id"] . "%");
        }

        // filter sort
        if (isset($filter["sort_type"]) && $filter["sort_type"] != null) {
            if ($filter["sort_type"] == "price") {
                $ds->orderBy("new_price", "asc");
            } else if ($filter["sort_type"] == "time") {
                $ds->orderBy("services.time", "asc");
            }
        } else {
            $ds->orderBy("services.service_id", "desc");
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Danh sách dịch vụ theo chi nhánh chính
     *
     * @param $filter
     * @return mixed
     */
    public function getServiceRepresentative($filter)
    {
        $ds = $this
            ->select(
                "branches.branch_name as branch_name",
                "{$this->table}.service_id as service_id",
                "services.service_name",
                "services.service_code",
                "services.service_avatar",
                "{$this->table}.old_price",
                "{$this->table}.new_price",
                "services.detail_description",
                "services.description",
                "services.time",
                "service_categories.name as category_name"
            )
            ->join("services", "services.service_id", "=", "{$this->table}.service_id")
            ->join("service_categories", "service_categories.service_category_id", "=", "services.service_category_id")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("services.is_actived", self::IS_ACTIVE)
            ->where("services.is_deleted", self::NOT_DELETE)
            ->where("services.is_surcharge", self::SURCHARGE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("branches.is_representative", self::IS_REPRESENTATIVE)
            ->where("service_categories.is_deleted", self::NOT_DELETE)
            ->where("service_categories.is_actived", self::IS_ACTIVE)
            ->where("branches.is_actived", self::IS_ACTIVE)
            ->where("branches.is_deleted", self::NOT_DELETE);
        // get số trang
        $page = (int)($filter['page'] ?? 1);

        // filter service name
        if (isset($filter['service_name']) && $filter['service_name'] != null) {
            $ds->where("services.service_name", 'like', '%' . $filter['service_name'] . '%');
        }

        // filter service category
        if (isset($filter["service_category_id"]) && $filter["service_category_id"] != null) {
            $ds->where("services.service_category_id", "like", "%" . $filter["service_category_id"] . "%");
        }

        // filter sort
        if (isset($filter["sort_type"]) && $filter["sort_type"] != null) {
            if ($filter["sort_type"] == "price") {
                $ds->orderBy("new_price", "asc");
            } else if ($filter["sort_type"] == "time") {
                $ds->orderBy("services.time", "asc");
            }
        } else {
            $ds->orderBy("services.service_id", "desc");
        }
        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Chi tiết dịch vụ
     *
     * @param $serviceId
     * @return mixed
     */
    public function getDetail($serviceId)
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
            ->where("services.is_surcharge", self::SURCHARGE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.service_id", $serviceId)
            ->first();
    }

    /**
     * Lay danh sach dich vu theo category
     *
     * @param $idCatService
     * @param $idService
     * @return mixed
     */
    public function getServiceByCategory($categoryServiceId, $serviceId)
    {
        $ds = $this
            ->select(
                "{$this->table}.service_id as service_id",
//                "{$this->table}.old_price",
                "{$this->table}.new_price",
                "services.service_name",
                "services.service_avatar",
                "services.time",
                "services.service_code"
            )
            ->join("services", "services.service_id", "=", "{$this->table}.service_id")
            ->join("service_categories", "service_categories.service_category_id", "=", "services.service_category_id")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("services.is_actived", self::IS_ACTIVE)
            ->where("services.is_deleted", self::NOT_DELETE)
            ->where("services.is_surcharge", self::SURCHARGE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("service_categories.is_deleted", self::NOT_DELETE)
            ->where("service_categories.is_actived", self::IS_ACTIVE)
            ->where("branches.is_representative", self::IS_REPRESENTATIVE)
            ->where("service_categories.service_category_id", $categoryServiceId)
            ->where("{$this->table}.service_id","<>",$serviceId);
        return $ds->limit(5)->get();
    }


    /**
     * Danh sách tất cả dịch vụ theo chi nhánh chính
     *
     * @return mixed
     */
    public function getAllServiceByRepresentative()
    {
        $ds = $this
            ->select(
                "branches.branch_name as branch_name",
                "{$this->table}.service_id as service_id",
                "services.service_name",
                "services.service_code",
                "services.service_avatar",
                "{$this->table}.old_price",
                "{$this->table}.new_price",
                "services.detail_description",
                "services.description",
                "services.time",
                "service_categories.name as category_name"
            )
            ->join("services", "services.service_id", "=", "{$this->table}.service_id")
            ->join("service_categories", "service_categories.service_category_id", "=", "services.service_category_id")
            ->join("branches", "branches.branch_id", "=", "{$this->table}.branch_id")
            ->where("services.is_actived", self::IS_ACTIVE)
            ->where("services.is_deleted", self::NOT_DELETE)
            ->where("services.is_surcharge", self::SURCHARGE)
            ->where("{$this->table}.is_deleted", self::NOT_DELETE)
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("branches.is_representative", self::IS_REPRESENTATIVE)
            ->where("service_categories.is_deleted", self::NOT_DELETE)
            ->where("service_categories.is_actived", self::IS_ACTIVE);

        return $ds->get();
    }
}