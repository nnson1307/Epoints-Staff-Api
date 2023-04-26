<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:20 PM
 */

namespace Modules\Service\Repositories\ServiceCategory;


use Illuminate\Database\QueryException;
use Modules\Service\Models\ServiceCategoryTable;
use MyCore\Repository\PagingTrait;

class ServiceCategoryRepo implements ServiceCategoryRepoInterface
{
    use PagingTrait;
    protected $serviceCategory;

    public function __construct(
        ServiceCategoryTable $serviceCategory
    ) {
        $this->serviceCategory = $serviceCategory;
    }

    /**
     * Danh sách loại dịch vụ
     *
     * @param $input
     * @return array|mixed
     * @throws ServiceCategoryRepoException
     */
    public function getServiceCategories($input)
    {
        try {
            $data = $this->toPagingData($this->serviceCategory->getServiceCategories($input));

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ServiceCategoryRepoException(ServiceCategoryRepoException::GET_SERVICE_CATEGORY_LIST_FAILED);
        }
    }

    /**
     * Lấy option loại dịch vụ
     *
     * @return mixed
     * @throws ServiceCategoryRepoException
     */
    public function getOption()
    {
        try {
            $data = $this->serviceCategory->getOption();

            return $data;
        } catch (\Exception | QueryException $exception) {
            throw new ServiceCategoryRepoException(ServiceCategoryRepoException::GET_OPTION_SERVICE_CATEGORY_FAILED);
        }
    }
}