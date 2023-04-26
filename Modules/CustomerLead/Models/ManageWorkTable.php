<?php

namespace Modules\CustomerLead\Models;

use Illuminate\Database\Eloquent\Model;

class ManageWorkTable extends Model
{
    protected $table = "manage_work";
    protected $primaryKey = "manage_work_id";

    const NOT_DELETED = 0;

    public  function getWorkLead($id){
        $mSelect = $this
            -> select(
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_work_customer_type",
                "{$this->table}.manage_status_id",
                "{$this->table}.manage_type_work_id",
                "{$this->table}.manage_work_title"
            )
            ->where("{$this->table}.customer_id",$id)
            ->where("{$this->table}.manage_status_id",'<>', 6)
            ->where("{$this->table}.manage_work_customer_type","lead");
        return $mSelect->get();

    }
    public  function getNumberOfAppointmentLead($id){
        $mSelect = $this
            -> select(
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_work_customer_type",
                "{$this->table}.manage_status_id",
                "{$this->table}.manage_type_work_id",
                "{$this->table}.manage_work_title"
            )
            ->where("{$this->table}.customer_id",$id)
            ->where("{$this->table}.manage_type_work_id", 4)
            ->where("{$this->table}.manage_work_customer_type","lead");
        return $mSelect->get();

    }
    public function saveWork($data){
        return $this->insertGetid($data);
    }
    public  function getWorkDeal($id){
        $mSelect = $this
            -> select(
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_work_customer_type",
                "{$this->table}.manage_type_work_id",
                "{$this->table}.manage_work_title"
            )
            ->where("{$this->table}.customer_id",$id)
            ->where("{$this->table}.manage_status_id",'<>', 6)
            ->where("{$this->table}.manage_work_customer_type","deal");
        return $mSelect->get();

    }
    public  function getNumberOfAppointmentDeal($id){
        $mSelect = $this
            -> select(
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_work_customer_type",
                "{$this->table}.manage_status_id",
                "{$this->table}.manage_type_work_id",
                "{$this->table}.manage_work_title"
            )
            ->where("{$this->table}.customer_id",$id)
            ->where("{$this->table}.manage_type_work_id", 4)
            ->where("{$this->table}.manage_work_customer_type","deal");
        return $mSelect->get();

    }
    public function getClosestInteraction($id){
        $mSelect = $this
            -> select(
                "{$this->table}.updated_at"
            )
            ->where("{$this->table}.customer_id",$id)
            ->orderBy("{$this->table}.updated_at" ,'desc');
        return $mSelect->first();
    }
    public function getCare($id,$type){
        $mSelect = $this
            -> select(
                "{$this->table}.manage_work_id",
                "{$this->table}.manage_work_code",
                "{$this->table}.manage_work_customer_type",
                "{$this->table}.manage_project_id",
                "manage_project.manage_project_name",
                "{$this->table}.manage_type_work_id",
                "manage_type_work.manage_type_work_key",
                "manage_type_work.manage_type_work_name",
                "manage_type_work.manage_type_work_icon",
                "{$this->table}.created_at",
                "{$this->table}.manage_work_title",
                "{$this->table}.date_start",
                "{$this->table}.date_end",
                "{$this->table}.date_finish",
                "{$this->table}.processor_id",
                "staffs.full_name as staff_full_name",
                "staffs.staff_avatar",
                "{$this->table}.manage_status_id",
                "manage_status.manage_status_name",
                "manage_status.manage_status_color"
            )
            ->leftJoin("manage_type_work","{$this->table}.manage_type_work_id","manage_type_work.manage_type_work_id")
            ->leftJoin("staffs","{$this->table}.processor_id","staffs.staff_id")
            ->leftJoin("manage_project","{$this->table}.manage_project_id","manage_project.manage_project_id")
            ->leftJoin("manage_status","{$this->table}.manage_status_id","manage_status.manage_status_id")
//            ->where("{$this->table}.obj_id",$id)
            ->where("{$this->table}.customer_id",$id)
//            ->where("{$this->table}.manage_status_id", '<>' , 6)
            ->where("{$this->table}.manage_work_customer_type",$type)
        ->orderBy("{$this->table}.updated_at", 'desc');
        return $mSelect->get();
    }
//    public function getCareHistory($id,$type){
//        $mSelect = $this
//            -> select(
//                "{$this->table}.manage_work_id",
//                "{$this->table}.manage_work_code",
//                "{$this->table}.manage_work_customer_type",
//                "{$this->table}.manage_project_id",
//                "manage_project.manage_project_name",
//                "{$this->table}.manage_type_work_id",
//                "manage_type_work.manage_type_work_key",
//                "manage_type_work.manage_type_work_name",
//                "manage_type_work.manage_type_work_icon",
//                "{$this->table}.created_at",
//                "{$this->table}.manage_work_title",
//                "{$this->table}.date_start",
//                "{$this->table}.date_end",
//                "{$this->table}.date_finish",
//                "{$this->table}.processor_id",
//                "staffs.full_name as staff_full_name",
//                "staffs.staff_avatar",
//                "{$this->table}.manage_status_id",
//                "manage_status.manage_status_name",
//                "manage_status.manage_status_color"
//            )
//            ->leftJoin("manage_type_work","{$this->table}.manage_type_work_id","manage_type_work.manage_type_work_id")
//            ->leftJoin("staffs","{$this->table}.processor_id","staffs.staff_id")
//            ->leftJoin("manage_project","{$this->table}.manage_project_id","manage_project.manage_project_id")
//            ->leftJoin("manage_status","{$this->table}.manage_status_id","manage_status.manage_status_id")
////            ->where("{$this->table}.obj_id",$id)
//            ->where("{$this->table}.customer_id",$id)
//            ->where("{$this->table}.manage_status_id", 6)
//            ->where("{$this->table}.manage_work_customer_type",$type)
//            ->orderBy("{$this->table}.updated_at", 'desc');
//        return $mSelect->get();
//    }
}