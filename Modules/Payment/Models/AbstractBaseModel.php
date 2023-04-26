<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Models;


use Illuminate\Database\Eloquent\Model;

abstract class AbstractBaseModel extends Model
{
    public function insertGetItemId($data){
        return $this->insertGetId($data);
    }

    public function insertMultiItem($data){
        return $this->insert($data);
    }

    public function updateItem($id, $data){
        return $this->where($this->primaryKey, $id)->update($data);
    }
}
