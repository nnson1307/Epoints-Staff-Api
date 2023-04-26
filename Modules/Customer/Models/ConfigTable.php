<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/10/2021
 * Time: 15:23
 */

namespace Modules\Customer\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigTable extends Model
{
    protected $table = "config";
    protected $primaryKey = "config_id";
    public $timestamps = false;
    protected $fillable
        = [
            'config_id', 'key', 'value', 'name', 'is_show', 'type'
        ];

    /**
     * @param $key
     * @return mixed
     */
    public function getInfoByKey($key)
    {
        return $this->where('key', $key)->first();
    }
}