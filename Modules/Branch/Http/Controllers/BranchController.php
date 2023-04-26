<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:16 PM
 */

namespace Modules\Branch\Http\Controllers;


use Modules\Branch\Http\Requests\Branch\GetBranchETLRequest;
use Modules\Branch\Repositories\Branch\BranchRepoException;
use Modules\Branch\Repositories\Branch\BranchRepoInterface;

class BranchController extends Controller
{
    protected $branch;

    public function __construct(
        BranchRepoInterface $branch
    ) {
        $this->branch = $branch;
    }

    /**
     * Lấy thông tin chi nhánh ETL
     *
     * @param GetBranchETLRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranchETL(GetBranchETLRequest $request)
    {
        try {
            $data = $this->branch->getBranchETL($request->all());

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (BranchRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    
    /**
     * Lấy danh sách chi nhánh
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBranch()
    {
        try {
            $data = $this->branch->getBranch();

            return $this->responseJson(CODE_SUCCESS, null, $data);
        } catch (BranchRepoException $ex) {
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}