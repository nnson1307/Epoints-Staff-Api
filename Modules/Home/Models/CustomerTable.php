<?php
/**
 * Created by PhpStorm.
 * User: Mr Son
 * Date: 11/2/2018
 * Time: 4:09 PM
 */

namespace Modules\Home\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use MyCore\Models\Traits\ListTableTrait;

class CustomerTable extends Model
{
    use ListTableTrait;
    protected $table = 'customers';
    protected $primaryKey = 'customer_id';
    protected $fillable = [
        'customer_id', 'branch_id', 'customer_group_id', 'full_name', 'birthday', 'gender', 'phone1', 'phone2', 'email',
        'facebook', 'address', 'customer_source_id', 'customer_refer_id', 'customer_avatar', 'note', 'date_last_visit',
        'is_actived', 'is_deleted', 'created_by', 'updated_by', 'created_at', 'updated_at', 'zalo', 'account_money', 'customer_code',
        'province_id', 'district_id', 'postcode'
    ];

    /**
     * @return mixed
     */
    protected function _getList($filter = [])
    {
        $ds = $this
            ->leftJoin('branches', 'branches.branch_id', '=', 'customers.branch_id')
            ->leftJoin('customer_groups', 'customer_groups.customer_group_id', '=', 'customers.customer_group_id')
            ->select(
                'customers.customer_id as customer_id',
                'customers.branch_id as branch_id',
                'customers.full_name as full_name',
                'customers.birthday as birthday',
                'customers.gender as gender',
                'customers.email as email',
                'customers.phone1 as phone1',
                'customers.customer_code as customer_code',
                'customer_groups.group_name as group_name',
                'customers.customer_avatar as customer_avatar',
                'customers.created_at as created_at',
                'customers.updated_at as updated_at',
                'customers.customer_group_id as customer_group_id',
                'branches.branch_name as branch_name',
                'customers.is_actived')
            ->where('customers.is_deleted', 0)
            ->where('customers.customer_id', '<>', 1)
            ->orderBy('customers.customer_id', 'desc');
        if (isset($filter["created_at"]) != "") {
            $arr_filter = explode(" - ", $filter["created_at"]);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('customers.created_at', [$startTime, $endTime]);
        }


        if (isset($filter["birthday"]) != "") {
            $arr_filter = explode(" - ", $filter["birthday"]);
            $from = Carbon::createFromFormat('m/d/Y', $arr_filter[0])->format('Y-m-d');
            $ds->whereDate('customers.birthday', $from);
        }
        if (isset($filter['search']) != "") {
            $search = $filter['search'];
            $ds->where(function ($query) use ($search) {
                $query->where('customers.full_name', 'like', '%' . $search . '%')
                    ->orWhere('customers.customer_code', '%' . $search . '%')
                    ->orWhere('customers.phone1', 'like', '%' . $search . '%')
                    ->where('customers.is_deleted', 0);
            });
        }
//        if (Auth::user()->is_admin != 1) {
//            $ds->where('customers.branch_id', Auth::user()->branch_id);
//        }
        return $ds;
    }

    /**
     * @param array $data
     * @return mixed
     */
    public function add(array $data)
    {
        $add = $this->create($data);
        return $add->customer_id;
    }

    public function getCustomerSearch($data)
    {
        $select = $this
            ->leftJoin('customer_groups as group', 'group.customer_group_id', '=', 'customers.customer_group_id')
            ->leftJoin('province', 'province.provinceid', '=', 'customers.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customers.district_id')
            ->select(
                'customers.customer_id',
                'customers.customer_group_id',
                'customers.full_name',
                'customers.phone1',
                'customers.customer_avatar',
                'customers.account_money',
                'customers.address',
                'customers.postcode',
                'customers.province_id as province_id',
                'customers.district_id as district_id',
                'group.group_name as group_name',
                'province.name as province_name',
                'district.name as district_name'

            )
//            ->where('full_name', 'like', '%' . $data . '%')
//            ->orWhere('phone1', 'like', '%' . $data . '%')

            ->where(function ($query) use ($data) {
                $query->where('full_name', 'like', '%' . $data . '%')
                    ->orWhere('phone1', 'like', '%' . $data . '%');
            })
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1);
//        if (Auth::user()->is_admin != 1) {
//            $select->where('branch_id', Auth::user()->branch_id);
//        }

