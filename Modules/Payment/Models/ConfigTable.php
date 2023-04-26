<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Models;


class ConfigTable extends AbstractBaseModel
{
    protected $table = 'config';
    protected $primaryKey = 'config_id';

    public function getConfig(){
        return $this->get()->pluck('value','key')->toArray();
    }
}
