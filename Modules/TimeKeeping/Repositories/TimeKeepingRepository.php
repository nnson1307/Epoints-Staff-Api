<?php


namespace Modules\TimeKeeping\Repositories;


use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Modules\TimeKeeping\Enum\TimeKeepingTypeEnum;
use Modules\TimeKeeping\Helpers\Helpers;
use Modules\TimeKeeping\Models\CheckInLogTable;
use Modules\TimeKeeping\Models\CheckOutLogTable;
use Modules\TimeKeeping\Models\ConfigGeneralTable;
use Modules\TimeKeeping\Models\StaffHolidayTable;
use Modules\TimeKeeping\Models\TimeWorkingStaffTable;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use MyCore\Repository\PagingTrait;

class TimeKeepingRepository implements TimeKeepingInterface
{

    use PagingTrait;
    protected $timeWorkingStaffTable;
    protected $checkInLogTable;
    protected $checkOutLogTable;

    public function __construct(
        TimeWorkingStaffTable $timeWorkingStaffTable,
        CheckInLogTable $checkInLogTable,
        CheckOutLogTable $checkOutLogTable
    )
    {
        $this->timeWorkingStaffTable = $timeWorkingStaffTable;
        $this->checkInLogTable = $checkInLogTable;
        $this->checkOutLogTable = $checkOutLogTable;
    }

    /**
     * Lấy ca làm việc hiện tại của staff
     * @param array $all
     * @return mixed
     */
    public function getShift(array $all)
    {
        $timeWorking = $this->timeWorkingStaffTable->getCurrentShift(Auth::id());
        if (empty($timeWorking)) {
            $timeWorking = $this->timeWorkingStaffTable->getBeforeShift(Auth::id());
        }
        throw_if((empty($timeWorking)), new TimeKeepingRepoException(TimeKeepingRepoException::STAFF_NOT_HAVE_TIME_WORKING));
        return $timeWorking;
    }

    /**
     * Check in
     * @param array $all
     * @return mixed
     * @throws TimeKeepingRepoException
     * @throws \Throwable
     */
    public function checkIn(array $all)
    {
        $timeWorking = $this->timeWorkingStaffTable->getTimeWorking(Auth::id(), $all['time_working_staff_id']);
        $currentDate = Carbon::now();
        try {
            if (empty($timeWorking)) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::EMPTY_TIME_WORKING);
            }
            if ($timeWorking['is_check_in'] == 1) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::HAVE_BEEN_CHECK_IN);
            }

            $user = Auth::user();
            $checkSumHeaderStr = "{$all['access_point_ip']}{$user['salt']}";
            $checkSumHeader = md5($checkSumHeaderStr);
            if ($checkSumHeader != $all['access_point_check_sum']) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::PAYLOAD_HAVE_BEEN_CHANGED);
            }
            $latitude = $all['latitude'] ?? "";
            $longitude = $all['longitude'] ?? "";
            $checkSumStr = "{$latitude}{$longitude}{$all['time_working_staff_id']}{$all['device_id']}{$all['access_point_ip']}{$all['access_point_check_sum']}{$user['salt']}";
            $checkSum = md5($checkSumStr);
            if ($checkSum != $all['check_sum']) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::PAYLOAD_HAVE_BEEN_CHANGED);
            }
