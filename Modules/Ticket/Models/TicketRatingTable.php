<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\Ticket\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class TicketRatingTable extends Model
{
    protected $table = "ticket_rating";
    protected $primaryKey = "ticket_rating_id";

//    Lấy thông tin đánh giá
    public function getRating($ticketId){
        return $this
            ->select(
                $this->table.'.ticket_rating_id',
                $this->table.'.point',
                $this->table.'.description',
                'staffs.full_name as customer_rating',
                $this->table.'.created_at as rating_date'
            )
            ->join('staffs','staffs.staff_id',$this->table.'.created_by')
            ->where($this->table.'.ticket_id',$ticketId)
            ->first();
    }
}