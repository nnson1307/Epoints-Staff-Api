<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/15/2020
 * Time: 11:08 AM
 */

namespace Modules\Product\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttributeGroupsTable extends Model
{
    const NOT_DELETE = 0;
    const IS_ACTIVE = 1;
    protected $table = "product_attribute_groups";
    protected $primaryKey = "product_attribute_groups_id";
    protected $fillable = [
        "product_attribute_group_id",
        "product_attribute_group_name",
        "is_deleted",
        "is_actived"
    ];
    /**
     * Get Product
     *
     * @param
     * @return mixed
     */
    public function getAll()
    {
        $data =  $this
            ->select(
                "{$this->table}.product_attribute_group_id",
                "{$this->table}.product_attribute_group_name"
            )
            ->where("{$this->table}.is_actived", self::IS_ACTIVE)
            ->where("{$this->table}.is_deleted",self::NOT_DELETE)
            ->get();
        return $data;
    }



}