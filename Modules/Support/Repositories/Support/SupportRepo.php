<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 07-04-02020
 * Time: 11:20 PM
 */

namespace Modules\Support\Repositories\Support;


use Illuminate\Database\QueryException;
use Modules\Support\Models\FaqTable;
use MyCore\Repository\PagingTrait;

class SupportRepo implements SupportRepoInterface
{
    use PagingTrait;
    protected $mFaq;

    public function __construct(
        FaqTable $mFaq
    ) {
        $this->mFaq = $mFaq;
    }
    /**
     * Danh sách loại dịch vụ
     *
     * @param $input
     * @return array|mixed
     * @throws ServiceCategoryRepoException
     */
    public function getListFaq()
    {
        try {
            return $this->mFaq->getFaq();
        } catch (\Exception | QueryException $exception) {
            throw new SupportRepoException(SupportRepoException::GET_LIST_FAILED, $exception->getMessage());
        }
    }

    
}