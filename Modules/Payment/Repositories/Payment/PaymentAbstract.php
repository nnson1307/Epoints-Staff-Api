<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Repositories\Payment;


use Carbon\Carbon;
use Modules\Payment\Entities\PaymentCustomer;
use Modules\Payment\Entities\PaymentItem;
use Modules\Payment\Entities\PaymentLog;
use Modules\Payment\Entities\PaymentShipping;
use Modules\Payment\Entities\PaymentTransaction;
use Modules\Payment\Entities\RequestData;
use Modules\Payment\Entities\ResponseData;
use Modules\Payment\Models\PaymentCustomerTable;
use Modules\Payment\Models\PaymentItemTable;
use Modules\Payment\Models\PaymentLogTable;
use Modules\Payment\Models\PaymentShippingTable;
use Modules\Payment\Models\PaymentTransactionTable;
use Modules\Payment\Repositories\PaymentResponse;

abstract class PaymentAbstract implements PaymentInterface
{
    use PaymentResponse;
    /**
     * Thông tin KEY
     */
    protected $apiKey;

    /**
     * Thông tin pass hoặc secret
     */
    protected $apiPassword;

    /**
     * Môi trường hoặc đích đến
     */
    protected $apiEndpoint;

    protected $oClient;

    protected $CODE_SUCCESS = 0;

    protected $CODE_ERROR = 1;

    protected $PartnerId;

    protected $TransactionType;

    protected $_mDbTransaction;
    protected $_mDbCustomer;
    protected $_mDbShipping;
    protected $_mDbItem;
    protected $_mDbLog;

    abstract public function getClient($data);

    abstract public function call($data);

    abstract public function callback($data);

    abstract public function response($data);

    protected function saveTransactionRequest($data, $result){
        $transactionMasterId = $this->saveTransaction($data, $result);
        $this->saveCustomer($transactionMasterId, $data);
        $this->saveShipping($transactionMasterId, $data);
        $this->saveItem($transactionMasterId, $data);
        $this->saveLog($transactionMasterId, $result);
    }

    protected function saveTransactionResponse($data, $result){
        $mDbTransaction = new PaymentTransactionTable();
        $oTransaction = $mDbTransaction->getTransactionByAccessCode($data['AccessCode'], $this->TransactionType);

        if(!$oTransaction){
            return;
        }

        $transactionMasterId = $oTransaction->TransactionMasterId;

        $transactionUpdate = [
            'TransactionID' => isset($result['TransactionID']) ? $result['TransactionID'] : null,
            'ResponseTime' => Carbon::now(),
            'Status' => $result['StatusPayment'],
            'UpdatedAt' => Carbon::now(),
            'Message' => isset($result['ResponseMessage']) ? $result['ResponseMessage'] : null,
            'Code' => isset($result['Code']) ? $result['Code'] : null,
        ];

        $mDbTransaction->updateItem($transactionMasterId, $transactionUpdate);

        $this->saveLog($transactionMasterId, $result);
    }

    protected function getListTransaction(){

    }

    protected function getTransaction(){

    }

    protected function saveTransaction($data, $result){

        $mDbTransaction = new PaymentTransactionTable();
        $transaction = [
            'PartnerID' => $this->PartnerId,
            'TransactionType' => $this->TransactionType,
            'AccessCode' => isset($result['AccessCode']) ? $result['AccessCode'] : null,
            'InvoiceReference' => $result['InvoiceReference'],
            'InvoiceNumber' => $result['InvoiceNumber'],
            'TotalAmount' => $data['Payment']['TotalAmount'],
            'DeviceID' => $data['DeviceID'],
            'CustomerIP' => $data['CustomerIP'],
            'Language' => $data['Language'],
            'RequestTime' => Carbon::now(),
            'Status' => $result['StatusPayment'],
            'Message' => $result['Message'],
            'Code' => $result['Code'],
            'CreatedAt' => Carbon::now(),
            'CreatedBy' => $data['Customer']['Reference'],
        ];

        $eCustomer = new PaymentTransaction($transaction);

        return $mDbTransaction->insertGetItemId($eCustomer->toArray());
    }

    protected function saveCustomer($transactionMasterId, $data){
        $mDbCustomer = new PaymentCustomerTable();

        $eCustomer = new PaymentCustomer($data['Customer']);
        $eCustomer->TransactionMasterId = $transactionMasterId;

        $mDbCustomer->insertGetItemId($eCustomer->toArray());
    }

    protected function saveShipping($transactionMasterId, $data){
        if(isset($data['ShippingAddress'])){
            $mDbShipping = new PaymentShippingTable();

            $eShipping = new PaymentShipping($data['ShippingAddress']);
            $eShipping->TransactionMasterId = $transactionMasterId;

            $mDbShipping->insertGetItemId($eShipping->toArray());
        }
    }

    protected function saveItem($transactionMasterId, $data){

        $mDbItem = new PaymentItemTable();

        $itemsInsert = [];

        foreach ($data['Items'] as $items){
            $eItem = new PaymentItem($items);
            $eItem->TransactionMasterId = $transactionMasterId;
            $itemsInsert[] = $eItem->toArray();
        }

        $mDbItem->insertMultiItem($itemsInsert);
    }

    protected function saveLog($transactionMasterId, $data){

        $mDbLog = new PaymentLogTable();

        $eLog = new PaymentLog($data);
        $eLog->TransactionMasterId = $transactionMasterId;
        $eLog->DataInput = json_encode($eLog->DataInput);
        $eLog->DataOutput = json_encode($eLog->DataOutput);
        $eLog->CreatedAt = Carbon::now();

        $mDbLog->insertGetItemId($eLog->toArray());
    }

    /**
     * @return mixed|string
     */
    protected function getClientIp() {
        $ipAddress = '';
        if (isset($_SERVER['HTTP_CLIENT_IP']))
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_X_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_X_FORWARDED'];
        else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
            $ipAddress = $_SERVER['HTTP_FORWARDED_FOR'];
        else if(isset($_SERVER['HTTP_FORWARDED']))
            $ipAddress = $_SERVER['HTTP_FORWARDED'];
        else if(isset($_SERVER['REMOTE_ADDR']))
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        else
            $ipAddress = 'UNKNOWN';
        return $ipAddress;
    }

    protected function responseData($data){
        return new ResponseData ($data);
    }

    protected function requestData($data){
        return new RequestData($data);
    }
}
