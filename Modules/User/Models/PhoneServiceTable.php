<?php
namespace Modules\User\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class PhoneServiceTable
 * @package Modules\User\Models
 * @author DaiDP
 * @since Aug, 2019
 */
class PhoneServiceTable extends Model
{
    protected $table = 'phone_service';

    /**
     * Lấy thông tin đầu số dịch vụ
     *
     * @param $serviceNum
     * @return mixed
     */
    public function getServiceInfo($serviceNum)
    {
        return $this->where('service_num', $serviceNum)
                    ->first();
    }

    /**
     * Lấy danh sách đầu số
     *
     * @return array
     */
    public function getServiceNumList()
    {
        $rs = $this->select('service_num')
                   ->orderBy('service_num')
                   ->get();

        $data = [];
        foreach ($rs as $item) {
            $data[] = $item->service_num;
        }

        return $data;
    }
}