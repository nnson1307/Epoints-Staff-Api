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


class ManageWorkTable extends Model
{
    protected $table = "manage_work";
    protected $primaryKey = "manage_work_id";
    public $timestamps = false;

    protected $casts = [
        "create_object_id" => 'int'
    ];

    const APPROVE = 3;

    /**
     * Tạo công việc
     */
    public function createdWork($data)
    {
        return $this->insertGetId($data);
    }

    /**
     * Chỉnh sửa công việc
     * @param $data
     * @param $id
     */
    public function editWork($data, $id)
    {
        if (isset($data['manage_status_id']) && $data['manage_status_id'] == 6 && !isset($data['date_finish'])) {
            $data['date_finish'] = Carbon::now();
        }

        return $this->where('manage_work_id', $id)->update($data);
    }

    public function editParentWork($data, $parent_id)
    {
        if (isset($data['manage_status_id']) && $data['manage_status_id'] == 6 && !isset($data['date_finish'])) {
            $data['date_finish'] = Carbon::now();
        }

        return $this->where('parent_id', $parent_id)->update($data);
    }

    public function getTotalHome($data)
    {
        $staffId = Auth::id();
        $oSelect = $this
            ->select(
                DB::raw('SUM(IF((manage_work.date_start IS NULL OR manage_work.date_start < NOW()) AND manage_work.date_end > NOW() AND manage_work.manage_status_id NOT IN (6,7) , 1 , 0)) as total_work_day'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id IN (1,2,5,6) , 1 , 0)) as total_work'),
                DB::raw('SUM(IF(manage_work.manage_status_id IN (1,2,3,4,5,6,7) , 1 , 0)) as total_work'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 1 , 1 , 0)) as total_not_started_yet'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 2 , 1 , 0)) as total_started'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 6 , 1 , 0)) as total_complete'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 5 , 1 , 0)) as total_unfinished'),
                //                DB::raw('SUM(IF(manage_work.date_end < NOW() AND (manage_work.manage_status_id IN (1,2,5) ) , 1 , 0)) as total_overdue')
                DB::raw('SUM(IF(manage_work.date_end < NOW() AND (manage_work.manage_status_id NOT IN (6,7) ) , 1 , 0)) as total_overdue')
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($staffId) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $staffId);
            });

        if (!isset($data['job_overview'])) {
            $oSelect = $oSelect->where(function ($sql) use ($staffId) {
                $sql->where($this->table . '.processor_id', $staffId);
                //                    ->orWhere($this->table.'.assignor_id',$staffId)
                //                    ->orWhere($this->table.'.approve_id',$staffId)
                //                    ->orWhere('manage_work_support.staff_id',$staffId);
            });
        }

        //        $oSelect = $oSelect->where($this->table.'.processor_id',$staffId);


        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = Carbon::createFromFormat('Y/m/d', $data['from_date'])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $data['to_date'])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        } else {
            //            $oSelect = $oSelect
            //                ->where('manage_work.date_start','<=', Carbon::now())
            //                ->where('manage_work.date_end','>=', Carbon::now());
        }

        //        Tìm kiếm chi nhánh
        if (isset($data['branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $data['branch_id']);
        }

        //        Tìm kiếm phòng ban
        if (isset($data['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $data['department_id']);
        }

        //        Tìm kiếm dự án
        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where('manage_work.manage_project_id', $data['manage_project_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /*
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->first();
        return $oSelect;
    }

    public function getTotalHomeSupport($data)
    {
        $staffId = Auth::id();
        $oSelect = $this
            ->select(
                DB::raw('SUM(IF((manage_work.date_start IS NULL OR manage_work.date_start < NOW()) AND manage_work.date_end > NOW() AND manage_work.manage_status_id NOT IN (6,7) , 1 , 0)) as total_work_day'),
                //                DB::raw('SUM(IF(manage_work.manage_status_id IN (1,2,5,6) , 1 , 0)) as total_work'),
                DB::raw('SUM(IF(manage_work.manage_status_id IN (1,2,3,4,5,6,7) , 1 , 0)) as total_work'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 1 , 1 , 0)) as total_not_started_yet'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 2 , 1 , 0)) as total_started'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 6 , 1 , 0)) as total_complete'),
                DB::raw('SUM(IF(manage_work.manage_status_id = 5 , 1 , 0)) as total_unfinished'),
                //                DB::raw('SUM(IF(manage_work.date_end < NOW() AND (manage_work.manage_status_id IN (1,2,5) ) , 1 , 0)) as total_overdue')
                DB::raw('SUM(IF(manage_work.date_end < NOW() AND (manage_work.manage_status_id NOT IN (6,7) ) , 1 , 0)) as total_overdue')
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($staffId) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $staffId);
            });

        if (!isset($data['job_overview'])) {
            $oSelect = $oSelect->where(function ($sql) use ($staffId) {
                $sql
                    //                    ->where($this->table.'.processor_id',$staffId)
                    //                    ->orWhere($this->table.'.assignor_id',$staffId)
                    //                    ->orWhere($this->table.'.approve_id',$staffId)
                    ->where('manage_work_support.staff_id', $staffId);
            });
        }

        //        $oSelect = $oSelect->where($this->table.'.processor_id',$staffId);


        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = Carbon::createFromFormat('Y/m/d', $data['from_date'])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $data['to_date'])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        } else {
            //            $oSelect = $oSelect
            //                ->where('manage_work.date_start','<=', Carbon::now())
            //                ->where('manage_work.date_end','>=', Carbon::now());
        }

        //        Tìm kiếm chi nhánh
        if (isset($data['branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $data['branch_id']);
        }

        //        Tìm kiếm phòng ban
        if (isset($data['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $data['department_id']);
        }

        //        Tìm kiếm dự án
        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where('manage_work.manage_project_id', $data['manage_project_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /*
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->first();
        return $oSelect;
    }

    public function getTotalHomeUpdate($data)
    {
        $staffId = Auth::id();
        $oSelect = $this
            ->select(
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 1, 1 , 0)) as status_1'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 2, 1 , 0)) as status_2'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 3, 1 , 0)) as status_3'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 4, 1 , 0)) as status_4'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 5, 1 , 0)) as status_5'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 6, 1 , 0)) as status_6'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 7, 1 , 0)) as status_7'),
            //                DB::raw('SUM(IF(manage_work.date_end < now(), 1 , 0)) as overdue')

                DB::raw('COUNT(*) as total_work'),
                'manage_work.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color'
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_work_support', function ($sql) use ($staffId) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $staffId);
            });

        if (!isset($data['job_overview'])) {
            $oSelect = $oSelect->where(function ($sql) use ($staffId) {
                $sql->where($this->table . '.processor_id', $staffId);
                //                    ->orWhere($this->table.'.assignor_id',$staffId)
                //                    ->orWhere($this->table.'.approve_id',$staffId)
                //                    ->orWhere('manage_work_support.staff_id',$staffId);
            });
        }

        //        $oSelect = $oSelect->where($this->table.'.processor_id',$staffId);


        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = Carbon::createFromFormat('Y/m/d', $data['from_date'])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $data['to_date'])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        } else {
            //            $oSelect = $oSelect
            //                ->where('manage_work.date_start','<=', Carbon::now())
            //                ->where('manage_work.date_end','>=', Carbon::now());
        }

        //        Tìm kiếm chi nhánh
        if (isset($data['branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $data['branch_id']);
        }

        //        Tìm kiếm phòng ban
        if (isset($data['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $data['department_id']);
        }

        //        Tìm kiếm dự án
        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where('manage_work.manage_project_id', $data['manage_project_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /*
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->groupBy('manage_work.manage_status_id')->get();
        return $oSelect;
    }

    public function getTotalHomeUpdateSupport($data)
    {
        $staffId = Auth::id();
        $oSelect = $this
            ->select(
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 1, 1 , 0)) as status_1'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 2, 1 , 0)) as status_2'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 3, 1 , 0)) as status_3'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 4, 1 , 0)) as status_4'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 5, 1 , 0)) as status_5'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 6, 1 , 0)) as status_6'),
            //                DB::raw('SUM(IF(manage_work.manage_status_id = 7, 1 , 0)) as status_7'),
            //                DB::raw('SUM(IF(manage_work.date_end < now(), 1 , 0)) as overdue')

                DB::raw('COUNT(*) as total_work'),
                'manage_work.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color'
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_work_support', function ($sql) use ($staffId) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $staffId);
            });

        if (!isset($data['job_overview'])) {
            $oSelect = $oSelect->where(function ($sql) use ($staffId) {
                $sql
                    //                    ->where($this->table.'.processor_id',$staffId);
                    //                    ->orWhere($this->table.'.assignor_id',$staffId)
                    //                    ->orWhere($this->table.'.approve_id',$staffId)
                    ->where('manage_work_support.staff_id', $staffId);
            });
        }

        //        $oSelect = $oSelect->where($this->table.'.processor_id',$staffId);


        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = Carbon::createFromFormat('Y/m/d', $data['from_date'])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $data['to_date'])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        } else {
            //            $oSelect = $oSelect
            //                ->where('manage_work.date_start','<=', Carbon::now())
            //                ->where('manage_work.date_end','>=', Carbon::now());
        }

        //        Tìm kiếm chi nhánh
        if (isset($data['branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $data['branch_id']);
        }

        //        Tìm kiếm phòng ban
        if (isset($data['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $data['department_id']);
        }

        //        Tìm kiếm dự án
        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where('manage_work.manage_project_id', $data['manage_project_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /*
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->groupBy('manage_work.manage_status_id')->get();
        return $oSelect;
    }

    /**
     * Xoá công việc
     * @param $manage_work_id
     * @return mixed
     */
    public function deleteWork($manage_work_id)
    {
        return $this
            ->where('manage_work_id', $manage_work_id)
            ->delete();
    }

    /**
     * Xoá công việc con
     * @param $manage_work_id
     * @return mixed
     */
    public function deleteWorkChild($manage_work_id)
    {
        return $this
            ->where('parent_id', $manage_work_id)
            ->delete();
    }

    /**
     * Lấy danh sách công việc bị trễ hạn
     *
     * @param $data
     */
    public function getListOverdue($data)
    {
        $high = __('Cao');
        $normal = __('Bình thường');
        $low = __('Thấp');

        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.is_approve_id',
                $this->table . '.priority',
                $this->table . '.approve_id',
                $this->table . '.branch_id',
                'manage_project.manage_project_name',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("IF(manage_work.priority = 1 , '{$high}' , IF(manage_work.priority = 2 , '{$normal}','{$low}') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3 , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($user) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $user);
            })
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id');

        if (!isset($data['job_overview'])) {
            $oSelect = $oSelect->where(function ($sql) use ($user) {
                $sql->where($this->table . '.processor_id', $user)
                    ->orWhere($this->table . '.assignor_id', $user)
                    ->orWhere($this->table . '.approve_id', $user)
                    ->orWhere('manage_work_support.staff_id', $user);
            });
        }


        //        Tìm kiếm chi nhánh
        if (isset($data['branch_id'])) {
            $oSelect = $oSelect->where('staffs.branch_id', $data['branch_id']);
        }

        //        Tìm kiếm phòng ban
        if (isset($data['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $data['department_id']);
        }

        //        Tìm kiếm dự án
        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where('manage_work.manage_project_id', $data['manage_project_id']);
        }

        if (isset($data['status_overdue_fix'])) {
            $oSelect = $oSelect
                ->where($this->table . '.date_end', '<=', Carbon::now())
                ->whereNotIn($this->table . '.manage_status_id', $data['status_overdue_fix']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = Carbon::createFromFormat('Y/m/d', $data['from_date'])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $data['to_date'])->format('Y-m-d 23:59:59');
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $start)
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect
            ->orderBy($this->table . '.date_end', 'DESC')
            ->get();
        return $oSelect;
    }

    /**
     * lấy danh sách công việc
     */
    public function getListWork($data)
    {
        $oSelect = $this
            ->select(
                'manage_work_id',
                'manage_work_title',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            );

        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        if (isset($data['manage_work_id'])) {
            $oSelect = $oSelect->where('manage_work_id', $data['manage_work_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->orderBy($this->table . '.created_at', 'DESC')->get();
    }

    /**
     * lấy danh sách công việc
     */
    public function getListWorkParent($data)
    {
        $oSelect = $this
            ->select(
                'manage_work_id',
                'manage_work_title'
            )->whereNull('parent_id');
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where('manage_project_id', $data['manage_project_id']);
        }

        $oSelect = $this->getPermission($oSelect);
        $oSelect->orderBy($this->table . '.created_at', 'DESC');
        // get số trang
        $page = (int)($data['page'] ?? 1);

        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Lấy chi tiết công việc
     * @param $manage_work_id
     */
    public function detailWork($manage_work_id)
    {
        $customer = __('Khách hàng');
        $customerLead = __('Khách hàng tiềm năng');
        $listDeal = __('Danh sách deal');

        $high = __('Cao');
        $normal = __('Bình thường');
        $low = __('Thấp');

        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.manage_work_customer_type',
                Db::raw("IF(manage_work.manage_work_customer_type = 'customer', '$customer',(IF(manage_work.manage_work_customer_type = 'lead', '$customerLead',(IF(manage_work.manage_work_customer_type = 'deal','$listDeal',''))))) as manage_work_customer_type_title"),
                $this->table . '.parent_id',
                $this->table . '.progress',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'processor.full_name as processor_name',
                'processor.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.description',
                $this->table . '.date_finish',
                $this->table . '.manage_project_id',
                'manage_project.manage_project_name',
                $this->table . '.manage_type_work_id',
                'manage_type_work.manage_type_work_name',
                $this->table . '.priority',
                DB::raw("IF(manage_work.priority = 1 , '$high' , IF(manage_work.priority = 2 , '$normal', '$low') ) as priority_name"),
                $this->table . '.type_card_work',
                DB::raw("IF(manage_work.type_card_work = 'bonus' , '$normal', IF(manage_work.type_card_work = 'kpi' , 'Kpi','') ) as type_card_work_name"),
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.approve_id',
                'approve.full_name as approve_name',
                $this->table . '.manage_status_id',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.customer_id',
                $this->table . '.is_approve_id',
                $this->table . '.branch_id',
                //                'customers.full_name as customer_name',
                DB::raw("IF(manage_work.manage_work_customer_type = 'customer' , customers.full_name , (IF(manage_work.manage_work_customer_type = 'lead' , lead.full_name , (IF(manage_work.manage_work_customer_type = 'deal', deal.deal_name , ''))))) as customer_name"),
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                //                'manage_status_config.is_edit',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                //                'manage_status_config.is_deleted',
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3, 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job"),
                "{$this->table}.create_object_type",
                "{$this->table}.create_object_id"
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('staffs as processor', 'processor.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('cpo_deals as deal', 'deal.deal_id', $this->table . '.customer_id')
            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', $this->table . '.customer_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->where($this->table . '.manage_work_id', $manage_work_id);

        $oSelect = $this->getPermission($oSelect);

        return $oSelect->first();
    }

    /**
     * Lấy danh sách tác vụ con
     * @param $data
     */
    public function getChildWork($data)
    {
        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                'manage_project.manage_project_name',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.approve_id',
                $this->table . '.branch_id',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3 , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id');

        //        Tìm kiếm theo công việc
        if (isset($data['manage_work_id'])) {
            $oSelect = $oSelect->where($this->table . '.parent_id', $data['manage_work_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        //        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect
            ->orderBy($this->table . '.date_end', 'DESC')
            ->get();
        return $oSelect;
    }

    /**
     * lấy danh sách công việc để group trạng thái
     * @param $data
     */
    public function getListWorkAllStatus($data)
    {
        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                'manage_project.manage_project_name',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.approve_id',
                $this->table . '.branch_id',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3 , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->whereNotIn($this->table . '.manage_status_id', [6, 7]);

        //        Tìm kiếm theo công việc
        if (isset($data['manage_work_id'])) {
            $oSelect = $oSelect->where($this->table . '.parent_id', $data['manage_work_id']);
        }

        //        Tìm theo user giao việc
        if (isset($data['assignor_id'])) {
            $oSelect = $oSelect->where($this->table . '.assignor_id', $data['assignor_id']);
        }


        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC')
            ->get();

        if (count($oSelect) != 0) {
            foreach ($oSelect as $key => $item) {
                $oSelect[$key]['text_overdue'] = '';
            }
        }

        return $oSelect;
    }

    /**
     * lấy danh sách công việc của tôi bị trễ hạn
     * @param $data
     */
    public function getMyWorkOrverDue($data)
    {
        $staffId = $data['staff_id'];
        $user = Auth::id();

        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.approve_id',
                $this->table . '.branch_id',
                'manage_project.manage_project_name',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3 , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($user) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $user);
            })
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id');
        //            ->leftJoin('manage_work_support','manage_work_support.manage_work_id',$this->table.'.manage_work_id');

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        //        Kiểm tra ở tab việc của tôi
        if (isset($data['tab_my_work'])) {
            $oSelect = $oSelect
                ->where(function ($sql1) use ($staffId) {
                    $sql1->where(function ($sql) use ($staffId) {
                        $sql->where($this->table . '.processor_id', $staffId)
                            ->orWhere($this->table . '.assignor_id', $staffId)
                            ->orWhere($this->table . '.approve_id', $staffId)
                            ->orWhere('manage_work_support.staff_id', $staffId);
                    })
                        ->where($this->table . '.processor_id', Auth::id())
                        ->orWhere('manage_work_support.staff_id', Auth::id());
                });
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect
                ->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        if (isset($data['status_overdue'])) {
            $oSelect = $oSelect
                ->whereNotIn($this->table . '.manage_status_id', $data['status_overdue'])
                ->where('manage_work.date_end', '<=', Carbon::now());
        }

        $oSelect = $oSelect
            ->where($this->table . '.date_end', '<', Carbon::now())
            ->whereNotIn($this->table . '.manage_work_id', [6, 7])
            ->groupBy($this->table . '.manage_work_id')
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC');

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->get();

        if (count($oSelect) != 0) {
            foreach ($oSelect as $key => $item) {
                $oSelect[$key]['text_overdue'] = '';
            }
        }

        return $oSelect;
    }

    /**
     * lấy danh sách công việc của tôi bị trễ hạn chỉ lấy ngày hết hạn
     * @param $data
     */
    public function getMyWorkOrverDueDate($data)
    {
        $staffId = $data['staff_id'];
        $user = Auth::id();

        $oSelect = $this
            ->select(
                $this->table . '.date_end'
            )
            ->leftJoin('manage_work_support', function ($sql) use ($user) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $user);
            });

        if (isset($data['status_overdue'])) {
            $oSelect = $oSelect->whereIn($this->table . '.manage_status_id', $data['status_overdue']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $oSelect
            ->where(function ($sql) use ($staffId) {
                $sql->where($this->table . '.processor_id', $staffId)
                    ->orWhere($this->table . '.assignor_id', $staffId)
                    ->orWhere($this->table . '.approve_id', $staffId)
                    ->orWhere('manage_work_support.staff_id', $staffId);
            })
            ->whereNotIn($this->table . '.manage_status_id', [6, 7])
            ->where($this->table . '.date_end', '<', Carbon::now())
            ->groupBy($this->table . '.manage_work_id')
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC');

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->get();

        //        if (count($oSelect) != 0){
        //            foreach ($oSelect as $key => $item){
        //                $oSelect[$key]['text_overdue'] = '';
        //            }
        //        }

        return $oSelect;
    }

    /**
     * lấy danh sách công việc của tôi theo ngày
     * @param $data
     */
    public function getMyWorkByDate($data)
    {
        $staffId = $data['staff_id'];
        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                'manage_project.manage_project_name',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.approve_id',
                $this->table . '.branch_id',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3 , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($user) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $user);
            })
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id');
        //            ->leftJoin('manage_work_support','manage_work_support.manage_work_id',$this->table.'.manage_work_id');

        //        Kiểm tra ở tab việc của tôi
        if (isset($data['tab_my_work'])) {
            $oSelect = $oSelect
                ->where(function ($sql) {
                    $sql->where($this->table . '.processor_id', Auth::id());
//                        ->orWhere('manage_work_support.staff_id', Auth::id());
                });
        }

        if (isset($data['from_date']) && isset($data['to_date'])) {
            $start = $data['from_date'];
            $end = $data['to_date'];
            $oSelect = $oSelect->where(function ($sql) use ($start, $end) {
                $sql
                    ->whereBetween('manage_work.date_start', [$start, $end])
                    ->orWhereBetween('manage_work.date_end', [$start, $end])
                    ->orWhere(function ($sql1) use ($start) {
                        $sql1
                            //                            ->where('manage_work.date_start','<=',$start)
                            ->where(function ($sql2) use ($start) {
                                $sql2
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNull('manage_work.date_start');
                                    })
                                    ->orWhere(function ($sql3) use ($start) {
                                        $sql3
                                            ->whereNotNull('manage_work.date_start')
                                            ->where('manage_work.date_start', '<=', $start);
                                    });
                            })
                            ->where('manage_work.date_end', '>=', $start);
                    })
                    ->orWhere(function ($sql1) use ($end) {
                        $sql1
                            ->where('manage_work.date_start', '<=', $end)
                            ->where('manage_work.date_end', '>=', $end);
                    });
            });
        }

        if (isset($data['manage_status_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_status_id', $data['manage_status_id']);
        }

        //        Người xử lý
        if (isset($data['processor_id'])) {
            $oSelect = $oSelect->where($this->table . '.processor_id', $data['processor_id']);
        }

        if (isset($data['support_id'])) {
            $oSelect = $oSelect->where('manage_work_support.staff_id', $data['support_id']);
        }

        //        Người giao công việc
        if (isset($data['assignor_id'])) {
            $oSelect = $oSelect->where($this->table . '.assignor_id', $data['assignor_id']);
        }

        //        Dự án
        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $data['manage_project_id']);
        }

        //        Loại công việc
        if (isset($data['manage_type_work_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_type_work_id', $data['manage_type_work_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $oSelect
            //            ->where(function ($sql) use ($staffId){
            //                $sql->where($this->table.'.processor_id',$staffId)
            //                    ->orWhere($this->table.'.assignor_id',$staffId)
            //                    ->orWhere($this->table.'.approve_id',$staffId)
            //                    ->orWhere('manage_work_support.staff_id',$staffId);
            //            })
            ->whereNotIn($this->table . '.manage_status_id', [6, 7])
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC')
            ->groupBy($this->table . '.manage_work_id');

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->get();

        if (count($oSelect) != 0) {
            foreach ($oSelect as $key => $item) {
                $oSelect[$key]['text_overdue'] = '';
            }
        }

        return $oSelect;
    }

    /**
     * lấy danh sách công việc của tôi theo ngày search
     * @param $data
     */
    public function getMyWorkByDateSearch($data)
    {
        $staffId = $data['staff_id'];

        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                'manage_project.manage_project_name',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.approve_id',
                $this->table . '.branch_id',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3 , 1,0) as is_approve"),
//                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($user) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $user);
            })
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id');
        //            ->leftJoin('manage_work_support','manage_work_support.manage_work_id',$this->table.'.manage_work_id');

        if (isset($data['start_date']) && isset($data['end_date']) && $data['start_date'] == $data['end_date']) {

            //            $date = explode('-',$data['end_date']);
            $start = Carbon::createFromFormat('Y/m/d', $data['start_date'])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $data['end_date'])->format('Y-m-d 23:59:59');
            //            $oSelect = $oSelect->whereBetween($this->table.'.date_end',[$start,$end]);
            $oSelect = $oSelect
                ->where(function ($sub) use ($start, $end) {
                    $sub->where(function ($ds) use ($start, $end) {
                        $ds->where(function ($sql) use ($start, $end) {
                            $sql->whereNull($this->table . '.date_start')
                                ->whereDate($this->table . '.created_at', '<=', $start);
                        })
                            ->orWhere(function ($sql) use ($start, $end) {
                                $sql->whereNotNull($this->table . '.date_start')
                                    ->whereDate($this->table . '.date_start', '<=', $start);
                            });
                    })
                        ->whereDate($this->table . '.date_end', '>=', $start)
                        ->orWhere(function ($sql) use ($end) {
                            $sql->whereDate($this->table . '.date_end', '>=', $end);
                        })
                        ->whereBetween($this->table . '.date_end', [$start, $end]);
                });
        } else {

            if (isset($data["start_date"])) {

                $start = Carbon::createFromFormat('Y/m/d', $data['start_date'])->format('Y-m-d 00:00:00');
                $oSelect = $oSelect
                    //                    ->where($this->table.'.date_end' ,'>=',$start)
                    ->where(function ($ds) use ($start) {
                        $ds->where(function ($sql) use ($start) {
                            $sql->whereNull($this->table . '.date_start')
                                ->orWhere($this->table . '.created_at', '<=', $start);
                        })
                            ->orWhere(function ($sql) use ($start) {
                                $sql->whereNotNull($this->table . '.date_start')
                                    ->orWhere($this->table . '.date_start', '<=', $start);
                            });
                    })
                    ->whereDate($this->table . '.date_end', '>=', $start);
            }

            if (isset($data["end_date"])) {
                $end = Carbon::createFromFormat('Y/m/d', $data['end_date'])->format('Y-m-d 23:59:59');
                $oSelect = $oSelect
                    ->where(function ($ds) use ($end) {
                        $ds
                            ->where(function ($qs) use ($end) {
                                $qs->whereNull($this->table . '.date_start')
                                    ->where($this->table . '.created_at', '<=', $end);
                            })
                            ->orWhere(function ($qs) use ($end) {
                                $qs->wherenotNull($this->table . '.date_start')
                                    ->where($this->table . '.date_start', '<=', $end);
                            });
                    })
                    ->where(function ($ds) use ($end) {
                        $ds->whereDate($this->table . '.date_end', '>=', $end)
                            ->orWhere(function ($ds) use ($end) {
                                $ds->whereDate($this->table . '.date_end', '<=', $end);
                            });
                    });
            }
        }


        if (isset($data['manage_status_id'])) {
            switch ($data['manage_status_id']) {
                case -1:
                    $oSelect = $oSelect
                        ->where($this->table . '.date_end', '<=', Carbon::now())
                        ->whereNotIn($this->table . '.manage_status_id', [6, 7]);
                    break;
                default:
                    $oSelect = $oSelect->where($this->table . '.manage_status_id', $data['manage_status_id']);

            }
        }

        //        Người xử lý
        if (isset($data['processor_id']) && count($data['processor_id']) != 0) {
            $listProcessor = collect($data['processor_id'])->pluck('staff_id');
            $oSelect = $oSelect->whereIn($this->table . '.processor_id', $listProcessor);
        }

        if (isset($data['support_id']) && count($data['support_id']) != 0) {
            $listSupport = collect($data['support_id'])->pluck('staff_id');
            $oSelect = $oSelect->whereIn('manage_work_support.staff_id', $listSupport);
        }

        //        Người giao công việc
        if (isset($data['assignor_id']) && count($data['assignor_id']) != 0) {
            $listAssignor = collect($data['assignor_id'])->pluck('staff_id');
            $oSelect = $oSelect->whereIn($this->table . '.assignor_id', $listAssignor);
        }

        //        Dự án
        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $data['manage_project_id']);
        }

        //        Loại công việc
        if (isset($data['manage_type_work_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_type_work_id', $data['manage_type_work_id']);
        }

        //        Phòng ban
        if (isset($data['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $data['department_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        if (isset($data['tracker'])) {
            if ($data['tracker'] == 'parent') {
                $oSelect = $oSelect->whereNull($this->table . '.parent_id');
            } else if ($data['tracker'] == 'child') {
                $oSelect = $oSelect->whereNoteNull($this->table . '.parent_id');
            }
        }

        if (!isset($data['processor_id']) && !isset($data['support_id']) && !isset($data['assignor_id'])) {
            $oSelect = $oSelect
                ->where(function ($sql) use ($staffId) {
                    $sql->where($this->table . '.processor_id', $staffId)
                        ->orWhere($this->table . '.assignor_id', $staffId)
                        ->orWhere($this->table . '.approve_id', $staffId)
                        ->orWhere('manage_work_support.staff_id', $staffId);
                });
        }

        //Tìm kiếm chi nhánh
        if (isset($data['branch_id']) && $data['branch_id'] != null) {
            $oSelect = $oSelect->where('staffs.branch_id', $data['branch_id']);
        }

        //            ->whereNotIn($this->table.'.manage_status_id',[6,7])
        $oSelect = $oSelect->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC')
            ->groupBy($this->table . '.manage_work_id');

        $oSelect = $this->getPermission($oSelect);

        // $oSelect = $oSelect->get();
        // if (isset($data['tracker'])) {
        //     if ($data['tracker'] == 'parent') {
        //         $page = (int)($input["page"] ?? 1);
        //         $oSelect = $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = "page", $page);
        //     } else {
        //         $oSelect = $oSelect->get();
        //     }
        // } else {
        //     $oSelect = $oSelect->get();
        // }

        // if (count($oSelect) != 0){
        //     foreach ($oSelect as $key => $item){
        //         $oSelect[$key]['text_overdue'] = '';
        //     }
        // }
        $page = (int)($data['page'] ?? 1);
        return $oSelect->paginate(PAGING_ITEM_PER_PAGE, $columns = ["*"], $pageName = 'page', $page);
    }

    /**
     * Danh sách công việc cần duyệt
     */
    public function getListWorkApprove($data)
    {
        $user = Auth::id();
        $oSelect = $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'staffs.full_name as processor_name',
                'staffs.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.manage_status_id',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                'manage_project.manage_project_name',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.approve_id',
                $this->table . '.branch_id',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                DB::raw("IF(manage_work.approve_id = {$user} AND manage_work.manage_status_id = 3 , 1,0) as is_approve"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('manage_work_support', function ($sql) use ($user) {
                $sql->on('manage_work_support.manage_work_id', $this->table . '.manage_work_id')
                    ->where('manage_work_support.staff_id', $user);
            })
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', '=', "{$this->table}.manage_status_id")
            //            ->leftJoin('manage_work_support','manage_work_support.manage_work_id',$this->table.'.manage_work_id')
            ->where($this->table . '.approve_id', $user)
            ->where($this->table . '.manage_status_id', self::APPROVE);

        if (isset($data['start_date'])) {
            $date = explode('-', $data['start_date']);
            $start = Carbon::createFromFormat('Y/m/d', $date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $date[1])->format('Y-m-d 23:59:59');

            $oSelect = $oSelect->whereBetween($this->table . '.date_start', [$start, $end]);
        }

        if (isset($data['end_date'])) {
            $date = explode('-', $data['end_date']);
            $start = Carbon::createFromFormat('Y/m/d', $date[0])->format('Y-m-d 00:00:00');
            $end = Carbon::createFromFormat('Y/m/d', $date[1])->format('Y-m-d 23:59:59');

            $oSelect = $oSelect->whereBetween($this->table . '.date_end', [$start, $end]);
        }

        //        Người xử lý
        if (isset($data['processor_id']) && count($data['processor_id']) != 0) {
            $listProcessor = collect($data['processor_id'])->pluck('staff_id');
            $oSelect = $oSelect->whereIn($this->table . '.processor_id', $listProcessor);
        }

        if (isset($data['support_id']) && count($data['support_id']) != 0) {
            $listSupport = collect($data['support_id'])->pluck('staff_id');
            $oSelect = $oSelect->whereIn('manage_work_support.staff_id', $listSupport);
        }

        //        Người giao công việc
        if (isset($data['assignor_id']) && count($data['assignor_id']) != 0) {
            $listAssignor = collect($data['assignor_id'])->pluck('staff_id');
            $oSelect = $oSelect->whereIn($this->table . '.assignor_id', $listAssignor);
        }

        //        Dự án
        if (isset($data['manage_project_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_project_id', $data['manage_project_id']);
        }

        //        Loại công việc
        if (isset($data['manage_type_work_id'])) {
            $oSelect = $oSelect->where($this->table . '.manage_type_work_id', $data['manage_type_work_id']);
        }

        //        Phòng ban
        if (isset($data['department_id'])) {
            $oSelect = $oSelect->where('staffs.department_id', $data['department_id']);
        }

        //        Tên công việc
        if (isset($data['manage_work_title'])) {
            $oSelect = $oSelect->where('manage_work.manage_work_title', 'like', '%' . $data['manage_work_title'] . '%');
        }

        /**
         * Số ngày quá hạn
         */
        if (isset($data['date_overdue'])) {
            $oSelect = $oSelect->whereBetween(DB::raw("DATEDIFF(NOW() ,manage_work.date_end)"), [0, $data['date_overdue']]);
        }

        $oSelect = $oSelect
            ->orderBy($this->table . '.manage_status_id', 'ASC')
            ->orderBy($this->table . '.date_end', 'DESC')
            ->groupBy($this->table . '.manage_work_id');

        $oSelect = $this->getPermission($oSelect);

        $oSelect = $oSelect->get();

        if (count($oSelect) != 0) {
            foreach ($oSelect as $key => $item) {
                $oSelect[$key]['text_overdue'] = '';
            }
        }

        return $oSelect;
    }

    /**
     * Lấy chi tiết công việc
     * @param $manage_work_id
     */
    public function detailWorkNoti($manage_work_id)
    {
        if (Auth::user() != null) {
            $user = Auth::id();
        } else {
            $user = 0;
        }
        return $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_title',
                $this->table . '.parent_id',
                $this->table . '.progress',
                'parent.manage_work_title as parent_name',
                $this->table . '.processor_id',
                'processor.full_name as processor_name',
                'processor.staff_avatar as processor_avatar',
                $this->table . '.assignor_id',
                $this->table . '.assignor_id as created_by',
                'assignor.full_name as assignor_name',
                'assignor.staff_avatar as assignor_avatar',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.description',
                $this->table . '.date_finish',
                $this->table . '.manage_project_id',
                'manage_project.manage_project_name',
                $this->table . '.manage_type_work_id',
                'manage_type_work.manage_type_work_name',
                $this->table . '.priority',
                DB::raw("IF(manage_work.priority = 1 , 'Cao' , IF(manage_work.priority = 2 , 'Bình thường','Thấp') ) as priority_name"),
                $this->table . '.type_card_work',
                DB::raw("IF(manage_work.type_card_work = 'bonus' , 'Thường' , IF(manage_work.type_card_work = 'kpi' , 'Kpi','') ) as type_card_work_name"),
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.approve_id',
                'approve.full_name as approve_name',
                'updated.full_name as updated_name',
                'updated.email as updated_email',
                $this->table . '.manage_status_id',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.customer_id',
                $this->table . '.is_approve_id',
                $this->table . '.created_by',
                $this->table . '.updated_by',
                $this->table . '.branch_id',
                'customers.full_name as customer_name',
                'manage_status.manage_status_name',
                'manage_status.manage_status_color',
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_edit = 1, 1,0) as is_edit"),
                DB::raw("IF((manage_work.processor_id = {$user} OR manage_work.assignor_id = {$user}) AND manage_status_config.is_deleted = 1, 1,0) as is_deleted"),
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('staffs as processor', 'processor.staff_id', $this->table . '.processor_id')
            ->leftJoin('staffs as assignor', 'assignor.staff_id', $this->table . '.assignor_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->leftJoin('staffs as updated', 'updated.staff_id', $this->table . '.updated_by')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->where($this->table . '.manage_work_id', $manage_work_id)
            ->first();
    }

    /**
     * Kiểm tra code tạo trong ngày
     * @param $code
     */
    public function getCodeWork($code)
    {
        $oSelect = $this
            ->where('manage_work_code', 'like', '%' . $code . '%')
            ->orderBy('manage_work_id', 'DESC')
            ->first();

        return $oSelect != null ? $oSelect['manage_work_code'] : null;
    }

    public function getPermission($oSelect)
    {
        $user = Auth::user();

        $userId = $user->staff_id;

        $dataRole = DB::table('map_role_group_staff')
            ->select('manage_role.role_group_id', 'is_all', 'is_branch', 'is_department', 'is_own')
            ->join('manage_role', 'manage_role.role_group_id', 'map_role_group_staff.role_group_id')
            ->where('staff_id', $userId)
            ->get()->toArray();

        $isAll = $isBranch = $isDepartment = $isOwn = 0;
        foreach ($dataRole as $role) {
            $role = (array)$role;
            if ($role['is_all']) {
                $isAll = 1;
            }

            if ($role['is_branch']) {
                $isBranch = 1;
            }

            if ($role['is_department']) {
                $isDepartment = 1;
            }

            if ($role['is_own']) {
                $isOwn = 1;
            }
        }
        $listManageSupport = DB::table('manage_work_support')
            ->where('staff_id', $userId)
            ->get()->pluck('manage_work_id')->toArray();

        if ($isAll) {
        } else if ($isBranch) {
            $myBrand = $user->branch_id;

            //            $oSelect = $oSelect->join('staffs as per_staff', function ($join) use($myBrand){
            //                $join->on('per_staff.staff_id', '=', $this->table.'.processor_id')->on('per_staff.branch_id', '=', DB::raw($myBrand));
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($userId, $listManageSupport, $myBrand) {
                $sql->join('staffs as per_staff', function ($join) use ($myBrand) {
                    $join->on('per_staff.staff_id', '=', $this->table . '.processor_id')->on('per_staff.branch_id', '=', DB::raw($myBrand));
                })->orWhere($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        } else if ($isDepartment) {
            $myDep = $user->department_id;

            //            $oSelect = $oSelect->join('staffs as per_staff', function ($join) use($myDep){
            //                $join->on('per_staff.staff_id', '=', $this->table.'.processor_id')->on('per_staff.department_id', '=', DB::raw($myDep));
            //            });

            $oSelect = $oSelect->where(function ($sql) use ($userId, $listManageSupport, $myDep) {
                $sql->join('staffs as per_staff', function ($join) use ($myDep) {
                    $join->on('per_staff.staff_id', '=', $this->table . '.processor_id')->on('per_staff.department_id', '=', DB::raw($myDep));
                })->orWhere($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        } else {
            $listManageSupport = DB::table('manage_work_support')
                ->where('staff_id', $userId)
                ->get()->pluck('manage_work_id')->toArray();

            $oSelect = $oSelect->where(function ($query) use ($userId, $listManageSupport) {
                $query->where($this->table . '.processor_id', $userId)->orWhere($this->table . '.assignor_id', $userId)
                    ->orWhere($this->table . '.approve_id', $userId)->orWhereIn($this->table . '.manage_work_id', $listManageSupport);
            });
        }

        return $oSelect;
    }

    /**
     * lấy danh sách công việc con
     * @param $manage_work_id
     * @return mixed
     */
    public function getListWorkChildInsert($manage_work_id)
    {
        return $this
            ->select(
                $this->table . '.manage_work_id',
                $this->table . '.manage_work_customer_type',
                $this->table . '.manage_work_code',
                $this->table . '.manage_work_title',
                $this->table . '.progress',
                $this->table . '.parent_id',
                $this->table . '.processor_id',
                $this->table . '.assignor_id',
                $this->table . '.approve_id',
                $this->table . '.manage_status_id',
                $this->table . '.manage_project_id',
                $this->table . '.manage_type_work_id',
                $this->table . '.date_start',
                $this->table . '.date_end',
                $this->table . '.priority',
                $this->table . '.is_approve_id',
                $this->table . '.type_card_work',
                $this->table . '.time',
                $this->table . '.time_type',
                $this->table . '.date_finish',
                $this->table . '.repeat_type',
                $this->table . '.repeat_end',
                $this->table . '.repeat_end_time',
                $this->table . '.repeat_end_type',
                $this->table . '.repeat_end_full_time',
                $this->table . '.repeat_time',
                $this->table . '.description',
                $this->table . '.customer_id',
                $this->table . '.is_approve_id',
                $this->table . '.is_booking',
                $this->table . '.branch_id',
                'manage_status.manage_status_name',
                'staffs.full_name as staff_name',
                'approve.full_name as approve_name',
                'createdStaff.full_name as createdStaff_name',
                'parent.manage_work_title as parent_manage_work_title',
                'parent.manage_work_code as parent_manage_work_code',
                'manage_project.manage_project_name',
                'manage_type_work.manage_type_work_name',
                'manage_status_config.manage_color_code',
                'manage_status_config.is_edit',
                'manage_status_config.is_deleted',
                DB::raw('CONCAT((CASE WHEN customers.customer_type = "bussiness" THEN "Cá nhân" ELSE "Doanh nghiệp" END),"_",COALESCE(customers.full_name,""),"_",COALESCE(customers.phone1,""),"_",COALESCE(customers.email,"")) as customer_name'),
                DB::raw('CONCAT((CASE WHEN lead.customer_type = "bussiness" THEN "Cá nhân" ELSE "Doanh nghiệp" END),"_",COALESCE(lead.full_name,""),"_",COALESCE(lead.phone,""),"_",COALESCE(lead.email,"")) as lead_name'),
                'deal.deal_name',
                DB::raw("(SELECT COUNT(manage_work_parent.manage_work_id) FROM manage_work as manage_work_parent where manage_work.manage_work_id = manage_work_parent.parent_id ) as total_child_job")
            )
            ->leftJoin('manage_work as parent', 'parent.manage_work_id', $this->table . '.parent_id')
            ->leftJoin('customers', 'customers.customer_id', $this->table . '.customer_id')
            ->leftJoin('cpo_deals as deal', 'deal.deal_id', $this->table . '.customer_id')
            ->leftJoin('cpo_customer_lead as lead', 'lead.customer_lead_id', $this->table . '.customer_id')
            ->leftJoin('staffs as approve', 'approve.staff_id', $this->table . '.approve_id')
            ->join('manage_type_work', 'manage_type_work.manage_type_work_id', $this->table . '.manage_type_work_id')
            ->leftJoin('manage_project', 'manage_project.manage_project_id', $this->table . '.manage_project_id')
            ->leftJoin('manage_status_config', 'manage_status_config.manage_status_id', $this->table . '.manage_status_id')
            ->join('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->join('staffs', 'staffs.staff_id', $this->table . '.processor_id')
            ->join('staffs as createdStaff', 'createdStaff.staff_id', $this->table . '.created_by')
            ->where($this->table . '.parent_id', $manage_work_id)
            ->get();
    }

    /**
     * Lấy tổng số tác vụ con
     * @param $parentId
     */
    public function getTotalChild($parentId)
    {
        return $this
            ->where('parent_id', $parentId)
            ->get()
            ->count();
    }

    public function getListChildWorkByParent($parentId)
    {
        return $this
            ->select(
                DB::raw("SUM(progress) as total_process"),
                DB::raw("COUNT(manage_work_id) as total_child")
            )
            ->where('parent_id', $parentId)
            ->first();
    }

    public function updateByParentId($data, $parentId)
    {
        return $this
            ->where('parent_id', $parentId)
            ->update($data);
    }

    /**
     * Lấy danh sách công việc con
     * @param $parentId
     * @return mixed
     */
    public function getListTaskOfParent($parentId)
    {
        return $this
            ->select(
                $this->table . '.*',
                'manage_status.manage_status_name'
            )
            ->leftJoin('manage_status', 'manage_status.manage_status_id', $this->table . '.manage_status_id')
            ->where($this->table . '.parent_id', $parentId)
            ->get();
    }

    public function getPrefix($id)
    {
        return $this
            ->select(
                "manage_project.prefix_code"
            )
            ->leftJoin("manage_project", "{$this->table}.manage_project_id", "manage_project.manage_project_id")
            ->where("manage_project.manage_project_id", $id)
            ->first();
    }

    public function getTotalWork($id)
    {
        $mSelect = $this
            ->select('manage_project_id', DB::raw('count(*) as total'))
            ->groupBy('manage_project_id')
            ->where("{$this->table}.manage_project_id", $id);
        return $mSelect->first();
    }

    /**
     * Lấy số công việc con từ công việc cha
     *
     * @param $manageWorkId
     * @return mixed
     */
    public function getTotalWorkChild($manageWorkId)
    {
        return $this->where("{$this->table}.parent_id", $manageWorkId)->get()->count();
    }
}
