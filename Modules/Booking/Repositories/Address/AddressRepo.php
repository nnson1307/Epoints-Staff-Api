<?php
/**
 * Created by PhpStorm   .
 * User: Mr Son
 * Date: 2020-01-06
 * Time: 2:14 PM
 * @author SonDepTrai
 */

namespace Modules\Booking\Repositories\Address;

use Illuminate\Database\QueryException;
use Modules\Booking\Models\BranchTable;
use Modules\Booking\Models\DistrictTable;
use Modules\Booking\Models\ProvinceTable;
use Modules\Booking\Models\WardTable;

class AddressRepo implements AddressRepoInterface
{

    /**
     * Lấy Option Tỉnh Thành của chi nhánh
     *
     * @return mixed
     * @throws AddressRepoException
     */
    public function getProvinces()
    {
        $mProvince = app()->get(ProvinceTable::class);
        $mBranch = app()->get(BranchTable::class);

        try {
            $mProvinceBranch = $mBranch->getProvinceBranch();
            $arrProvinceBranch = [];

            if (count($mProvinceBranch) > 0) {
                foreach ($mProvinceBranch as $v) {
                    $arrProvinceBranch[] = $v['provinceid'];
                }
            }

            $data = $mProvince->getProvinces($arrProvinceBranch);

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new AddressRepoException(AddressRepoException::GET_OPTION_PROVINCE_FAILED);
        }
    }

    /**
     * Lấy Option Quận Huyện
     *
     * @param $provinceId
     * @return mixed
     * @throws AddressRepoException
     */
    public function getDistricts($provinceId)
    {
        $mDistrict = app()->get(DistrictTable::class);

        try {
            $provinceId = sprintf("%02d", $provinceId);
            $data = $mDistrict->getDistricts($provinceId);

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new AddressRepoException(AddressRepoException::GET_OPTION_DISTRICT_FAILED);
        }
    }

    /**
     * Lấy option tỉnh thành full
     *
     * @return mixed
     * @throws AddressRepoException
     */
    public function getProvinceFull()
    {
        $mProvince = app()->get(ProvinceTable::class);

        try {
            $data = $mProvince->getProvinceFull();
            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new AddressRepoException(AddressRepoException::GET_OPTION_PROVINCE_FAILED);
        }
    }

    /**
     * Lấy option phường xã
     *
     * @param $input
     * @return mixed|void
     * @throws AddressRepoException
     */
    public function getWard($input)
    {
        try {
            $mWard = app()->get(WardTable::class);

            return $mWard->getDistricts($input['district_id']);
        } catch (\Exception | QueryException $exception) {
            throw new AddressRepoException(AddressRepoException::GET_OPTION_WARD_FAILED);
        }
    }
}