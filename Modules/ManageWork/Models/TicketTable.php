<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 03/10/2022
 * Time: 14:30
 */

namespace Modules\ManageWork\Models;


use Illuminate\Database\Eloquent\Model;

class TicketTable extends Model
{
    protected $table = "ticket";
    protected $primaryKey = "ticket_id";

    /**
     * Lấy thông tin ticket
     *
     * @param $ticketId
     * @return mixed
     */
    public function getInfo($ticketId)
    {
        return $this
            ->select(
                "ticket_id",
                "ticket_code",
                "title"
            )
            ->where("ticket_id", $ticketId)
            ->first();
    }
}