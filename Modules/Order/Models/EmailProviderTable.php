<?php


namespace Modules\Order\Models;


use Illuminate\Database\Eloquent\Model;

class EmailProviderTable extends Model
{
    protected $table = 'email_provider';
    protected $primaryKey = 'id';

    /**
     * Lấy thông tin email provider
     *
     * @param $id
     * @return mixed
     */
    public function getProvider($id)
    {
        return $this
            ->select(
                "type",
                "name_email",
                "email",
                "password",
                "is_actived",
                "email_template_id"
            )
            ->where("id", $id)
            ->first();
    }
}