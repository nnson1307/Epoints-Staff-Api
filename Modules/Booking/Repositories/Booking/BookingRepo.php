<?php

/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-02-17
 * Time: 2:31 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Repositories\Booking;


use App\Jobs\FunctionSendNotify;
use App\Jobs\SendNotification;
use App\Jobs\SendStaffNotification;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Booking\Libs\Sms\SmsFactory;
use Modules\Booking\Models\AppointmentSourceTable;
use Modules\Booking\Models\AppointmentStatusColorTable;
use Modules\Booking\Models\ConfigDetailTable;
use Modules\Booking\Models\ConfigTable;
use Modules\Booking\Models\CustomerAppointmentLogTable;
use Modules\Booking\Models\CustomerTable;
use Modules\Booking\Models\NotificationAutoConfigTable;
use Modules\Booking\Models\NotificationDetailTable;
use Modules\Booking\Models\NotificationLogTable;
use Modules\Booking\Models\CustomerAppointmentDetailTable;
use Modules\Booking\Models\CustomerAppointmentTable;
use Modules\Booking\Models\EmailConfigTable;
use Modules\Booking\Models\EmailLogTable;
use Modules\Booking\Models\EmailProviderTable;
use Modules\Booking\Models\PointHistoryDetailTable;
use Modules\Booking\Models\PointHistoryTable;
use Modules\Booking\Models\PointRewardRuleTable;
use Modules\Booking\Models\RatingLogTable;
use Modules\Booking\Models\RoomTable;
use Modules\Booking\Models\RuleSettingOtherTable;
use Modules\Booking\Models\ServiceBranchPriceTable;
use Modules\Booking\Models\SmsConfigTable;
use Modules\Booking\Models\SmsLogTable;
use Modules\Booking\Models\SmsSettingBrandNameTable;
use Modules\Booking\Models\SpaInfoTable;
use Modules\Booking\Models\StaffTable;
use Modules\Booking\Models\TimeWorkingTable;
use Modules\Home\Repositories\Home\HomeRepoInterface;
use Modules\Product\Repositories\Product\ProductRepoInterface;
use MyCore\Repository\PagingTrait;


class BookingRepo implements BookingRepoInterface
{
    use PagingTrait;

    /**
     * Lấy rule setting other
     *
     * @return mixed|void
     * @throws BookingRepoException
     */
    public function getSettingOther()
    {
        try {
            $mSettingOther = app()->get(RuleSettingOtherTable::class);

            $data = $mSettingOther->getSettingOther();

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new BookingRepoException(BookingRepoException::GET_SETTING_OTHER_FAILED);
        }
    }

    /**
     * Lấy thời gian làm việc trong tuần
     *
     * @return mixed
     * @throws BookingRepoException
     */
    public function getTimes()
    {
        try {
            $mTime = app()->get(TimeWorkingTable::class);

            $data = $mTime->getTimes();

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new BookingRepoException(BookingRepoException::GET_TIME_WORK_FAILED);
        }
    }

    /**
     * Lấy danh sách kỹ thuật viên
     *
     * @param $input
     * @return mixed
     * @throws BookingRepoException
     */
    public function getStaffs($input)
    {
        try {
            $mStaff = app()->get(StaffTable::class);

            $data = $mStaff->getStaffs($input);

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new BookingRepoException(BookingRepoException::GET_SETTING_OTHER_FAILED);
        }
    }

    /**
     * Kiểm tra số lần đặt lịch
     *
     * @param $input
     * @return int|mixed
     * @throws BookingRepoException
     */
    public function checkAppointment($input)
    {
        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);

            $date = Carbon::createFromFormat('d/m/Y', $input['date'])->format('Y-m-d');

            $check = $mAppointment->checkAppointment($input['customer_id'], $date, $input['branch_id'])->toArray();

