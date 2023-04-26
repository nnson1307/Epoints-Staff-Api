<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:15 PM
 */

namespace Modules\Service\Models;


use Illuminate\Database\Eloquent\Model;

class ServiceCategoryTable extends Model
{
    protected $table = "service_categories";
    protected $primaryKey = "service_category_id";
    protected $fillable = [
        "service_category_id",
        "name",
        "slug",
        "description",
        "is_actived",
        "is_deleted",
        "updated_at",
        "created_at",
        "created_by",
        "updated_by"
    ];

    const IS_ACTIVE = 1;
    const NOT_DELETE = 0;

    /**
     * Danh sách loại dịch vụ
     *
     * @param $filter
     * @return mixed
     */
    public function getServiceCategories($filter)
    {
        $ds = $this
            ->select(
                "service_category_id",
                "name",
                "description",
                "is_actived"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE);

        // get số trang
        $page = (int)($filter['page'] ?? 1);

        // filter service_category_name
        if (isset($filter['service_category_name']) && $filter['service_category_name'] != null) {
            $ds->where("name", 'like', '%' . $filter['service_category_name'] . '%');
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Lấy option loại dịch vụ
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "service_category_id",
                "name",
                "description"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }
}