<?php


namespace Modules\Product\Models;


use Illuminate\Database\Eloquent\Model;

class SuppliersTable extends Model
{
    protected $table = "suppliers";
    protected $primaryKey = "supplier_id";
    /**
     * Get Product
     *
     * @param $productId
     * @return mixed
     */
    public function getByID($productId)
    {
        $data =  $this
            ->select(
                "{$this->table}.supplier_id",
                "{$this->table}.supplier_name"
            )
            ->where("supplier_id", $productId)
            ->where("is_deleted", 0)
            ->first();
        return $data;
    }
}