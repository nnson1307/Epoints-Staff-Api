<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;


class CustomerTable extends Model
{
    protected $table = "customers";
    protected $primaryKey = "customer_id";

    public function getCustomer($filter = [])
    {

        $mSelect = $this
            ->select(
                "{$this->table}.customer_id",
                "{$this->table}.full_name",
                "{$this->table}.gender",
                "{$this->table}.phone1 as phone",
                "{$this->table}.email",
                "{$this->table}.customer_type"
            );
        if (isset($filter['customer_type']) && $filter['customer_type'] != null) {
            $mSelect->where("{$this->table}.customer_type", $filter['customer_type']);
        }
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $mSelect->where(function ($query) use ($search) {
                $query->where("{$this->table}.full_name", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.email", '%' . $search . '%')
                    ->orWhere("{$this->table}.phone1", 'like', '%' . $search . '%');
            });
        }
        $page = (int)($filter["page"] ?? 1);
        return $mSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);


    }

    public function getCustomerAll($filter = [])
    {
        $mSelect = $this
            ->select(
                "{$this->table}.customer_id",
                "{$this->table}.full_name as customer_name",
                "{$this->table}.customer_avatar as customer_avatar",
                "{$this->table}.gender",
                "{$this->table}.phone1 as phone",
                "{$this->table}.email",
                "{$this->table}.customer_type"
            );
        if (isset($filter['arrIdCustomer']) && $filter['arrIdCustomer'] != '' && $filter['arrIdCustomer'] != null) {
            $mSelect = $mSelect->whereIn("{$this->table}.customer_id", $filter['arrIdCustomer']);
        }
        if (isset($filter['customer_id']) && $filter['customer_id'] != '' && $filter['customer_id'] != null) {
            $mSelect = $mSelect->where("{$this->table}.customer_id", $filter['customer_id']);
        }
        return $mSelect->get()->toArray();
    }
}