            return [
                'rule' => 3,
                'number' => count($check),
                'detail' => $check
            ];
        } catch (\Exception | QueryException $exception) {
            throw new BookingRepoException(BookingRepoException::CHECK_APPOINTMENT_FAILED);
        }
    }

    /**
     * Thêm lịch hẹn
     *
     * @param $input
     * @return array|mixed
     * @throws BookingRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function store($input)
    {
        DB::beginTransaction();
        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);
            $mConfig = app()->get(SpaInfoTable::class);

            $customerId = $input['customer_id'];
            $input['date'] = Carbon::createFromFormat('d/m/Y', $input['date'])->format('Y-m-d');


            //Lấy thông tin cấu hình spa
            $infoSpa = $mConfig->getInfo(1);
            //Kiểm tra số lịch hẹn đã đặt trong khung giờ
            $numAppointment = $mAppointment->getAppointmentByTime(Carbon::createFromFormat('Y-m-d', $input['date'])->format('Y-m-d'), $input['time']);

            if ($infoSpa['total_booking_time'] > 0 && $infoSpa['total_booking_time'] <= $numAppointment) {
                throw new BookingRepoException(BookingRepoException::STORE_APPOINTMENT_FAILED);
            }

            $serviceUsingName = '';

            if (isset($input['service']) && count($input['service']) > 0) {
                foreach ($input['service'] as $key => $v) {
                    $serviceUsingName .= $key == 0 ? $v['object_name'] : ',' . $v['object_name'];
                }
            }

            $idSource = $input['appointment_source_id'];

            if ($idSource == null) {
                $idSource = 4; //Set nguồn mặc định là gọi điện
            }

            //Insert customer appointment
            $idAppointment = $mAppointment->add([
                'customer_id' => $customerId,
                'branch_id' => $input['branch_id'],
                'customer_appointment_type' => 'booking',
                'appointment_source_id' => $idSource,
                'customer_quantity' => 1,
                'date' => $input['date'],
                'time' => $input['time'],
                'status' => 'new',
                'total' => $input['total'],
                'discount' => $input['discount'],
                'amount' => $input['amount'],
                'service_using_name' => $serviceUsingName,
                'description' => $input['description'],
                'created_by' => Auth()->id(),
                'updated_by' => Auth()->id()
            ]);

            //Update appointment Code
            if ($idAppointment < 10) {
                $idAppointment = '0' . $idAppointment;
            }
            $mAppointment->edit([
                'customer_appointment_code' => 'LH_' . date('dmY') . $idAppointment
            ], $idAppointment);

            if (isset($input['service']) && count($input['service']) > 0) {
                foreach ($input['service'] as $v) {
                    if (isset($v['object_type']) && $v['object_type'] == 'service') {
                        //Lấy thông tin dv + tên dv
                        $servicePromotion = $this->getPriceBooking([
                            'date' => $input['date'],
                            'time' => $input['time'],
                            'object_type' => $v['object_type'],
                            'object_id' => $v['object_id'],
                            'customer_id' => $customerId
                        ]);

                        $v['price'] = $servicePromotion['price'];
                    }

                    //Insert Appointment Detail
                    $mAppointmentDetail->add([
                        'customer_appointment_id' => $idAppointment,
                        'service_id' => isset($v['object_id']) ? $v['object_id'] : '',
                        'staff_id' => isset($input['staff_id']) && $input['staff_id'] != null ? $input['staff_id'] : null,
                        'room_id' => isset($input['room_id']) && $input['room_id'] != null ? $input['room_id'] : null,
                        'customer_order' => 1,
                        'price' => $v['price'],
                        'object_type' => isset($v['object_type']) ? $v['object_type'] : '',
                        'object_id' => isset($v['object_id']) ? $v['object_id'] : '',
                        'object_code' => isset($v['object_code']) ? $v['object_code'] : '',
                        'object_name' => $v['object_name'],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                    ]);
                }
            }
            //Insert log lịch hẹn
            $mAppointmentLog = app()->get(CustomerAppointmentLogTable::class);
            $mAppointmentLog->add([
                'customer_appointment_id' => $idAppointment,
                'created_type' => 'app',
                'status' => 'new',
                'note' => __('Thêm lịch hẹn từ app'),
                'created_by' => Auth()->id()
            ]);

            DB::commit();

            //Insert Email Log
            $this->addEmailLog($idAppointment, 'LH_' . date('dmY') . $idAppointment, $input['date'], $input['time']);
            //Insert Sms Log
            $mSms = SmsFactory::sendSms('new_appointment');
            $mSms->insertLogSms([
                'sms_type' => 'new_appointment',
                'object_id' => $idAppointment,
                'customer_id' => $input['customer_id']
            ]);

            //Send Notification
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_CUSTOMER,
                'key' => 'appointment_W',
                'customer_id' => $input['customer_id'],
                'object_id' => $idAppointment,
                'tenant_id' => session()->get('idTenant')
            ]);
            //Gửi thông báo nhân viên
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_STAFF,
                'key' => 'appointment_W',
                'customer_id' => $input['customer_id'],
                'object_id' => $idAppointment,
                'branch_id' => $input['branch_id'],
                'tenant_id' => session()->get('idTenant')
            ]);

            //Cộng điểm khi đặt lịch
            $this->plusPoint([
                'customer_id' => Auth()->id(),
                'rule_code' => 'appointment_app',
                'object_id' => $idAppointment
            ]);

            return [
                'customer_appointment_id' => intval($idAppointment)
            ];
        } catch (\Exception | QueryException $e) {
            throw new BookingRepoException(BookingRepoException::STORE_APPOINTMENT_FAILED, $e->getMessage() . $e->getLine());
        }
    }

    /**
     * Cập nhật lịch hẹn
     *
     * @param $input
     * @return array|mixed
     * @throws BookingRepoException
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function update($input)
    {

        DB::beginTransaction();
        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);
            $mConfig = app()->get(SpaInfoTable::class);

            //Lấy thông tin lịch hẹn
            $info = $mAppointment->appointmentInfo($input['customer_appointment_id']);

            $customerId = $info['customer_id'];
            $input['date'] = Carbon::createFromFormat('d/m/Y', $input['date'])->format('Y-m-d');

            $timeOld = Carbon::parse($info['date'] . ' ' . $info['time'])->format('Y-m-d H:i');
            $timeNew = Carbon::parse($input['date'] . ' ' . $input['time'])->format('Y-m-d H:i');

            //Lấy thông tin cấu hình spa
            $infoSpa = $mConfig->getInfo(1);
            //Kiểm tra số lịch hẹn đã đặt trong khung giờ
            $numAppointment = $mAppointment->getAppointmentByTime(Carbon::createFromFormat('Y-m-d', $input['date'])->format('Y-m-d'), $input['time']);

            if ($infoSpa['total_booking_time'] > 0 && $infoSpa['total_booking_time'] <= $numAppointment && $timeOld != $timeNew) {
                throw new BookingRepoException(BookingRepoException::STORE_APPOINTMENT_FAILED);
            }

            //Update customer appointment
            $mAppointment->edit([
                'branch_id' => $input['branch_id'],
                'customer_appointment_type' => 'appointment',
                'appointment_source_id' => 5,
                'customer_quantity' => 1,
                'date' => $input['date'],
                'time' => $input['time'],
                'status' => $input['status'],
                'total' => $input['total'],
                'discount' => $input['discount'],
                'amount' => $input['amount'],
                'description' => $input['description'],
                'updated_by' => Auth()->id()
            ], $input['customer_appointment_id']);

            //Remove appointment detail
            $mAppointmentDetail->remove($input['customer_appointment_id']);

            if (isset($input['service']) && count($input['service']) > 0) {
                foreach ($input['service'] as $v) {

                    if (isset($v['object_type']) && $v['object_type'] == 'service') {

                        //Lấy thông tin dv + tên dv
                        $servicePromotion = $this->getPriceBooking([
                            'date' => $input['date'],
                            'time' => $input['time'],
                            'object_type' => $v['object_type'],
                            'object_id' => $v['object_id'],
                            'customer_id' => $customerId
                        ]);
                        if ($servicePromotion != null) {
                            $v['price'] = $servicePromotion['price'];
                        } else {
                            $v['price'] = 0;
                        }
                    }

                    //Insert Appointment Detail
                    $mAppointmentDetail->add([
                        'customer_appointment_id' => $input['customer_appointment_id'],
                        'service_id' => isset($v['object_id']) ? $v['object_id'] : '',
                        'staff_id' => isset($input['staff_id']) && $input['staff_id'] != null ? $input['staff_id'] : 0,
                        'room_id' => isset($input['room_id']) && $input['room_id'] != null ? $input['room_id'] : null,
                        'customer_order' => 1,
                        'price' => $v['price'],
                        'object_type' => isset($v['object_type']) ? $v['object_type'] : '',
                        'object_id' => isset($v['object_id']) ? $v['object_id'] : '',
                        'object_code' => isset($v['object_code']) ? $v['object_code'] : '',
                        'object_name' => $v['object_name'],
                        'created_by' => Auth()->id(),
                        'updated_by' => Auth()->id(),
                    ]);
                }
            }

            DB::commit();

            return [
                'customer_appointment_id' => $input['customer_appointment_id']
            ];
        } catch (\Exception | QueryException $e) {
            DB::rollback();
            throw new BookingRepoException(BookingRepoException::UPDATE_APPOINTMENT_FAILED, $e->getMessage() . ' ' . $e->getLine() . $e->getFile());
        }
    }

    /**
     * Lấy giá dv + tên dv
     *
     * @param $input
     * @return array
     * @throws BookingRepoException
     */
    protected function getPriceBooking($input)
    {

        try {
            $mServiceBranch = app()->get(ServiceBranchPriceTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            $t = 0;
            //Check ngày đặt lịch theo rule gia khang
            if (session()->get('brand_code') == 'giakhang') {
                $mConfig = app()->get(ConfigTable::class);

                $rangeStart = $mConfig->getConfig('start_time')['value'];
                //                $rangeEnd = $mConfig->getConfig('end_time')['value'];

                if ($input['time'] >= $rangeStart) {
                    $t = 1;
                }
            }
            //Lấy ngày booking
            $date = Carbon::createFromFormat('Y-m-d H:i', $input['date'] . ' ' . $input['time'])
                ->addDays($t);
            //Lấy thông tin dv
            $getInfo = $mServiceBranch->getDetail($input['object_id']);

            if ($getInfo != null) {

                $price = floatval($getInfo['new_price']);
                $serviceName = $getInfo['service_name'];
                //Lấy giá KM của dv + tên dv

                $getPromotion = $mHome->getPromotionDetail(
                    'service',
                    $getInfo['service_code'],
                    $input['customer_id'],
                    'app',
                    null,
                    $getInfo['service_id'],
                    $date->format('Y-m-d H:i')
                );

                if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                    if (isset($getPromotion['price']) && $getPromotion['price'] != null && $getPromotion['price'] < $getInfo['new_price']) {
                        $price = floatval($getPromotion['price']);
                    }
                }

                if (session()->get('brand_code') == 'giakhang') {
                    //Tên dịch vụ của Gia Khang thì kèm theo ngày
                    $serviceName = $serviceName . ' (' . $date->format('d/m/Y H:i') . ')';
                }

                return [
                    'price' => $price,
                    'service_name' => $serviceName
                ];
            } else {
                return null;
            }
        } catch (\Exception | QueryException $e) {
            throw new BookingRepoException(BookingRepoException::STORE_APPOINTMENT_FAILED, $e->getMessage());
        }
    }

    /**
     * Thêm email log
     *
     * @param $idAppointment
     * @param $appointmentCode
     * @param $date
     * @param $time
     */
    private function addEmailLog($idAppointment, $appointmentCode, $date, $time)
    {
        $mProvider = app()->get(EmailProviderTable::class);
        $mEmailConfig = app()->get(EmailConfigTable::class);
        $mLog = app()->get(EmailLogTable::class);

        $checkProvider = $mProvider->getProvider(1);
        if ($checkProvider['is_actived'] == 1) {
            $checkConfig = $mEmailConfig->getEmailConfig('new_appointment');
            if ($checkConfig['is_actived'] == 1) {
                if (Auth::user()->gender == 'male') {
                    $gender = 'Anh';
                } else if (Auth::user()->gender == 'female') {
                    $gender = 'Chị';
                } else {
                    $gender = 'Anh/Chị';
                }
                //replace giá trị của tham số
                $params = [
                    '{name}',
                    '{full_name}',
                    '{gender}',
                    '{birthday}',
                    '{email}',
                    '{day_appointment}',
                    '{time_appointment}',
                    '{code_appointment}',
                    '{name_spa}'
                ];
                $explodeName = explode(' ', Auth::user()->full_name);
                $replaceParams = [
                    array_pop($explodeName),
                    Auth::user()->full_name,
                    $gender,
                    Auth::user()->birthday != null ? date('d/m/Y', strtotime(Auth::user()->birthday)) : '',
                    Auth::user()->email,
                    date('d/m/Y', strtotime($date)),
                    $time,
                    $appointmentCode,
                    'Piospa'
                ];
                $contentLog = $checkConfig['content'];
                $subject = str_replace($params, $replaceParams, $contentLog);

                if (Auth::user()->email != null) {
                    //Insert Email Log
                    $dataLog = [
                        'email' => Auth::user()->email,
                        'customer_name' => Auth::user()->full_name,
                        'email_status' => 'new',
                        'email_type' => 'new_appointment',
                        'content_sent' => $subject,
                        'object_id' => $idAppointment,
                        'object_type' => 'customer_appointment',
                        'created_by' => 0,
                        'updated_by' => 0
                    ];
                    $mLog->add($dataLog);
                }
            }
        }
    }

    /**
     * Thêm sms log
     *
     * @param $idAppointment
     * @param $type
     */
    private function addSmsLog($type, $idAppointment)
    {
        //        $mSettingBrandName = app()->get(SmsSettingBrandNameTable::class);
        //        $mConfig = app()->get(SmsConfigTable::class);
        //        $mLog = app()->get(SmsLogTable::class);
        //
        //        $checkSetting = $mSettingBrandName->getSetting(1);
        //        if ($checkSetting['is_actived'] == 1) {
        //            $checkConfig = $mConfig->getSmsConfig('new_appointment');
        //            if ($checkConfig['is_active'] == 1) {
        //                if (Auth::user()->gender == 'male') {
        //                    $gender = 'Anh';
        //                } else if (Auth::user()->gender == 'female') {
        //                    $gender = 'Chị';
        //                } else {
        //                    $gender = 'Anh/Chị';
        //                }
        //                //replace giá trị của tham số
        //                $params = [
        //                    '{CUSTOMER_NAME}',
        //                    '{CUSTOMER_FULL_NAME}',
        //                    '{CUSTOMER_GENDER}',
        //                    '{DATETIME_APPOINTMENT}',
        //                    '{CODE_APPOINTMENT}',
        //                    '{NAME_SPA}'
        //                ];
        //                $explodeName = explode(' ', Auth::user()->full_name);
        //                $replaceParams = [
        //                    array_pop($explodeName),
        //                    Auth::user()->full_name,
        //                    $gender,
        //                    date('H:i d/m/Y', strtotime($time . $date)),
        //                    $appointmentCode,
        //                    'Piospa'
        //                ];
        //                $contentLog = $checkConfig['content'];
        //                $message = str_replace($params, $replaceParams, $contentLog);
        //                //Insert Sms Log
        //                $dataLog = [
        //                    'brandname' => $checkSetting['value'],
        //                    'phone' => Auth::user()->phone1,
        //                    'customer_name' => Auth::user()->full_name,
        //                    'message' => $message,
        //                    'sms_type' => 'new_appointment',
        //                    'time_sent' => null,
        //                    'created_by' => 0,
        //                    'sms_status' => 'new',
        //                    'object_id' => $idAppointment,
        //                    'object_type' => 'customer_appointment',
        //                ];
        //                $mLog->add($dataLog);
        //            }
        //        }
    }


    /**
     * Thời gian đặt lịch
     *
     * @param $input
     * @return array|mixed
     * @throws BookingRepoException
     */
    public function timeBooking($input)
    {
        try {
            $mCustomerAppointment = app()->get(CustomerAppointmentTable::class);
            $mTimeWorking = app()->get(TimeWorkingTable::class);
            $mConfig = app()->get(SpaInfoTable::class);
            //Lấy thông tin cấu hình spa
            $infoSpa = $mConfig->getInfo(1);

            $date = Carbon::createFromFormat('d/m/Y', $input['date'])->format('l');

            $getTime = $mTimeWorking->getTimeByEngName($date);

            $arrayTime = [
                "00:00" => true, "00:15" => true, "00:30" => true, "00:45" => true,
                "01:00" => true, "01:15" => true, "01:30" => true, "01:45" => true,
                "02:00" => true, "02:15" => true, "02:30" => true, "02:45" => true,
                "03:00" => true, "03:15" => true, "03:30" => true, "03:45" => true,
                "04:00" => true, "04:15" => true, "04:30" => true, "04:45" => true,
                "05:00" => true, "05:15" => true, "05:30" => true, "05:45" => true,
                "06:00" => true, "06:15" => true, "06:30" => true, "06:45" => true,
                "07:00" => true, "07:15" => true, "07:30" => true, "07:45" => true,
                "08:00" => true, "08:15" => true, "08:30" => true, "08:45" => true,
                "09:00" => true, "09:15" => true, "09:30" => true, "09:45" => true,
                "10:00" => true, "10:15" => true, "10:30" => true, "10:45" => true,
                "11:00" => true, "11:15" => true, "11:30" => true, "11:45" => true,
                "12:00" => true, "12:15" => true, "12:30" => true, "12:45" => true,
                "13:00" => true, "13:15" => true, "13:30" => true, "13:45" => true,
                "14:00" => true, "14:15" => true, "14:30" => true, "14:45" => true,
                "15:00" => true, "15:15" => true, "15:30" => true, "15:45" => true,
                "16:00" => true, "16:15" => true, "16:30" => true, "16:45" => true,
                "17:00" => true, "17:15" => true, "17:30" => true, "17:45" => true,
                "18:00" => true, "18:15" => true, "18:30" => true, "18:45" => true,
                "19:00" => true, "19:15" => true, "19:30" => true, "19:45" => true,
                "20:00" => true, "20:15" => true, "20:30" => true, "20:45" => true,
                "21:00" => true, "21:15" => true, "21:30" => true, "21:45" => true,
                "22:00" => true, "22:15" => true, "22:30" => true, "22:45" => true,
                "23:00" => true, "23:15" => true, "23:30" => true, "23:45" => true
            ];

            $arrBooking = [];

            foreach ($arrayTime as $key => $item) {
                //                $timeNow = Carbon::now()->format('Y-m-d H:i');
                //                $timeBook = Carbon::createFromFormat('d/m/Y H:i', $input['date'] . $key)->format('Y-m-d H:i');
                //                if ($timeBook > $timeNow && strtotime($key) >= strtotime($getTime['start_time']) && strtotime($key) <= strtotime($getTime['end_time'])) {
                //Kiểm tra số lịch hẹn đã đặt trong khung giờ
                $numAppointment = $mCustomerAppointment->getAppointmentByTime(Carbon::createFromFormat('d/m/Y', $input['date'])->format('Y-m-d'), $key);

                if ($infoSpa['total_booking_time'] > 0 && $infoSpa['total_booking_time'] <= $numAppointment) {
                    $item = false;
                }

                $arrBooking[] = [
                    'time' => $key,
                    'rule' => $item
                ];
                //                }
            }

            return [
                'date' => $input['date'],
                'times' => $arrBooking
            ];
        } catch (\Exception | QueryException $exception) {
            throw new BookingRepoException(BookingRepoException::GET_TIME_BOOKING_FAILED);
        }
    }

    /**
     * Chi tiết lịch sử đặt lịch
     *
     * @param $appointmentId
     * @return mixed
     * @throws BookingRepoException
     */
    public function getBookingHistoryDetail($appointmentId)
    {
        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);
            $mPointHistory = app()->get(PointHistoryTable::class);

            //Thông tin lịch hẹn
            $data = $mAppointment->appointmentInfo($appointmentId);

            //Format time
            $data['time'] = Carbon::parse($data['time'])->format('H:i');

            $data['full_address'] = $data['address'];

            if ($data['ward_name'] != null) {
                $data['full_address'] .=  ', ' . $data['ward_type'] . ' ' . $data['ward_name'];
            }

            if ($data['district_name'] != null) {
                $data['full_address'] .=  ', ' . $data['district_type'] . ' ' . $data['district_name'];
            }

            if ($data['province_name'] != null) {
                $data['full_address'] .=  ', ' . $data['province_type'] . ' ' . $data['province_name'];
            }

            $customerId = $data['customer_id'];
            //Danh sách cộng điểm khi đặt lịch
            $getPoint = $mPointHistory->getPointBooking($customerId);
            $dataPoint = [];
            if (count($getPoint) > 0) {
                foreach ($getPoint as $v) {
                    $dataPoint[$v['object_id']] = $v['point'];
                }
            }

            $data['point'] = isset($dataPoint[$appointmentId]) ? $dataPoint[$appointmentId] : 0;

            if ($data['date'] >= Carbon::now()->format('Y-m-d') && !in_array($data['status'], ['wait', 'finish', 'cancel'])) {
                //Cho phép hủy
                $data['is_cancel'] = 1;
            } else {
                //Ko cho hủy
                $data['is_cancel'] = 0;
            }

            $data['is_edit'] = 1;

            if (in_array($data['status'], ['finish', 'cancel'])) {
                $data['is_edit'] = 0;
            }

            //Lấy tên status
            $data['status_name'] = $this->setStatusName($data['status']);
            //Lấy status được update
            $data['status_update'] = $this->setStatusUpdate($data['status']);
            //Kiểm tra user đã đánh giá lịch hẹn này chưa
            $data['is_review'] = 0;
            $mRatingLog = app()->get(RatingLogTable::class);
            $log = $mRatingLog->getLogByUser("appointment", $appointmentId, Auth()->id());
            if ($log != null) {
                $data['is_review'] = 1;
            }
            //Chi tiết lịch hẹn
            $arrStyList = [];

            $data['appointment_detail'] = [];
            $data['appointment_detail'] = $mAppointmentDetail->getDetailAppointment($appointmentId, $customerId);
            if (isset($data['appointment_detail']) && count($data['appointment_detail']) > 0) {
                foreach ($data['appointment_detail'] as $v) {
                    if ($v['staff_name'] != null) {
                        $arrStyList[] = [
                            'staff_id' => $v['staff_id'],
                            'staff_name' => $v['staff_name'],
                            'staff_avatar' => $v['staff_avatar'],
                            'room_name' => $v['room_name'],
                            'room_id' => $v['room_id']
                        ];
                    }
                }
            }

            $data['stylist'] = count($arrStyList) > 0 ? $arrStyList[0] : null;
            //Log cập nhật trạng thái lịch hẹn
            $mAppointmentLog = app()->get(CustomerAppointmentLogTable::class);
            $data['log'] = $mAppointmentLog->getLog($appointmentId);

            return $data;
        } catch (\Exception $exception) {

            throw new BookingRepoException(BookingRepoException::GET_BOOKING_HISTORY_DETAIL_FAILED);
        }
    }

    /**
     * Lấy tên trạng thái đặt lịch
     *
     * @param $status
     * @return array|null|string
     */
    private function setStatusName($status)
    {
        $statusName = '';

        switch ($status) {
            case 'new':
                $statusName = __('Mới');
                break;
            case 'confirm':
                $statusName = __('Xác nhận');
                break;
            case 'cancel':
                $statusName = __('Đã huỷ');
                break;
            case 'finish':
                $statusName = __('Đã hoàn thành');
                break;
            case 'wait':
                $statusName = __('Chờ phục vụ');
                break;
            case 'processing':
                $statusName = __('Đang phục vụ');
                break;
        }

        return $statusName;
    }

    /**
     * Lấy trạng thái được cập nhật
     *
     * @param $status
     * @return array
     */
    private function setStatusUpdate($status)
    {
        $mStatusColor = app()->get(AppointmentStatusColorTable::class);

        //Lấy màu trạng thái lịch hẹn
        $getColor = $mStatusColor->getStatusColor()->toArray();

        $collection = collect($getColor);

        $data = [
            [
                'status' => 'new',
                'status_name' => $this->setStatusName('new'),
                'status_primary_color' => $collection->where('status', 'new')[0]['status_primary_color'],
                'status_sub_color' => $collection->where('status', 'new')[0]['status_sub_color'],
                'status_text_color' => $collection->where('status', 'new')[0]['status_text_color']
            ],
            [
                'status' => 'confirm',
                'status_name' => $this->setStatusName('confirm'),
                'status_primary_color' => $collection->where('status', 'confirm')[1]['status_primary_color'],
                'status_sub_color' => $collection->where('status', 'confirm')[1]['status_sub_color'],
                'status_text_color' => $collection->where('status', 'confirm')[1]['status_text_color']
            ],
            [
                'status' => 'wait',
                'status_name' => $this->setStatusName('wait'),
                'status_primary_color' => $collection->where('status', 'wait')[4]['status_primary_color'],
                'status_sub_color' => $collection->where('status', 'wait')[4]['status_sub_color'],
                'status_text_color' => $collection->where('status', 'wait')[4]['status_text_color']
            ],
            [
                'status' => 'processing',
                'status_name' => $this->setStatusName('processing'),
                'status_primary_color' => $collection->where('status', 'processing')[5]['status_primary_color'],
                'status_sub_color' => $collection->where('status', 'processing')[5]['status_sub_color'],
                'status_text_color' => $collection->where('status', 'processing')[5]['status_text_color']
            ],
            [
                'status' => 'finish',
                'status_name' => $this->setStatusName('finish'),
                'status_primary_color' => $collection->where('status', 'finish')[3]['status_primary_color'],
                'status_sub_color' => $collection->where('status', 'finish')[3]['status_sub_color'],
                'status_text_color' => $collection->where('status', 'finish')[3]['status_text_color']
            ],
            [
                'status' => 'cancel',
                'status_name' => $this->setStatusName('cancel'),
                'status_primary_color' => $collection->where('status', 'cancel')[2]['status_primary_color'],
                'status_sub_color' => $collection->where('status', 'cancel')[2]['status_sub_color'],
                'status_text_color' => $collection->where('status', 'cancel')[2]['status_text_color']
            ],
        ];

        switch ($status) {
            case 'new':
                unset($data[4]);
                break;
            case 'confirm':
                unset($data[0], $data[4]);
                break;
            case 'wait':
                unset($data[0], $data[1], $data[4]);
                break;
            case 'processing':
                unset($data[0], $data[1], $data[2], $data[4]);
                break;
            case 'finish':
                unset($data[0], $data[1], $data[2], $data[3], $data[5]);
                break;
            case 'cancel':
                unset($data[0], $data[1], $data[2], $data[3], $data[4]);
                break;
        }

        return array_values($data);
    }

    /**
     * Trạng thái lịch hẹn
     *
     * @return array|mixed
     * @throws BookingRepoException
     */
    public function getStatusBooking()
    {
        try {
            $data = [
                [
                    'status' => 'new',
                    'name' => __('Mới')
                ],
                [
                    'status' => 'confirm',
                    'name' => __('Đã xác nhận')
                ],
                [
                    'status' => 'cancel',
                    'name' => __('Hủy')
                ],
                [
                    'status' => 'finish',
                    'name' => __('Hoàn thành')
                ],
                [
                    'status' => 'wait',
                    'name' => __('Chờ phục vụ')
                ]
            ];

            return $data;
        } catch (\Exception $exception) {
            throw new BookingRepoException(BookingRepoException::GET_STATUS_FAILED);
        }
    }

    /**
     * Cộng điểm khi có event
     *
     * @param $param
     * @return mixed
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function plusPoint($param)
    {;
        $brandCode = session()->get('brand_code');

        $endpoint = sprintf(BASE_URL_API, $brandCode) . '/loyalty/plus-point-event';

        $client = new \GuzzleHttp\Client();

        $response = $client->request('POST', $endpoint, ['query' => $param]);

        $statusCode = $response->getStatusCode();
        $content = $response->getBody();

        return json_decode($response->getBody(), true);
    }

    /**
     * Hủy lịch hẹn
     *
     * @param $input
     * @return mixed|void
     * @throws BookingRepoException
     */
    public function cancel($input)
    {
        try {
            $mConfig = app()->get(ConfigTable::class);
            $mConfigDetail = app()->get(ConfigDetailTable::class);
            $mPointHistory = app()->get(PointHistoryTable::class);
            $mAppointment = app()->get(CustomerAppointmentTable::class);

            //Lấy thông tin lịch hẹn
            $info = $mAppointment->appointmentInfo($input['customer_appointment_id'], Auth()->id());
            //Cập nhật trạng thái lịch hẹn
            $mAppointment->edit([
                'status' => 'cancel'
            ], $input['customer_appointment_id']);
            //Lưu log
            $mAppointmentLog = app()->get(CustomerAppointmentLogTable::class);
            $mAppointmentLog->add([
                'customer_appointment_id' => $input['customer_appointment_id'],
                'created_type' => 'app',
                'status' => 'cancel',
                'note' => 'Hủy lịch hẹn từ app',
                'created_by' => Auth()->id()
            ]);
            //Send sms
            $mSms = SmsFactory::sendSms('cancel_appointment');
            $mSms->insertLogSms([
                'sms_type' => 'cancel_appointment',
                'object_id' => $input['customer_appointment_id'],
                'customer_id' => Auth()->id()
            ]);
            //Send notification
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_CUSTOMER,
                'key' => 'appointment_C',
                'customer_id' => Auth()->id(),
                'object_id' => $input['customer_appointment_id'],
                'tenant_id' => session()->get('idTenant')
            ]);
            //Gửi thông báo nhân viên
            FunctionSendNotify::dispatch([
                'type' => SEND_NOTIFY_STAFF,
                'key' => 'appointment_C',
                'customer_id' => Auth()->id(),
                'object_id' => $input['customer_appointment_id'],
                'branch_id' => $info['branch_id'],
                'tenant_id' => session()->get('idTenant')
            ]);
            //Kiểm tra cấu hình giữ điểm khi hủy lịch hẹn
            $config = $mConfig->getConfig('save_point_appointment_cancel');
            if ($config['value'] == 1) {
                $configDetail = $mConfigDetail->getDetail($config['config_id']);
                //Đếm số lịch hẹn đã hủy
                $numberCancel = $mAppointment->numberAppointmentCancel(Carbon::now()->format('Y-m-d'));

                if ($numberCancel > $configDetail['value']) {
                    //Trừ điểm khi vượt quá số lịch cho phép trong ngày
                    $this->subtractPoint($input['customer_appointment_id']);
                }
            } else {
                //Trừ điểm khi không cấu hình
                $this->subtractPoint($input['customer_appointment_id']);
            }
        } catch (\Exception $exception) {
            throw new BookingRepoException(BookingRepoException::CANCEL_APPOINTMENT_FAILED, $exception->getMessage());
        }
    }

    /**
     * Trừ điểm thành viên khi hủy lịch hẹn
     *
     * @param $objectId
     */
    private function subtractPoint($objectId)
    {
        $mCustomer = app()->get(CustomerTable::class);
        $mRule = app()->get(PointRewardRuleTable::class);
        $mPointHistory = app()->get(PointHistoryTable::class);
        $mPointHistoryDetail = app()->get(PointHistoryDetailTable::class);

        $point = $mPointHistory->getPointBookingByAppointment(Auth()->id(), $objectId);
        //Xóa lịch sử tích điểm của lịch bị xóa
        $mPointHistory->edit([
            'is_deleted' => 1
        ], $point['point_history_id']);
        //Lấy thông tin khách hàng
        $customer = $mCustomer->getInfoById(Auth()->id());
        //Cập nhật điểm user
        $mCustomer->editUser([
            'point' => $customer['point'] - $point['point']
        ], Auth()->id());

        if ($point['point'] != null) {
            //Insert point history
            $pointHistoryId = $mPointHistory->add([
                'customer_id' => Auth()->id(),
                'point' => $point['point'],
                'type' => 'subtract',
                'point_description' => 'appointment_app',
                'object_id' => $objectId
            ]);
            //Insert point history detail
            $rule = $mRule->getRule('appointment_app');
            $mPointHistoryDetail->add([
                'point_history_id' => $pointHistoryId,
                'point_reward_rule_id' => $rule['point_reward_rule_id']
            ]);
        }
    }

    /**
     * Đặt lịch lại
     *
     * @param $input
     * @return mixed|void
     * @throws BookingRepoException
     */
    public function reBooking($input)
    {
        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);
            $mServiceBranchPrice = app()->get(ServiceBranchPriceTable::class);
            $mHome = app()->get(HomeRepoInterface::class);

            //Lấy thông tin lịch hẹn
            $getBooking = $mAppointment->appointmentInfoByCode($input['customer_appointment_code'], Auth()->id());
            //Lấy thông tin chi tiết lịch hẹn
            $getDetail = $mAppointmentDetail->getDetailAppointment($getBooking['customer_appointment_id'], Auth()->id());
            $data['service'] = [];

            if (count($getDetail) > 0) {
                foreach ($getDetail as $v) {

                    if ($v['service_id'] != null) {
                        //Lấy thông tin service_branch_price
                        $getService = $mServiceBranchPrice->getDetail($v['service_id']);
                        $getService['old_price'] = null;
                        $getService['new_price'] = floatval($getService['new_price']);

                        if ($getService != null) {
                            $getPromotion = $mHome->getPromotionDetail('service', $getService['service_code'], Auth()->id(), 'app', null, $getService['service_id']);

                            $promotion = [];
                            if (isset($getPromotion) && $getPromotion['price'] != null || $getPromotion['price'] != null) {
                                if (isset($getPromotion['price']) && $getPromotion['price'] != null) {
                                    // Tinh phan tram
                                    if ($getPromotion['price'] < $getService['new_price']) {
                                        $percent = $getPromotion['price'] / $getService['new_price'] * 100;
                                        $promotion['price'] = (100 - round($percent, 2)) . '%';
                                        // Tính lại giá khi có khuyến mãi
                                        $getService['old_price'] = $getService['new_price'];
                                        $getService['new_price'] = floatval($getPromotion['price']);
                                        $getService['is_new'] = 0;
                                    }
                                }
                                if (isset($getPromotion['gift'])) {
                                    $promotion['gift'] = $getPromotion['gift'];
                                    $getService['is_new'] = 0;
                                }
                            } else {
                                // service new
                                $getService['is_new'] = 1;
                                $getService['promotion'] = null;
                            }

                            if (empty($promotion)) {
                                $promotion = null;
                            }
                            $getService['promotion'] = $promotion;

                            $data['service'][] = $getService;
                        }
                    }
                }
            }

            return $data;
        } catch (\Exception $exception) {
            throw new BookingRepoException(BookingRepoException::CANCEL_APPOINTMENT_FAILED, $exception->getMessage());
        }
    }

    /**
     * Lấy giá KM dịch vụ
     *
     * @param $input
     * @return mixed|void
     * @throws BookingRepoException
     */
    public function getPriceService($input)
    {
        try {
            $date = Carbon::createFromFormat('d/m/Y', $input['date'])->format('Y-m-d');

            $service = [];

            if (isset($input['service']) && count($input['service']) > 0) {
                foreach ($input['service'] as $v) {
                    //Lấy thông tin dv + tên dv
                    $servicePromotion = $this->getPriceBooking([
                        'date' => $date,
                        'time' => $input['time'],
                        'object_type' => $v['object_type'],
                        'object_id' => $v['object_id'],
                    ]);

                    $v['price'] = $servicePromotion['price'];
                    $service[] = $v;
                }
            }

            return [
                'date' => $input['date'],
                'time' => $input['time'],
                'service' => $service
            ];
        } catch (\Exception $e) {
            throw new BookingRepoException(BookingRepoException::GET_PRICE_SERVICE_FAILED, $e->getMessage());
        }
    }

    /**
     * Lấy ds phòng phục vụ
     *
     * @return mixed
     * @throws BookingRepoException
     */
    public function getRoom()
    {
        try {
            $mRoom = app()->get(RoomTable::class);

            return $mRoom->optionRoom();
        } catch (\Exception $e) {
            throw new BookingRepoException(BookingRepoException::GET_ROOM_FAILED, $e->getMessage());
        }
    }

    /**
     * Lấy nguồn lịch hẹn
     *
     * @return mixed|void
     * @throws BookingRepoException
     */
    public function getAppointmentSource()
    {
        try {
            $mSource = app()->get(AppointmentSourceTable::class);

            return $mSource->optionSource();
        } catch (\Exception $e) {
            throw new BookingRepoException(BookingRepoException::GET_APPOINTMENT_SOURCE_FAILED, $e->getMessage());
        }
    }

    /**
     * DS lịch hẹn theo ngày/tuần/tháng
     *
     * @param $input
     * @return mixed|void
     * @throws BookingRepoException
     */
    public function getListByDayWeekMonth($input)
    {

        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);

            $input['date_end'] = $input['date_end'] != null ? $input['date_end'] : $input['date_start'];

            //Lấy ds lịch hẹn
            $data = $mAppointment->getAppointments($input);

            if (count($data) > 0) {
                foreach ($data as $v) {
                    $v['time'] = Carbon::parse($v['time'])->format('H:i');
                    //Lấy tên status
                    $v['status_name'] = $this->setStatusName($v['status']);
                    //Lấy status được update
                    $v['status_update'] = $this->setStatusUpdate($v['status']);
                    //Chi tiết lịch hẹn
                    $arrStyList = [];

                    $v['appointment_detail'] = [];
                    $v['appointment_detail'] = $mAppointmentDetail->getDetailAppointment($v['customer_appointment_id'], $v['customer_id']);

                    if (isset($v['appointment_detail']) && count($v['appointment_detail']) > 0) {
                        foreach ($v['appointment_detail'] as $v1) {
                            if ($v1['staff_name'] != null) {
                                $arrStyList[] = [
                                    'staff_id' => $v1['staff_id'],
                                    'staff_name' => $v1['staff_name'],
                                    'staff_avatar' => $v1['staff_avatar'],
                                    'room_name' => $v1['room_name'],
                                    'room_id' => $v1['room_id']
                                ];
                            }
                        }
                    }

                    $v['stylist'] = count($arrStyList) > 0 ? $arrStyList[0] : null;
                }
            }

            return $data;
        } catch (\Exception $e) {
            throw new BookingRepoException(BookingRepoException::GET_LIST_FAILED, $e->getMessage());
        }
    }

    /**
     * DS lịch hẹn theo khung giờ
     *
     * @param $input
     * @return mixed|void
     * @throws BookingRepoException
     */
    public function getListRangeTime($input)
    {
        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);

            $data = $mAppointment->getAppointmentRangeTime($input);

            if (count($data) > 0) {
                foreach ($data as $v) {
                    $v['time'] = Carbon::parse($v['time'])->format('H:i');
                    //Lấy tên status
                    $v['status_name'] = $this->setStatusName($v['status']);
                    //Lấy status được update
                    $v['status_update'] = $this->setStatusUpdate($v['status']);
                    //Chi tiết lịch hẹn
                    $arrStyList = [];

                    $v['appointment_detail'] = [];
                    $v['appointment_detail'] = $mAppointmentDetail->getDetailAppointment($v['customer_appointment_id'], $v['customer_id']);

                    if (isset($v['appointment_detail']) && count($v['appointment_detail']) > 0) {
                        foreach ($v['appointment_detail'] as $v1) {
                            if ($v1['staff_name'] != null) {
                                $arrStyList[] = [
                                    'staff_id' => $v1['staff_id'],
                                    'staff_name' => $v1['staff_name'],
                                    'staff_avatar' => $v1['staff_avatar'],
                                    'room_name' => $v1['room_name'],
                                    'room_id' => $v1['room_id']
                                ];
                            }
                        }
                    }

                    $v['stylist'] = count($arrStyList) > 0 ? $arrStyList[0] : null;
                }
            }

            return $data;
        } catch (\Exception $e) {
            throw new BookingRepoException(BookingRepoException::GET_LIST_RANGE_TIME_FAILED, $e->getMessage());
        }
    }

    /**
     * Danh sách lịch hẹn của KH
     *
     * @param $input
     * @return array|mixed
     * @throws BookingRepoException
     */
    public function getListCustomer($input)
    {
        try {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);

            $data = $mAppointment->getBookingCustomer($input);

            if (count($data->items()) > 0) {
                foreach ($data->items() as $v) {
                    $v['time'] = Carbon::parse($v['time'])->format('H:i');
                    //Lấy tên status
                    $v['status_name'] = $this->setStatusName($v['status']);
                    //Lấy status được update
                    $v['status_update'] = $this->setStatusUpdate($v['status']);
                    //Chi tiết lịch hẹn
                    $arrStyList = [];

                    $v['appointment_detail'] = [];
                    $v['appointment_detail'] = $mAppointmentDetail->getDetailAppointment($v['customer_appointment_id'], $v['customer_id']);

                    if (isset($v['appointment_detail']) && count($v['appointment_detail']) > 0) {
                        foreach ($v['appointment_detail'] as $v1) {
                            if ($v1['staff_name'] != null) {
                                $arrStyList[] = [
                                    'staff_id' => $v1['staff_id'],
                                    'staff_name' => $v1['staff_name'],
                                    'staff_avatar' => $v1['staff_avatar'],
                                    'room_name' => $v1['room_name'],
                                    'room_id' => $v1['room_id']
                                ];
                            }
                        }
                    }

                    $v['stylist'] = count($arrStyList) > 0 ? $arrStyList[0] : null;
                }
            }

            return $this->toPagingData($data);
        } catch (\Exception $e) {
            throw new BookingRepoException(BookingRepoException::GET_BOOKING_HISTORY_LIST_FAILED, $e->getMessage());
        }
    }
}