//
//            if ($all['request_ip'] != $all['access_point_ip']) {
//                throw new TimeKeepingRepoException(TimeKeepingRepoException::WIFI_IP_HAVE_BEEN_CHANGED);
//            }

            if (!isset($timeWorking['configs']) || sizeof($timeWorking['configs']) == 0) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::WIFI_IP_NOT_CONFIG);
            }

            $correctConfig = $this->checkWithWifi($timeWorking, $all['access_point_ip'], $all['latitude'] ?? null, $all['longitude'] ?? null);
            // $correctConfig = $this->checkWithWifi($timeWorking, $all['request_ip'], $all['latitude'] ?? null, $all['longitude'] ?? null);


            $this->checkInLogTable->checkIn(
                [
                    "time_working_staff_id" => $timeWorking['time_working_staff_id'],
                    "staff_id" => Auth::id(),
                    "branch_id" => $timeWorking['branch_id'],
                    "shift_id" => $timeWorking['shift_id'],
                    "check_in_day" => $currentDate->format("Y-m-d"),
                    "check_in_time" => $currentDate->format("H:i:s"),
                    "status" => CheckInLogTable::OK_STATUS,
                    "reason" => "",
                    "created_type" => CheckInLogTable::STAFF_CREATED_TYPE,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                    "created_by" => Auth::id(),
                    "wifi_name" => $all['wifi_name'] ?? "",
                    "wifi_ip" => $all['access_point_ip'] ?? "",
                    "request_ip" => $all['request_ip'],
                    "timekeeping_type" => $correctConfig['config']['timekeeping_type'] ?? TimeKeepingTypeEnum::WIFI,
                    "latitude" => $all['latitude'] ?? null,
                    "longitude" => $all['longitude'] ?? null,
                    "radius" => $correctConfig['distance'] ?? 0,
                ]
            );

            $mConfigGeneral = app()->get(ConfigGeneralTable::class);
            //Lấy cầu hình chung của ca làm việc
            $listConfig = $mConfigGeneral->getConfig();
            //Set giá trị cấu hình chung
            $arrConfigGeneral = $this->_setConfigGeneral($listConfig);


            $dateWorking = Carbon::createFromFormat('Y-m-d H:i:s', "{$timeWorking['working_day']} {$timeWorking['working_time']}")->addMinutes($arrConfigGeneral['late_check_in']);
            $interval = $dateWorking->diffInMinutes($currentDate, false);

            $this->timeWorkingStaffTable->checkIn(
                Auth::id(),
                $all['time_working_staff_id'],
                [
                    "is_check_in" => 1,
                    "number_late_time" => $interval > 0 ? $interval : 0,
                    "updated_at" => Carbon::now()
                ]
            );

            return $this->getShift($all);
        } catch (Exception | TimeKeepingRepoException $e) {
            if (isset($timeWorking)) {
                $this->checkInLogTable->checkIn(
                    [
                        "time_working_staff_id" => 0,
                        "staff_id" => Auth::id(),
                        "branch_id" => $timeWorking['branch_id'],
                        "shift_id" => $timeWorking['shift_id'],
                        "check_in_day" => $currentDate->format("Y-m-d"),
                        "check_in_time" => $currentDate->format("H:i:s"),
                        "status" => CheckInLogTable::NOT_OK_STATUS,
                        "reason" => $timeWorking['time_working_staff_id'] . ':' . $e->getMessage(),
                        "created_type" => CheckInLogTable::STAFF_CREATED_TYPE,
                        "created_at" => Carbon::now(),
                        "updated_at" => Carbon::now(),
                        "created_by" => Auth::id(),
                        "wifi_name" => $all['wifi_name'] ?? "",
                        "wifi_ip" => $all['access_point_ip'] ?? "",
                        "request_ip" => $all['request_ip'],
                        "timekeeping_type" => TimeKeepingTypeEnum::WIFI,
                        "latitude" => $all['latitude'] ?? "",
                        "longitude" => $all['longitude'] ?? "",
                        "radius" => 0,
                    ]
                );
            }
            throw $e;
        }
    }

    /**
     * Ưu tiên checkin bằng wifi
     *
     * @param $timeWorking
     * @param $request_ip
     * @return float|int|null
     * @throws TimeKeepingRepoException
     */
    private function checkWithWifi($timeWorking, $request_ip, $latitude, $longitude)
    {
        // danh sách cấu hình wifi
        $wifiConfigs = $timeWorking['configs']->filter(function ($item) {
            return $item['timekeeping_type'] == TimeKeepingTypeEnum::WIFI;
        })->values();

        // check wifi cấu hình với wifi request
        $currentConfig = $this->getCurrentConfig($wifiConfigs, $request_ip);
        if ($currentConfig == null) {

            // Nếu không có wifi thì lấy cấu hình gps
            $gpsConfigs = $timeWorking['configs']->filter(function ($item) {
                return $item['timekeeping_type'] == TimeKeepingTypeEnum::GPS;
            })->values();
            if (sizeof($gpsConfigs) > 0) {
                $result = $this->checkWithGPS($gpsConfigs, $latitude, $longitude);
                if (empty($result)) {
                    if (sizeof($wifiConfigs) > 0) {
                        throw new TimeKeepingRepoException(
                            TimeKeepingRepoException::WIFI_CHECK_IN_NOT_CORRECT,
                            __("Vui lòng kết nối với wifi :wifi_name hoặc đến gần văn phòng để check-in", ["wifi_name" => $this->getWifiName($wifiConfigs)])
                        );
                    } else {
                        throw new TimeKeepingRepoException(
                            TimeKeepingRepoException::WIFI_CHECK_IN_NOT_CORRECT,
                            __("Vui lòng đến gần văn phòng để check-in")
                        );
                    }
                }
                return $result;
            } else {
                throw new TimeKeepingRepoException(
                    TimeKeepingRepoException::WIFI_CHECK_IN_NOT_CORRECT,
                    __("Vui lòng kết nối với wifi :wifi_name để check-in", ["wifi_name" => $this->getWifiName($wifiConfigs)])
                );
            }
        }
        return [
            'config' => $currentConfig,
            'distance' => 0,
        ];
    }

    private function checkWithGPS($gpsConfigs, $latitude, $longitude)
    {

        foreach ($gpsConfigs as $config) {
            $distance = Helpers::distance(
                floatval($config['latitude']),
                floatval($config['longitude']),
                floatval($latitude),
                floatval($longitude)
            );
            if ($config['allowable_radius'] >= $distance) {
                return [
                    'config' => $config,
                    'distance' => $distance,
                ];
            }
        }
        return null;
    }

    /**
     * Check out
     * @param array $all
     * @return mixed
     * @throws \Throwable
     */
    public function checkOut(array $all)
    {
        //Lấy thông tin ca
        $timeWorking = $this->timeWorkingStaffTable->getTimeWorking(Auth::id(), $all['time_working_staff_id']);

        //Thời gian check out
        $currentDate = Carbon::now();

        try {
            if (empty($timeWorking)) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::EMPTY_TIME_WORKING);
            }
            if ($timeWorking['is_check_out'] == 1) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::HAVE_BEEN_CHECK_OUT);
            }
            $user = Auth::user();
            $checkSumHeaderStr = "{$all['access_point_ip']}{$user['salt']}";
            $checkSumHeader = md5($checkSumHeaderStr);
            if ($checkSumHeader != $all['access_point_check_sum']) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::PAYLOAD_HAVE_BEEN_CHANGED);
            }
            $latitude = $all['latitude'] ?? "";
            $longitude = $all['longitude'] ?? "";
            $checkSumStr = "{$latitude}{$longitude}{$all['time_working_staff_id']}{$all['device_id']}{$all['access_point_ip']}{$all['access_point_check_sum']}{$user['salt']}";
            $checkSum = md5($checkSumStr);
            if ($checkSum != $all['check_sum']) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::PAYLOAD_HAVE_BEEN_CHANGED);
            }
