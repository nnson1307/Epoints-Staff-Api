<?php
namespace Modules\User\Repositories\Device;

use App\Http\Middleware\SwitchDatabaseTenant;
use App\Models\PiospaBrandTable;
use Modules\User\Models\CheckVersionTable;

/**
 * Class DeviceRepo
 * @package Modules\User\Repositories\Device
 * @author DaiDP
 * @since Aug, 2019
 */
class DeviceRepo implements DeviceRepoInterface
{
    /**
     * Check version ứng dụng
     *
     * @param $deviceInfo
     * @return mixed
     */
    public function checkVersion($deviceInfo)
    {
        $mVersion = app(CheckVersionTable::class);
        $oVersion = $mVersion->getLastestVersion($deviceInfo['platform']);

        return [
            // 'is_update'    => (int) $this->checkUpdate($deviceInfo, $oVersion),
            'is_update'    => 0,
            'version'      => $oVersion->version ?? null,
            'release_date' => $oVersion->release_date ?? null,
            'link'         => $oVersion->link ?? null,
            'is_review' => $oVersion->is_review ?? null
        ];
    }

    /**
     * Kiểm tra điều kiện cập nhật
     *
     * @param $deviceInfo
     * @param $oVersion
     * @return bool
     */
    protected function checkUpdate($deviceInfo, $oVersion)
    {
        // Không có thông tin version
        if (! $oVersion) {
            return false;
        }

        // Không có bật cờ cập nhật
        if (! $oVersion->flag) {
            return false;
        }

        // Tên phiên bản giống nhau
        if (version_compare($deviceInfo['version'], $oVersion->version, '>=')) {
            return false;
        }

        return true;
    }
}