        return $select->paginate(6);
    }

    /**
     * @param $id
     */
    public function getItem($id)
    {
        $get = $this
            ->leftJoin('customer_groups as group', 'group.customer_group_id', '=', 'customers.customer_group_id')
            ->leftJoin('customer_sources as source', 'source.customer_source_id', '=', 'customers.customer_source_id')
            ->leftJoin('province', 'province.provinceid', '=', 'customers.province_id')
            ->leftJoin('district', 'district.districtid', '=', 'customers.district_id')
            ->leftJoin('member_levels', 'member_levels.member_level_id', '=', 'customers.member_level_id')
            ->select('customers.customer_group_id as customer_group_id',
                'group.group_name as group_name',
                'customers.full_name as full_name',
                'customers.customer_code as customer_code',
                'customers.gender as gender',
                'customers.phone1 as phone1',
                'province.name as province_name',
                'province.type as province_type',
                'district.name as district_name',
                'district.type as district_type',
                'customers.address as address',
                'customers.email as email',
                'customers.customer_source_id as customer_source_id',
                'customers.birthday as birthday',
                'source.customer_source_name',
                'customers.customer_refer_id',
                'customers.facebook as facebook',
                'customers.zalo as zalo',
                'customers.note as note',
                'customers.customer_id as customer_id',
                'customers.is_actived as is_actived',
                'customers.phone2 as phone2',
                'customers.customer_avatar as customer_avatar',
                'customers.created_at as created_at',
                'customers.account_money as account_money',
                'customers.province_id as province_id',
                'customers.district_id as district_id',
                'customers.point as point',
                'customers.member_level_id as member_level_id',
                'member_levels.name as member_level_name',
                'customers.point as point',
                'member_levels.discount as member_level_discount',
                "{$this->table}.postcode"
                )
            ->where('customers.customer_id', $id);
//        if (Auth::user()->is_admin != 1) {
//            $get->where('customers.branch_id', Auth::user()->branch_id);
//        }
        return $get->first();
    }


    /**
     * @param $id
     */
    public function getItemRefer($id)
    {
        $get = $this->Join('customers as cs', 'cs.customer_refer_id', '=', 'customers.customer_id')
            ->select('customers.full_name as full_name_refer', 'customers.customer_id as customer_id')
            ->where('cs.customer_id', $id)->first();
        return $get;
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     */
    public function edit(array $data, $id)
    {
        return $this->where('customer_id', $id)->update($data);
    }

    /**
     * @param $id
     */
    public function remove($id)
    {
        $this->where('customer_id', $id)->update(['is_deleted' => 1]);
    }

    /**
     * @return mixed
     */
    public function getCustomerOption()
    {
        return $this->select('full_name', 'customer_code', 'customer_id', 'phone1')
            ->where('is_actived', 1)
            ->where('is_deleted', 0)
            ->where('customer_id', '!=', 1)->get()->toArray();
    }

    /**
     * @param $phone
     * @param $id
     * @return mixed
     * Kiểm tra số điện thoại đã tồn tại chưa
     */
    public function testPhone($phone, $id)
    {
        return $this->where(function ($query) use ($phone) {
            $query->where('phone1', '=', $phone)
                ->orWhere('phone2', '=', $phone);
        })->where('customer_id', '<>', $id)
            ->where('is_deleted', 0)->first();
    }

    public function searchPhone($phone)
    {
        $select = $this->select('customer_id', 'full_name', 'phone1')
            ->where('phone1', 'like', '%' . $phone . '%')
            ->where('is_actived', 1)
            ->where('is_deleted', 0);
        return $select->get();
    }

    /**
     * @param $phone
     * @return mixed
     * Lấy danh sách sđt khách hàng
     */
    public function getCusPhone($phone)
    {
        $select = $this->select('customer_id', 'full_name', 'phone1')
            ->where('phone1', $phone)
            ->where('is_actived', 1)
            ->where('is_deleted', 0);
        return $select->first();
    }

    public function getCusPhone2($phone)
    {
        $select = $this->select('customer_id', 'full_name', 'phone1')
            ->where('phone1', $phone)
            ->where('is_deleted', 0);
        return $select->first();
    }

    /**
     * @return mixed
     * Tổng số khách hàng từ năm hiện tại trở về trước
     */
    public function totalCustomer($yearNow)
    {

        $ds = $this->select(DB::raw('count(customer_id) as number'))
            ->where(DB::raw('YEAR(created_at)'), '<=', $yearNow)
            ->where('is_deleted', 0)->get();
        return $ds;
    }

    /**
     * @param $dayNow
     * @return mixed
     * Tổng số khách hàng đã tạo trong năm hiện tại
     */
    public function totalCustomerNow($yearNow)
    {

        $ds = $this->select(DB::raw('count(customer_id) as number'))
//            ->whereRaw("DATE_FORMAT(created_at,'%Y-%m-%d') = DATE_FORMAT('$yearNow','%Y-%m-%d')")
            ->whereRaw("YEAR(created_at)=$yearNow")
            ->where('is_deleted', 0)
            ->get();
        return $ds;
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     * Tổng số khách hàng trong năm hiện tại trở về trước và chi nhánh
     */
    public function filterCustomerYearBranch($year, $branch)
    {

        $ds = $this->select(DB::raw('count(customer_id) as number'))
            ->where(DB::raw('YEAR(created_at)'), '<=', $year)->where('is_deleted', 0);
        if (!is_null($branch)) {
            $ds->where('branch_id', $branch);
        }
        return $ds->get();
    }

    /**
     * @param $year
     * @param $branch
     * @return mixed
     * Tổng số khách hàng trong năm hiện tại và chi nhánh
     */
    public function filterNowCustomerBranch($year, $branch)
    {
        $ds = $this->select(DB::raw('count(customer_id) as number'))
            ->where(DB::raw('YEAR(created_at)'), '=', $year)->where('is_deleted', 0);
        if (!is_null($branch)) {
            $ds->where('branch_id', $branch);
        }
        return $ds->get();
    }

    /**
     * @param $time
     * @param $branch
     * @return mixed
     * Tổng số KH từ thời gian endTime trở về trước và chi nhánh
     */
    public function filterTimeToTime($time, $branch)
    {

        $arr_filter = explode(" - ", $time);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $ds = $this->select(DB::raw('count(customer_id) as number'))
            ->where(DB::raw('DATE(created_at)'), '<=', $endTime)->where('is_deleted', 0);
        if (!is_null($branch)) {
            $ds->where('branch_id', $branch);
        }
        return $ds->get();
    }

    /**
     * @param $time
     * @param $branch
     * @return mixed
     * Tổng số KH từ khoản thời gian start time và end time
     */
    public function filterTimeNow($time, $branch)
    {
        $arr_filter = explode(" - ", $time);
        $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
        $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
        $ds = $this->select(DB::raw('count(customer_id) as number'))
            ->whereBetween(DB::raw('DATE(created_at)'), [$startTime, $endTime])
            ->where('is_deleted', 0);
        if (!is_null($branch)) {
            $ds->where('branch_id', $branch);
        }
        return $ds->get();
    }

    public function searchCustomerEmail($data, $birthday, $gender, $branch)
    {
        $select = $this->leftJoin('branches', 'branches.branch_id', '=', 'customers.branch_id')
            ->select('customers.customer_id',
                'customers.full_name',
                'customers.phone1', 'customers.birthday',
                'customers.gender', 'branches.branch_name', 'customers.email')
            ->where('customers.is_actived', 1)
            ->where('customers.is_deleted', 0)
            ->where('customers.customer_id', '<>', 1);
        if ($data != null) {
            $select->where(function ($query) use ($data, $birthday, $gender, $branch) {
                $query->where('customers.full_name', 'like', '%' . $data . '%')
                    ->orWhere('customers.phone1', 'like', '%' . $data . '%')
                    ->orWhere('customers.email', 'like', '%' . $data . '%');
            });
        }
        if ($birthday != null) {
            $select->where('customers.birthday', $birthday);
        }
        if ($gender != null) {
            $select->where('customers.gender', $gender);
        }
        if ($branch != null) {
            $select->where('customers.branch_id', $branch);
        }

        return $select->get();
    }

    public function getBirthdays()
    {
        $select = $this->whereMonth('birthday', '=', date('m'))
            ->whereDay('birthday', '=', date('d'))
            ->where('is_deleted', 0)
            ->where('is_actived', 1)
            ->where('phone1', '<>', null)->get();
        return $select;
    }

    //search dashboard
    public function searchDashboard($keyword)
    {
        $select = $this->select(
            'customer_id',
            'full_name',
            'phone1',
            'customers.email as email',
            'branches.branch_name as branch_name',
            'customers.updated_at as updated_at',
            'customer_avatar',
            'customer_id',
            'group_name',
            'customer_avatar'
        )
            ->leftJoin('branches', 'branches.branch_id', '=', 'customers.branch_id')
            ->leftJoin('customer_groups', 'customer_groups.customer_group_id', '=', 'customers.customer_group_id')
            ->where(function ($query) use ($keyword) {
                $query->where('customers.full_name', 'like', '%' . $keyword . '%')
                    ->orWhere('customers.phone1', 'like', '%' . $keyword . '%')
                    ->orWhere('customers.email', 'like', '%' . $keyword . '%');
            })
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1)
            ->where('customers.customer_id', '<>', 1)
            ->get();
        return $select;
    }

    /**
     * Báo cáo công nợ theo khách hàng
     *
     * @param $id_branch
     * @param $time
     * @param $top
     * @return mixed
     */
    public function reportCustomerDebt($id_branch, $time, $top)
    {
        $ds = $this
            ->leftJoin('customer_debt', 'customer_debt.customer_id', '=', 'customers.customer_id')
            ->leftJoin('branches', 'branches.branch_id', '=', 'customers.branch_id')
            ->select(
                'customers.full_name',
                'customer_debt.debt_type',
                'customer_debt.status',
                'customer_debt.amount',
                'customer_debt.amount_paid'
            );
        if (isset($id_branch)) {
            $ds->where('branches.branch_id', $id_branch);
        }
        if (isset($time) && $time != "") {
            $arr_filter = explode(" - ", $time);
            $startTime = Carbon::createFromFormat('d/m/Y', $arr_filter[0])->format('Y-m-d');
            $endTime = Carbon::createFromFormat('d/m/Y', $arr_filter[1])->format('Y-m-d');
            $ds->whereBetween('customer_debt.created_at', [$startTime. ' 00:00:00', $endTime. ' 23:59:59']);
        }
        return $ds->get();
    }

    protected function getListCore(&$filters = [])
    {
        $oSelect = $this
            ->leftJoin(
                'branches', 'branches.branch_id', '=', 'customers.branch_id'
            )
            ->leftJoin(
                'customer_groups', 'customer_groups.customer_group_id', '=',
                'customers.customer_group_id'
            )
            ->select(
                'customers.customer_id as customer_id',
                'customers.branch_id as branch_id',
                'customers.full_name as full_name',
                'customers.birthday as birthday',
                'customers.gender as gender',
                'customers.email as email',
                'customers.phone1 as phone1',
                'customers.customer_code as customer_code',
                'customer_groups.group_name as group_name',
                'customers.customer_avatar as customer_avatar',
                'customers.created_at as created_at',
                'customers.updated_at as updated_at',
                'customers.customer_group_id as customer_group_id',
                'branches.branch_name as branch_name',
                'customers.is_actived'
            )
            ->where('customers.is_deleted', 0)
            ->where('customers.customer_id', '<>', 1)
            ->orderBy('customers.customer_id', 'desc');
        if (isset($filters['arrayUser'])) {
            $oSelect->whereIn('phone1', $filters['arrayUser']);
            unset($filters['arrayUser']);
        }
        return $oSelect;
    }

    public function getCustomerInGroupAuto($arrayCondition)
    {
        $select = $this->select('customers.customer_id')->where(
            'customers.is_deleted', 0
        )->leftJoin(
            'customer_appointments',
            'customer_appointments.customer_id', '=',
            'customers.customer_id'
        );
        foreach ($arrayCondition as $key => $value) {
            if ($key == 1) {
                $select->leftJoin(
                    'customer_group_define_detail',
                    'customer_group_define_detail.phone', '=',
                    'customers.phone1'
                )->orWhere('customer_group_define_detail.id', $value);
            } elseif ($key == 2) {
                $select->orWhere('customer_appointments.date', '>=' , $value);
            } elseif ($key == 3) {
               $select->orWhere('customer_appointments.status', '=' , $value);
            } elseif ($key == 4) {
               $select->orWhereBetween('customer_appointments.time', [$value['hour_from'], $value['hour_to']]);
            }
        }
        dd($select->get());
    }

    public function getCustomerNotAppointment()
    {
        $select = $this->select('customers.customer_id', 'customer_appointments.customer_appointment_id')
            ->leftJoin(
                'customer_appointments',
                'customer_appointments.customer_id',
                'customers.customer_id'
            )
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1)
            ->get();
        return $select;
    }

    public function getCustomerUseService($arrService, $where)
    {
        $select = $this->select(
            'customers.customer_id',
            'orders.order_id',
            'order_details.object_type',
            'order_details.object_id',
            'orders.process_status'
        )
            ->leftJoin('orders','orders.customer_id', '=', 'customers.customer_id')
            ->leftJoin('order_details','order_details.order_id', '=', 'orders.order_id')
            ->where('customers.is_deleted', 0)
            ->where('customers.is_actived', 1);
        return $select->get();
    }

    /**
     * Lấy List full khách hàng
     *
     * @return mixed
     */
    public function getAllCustomer()
    {
        $ds = $this->select(
            'customer_id',
            'full_name',
            'phone1',
            'gender',
            'member_level_id'
        )
            ->where('customer_id', '!=',1)
            ->where('is_deleted', 0)
            ->get();
        return $ds;
    }
}