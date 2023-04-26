<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:37
 */

namespace Modules\ManageWork\Models;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageProjectTable extends Model
{
    protected $table = "manage_project";
    protected $primaryKey = "manage_project_id";

    /**
     * Tạo dự án
     */
    public function createdProject($data){
        return $this->insertGetId($data);
    }

    public function getListAll($data){

        $oSelect = $this
            ->select('manage_project.manage_project_id','manage_project.manage_project_name');
//        ->leftJoin("manage_project_status_config_map", "{$this->table}.manage_project_status_id",
//            "manage_project_status_config_map.manage_project_status_id")
//        ->leftJoin("manage_project_status_config", "manage_project_status_config_map.manage_project_status_config_id",
//            "manage_project_status_config.manage_project_status_config_id");

//        ->where("manage_project_status_config.manage_project_status_group_config_id","<>",3)
//        ->where("manage_project_status_config.manage_project_status_group_config_id","<>",4 );
        if (isset($data['manage_project_id'])){
            $oSelect = $oSelect->where('manage_project_id',$data['manage_project_id']);
        }

        if (isset($data['manage_project_name'])){
            $oSelect = $oSelect->where('manage_project_id','like','%'.$data['manage_project_name'].'%');
        }

        $oSelect = $oSelect
            ->groupBy($this->table.'.manage_project_id')
            ->orderBy('manage_project.created_at','DESC')
            ->get();

        return $oSelect;
    }

    public function getDetailProject($manage_project_id){
        return $this->where('manage_project_id',$manage_project_id)->first();
    }
    public function getDate($id){
        $oSelect = $this
            ->select(
                "{$this->table}.date_start",
                "{$this->table}.date_end"
            )
            ->where("{$this->table}.manage_project_id",$id);
        return $oSelect->first()->toArray();
    }

//    Lấy danh sách theo dự án mới
    public function getListAllNew($data){

        $oSelect = $this
            ->select('manage_project.manage_project_id','manage_project.manage_project_name')
            ->leftJoin("manage_project_status_config_map", "{$this->table}.manage_project_status_id",
                "manage_project_status_config_map.manage_project_status_id")
            ->leftJoin("manage_project_status_config", "manage_project_status_config_map.manage_project_status_config_id",
                "manage_project_status_config.manage_project_status_config_id")
            ->leftJoin('manage_project_staff','manage_project_staff.manage_project_id',$this->table.'.manage_project_id');
        if (isset($data['manage_project_id'])){
            $oSelect = $oSelect->where('manage_project_id',$data['manage_project_id']);
        }

        if (isset($data['manage_project_name'])){
            $oSelect = $oSelect->where('manage_project_id','like','%'.$data['manage_project_name'].'%');
        }

        if (isset($data['show_program']) && $data['show_program'] != 'show_all' ) {
            $oSelect->where("manage_project_status_config.manage_project_status_group_config_id","<>",3)
                ->where("manage_project_status_config.manage_project_status_group_config_id","<>",4 );
        }

        if (isset($data['show_program']) && $data['show_program'] == 'not_show_all'){
            $showProgram = $data['show_program'];
//            $oSelect = $oSelect->where('manage_projsuect_staff.staff_id',Auth::id());
            $oSelect = $oSelect->where(function ($query) use ($showProgram){
                if ($showProgram == 'not_show_all'){
                    $query->where(function ($query1) use ($showProgram){
                            $query1
                                ->where('manage_project_staff.staff_id',Auth::id());
//                                ->where($this->table.'.permission','private');
                        });
                } else if ($showProgram == 'show_all') {
                    $query->where($this->table.'.permission','public')
                        ->orWhere(function ($query1) use ($showProgram){
                            $query1
                                ->where('manage_project_staff.staff_id',Auth::id())
                                ->where($this->table.'.permission','private');
                        });
                }

            });
        }

        $oSelect = $oSelect
            ->groupBy($this->table.'.manage_project_id')
            ->orderBy('manage_project.created_at','DESC')
            ->get();

        return $oSelect;
    }

    /**
     * Lấy chi tiết
     * @param $idProject
     */
    public function getPrefix($idProject){

    }
}