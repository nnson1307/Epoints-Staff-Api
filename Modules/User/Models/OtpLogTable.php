<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-26
 * Time: 5:46 PM
 * @author SonDepTrai
 */

namespace Modules\User\Models;


use Illuminate\Database\Eloquent\Model;

class OtpLogTable extends Model
{
    protected $table = 'otp_log';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id',
        'brandname',
        'telco',
        'customer_id',
        'phone',
        'message',
        'otp',
        'otp_type',
        'otp_expired',
        'is_actived',
        'is_sent',
        'time_send',
        'created_at',
        'updated_at'
    ];

    /**
     * Thêm otp log
     *
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->id;
    }

    /**
     * Cập nhật otp log
     *
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where("id", $id)->update($data);
    }

    /**
     * Cập nhật trạng thái otp cũ theo type
     *
     * @param $userId
     * @param $type
     * @return mixed
     */
    public function updateStatusOtpOld($userId, $type)
    {
        return $this->where("customer_id", $userId)->where("otp_type", $type)->update(["is_actived" => 1]);
    }

    /**
     * Lấy code type
     *
     * @param $userId
     * @param $type
     * @param $code
     * @return mixed
     */
    public function getOtp($userId, $type, $code)
    {
        return $this
            ->where('otp', $code)
            ->where('otp_type', $type)
            ->where('customer_id', $userId)
            ->where('is_actived', 0)
//            ->where('is_sent', 1)
            ->first();
    }

}