//            if ($all['request_ip'] != $all['access_point_ip']) {
//                throw new TimeKeepingRepoException(TimeKeepingRepoException::WIFI_IP_HAVE_BEEN_CHANGED);
//            }
            if (!isset($timeWorking['configs']) || sizeof($timeWorking['configs']) == 0) {
                throw new TimeKeepingRepoException(TimeKeepingRepoException::WIFI_IP_NOT_CONFIG);
            }
            $correctConfig = $this->checkWithWifi($timeWorking, $all['access_point_ip'], $all['latitude'] ?? null, $all['longitude'] ?? null);
            // $correctConfig = $this->checkWithWifi($timeWorking, $all['request_ip'], $all['latitude'] ?? null, $all['longitude'] ?? null);

            //Insert log check out
            $this->checkOutLogTable->checkOut([
                "time_working_staff_id" => $timeWorking['time_working_staff_id'],
                "staff_id" => Auth::id(),
                "branch_id" => $timeWorking['branch_id'],
                "shift_id" => $timeWorking['shift_id'],
                "check_out_day" => $currentDate->format("Y-m-d"),
                "check_out_time" => $currentDate->format("H:i:s"),
                "status" => CheckOutLogTable::OK_STATUS,
                "reason" => "",
                "created_type" => CheckOutLogTable::STAFF_CREATED_TYPE,
                "created_at" => Carbon::now(),
                "updated_at" => Carbon::now(),
                "wifi_name" => $all['wifi_name'] ?? "",
                "wifi_ip" => $all['access_point_ip'] ?? "",
                "request_ip" => $all['request_ip'],
                "timekeeping_type" => $correctConfig['config']['timekeeping_type'] ?? TimeKeepingTypeEnum::WIFI,
                "latitude" => $all['latitude'] ?? null,
                "longitude" => $all['longitude'] ?? null,
                "radius" => $correctConfig['distance'] ?? 0,
            ]);

            $mConfigGeneral = app()->get(ConfigGeneralTable::class);
            //Lấy cầu hình chung của ca làm việc
            $listConfig = $mConfigGeneral->getConfig();
            //Set giá trị cấu hình chung
            $arrConfigGeneral = $this->_setConfigGeneral($listConfig);

            $dateWorking = Carbon::createFromFormat('Y-m-d H:i:s', "{$timeWorking['working_end_day']} {$timeWorking['working_end_time']}")->subMinutes($arrConfigGeneral['back_soon_check_out']);
            $interval = $dateWorking->diffInMinutes($currentDate, false);

            //Tính giờ thực tế làm việc
            $actuleTimeWork = 0;

            if ($timeWorking['is_check_in'] == 1 && $timeWorking['check_in_day'] != null && $timeWorking['check_in_time'] != null) {
                $dateTimeCheckIn = Carbon::parse($timeWorking['check_in_day'] .' '. $timeWorking['check_in_time']);

                $actuleTimeWork = $currentDate->diffInMinutes($dateTimeCheckIn) / 60;

                if ($actuleTimeWork > $timeWorking['time_work']) {
                    $actuleTimeWork = $timeWorking['time_work'];
                }
            }

            //Lưu check out vào ca
            $this->timeWorkingStaffTable->checkOut(
                Auth::id(),
                $all['time_working_staff_id'],
                [
                    "is_check_out" => 1,
                    "number_time_back_soon" => $interval < 0 ? abs($interval) : 0,
                    "actual_time_work" => $actuleTimeWork,
                    "updated_at" => Carbon::now()
                ]
            );
            return $this->getShift($all);
        } catch (TimeKeepingRepoException  $e) {
            if (isset($timeWorking)) {
                $this->checkOutLogTable->checkOut([

                    "time_working_staff_id" => 0,
                    "staff_id" => Auth::id(),
                    "branch_id" => $timeWorking['branch_id'],
                    "shift_id" => $timeWorking['shift_id'],
                    "check_out_day" => $currentDate->format("Y-m-d"),
                    "check_out_time" => $currentDate->format("H:i:s"),
                    "status" => CheckOutLogTable::NOT_OK_STATUS,
                    "reason" => $timeWorking['time_working_staff_id'] . ':' . $e->getMessage(),
                    "created_type" => CheckOutLogTable::STAFF_CREATED_TYPE,
                    "created_at" => Carbon::now(),
                    "updated_at" => Carbon::now(),
                    "wifi_name" => $all['wifi_name'] ?? "",
                    "wifi_ip" => $all['access_point_ip'] ?? "",
                    "request_ip" => $all['request_ip'],
                    "timekeeping_type" => TimeKeepingTypeEnum::WIFI,
                    "latitude" => $all['latitude'] ?? "",
                    "longitude" => $all['longitude'] ?? "",
                    "radius" => 0,
                ]);
            }
            throw $e;
        }
    }

    /**
     * Lấy lịch sử chấm công
     *
     * @param array $all
     * @return mixed
     */
    public function getHistories(array $all)
    {
        $mConfigGeneral = app()->get(ConfigGeneralTable::class);
        //Lấy cầu hình chung của ca làm việc
        $listConfig = $mConfigGeneral->getConfig();
        //Set giá trị cấu hình chung
        $arrConfigGeneral = $this->_setConfigGeneral($listConfig);

        $results = $this->toPagingData($this->timeWorkingStaffTable->countTimeKeepingHistories($all));
        $logs = [];
        if (sizeof($results['Items']) > 1) {
            $lastIndex = sizeof($results['Items']) - 1;
            $from_date = $results['Items'][$lastIndex]['working_day'];
            $to_date = $results['Items'][0]['working_day'];
            $logs = $this->timeWorkingStaffTable->getTimeKeepingHistories($all, $from_date, $to_date);
        } else if (sizeof($results['Items']) > 0) {
            $from_date = $results['Items'][0]['working_day'];
            $to_date = Carbon::now()->format('Y-m-d');
            $logs = $this->timeWorkingStaffTable->getTimeKeepingHistories($all, $from_date, $to_date);
        }
        $timeKeepingGroups = $this->groupTimeKeepingByDate($logs);
        $items = [];
        foreach ($timeKeepingGroups as $key => $value) {
            $timekeepingLogs = $this->getTimeKeepingLogs($value);
            $arrBackgroud = $this->checkShift($value, $arrConfigGeneral);
            $strBackground = $arrBackgroud[0];
            $arrayLogs = [];
            if (count($timekeepingLogs["timekeeping_logs"]) > 0) {
                foreach ($timekeepingLogs["timekeeping_logs"] as $key1 => $obj) {
                    $strBackground = $this->checkShiftObject($obj, $arrConfigGeneral);
                    $obj['back_ground'] = $strBackground;
                    array_push($arrayLogs, $obj);
                }
            }
            $item = [
                "timekeeping_date" => $key,
                "check_in_total" => SizeOf($value),
                "check_out_total" => SizeOf($value),
                "check_in_count" => $timekeepingLogs["check_in_count"],
                "check_out_count" => $timekeepingLogs["check_out_count"],
                "timekeeping_logs" => $arrayLogs,
                "back_ground" => $strBackground
            ];
            array_push($items, $item);
        }
        $results['Items'] = $items;
        return $results;
    }

    /**
     * Group chấm công theo ngày
     *
     * @param $Items
     * @return array
     */
    private function groupTimeKeepingByDate($Items): array
    {
        $items = [];
        foreach ($Items as $item) {
            $history = $item;
            if (isset($items[$item['working_day']])) {

                array_push($items[$item['working_day']], $history);
            } else {
                $items[$item['working_day']] = [$history];
            }
        }
        return $items;
    }

    /**
     * Log chấm công checkin & checkout
     * @param $timeKeepingLogs
     */
    private function getTimeKeepingLogs($timeKeepingLogs)
    {
        $result = [];
        $checkInCount = 0;
        $checkOutCount = 0;

        foreach ($timeKeepingLogs as $item) {
            if ($item['is_check_in'] == 1) {
                $checkInCount++;
            }
            if ($item['is_check_out'] == 1) {
                $checkOutCount++;
            }
            $result[] = $item;
        }

        return [
            "check_in_count" => $checkInCount,
            "check_out_count" => $checkOutCount,
            "timekeeping_logs" => $result
        ];
    }

    private function getCurrentConfig($configs, $request_ip)
    {
        foreach ($configs as $config) {
            if ($config['wifi_ip'] == $request_ip) {
                return $config;
            }
        }
        return null;
    }

    private function getWifiName($configs)
    {
        $wifiName = "";
        foreach ($configs as $config) {
            $wifiName = $wifiName . $config['wifi_name'] . ', ';
        }
        return strlen($wifiName) > 0 ? substr($wifiName, 0, strlen($wifiName) - 2) : $wifiName;
    }

    /**
     * Lấy lịch sử chấm công của cá nhân
     * @param array $all
     * @return mixed
     */
    public function getPersonalHistories(array $all)
    {
        try {
            $mConfigGeneral = app()->get(ConfigGeneralTable::class);

            //Lấy cầu hình chung của ca làm việc
            $listConfig = $mConfigGeneral->getConfig();
            //Set giá trị cấu hình chung
            $arrConfigGeneral = $this->_setConfigGeneral($listConfig);

            $results = $this->timeWorkingStaffTable->getPersonalTimeKeepingHistories(Auth::id(), $all);

            $timeKeepingGroups = $this->groupTimeKeepingByDate($results);

            $items = [];

            foreach ($timeKeepingGroups as $key => $value) {
                $timekeepingLogs = $this->getTimeKeepingLogs($value);
                $arrBackgroud = $this->checkShift($value, $arrConfigGeneral);
                $strBackground = $arrBackgroud[0];
                $arrayLogs = [];
                if (count($timekeepingLogs["timekeeping_logs"]) > 0) {
                    foreach ($timekeepingLogs["timekeeping_logs"] as $key1 => $obj) {
                        $strBackground = $this->checkShiftObject($obj, $arrConfigGeneral);
                        $obj['back_ground'] = $strBackground;
                        array_push($arrayLogs, $obj);
                    }
                }

                $item = [
                    "timekeeping_date" => $key,
                    "check_in_total" => SizeOf($value),
                    "check_out_total" => SizeOf($value),
                    "check_in_count" => $timekeepingLogs["check_in_count"],
                    "check_out_count" => $timekeepingLogs["check_out_count"],
                    "timekeeping_logs" => $arrayLogs,
                    "back_ground" => $strBackground
                ];
                array_push($items, $item);
            }

            return $items;
        } catch (\Exception $e) {
            dd($e->getMessage() . $e->getLine() . $e->getFile());
        }
    }

    /**
     * Set giá trị cấu hình chung của ca làm việc
     *
     * @param $listConfig
     * @return array
     */
    private function _setConfigGeneral($listConfig)
    {
        //Tính đi trễ khi check in vào sau
        $lateCheckIn = 0;
        //Tính nghỉ không lương khi check in vào sau
        $offCheckIn = 0;
        //Tính về sớm khi check in ra trước
        $backSoonCheckOut = 0;
        //Tính nghỉ không lương khi check out ra trước
        $offCheckOut = 0;

        if (count($listConfig) > 0) {
            foreach ($listConfig as $v) {
                if ($v['is_actived'] == 0) {
                    continue;
                }

                $unit = 1;

                if ($v['config_general_unit'] == 'hour') {
                    $unit = 60;
                }

                switch ($v['config_general_code']) {
                    case 'late_check_in':
                        $lateCheckIn = intval($v['config_general_value']) * $unit;
                        break;
                    case 'off_check_in':
                        $offCheckIn = intval($v['config_general_value']) * $unit;
                        break;
                    case 'back_soon_check_out':
                        $backSoonCheckOut = intval($v['config_general_value']) * $unit;
                        break;
                    case 'off_check_out':
                        $offCheckOut = intval($v['config_general_value']) * $unit;
                        break;
                }
            }
        }

        return [
            'late_check_in' => $lateCheckIn,
            'off_check_in' => $offCheckIn,
            'back_soon_check_out' => $backSoonCheckOut,
            'off_check_out' => $offCheckOut
        ];
    }

    function checkShift($arr = [], $arrConfigGeneral)
    {

        try {
            if (count($arr) == 0) {
                return [
                    "#D3D3D3"
                ];
            }

            $arrData = [];

            foreach ($arr as $value => $item) {
                $strBackground = "#D3D3D3";

                if (Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('Y-m-d') > \Carbon\Carbon::now()->format('Y-m-d')) {
                    $strBackground = "#D3D3D3";
                    if ($item['is_deducted'] === 0) {
                        $strBackground = "#D9DCF0";
                    }
                    if ($item['is_deducted'] === 1) {
                        $strBackground = "#EBD4EF";
                    }
                } elseif (Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                    $strBackground = "#DBEFDC";
                    if ($item['is_check_in'] == 0 || $item['is_check_out'] == 0) {
                        $strBackground = "#FDD9D7";
                    }
                    if ($item['is_deducted'] === 0) {
                        $strBackground = "#D9DCF0";
                    }
                    if ($item['is_deducted'] === 1) {
                        $strBackground = "#EBD4EF";
                    }
                    if ($item['check_in_day'] != null && $item['check_in_time'] != null) {
                        if ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['late_check_in']) < \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time'])) {
                            //Vào trễ
                            $strBackground = "#FFEACC";
                        }
                    }
                    if (isset($item['check_out_day']) && isset($item['check_out_time'])) {
                        if ($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['back_soon_check_out']) > \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time'])) {
                            //Ra sớm
                            $strBackground = "#FFEACC";
                        }
                    }

                    if (isset($item['check_in_day']) && isset($item['check_in_time']) && isset($item['check_out_day']) && isset($item['check_out_time'])) {
                        if (($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['back_soon_check_out']) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time']))
                            && ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['late_check_in']) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time']))
                        ) {
                            //Ra vào đúng giờ
                            $strBackground = "#DBEFDC";
                        }
                    }

                    //Check có check in (nghỉ không lương so với cấu hình)
                    if ($item['is_check_in'] === 1 &&
                        Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['off_check_in']) < Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time'])
                        && $arrConfigGeneral['off_check_in'] > 0) {
                        //Nghĩ không lương
                        $strBackground = "#EBD4EF";
                    }

                    //Check có check out (nghỉ không lương so với cấu hình)
                    if ($item['is_check_out'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['off_check_out']) > \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time'])
                        && $arrConfigGeneral['off_check_out'] > 0) {
                        //Nghĩ không lương
                        $strBackground = "#EBD4EF";
                    }
                } else {
                    if ($item['is_deducted'] === 0) {
                        $strBackground = "#D9DCF0";
                    } elseif ($item['is_deducted'] === 1) {
                        $strBackground = "#EBD4EF";
                    } else {
                        if ($item['is_check_in'] === 0 && $item['is_check_out'] === 0) {
                            if ($item['is_deducted'] === 0) {
                                $strBackground = "#D9DCF0";
                            } else {
                                $strBackground = "#EBD4EF";
                            }
                        } else {
                            if ($item['is_check_in'] === 0 || $item['is_check_out'] === 0) {
                                $strBackground = "#FDD9D7";
                            }
                        }

                        if (isset($item['check_in_day']) && isset($item['check_in_time'])) {
                            if ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['late_check_in']) < \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time'])) {
                                //Vào trễ
                                $strBackground = "#FFEACC";
                            }
                        }

                        if (isset($item['check_out_day']) && isset($item['check_out_time'])) {
                            if ($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['back_soon_check_out']) > \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time'])) {
                                //Ra sớm
                                $strBackground = "#FFEACC";
                            }
                        }

                        if (isset($item['check_in_day']) && isset($item['check_in_time']) && isset($item['check_out_day']) && isset($item['check_out_time'])) {
                            if (($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['back_soon_check_out']) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time']))
                                && ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['late_check_in']) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time']))
                            ) {
                                //Ra vào đúng giờ
                                $strBackground = "#DBEFDC";
                            }
                        }

                        //Check có check in (nghỉ không lương so với cấu hình)
                        if ($item['is_check_in'] === 1 &&
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['off_check_in']) < \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time'])
                            && $arrConfigGeneral['off_check_in'] > 0) {
                            //Nghĩ không lương
                            $strBackground = "#EBD4EF";
                        }

                        //Check có check out (nghỉ không lương so với cấu hình)
                        if ($item['is_check_out'] === 1 &&
                            \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['off_check_out']) > \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time'])
                            && $arrConfigGeneral['off_check_out'] > 0) {
                            //Nghĩ không lương
                            $strBackground = "#EBD4EF";
                        }
                    }
                }

                array_push($arrData, $strBackground);
            }

            return $arrData;
        } catch (Exception $ex) {
            var_dump($arr);
            die;
        }
    }

    function checkShiftObject($item, $arrConfigGeneral)
    {

        try {
            $strBackground = "#D3D3D3";
            if (Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('Y-m-d') > \Carbon\Carbon::now()->format('Y-m-d')) {
                $strBackground = "#D3D3D3";
                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                }
                if ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                }
            } elseif (Carbon::createFromFormat('Y-m-d', $item['working_day'])->format('Y-m-d') == Carbon::now()->format('Y-m-d')) {
                $strBackground = "#DBEFDC";
                if ($item['is_check_in'] == 0 || $item['is_check_out'] == 0) {
                    $strBackground = "#FDD9D7";
                }
                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                }
                if ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                }
                if ($item['check_in_day'] != null && $item['check_in_time'] != null) {
                    if ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['late_check_in']) < \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time'])) {
                        //Vào trễ
                        $strBackground = "#FFEACC";
                    }
                }
                if (isset($item['check_out_day']) && isset($item['check_out_time'])) {
                    if ($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['back_soon_check_out']) > \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time'])) {
                        //Ra sớm
                        $strBackground = "#FFEACC";
                    }
                }

                if (isset($item['check_in_day']) && isset($item['check_in_time']) && isset($item['check_out_day']) && isset($item['check_out_time'])) {
                    if (($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['back_soon_check_out']) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time']))
                        && ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['late_check_in']) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time']))
                    ) {
                        //Ra vào đúng giờ
                        $strBackground = "#DBEFDC";
                    }
                }

                //Check có check in (nghỉ không lương so với cấu hình)
                if ($item['is_check_in'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['off_check_in']) < \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time'])
                    && $arrConfigGeneral['off_check_in'] > 0) {
                    //Nghĩ không lương
                    $strBackground = "#EBD4EF";
                }

                //Check có check out (nghỉ không lương so với cấu hình)
                if ($item['is_check_out'] === 1 &&
                    \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['off_check_out']) > \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time'])
                    && $arrConfigGeneral['off_check_out'] > 0) {
                    //Nghĩ không lương
                    $strBackground = "#EBD4EF";
                }
            } else {
                if ($item['is_deducted'] === 0) {
                    $strBackground = "#D9DCF0";
                } elseif ($item['is_deducted'] === 1) {
                    $strBackground = "#EBD4EF";
                } else {
                    if ($item['is_check_in'] === 0 && $item['is_check_out'] === 0) {
                        if ($item['is_deducted'] === 0) {
                            $strBackground = "#D9DCF0";
                        } else {
                            $strBackground = "#EBD4EF";
                        }
                    } else {
                        if ($item['is_check_in'] === 0 || $item['is_check_out'] === 0) {
                            $strBackground = "#FDD9D7";
                        }
                    }
                    if (isset($item['check_in_day']) && isset($item['check_in_time'])) {
                        if ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['late_check_in']) < \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time'])) {
                            //Vào trễ
                            $strBackground = "#FFEACC";
                        }
                    }
                    if (isset($item['check_out_day']) && isset($item['check_out_time'])) {
                        if ($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['back_soon_check_out']) > \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time'])) {
                            //Ra sớm
                            $strBackground = "#FFEACC";
                        }
                    }

                    if (isset($item['check_in_day']) && isset($item['check_in_time']) && isset($item['check_out_day']) && isset($item['check_out_time'])) {
                        if (($item['is_check_out'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['back_soon_check_out']) <= \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time']))
                            && ($item['is_check_in'] === 1 && Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['late_check_in']) >= \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time']))
                        ) {
                            //Ra vào đúng giờ
                            $strBackground = "#DBEFDC";
                        }
                    }

                    //Check có check in (nghỉ không lương so với cấu hình)
                    if ($item['is_check_in'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['working_day'] . ' ' . $item['working_time'])->addMinutes($arrConfigGeneral['off_check_in']) < \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_in_day'] . ' ' . $item['check_in_time'])
                        && $arrConfigGeneral['off_check_in'] > 0) {
                        //Nghĩ không lương
                        $strBackground = "#EBD4EF";
                    }

                    //Check có check out (nghỉ không lương so với cấu hình)
                    if ($item['is_check_out'] === 1 &&
                        \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['working_end_day'] . ' ' . $item['working_end_time'])->subMinutes($arrConfigGeneral['off_check_out']) > \Carbon\Carbon::createFromFormat('Y-m-d H:i', $item['check_out_day'] . ' ' . $item['check_out_time'])
                        && $arrConfigGeneral['off_check_out'] > 0) {
                        //Nghĩ không lương
                        $strBackground = "#EBD4EF";
                    }
                }
            }

            return $strBackground;
        } catch (Exception $ex) {
        }
    }

    /**
     * Lấy ngày lễ
     *
     * @param $input
     * @return mixed|void
     * @throws TimeKeepingRepoException
     */
    public function getDayHoliday($input)
    {
        try {
            $tStart = Carbon::parse($input['start_date']);
            $tEnd = Carbon::parse($input['end_date']);

           if ($tStart->format('Y-m-d') > $tEnd->format('Y-m-d')) {
               throw new TimeKeepingRepoException(TimeKeepingRepoException::GET_DAY_HOLIDAY_FAILED, __('Ngày bắt đầu phải nhỏ hơn hoặc bằng ngày kết thúc'));
           }

            //Lấy số ngày cách nhau
            $diffDate = $tEnd->diffInDays($tStart);

            $data = [];

            $mStaffHoliday = app()->get(StaffHolidayTable::class);

            for ($i = 0; $i <= $diffDate; $i++) {
                $date = Carbon::parse($input['start_date'])->addDays($i)->format('Y-m-d');

                //Check ngày lễ
                $checkHoliday = $mStaffHoliday->checkDayInHoliday($date);

                if (count($checkHoliday) > 0) {
                    $holidayName = "";

                    foreach ($checkHoliday as $k => $v) {
                        $space = $k + 1 < count($checkHoliday) ? ', ' : '';

                        $holidayName .= $v['staff_holiday_title'] . $space;
                    }

                    $data [] = [
                        'holiday_date' => $date,
                        'holiday_name' => $holidayName
                    ];
                }

            }

            return $data;
        } catch (\Exception $e) {
            throw new TimeKeepingRepoException(TimeKeepingRepoException::GET_DAY_HOLIDAY_FAILED, $e->getMessage());
        }
    }
}