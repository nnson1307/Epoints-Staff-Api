<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 06/05/2021
 * Time: 14:38
 */

namespace Modules\User\Repositories\Brand;


use Modules\User\Http\Api\BrandApi;

class BrandRepo implements BrandRepoInterface
{
    /**
     * Lấy ds brand
     *
     * @param $input
     * @return mixed|void
     * @throws BrandRepoException
     */
    public function getBrand($input)
    {
        try {
            $mBrand = app()->get(BrandApi::class);

            //Lấy ds brand bằng client key
            $getBrand = $mBrand->getBrandByClient([
                'client_key' => $input['client_key']
            ]);

            return $getBrand;
        } catch (\Exception $exception) {
            throw new BrandRepoException(BrandRepoException::GET_BRAND_FAILED, $exception->getMessage());
        }
    }
}