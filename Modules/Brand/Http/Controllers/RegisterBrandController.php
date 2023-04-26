<?php

namespace Modules\Brand\Http\Controllers;

use Illuminate\Http\Request;
use Modules\Brand\Http\Requests\RegisterBrandRequest;
use Modules\Brand\Http\Requests\ScanBrandRequest;
use Modules\Brand\Repositories\Brand\BrandRepoException;
use Modules\Brand\Repositories\Brand\BrandRepoInterface;
use Spatie\Crypto\Rsa\KeyPair;
use Spatie\Crypto\Rsa\PrivateKey;
use Spatie\Crypto\Rsa\PublicKey;
use function MongoDB\BSON\toJSON;

class RegisterBrandController extends Controller
{
    protected $brandRepo;

    public function __construct(BrandRepoInterface $brandRepo) {
        $this->brandRepo = $brandRepo;
    }

    /**
     * Display a listing of the resource.
     * @param RegisterBrandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerBrandAction(RegisterBrandRequest $request)
    {
        try {
            $input = $request->all();
            $data = $this->brandRepo->registerBrand($input);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (BrandRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     * @param ScanBrandRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function scanQRCodeAction(ScanBrandRequest $request)
    {
        try {
            $input = $request->all();
            $data = $this->brandRepo->scanBrand($input);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (BrandRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function generateKeyAction(Request $request)
    {
        try {
            $input = $request->all();

            $path = storage_path('app');
            $data = (new KeyPair())->generate("{$path}/brand-private.key", "{$path}/brand-public.key");

            $publicKey = PublicKey::fromFile("{$path}/brand-public.key");
            $newString = json_encode($input);
            $data = $publicKey->encrypt($newString);
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (BrandRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
