<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 08-04-02020
 * Time: 9:16 AM
 */

namespace Modules\Product\Models;


use Illuminate\Database\Eloquent\Model;

class ProductCategoryTable extends Model
{
    protected $table = "product_categories";
    protected $primaryKey = "product_category_id";
    protected $fillable = [
        "product_category_id",
        "category_name",
        "description",
        "is_actived",
        "is_deleted",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "slug",
        "category_uuid"
    ];

    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;

    /**
     * Danh sách loại sản phẩm
     *
     * @param $filter
     * @return mixed
     */
    public function getServiceCategories($filter)
    {
        $ds = $this
            ->select(
                "product_category_id",
                "category_name",
                "description",
                "is_actived"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE);

        // get số trang
        $page = (int)($filter['page'] ?? 1);

        // filter service_category_name
        if (isset($filter['category_name']) && $filter['category_name'] != null) {
            $ds->where("category_name", 'like', '%' . $filter['category_name'] . '%');
        }

        return $ds->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Option loại sản phẩm
     *
     * @return mixed
     */
    public function getOption()
    {
        return $this
            ->select(
                "product_category_id",
                "category_name"
            )
            ->where("is_actived", self::IS_ACTIVE)
            ->where("is_deleted", self::NOT_DELETE)
            ->get();
    }

    /**
     * Lấy thông tin loại sản phẩm bằng uuid
     *
     * @param $categoryUuid
     * @return mixed
     */
    public function getCategoryByUuid($categoryUuid)
    {
        return $this
            ->select(
                "product_category_id",
                "category_name",
                "category_uuid",
                "description"
            )
            ->where("is_deleted", self::NOT_DELETE)
            ->where("category_uuid", $categoryUuid)
            ->first();
    }

    /**
     * Thêm loại sản phẩm
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data)->product_category_id;
    }
}