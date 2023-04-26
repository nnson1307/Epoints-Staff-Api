<?php
/**
 * Created by PhpStorm
 * User: Mr Son
 * Date: 6/12/2020
 * Time: 2:50 PM
 */

namespace Modules\ChatHub\Repositories\ChatHub;

use Modules\ChatHub\Models\CustomerTable;
use Modules\ChatHub\Models\CustomerLeadTable;

class ChatHubRepo implements ChatHubRepoInterface
{
    protected $mCustomer;
    protected $mCustomerLead;

    public function __construct(
        CustomerTable $mCustomer,
        CustomerLeadTable $mCustomerLead
    )
    {
        $this->mCustomer = $mCustomer;
        $this->mCustomerLead = $mCustomerLead;
    }

    /**
     * Lấy thông tin chi nhánh ETL
     *
     * @param $input
     * @return mixed|void
     * @throws BranchRepoException
     */
    public function getCustomer($input)
    {
        try {
           $data = $this->mCustomer->getCustomer($input)->toArray();
           $dataCustomerLead = $this->mCustomerLead->getList($input)->toArray();
         
           return array_merge($data,$dataCustomerLead);
        } catch (\Exception $exception) {
            throw new ChatHubRepoException(ChatHubRepoException::GET_FAILED, $exception->getMessage());
        }
    }
}