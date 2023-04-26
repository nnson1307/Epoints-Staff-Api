<?php

namespace Modules\Brand\Repositories\Brand;



use Illuminate\Support\Facades\Validator;
use Modules\Brand\Enum\BrandRegisterStatus;
use Modules\Brand\Http\Api\StaffApi;
use Modules\Brand\Http\Requests\RegisterBrandRequest;
use Modules\Brand\Http\Requests\ScanBrandRequest;
use Modules\Brand\Models\BrandRegisterTable;
use Modules\Brand\Models\BrandTable;
use Modules\User\Libs\SmsFpt\TechAPI\src\TechAPI\Exception;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;

class BrandRepo implements BrandRepoInterface
{

    protected $brandRegisterTable;
    protected $brandTable;
    protected $staffAPi;

    public function __construct(
        BrandRegisterTable $brandRegisterTable,
        BrandTable $brandTable,
        StaffApi $staffAPi
    )
    {
        $this->brandRegisterTable = $brandRegisterTable;
        $this->brandTable = $brandTable;
        $this->staffAPi = $staffAPi;
    }


    /**
     * Đăng kí cộng tác viên
     * @param array $input
     * @return mixed
     */
    public function registerBrand($input)
    {
//        $input = $this->decryptPayload($input);
//        $validate = new RegisterBrandRequest();
//        $validator = Validator::make($input, $validate->rules(), $validate->messages());
//        if ($validator->fails()) {
//            $error = $validator->errors()->first();
//            throw new BrandRepoException(BrandRepoException::BRAND_FAILED, $error);
//        }

        $brandRegister = $this->brandRegisterTable->getBrandRegister($input['phone'], $input['brand_name']);

        if($brandRegister){
            throw_if($brandRegister['status'] == BrandRegisterStatus::NEW_STATUS, new BrandRepoException(BrandRepoException::BRAND_PENDING_STATUS));
            throw_if($brandRegister['status'] == BrandRegisterStatus::APPROVED_STATUS, new BrandRepoException(BrandRepoException::BRAND_APPROVED_STATUS));
        }

        $result = $this->brandRegisterTable->createBrandRegister([
            'brand_name' => $input['brand_name'],
            'full_name' => $input['full_name'],
            'email' => $input['email'] ?? null,
            'phone' => $input['phone'],
            'status' => BrandRegisterStatus::NEW_STATUS,
        ]);

        $resultCustomerLead = $this->staffAPi->registerCustomerLead(REGISTER_BRAND_DEFAULT, [
            'customer_type' => 'personal',
            'full_name'     => $input['full_name'],
            'phone'         => $input['phone'],
            'customer_source' => REGISTER_BRAND_CUSTOMER_SOURCE,
            'pipeline_code' => REGISTER_BRAND_PIPELINE_CODE,
            'journey_code' => REGISTER_BRAND_JOURNEY_CODE,
            'email' => $input['email'],
        ]);

        return $result;
    }

    public function scanBrand($input)
    {
//        $input = $this->decryptPayload($input);
//        $validate = new ScanBrandRequest();
//        $validator = Validator::make($input, $validate->rules(), $validate->messages());
//        if ($validator->fails()) {
//            $error = $validator->errors()->first();
//            throw new BrandRepoException(BrandRepoException::BRAND_FAILED, $error);
//        }

        $brand = $this->brandTable->scanCode($input['brand_customer_code']);
        throw_if(empty($brand), new BrandRepoException(BrandRepoException::BRAND_NOT_EXITS));
        return $brand;
    }

    private function decryptPayload($input){
        try{
            $base64 = $input['payload'];
            $base64 = base64_decode($base64);
            $path = storage_path('app');
            $privateKey = PrivateKey::fromFile("{$path}/brand-private.key");
            $data = $privateKey->decrypt($base64);
            $input = json_decode($data);
        }catch (\Exception $e){
            throw new BrandRepoException(BrandRepoException::DECRYPT_PAYLOAD_FAILED);
        }
        return (array)$input;
    }
}