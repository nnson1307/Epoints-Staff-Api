<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 5/11/2020
 * Time: 10:14 AM
 */

namespace Modules\Booking\Models;


use Illuminate\Database\Eloquent\Model;

class BranchTable extends Model
{
    protected $table = "branches";
    protected $primaryKey = "branch_id";

    /**
     * Lấy tỉnh thành chi nhánh
     *
     * @return mixed
     */
    public function getProvinceBranch()
    {
        return $this
            ->select(
                "provinceid"
            )
            ->groupBy("provinceid")
            ->get();
    }
}