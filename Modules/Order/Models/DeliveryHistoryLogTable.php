<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 25/05/2021
 * Time: 15:03
 */

namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class DeliveryHistoryLogTable extends Model
{
    protected $table = "delivery_history_log";
    protected $primaryKey = "id";
    protected $fillable = [
        "id",
        "delivery_history_id",
        "status",
        "created_by",
        "created_type",
        "created_at",
        "updated_at"
    ];

    /**
     * Thêm log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        return $this->create($data);
    }
}