<?php


namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class ConfigTable extends Model
{
    protected $table = "config";
    protected $primaryKey = "config_id";
    protected $fillable
        = [
            'config_id', 'key', 'value', 'name'
        ];

    public function getAll()
    {
        return $this->select('config_id', 'key', 'value', 'name')->get();
    }
    /**
     * Edit
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('config_id', $id)->update($data);
    }

    /**
     * @param $key
     * @return mixed
     */
    public function getInfoByKey($key)
    {
        return $this->where('key', $key)->first();
    }

    public function getInfoById($id)
    {
        return $this->where('config_id', $id)->first();
    }
}