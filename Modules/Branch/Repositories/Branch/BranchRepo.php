<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\Branch\Repositories\Branch;


use Modules\Branch\Models\BranchTable;

class BranchRepo implements BranchRepoInterface
{
    protected $branch;

    public function __construct(
        BranchTable $branch
    )
    {
        $this->branch = $branch;
    }

    /**
     * Lấy thông tin chi nhánh ETL
     *
     * @param $input
     * @return mixed|void
     * @throws BranchRepoException
     */
    public function getBranchETL($input)
    {
        try {
            $check = $this->branch->getBranchByCode($input['branch_code']);

            if ($check == null) {
                $branchId = $this->branch->add([
                    'branch_name' => $input['branch_name'],
                    'branch_code' => $input['branch_code'],
                    'site_id' => $input['site_id'],
                    'slug' => str_slug($input['branch_name'])
                ]);
            } else {
                $branchId = $check['branch_id'];
            }

            return [
                'branch_id' => $branchId
            ];
        } catch (\Exception $exception) {
            throw new BranchRepoException(BranchRepoException::GET_BRANCH_ETL_FAILED);
        }
    }

    /**
     * Lấy danh sách chi nhánh
     *
     * @return mixed|void
     * @throws ReportRevenueOrderRepoException
     */
    public function getBranch()
    {
        try {
            $mBranch = app()->get(BranchTable::class);
            //Lấy danh sách chi nhánh
            $data = $mBranch->getBranch();

            if (count($data) > 0) {
                foreach ($data as $v) {
                    $v['full_address'] = $v['address'] . ', ' . $v['ward_type'] . ' ' . $v['ward_name'] .', ' . $v['district_type'] . ' ' . $v['district_name'] . ', ' . $v['province_type'] . ' ' . $v['province_name'];;
                }
            }

            return $data;
        } catch (\Exception $exception) {
            throw new BranchRepoException(BranchRepoException::GET_BRANCH_ETL_FAILED, $exception->getMessage());
        }
    }
}