<?php
namespace Modules\User\Repositories\Device;

/**
 * Class DeviceRepoInterface
 * @package Modules\User\Repositories\Device
 * @author DaiDP
 * @since Aug, 2019
 */
interface DeviceRepoInterface
{
    /**
     * Check version ứng dụng
     *
     * @param $deviceInfo
     * @return mixed
     */
    public function checkVersion($deviceInfo);
}