<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 26/05/2021
 * Time: 14:13
 */

namespace Modules\Support\Http\Controllers;

use Modules\Support\Repositories\Support\SupportRepoInterface;
use Modules\Support\Repositories\Support\SupportRepoException;
class SupportController extends Controller
{
    protected $supportRepo;

    public function __construct(
        SupportRepoInterface $supportRepo
    ) {
        $this->supportRepo = $supportRepo;
    }

    /**
     * Lấy tổng tồn kho
     *
     * @param TotalRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getListFaq()
    {
        try {
            
            $data = $this->supportRepo->getListFaq();
            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (SupportRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}