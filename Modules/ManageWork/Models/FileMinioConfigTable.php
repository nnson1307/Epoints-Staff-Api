<?php
/**
 * Created by PhpStorm.
 * User: LE DANG SINH
 * Date: 9/26/2018
 * Time: 4:31 PM
 */

namespace Modules\ManageWork\Models;
use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class FileMinioConfigTable extends Model
{
    use ListTableTrait;
    protected $table = "file_minio_config";
    protected $primaryKey = "id";

    /**
     * lấy Id cuối cùng
     * @return mixed
     */
    public function getLastConfig(){
        return $this
            ->orderBy('id','DESC')
            ->first();
    }
}