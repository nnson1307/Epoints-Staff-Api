<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 27/04/2021
 * Time: 15:47
 */

namespace Modules\Booking\Libs\Sms;
use Carbon\Carbon;
use Modules\Booking\Models\CustomerAppointmentDetailTable;
use Modules\Booking\Models\CustomerAppointmentTable;
use Modules\Booking\Models\SmsConfigTable;
use Modules\Booking\Models\SmsLogTable;
use Modules\Booking\Models\SmsSettingBrandNameTable;
use Modules\Booking\Models\SpaInfoTable;

class CancelAppointment extends SmsAbstract
{
    /**
     * Function lưu log sms
     *
     * @param $input
     * @return mixed|void
     */
    public function insertLogSms($input)
    {
        $mSettingBrandName = app()->get(SmsSettingBrandNameTable::class);
        $mConfig = app()->get(SmsConfigTable::class);

        //Kiểm tra cấu hình nhà cung cấp (sms provider)
        $checkProvider = $mSettingBrandName->getSetting(1);
        //Kiểm tra cấu hình sms (sms config)
        $checkConfig = $mConfig->getSmsConfig($input['sms_type']);

        if ($checkProvider['is_actived'] == 1 && $checkConfig['is_active'] == 1) {
            $mAppointment = app()->get(CustomerAppointmentTable::class);
            $mSpaInfo = app()->get(SpaInfoTable::class);
            //Lấy thông tin cửa hang (spa_info)
            $spaInfo = $mSpaInfo->getInfo(1);
            //Lấy thông tin lịch hẹn
            $getInfo = $mAppointment->getInfoSendSms($input['object_id']);
            //Build nội dung gửi
            $data = $this->replaceContent($checkConfig, $getInfo, $spaInfo);
            //Lấy brand name gửi sms
            $data['brandname'] = $checkProvider['value'];
            //Lưu log sms
            $this->_insertLog($data);
        }
    }

    /**
     * Build nội dung gửi sms
     *
     * @param $config
     * @param $info
     * @param $spaInfo
     * @return array
     */
    private function replaceContent($config, $info, $spaInfo)
    {
        $mAppointmentDetail = app()->get(CustomerAppointmentDetailTable::class);
        //Lấy chi tiết lịch hẹn
        $getDetail = $mAppointmentDetail->getDetail($info['customer_appointment_id']);

        $productName = '';
        if (count($getDetail) > 0) {
            foreach ($getDetail as $k => $v) {
                if (in_array($v['object_type'], ['service', 'member_card'])) {
                    $comma = $k + 1 < count($getDetail) ? ';' : '';
                    $productName .= $v['object_name'] . $comma;
                }
            }
        }
        //Cắt tên dịch vụ nếu quá 50 kí tự
        if (strlen($productName) > 50) {
            $productName = substr($productName, 0, 47) . '...';
        }

        $configContent = $config['content'];

        $gender = __('Anh');
        if ($info['gender'] == 'female') {
            $gender = __('Chị');
        } elseif ($info['gender'] == 'other') {
            $gender = __('Anh/Chị');
        }

        //Build nội dung tin nhắn
        $message = str_replace(
            [
                '{CUSTOMER_NAME}',
                '{CUSTOMER_FULL_NAME}',
                '{CUSTOMER_GENDER}',
                '{DATETIME_APPOINTMENT}',
                '{CODE_APPOINTMENT}',
                '{NAME_SPA}',
                '{PRODUCT_NAME}'
            ],
            [
                substr($info['full_name'], strrpos($info['full_name'], ' ') + 1) . ' ',
                $info['full_name'] . ' ',
                $gender . ' ',
                $info['time'] . ' ' . Carbon::createFromFormat('Y-m-d', $info['date'])->format('d/m/Y') . ' ',
                $info['customer_appointment_code'] . ' ',
                $spaInfo['name'] . ' ',
                $productName
            ], $configContent
        );
        //Data lưu sms log
        return [
            'phone' => $info['phone'],
            'customer_name' => $info['full_name'],
            'message' => $message,
            'sms_type' => $config['key'],
            'time_sent' => null,
            'sms_status' => 'new',
            'object_id' => $info['customer_appointment_id'],
            'object_type' => 'customer_appointment'
        ];
    }

    /**
     * Lưu log sms
     *
     * @param $data
     */
    private function _insertLog($data)
    {
        $mSmsLog = app()->get(SmsLogTable::class);

        if (isset($data['phone']) && !empty($data['phone'])) {
            //Lưu log sms
            $mSmsLog->add($data);
        }
    }
}