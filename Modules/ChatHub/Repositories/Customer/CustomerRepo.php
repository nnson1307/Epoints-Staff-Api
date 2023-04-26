<?php
/**.
 * User: HIEUPC
 * Date: 2020-01-04
 */

namespace Modules\ChatHub\Repositories\Customer;

use Carbon\Carbon;
use Modules\ChatHub\Models\CustomerContactTable;
use Modules\ChatHub\Models\CustomerTable;
use MyCore\Repository\PagingTrait;


class CustomerRepo implements CustomerRepoInterface
{
    protected $customer;
    protected $customerContact;

    public function __construct(
        CustomerTable $customer,
        CustomerContactTable $customerContact
    )
    {
        $this->customer = $customer;
        $this->customerContact = $customerContact;
    }

    use PagingTrait;

    /**
     * Chi tiết khách hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerRepoException
     */
    public function getDetail($input)
    {
        try {
            //Lấy thông tin khách hàng
            $info = $this->customer->getInfoById($input['customer_id']);

            $info['full_address'] = $info['address'];

            if ($info['ward_name'] != null) {
                $info['full_address'] .=  ', ' . $info['ward_name'];
            }

            if ($info['district_name'] != null) {
                $info['full_address'] .=  ', ' . $info['district_name'];
            }

            if ($info['province_name'] != null) {
                $info['full_address'] .=  ', ' . $info['province_name'];
            }

            //Format birthday
            $info['birthday'] = $info['birthday'] != null ? Carbon::parse($info['birthday'])->format('d/m/Y') : null;


            //Lấy địa chỉ giao hàng của khách hàng
            $deliveryAddress = $this->customerContact->getContact($info['customer_id'])->toArray();

            $arrDeliveryAddress = [];

            if (count($deliveryAddress) > 0) {
                foreach ($deliveryAddress as $v1) {
                    $v1['full_address'] = $v1['address'] . ', ' . $v1['ward_type'] . ' ' . $v1['ward_name'] .', ' . $v1['district_type'] . ' ' . $v1['district_name'] . ', ' . $v1['province_type'] . ' ' . $v1['province_name'];
                    $arrDeliveryAddress[] = $v1;
                }
            }
            $info['delivery_address'] = $arrDeliveryAddress;

            return $info;
        } catch (\Exception $ex) {
            throw new CustomerRepoException(CustomerRepoException::GET_CUSTOMER_DETAIL_FAILED);
        }
    }
}