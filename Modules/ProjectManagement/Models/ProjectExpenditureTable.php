<?php
namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;


class ProjectExpenditureTable extends Model
{
protected $table = "manage_project_expenditure";
protected $primaryKey = "manage_project_expenditure_id";
    protected $casts = [
        'total_money' => 'float',
        'total_amount' => 'float',
    ];


    public function getExpenditureReceipt($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_expenditure_id as expenditure_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.type",
                "{$this->table}.obj_id as receipt_id",
                "{$this->table}.obj_code as receipt_code",
                "receipts.total_money"
            )
        ->leftJoin("receipts", "{$this->table}.obj_id","receipts.receipt_id")
        ->where("receipts.status","paid")
        ->where( "{$this->table}.type","receipt");
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        return $mSelect->get()->toArray();
    }
    public function getExpenditurePayment($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_expenditure_id as expenditure_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.type",
                "{$this->table}.obj_id as payment_id",
                "{$this->table}.obj_code as payment_code",
                "payments.total_amount"
            )
            ->leftJoin("payments", "{$this->table}.obj_id","payments.payment_id")
            ->where("payments.status","paid")
            ->where("{$this->table}.type","payment");
        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        return $mSelect->get()->toArray();
    }
    public function getListExpenditure($filter = []){
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_expenditure_id as expenditure_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.type",
                "{$this->table}.obj_id",
                "{$this->table}.obj_code"
            );

        if(isset($filter['manage_project_id']) && $filter['manage_project_id'] != null){
            $mSelect->where("{$this->table}.manage_project_id",$filter['manage_project_id']);
        }
        if(isset($filter['receipt_payment_type']) && $filter['receipt_payment_type'] != null){
            $mSelect->where("{$this->table}.type",$filter['receipt_payment_type']);
        }
        return $mSelect->get()->toArray();
    }
}