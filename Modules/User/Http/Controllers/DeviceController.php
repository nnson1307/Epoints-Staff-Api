<?php
namespace Modules\User\Http\Controllers;

use Modules\User\Http\Requests\Device\CheckVersionRequest;
use Modules\User\Repositories\Device\DeviceRepoInterface;

/**
 * Class DeviceController
 * @package Modules\User\Http\Controllers
 * @author DaiDP
 * @since Aug, 2019
 */
class DeviceController extends Controller
{
    protected $device;


    /**
     * DeviceController constructor.
     * @param DeviceRepoInterface $device
     */
    public function __construct(DeviceRepoInterface $device)
    {
        $this->device = $device;
    }

    /**
     * Kiểm tra phiên bản ứng dụng
     *
     * @param CheckVersionRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function checkVersionAction(CheckVersionRequest $request)
    {
        $input = $request->all();
        $data  = $this->device->checkVersion($input);

        return $this->responseJson(CODE_SUCCESS, null, $data);
    }
}