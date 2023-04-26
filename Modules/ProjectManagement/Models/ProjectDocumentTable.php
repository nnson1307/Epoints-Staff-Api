<?php

namespace Modules\ProjectManagement\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;


class ProjectDocumentTable extends Model
{
    protected $table = "manage_project_document";
    protected $primaryKey = "manage_project_document_id";


    public function AddDocument($dataDocument)
    {
        return $this
            ->insertGetId($dataDocument);
    }

    public function deleteDocument($id)
    {
        return $this
            ->where("{$this->table}.manage_project_document_id", $id)
            ->delete();
    }

    public function getNumberDocument($filter = [])
    {
        $mSelect = $this
            ->select("{$this->table}.manage_project_id", DB::raw('count(*) as total'))
            ->groupBy('manage_project_id');

        if (isset($filter['arrIdProject']) && $filter['arrIdProject'] != '' && $filter['arrIdProject'] != null) {
            $mSelect = $mSelect->whereIn("{$this->table}.manage_project_id", $filter['arrIdProject']);
        }
        if (isset($filter['manage_project_id']) && $filter['manage_project_id'] != '' && $filter['manage_project_id'] != null) {
            $mSelect = $mSelect->where("{$this->table}.manage_project_id", $filter['manage_project_id']);
        }
        return $mSelect->get()->toArray();
    }

    public function getListDocument($input)
    {
        $mSelect = $this
            ->select(
                "{$this->table}.manage_project_document_id as document_id",
                "{$this->table}.manage_project_id",
                "{$this->table}.type",
                "{$this->table}.file_name as document_name",
                "{$this->table}.path",
                "{$this->table}.created_by",
                "staffs.full_name as creator",
                "{$this->table}.created_at",
                "{$this->table}.updated_at"
            )
            ->leftJoin("staffs", "{$this->table}.created_by", "staffs.staff_id")
            ->orderBy($this->table . '.created_at', 'desc');
        if (isset($input['search']) != "") {
            $search = $input['search'];
            $mSelect->where(function ($query) use ($search) {
                $query->where("manage_project_document.file_name", 'like', '%' . $search . '%')
                    ->orWhere("manage_project_document.created_at", 'like', '%' . $search . '%')
                    ->orWhere("{$this->table}.manager_id", 'like', '%' . $search . '%');
            });
        }
        if (isset($input['manage_project_id']) && $input['manage_project_id'] != null) {
            $mSelect->where("{$this->table}.manage_project_id", $input['manage_project_id']);
        }
        if (isset($input['document_id']) && $input['document_id'] != null) {
            $mSelect->where("{$this->table}.manage_project_document_id", $input['document_id']);
        }
        if (isset($input['file_name']) && $input['file_name'] != null) {
            $mSelect->where("manage_project_document.file_name", 'like', '%' . $input['file_name'] . '%');
        }
        if (isset($input['type']) && $input['type'] != null) {
            $mSelect->where("{$this->table}.type", $input['type']);
        }
        if (isset($input["updated_at"]) && $input["updated_at"] != null) {
            $arr_filter_create = explode(" - ", $input["updated_at"]);
            $a = strtotime(Carbon::createFromFormat('d/m/Y', $arr_filter_create[0])->format('Y-m-d'));
            $b = strtotime(Carbon::createFromFormat('d/m/Y', $arr_filter_create[1])->format('Y-m-d'));
            $c = abs($b - $a);
            $d = floor($c / (60 * 60 * 24));
            if ($d < 7) {
                $startCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[0])->format("Y-m-d");
                $endCreateTime = Carbon::createFromFormat("d/m/Y", $arr_filter_create[1])->format("Y-m-d");
                $mSelect->whereBetween("{$this->table}.updated_at", [$startCreateTime . " 00:00:00", $endCreateTime . " 23:59:59"]);
            }
        }
        if (isset($input['creator']) && $input['creator'] != null) {
            $mSelect->where("{$this->table}.created_by", $input['creator']);
        }
        $page = (int)($input["page"] ?? 1);
        return $mSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
    }

}