<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 23/08/2021
 * Time: 14:37
 */

namespace Modules\Ticket\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class ContractTable extends Model
{
    use ListTableTrait;
    protected $table = "contracts";
    protected $primaryKey = "contract_id";
    protected $fillable = [
        "contract_id",
        "contract_category_id",
        "contract_name",
        "contract_code",
        "contract_no",
        "contract_form",
        "sign_date",
        "performer_by",
        "effective_date",
        "expired_date",
        "warranty_start_date",
        "warranty_end_date",
        "content",
        "note",
        "status_code",
        "is_value_goods",
        "is_renew",
        "number_day_renew",
        "is_created_ticket",
        "status_code_created_ticket",
        "ticket_code",
        "reason_remove",
        "is_deleted",
        "custom_1",
        "custom_2",
        "custom_3",
        "custom_4",
        "custom_5",
        "custom_6",
        "custom_7",
        "custom_8",
        "custom_9",
        "custom_10",
        "custom_11",
        "custom_12",
        "custom_13",
        "custom_14",
        "custom_15",
        "custom_16",
        "custom_17",
        "custom_18",
        "custom_19",
        "custom_20",
        "created_by",
        "updated_by",
        "created_at",
        "updated_at",
        "is_browse"
    ];

    const NOT_DELETED = 0;

    const STATUS_CANCEL = 'cancel';
    const STATUS_LIQUIDATED = 'liquidated';
    const STATUS_PROCESSING = 'processing';
    const STATUS_DRAFT = 'draft';
    const STATUS_PENDING = 'pending';

    

     /**
     * Cập nhật HĐ
     *
     * @param array $data
     * @param $contractId
     * @return mixed
     */
    public function edit(array $data, $contractId)
    {
        return $this->where("contract_id", $contractId)->update($data);
    }
}