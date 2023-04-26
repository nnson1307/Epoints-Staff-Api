<?php


namespace Modules\Service\Models;


use Illuminate\Database\Eloquent\Model;

class CustomerPotentialLogTable extends Model
{
    protected $table = 'customer_potential_log';
    protected $primaryKey = 'customer_potential_log_id';
    protected $fillable = [
        'customer_potential_log_id',
        'customer_id',
        'type',
        'obj_id',
        'obj_code',
        'created_at'
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