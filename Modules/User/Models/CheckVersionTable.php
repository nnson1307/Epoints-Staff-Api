<?php
namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class CheckVersionTable
 * @package Modules\User\Models
 * @author DaiDP
 * @since Aug, 2019
 */
class CheckVersionTable extends Model
{
    protected $table = 'check_version';


    /**
     * Lấy version mới nhất
     *
     * @param $platform
     * @return mixed
     */
    public function getLastestVersion($platform)
    {
        return $this->where('platform', $platform)
                    ->orderBy($this->primaryKey, 'DESC')
                    ->first();
    }
}
