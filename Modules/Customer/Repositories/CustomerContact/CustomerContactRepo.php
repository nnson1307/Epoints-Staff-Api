<?php
/**
 * Created by PhpStorm.
 * User: nguyenngocson
 * Date: 24/05/2021
 * Time: 14:27
 */

namespace Modules\Customer\Repositories\CustomerContact;


use Modules\Customer\Http\Requests\CustomerContact\UpdateRequest;
use Modules\Customer\Models\CustomerContactTable;

class CustomerContactRepo implements CustomerContactRepoInterface
{
    protected $customerContact;

    public function __construct(
        CustomerContactTable $customerContact
    ) {
        $this->customerContact = $customerContact;
    }

    /**
     * Thêm địa chỉ giao hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerContactRepoException
     */
    public function store($input)
    {
        try {
            if ($input['address_default'] == 1) {
                //Update địa chỉ mặc định của các địa chỉ khác = 0
                $this->customerContact->updateNotDefault($input['customer_id']);
            }

            //Insert user contact
            $contactId = $this->customerContact->add([
                'customer_id' => $input['customer_id'],
                'province_id' => $input['province_id'],
                'district_id' => $input['district_id'],
                'ward_id' => $input['ward_id'],
                'full_address' => $input['address'],
                'contact_name' => $input['full_name'],
                'contact_phone' => $input['phone'],
                'address_default' => $input['address_default']
            ]);
            //Update contact code
            $this->customerContact->edit([
                'customer_contact_code' => 'CC_' . date('dmY') . sprintf("%02d", $contactId)
            ], $contactId);

            //get customer contact
            return $this->customerContact->getContact($input['customer_id']);
        } catch (\Exception $exception) {
            throw new CustomerContactRepoException(CustomerContactRepoException::STORE_USER_CONTACT_FAILED);
        }
    }

    /**
     * Xoá địa chỉ giao hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerContactRepoException
     */
    public function remove($input)
    {
        try {
            //Xoá địa chỉ giao hàng
            $this->customerContact->edit([
                'is_deleted' => 1
            ], $input['customer_contact_id']);
        } catch (\Exception $exception) {
            throw new CustomerContactRepoException(CustomerContactRepoException::REMOVE_USER_CONTACT_FAILED);
        }
    }

    const IS_ADDRESS_DEFAULT = 1;

    /**
     * Cập nhật địa chỉ giao hàng mặc định
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerContactRepoException
     */
    public function setDefault($input)
    {
        try {
            //Update địa chỉ mặc định của các địa chỉ khác = 0
            $this->customerContact->updateNotDefault($input['customer_id']);
            //Update địa chỉ mặc định
            $this->customerContact->edit([
                'address_default' => self::IS_ADDRESS_DEFAULT
            ], $input['customer_contact_id']);
        } catch (\Exception $exception) {
            throw new CustomerContactRepoException(CustomerContactRepoException::UPDATE_USER_CONTACT_FAILED);
        }
    }

    /**
     * Chỉnh sửa địa chỉ giao hàng
     *
     * @param $input
     * @return mixed|void
     * @throws CustomerContactRepoException
     */
    public function update($input)
    {
        try {
            //Update địa chỉ giao hàng
            $this->customerContact->edit([
                'province_id' => $input['province_id'],
                'district_id' => $input['district_id'],
                'ward_id' => $input['ward_id'],
                'full_address' => $input['address'],
                'contact_name' => $input['full_name'],
                'contact_phone' => $input['phone'],
                'address_default' => $input['address_default']], $input['customer_contact_id']);
            //get customer contact
            return $this->customerContact->getContact($input['customer_id']);
        } catch (\Exception $e) {
            throw new CustomerContactRepoException(CustomerContactRepoException::UPDATE_USER_CONTACT_FAILED, $e->getMessage());
        }
    }
}