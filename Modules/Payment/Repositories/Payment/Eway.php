<?php


/**
 * @Author : VuND
 */

namespace Modules\Payment\Repositories\Payment;

use Modules\Payment\Entities\Eway\Customer;
use Modules\Payment\Entities\Eway\Shipping;
use Modules\Payment\Entities\Eway\Payment;
use Modules\Payment\Entities\Eway\Item;
use Modules\Payment\Entities\Eway\Transaction;
use Modules\Payment\Models\ConfigTable;
use Modules\Payment\Models\PaymentTransactionTable;
use Modules\Payment\Repositories\PaymentFactory;

require_once base_path('Modules/Payment/Vendor/eway-rapid/include_eway.php');

class Eway extends PaymentAbstract
{
    protected $_ConversionRate = 100;

    protected $_STATUS_PAYMENT_NEW = 'N';

    protected $_STATUS_PAYMENT_FAIL = 'F';

    protected $_STATUS_PAYMENT_SUCCESS = 'S';

    protected $_WORKER_USER = 'user';

    protected $_WORKER_SYS = 'sys';

    protected $_TYPE_CANCEL = 'cancel';

    protected $_TYPE_REQUEST = 'request';

    protected $_TYPE_RESPONSE = 'response';

    /**
     * Eway constructor.
     */
    public function __construct()
    {
        $mConfig = new ConfigTable();
        $arrConfig = $mConfig->getConfig();

        if(!isset($arrConfig['eway_mode'])){
            throw new \Exception('Chưa cấu hình EWAY MODE.');
        }

        if($arrConfig['eway_mode'] == 1){

            if(!isset($arrConfig['eway_product_key']) || empty($arrConfig['eway_product_key'])){
                throw new \Exception('Chưa cấu hình EWAY KEY.');
            }

            if(!isset($arrConfig['eway_product_pass'])){
                throw new \Exception('Chưa cấu hình EWAY PASS.');
            }

            $mode = \Eway\Rapid\Contract\Client::MODE_PRODUCTION;
            $key = $arrConfig['eway_product_key'];
            $pass = $arrConfig['eway_product_pass'];

        } else {
            if(!isset($arrConfig['eway_sanbox_key'])){
                throw new \Exception('Chưa cấu hình EWAY KEY.');
            }


            if(!isset($arrConfig['eway_sanbox_pass'])){
                throw new \Exception('Chưa cấu hình EWAY PASS.');
            }
            $mode = \Eway\Rapid\Contract\Client::MODE_SANDBOX;
            $key = $arrConfig['eway_sanbox_key'];
            $pass = $arrConfig['eway_sanbox_pass'];
        }



        $this->apiKey = $key;

        $this->apiPassword = $pass;

        $this->apiEndpoint = $mode;

        $this->PartnerId = 1;

        $this->TransactionType = PaymentFactory::EWAY;

        $this->oClient = $this->getClient();
    }

    /**
     * @param null $data
     * @return \Eway\Rapid\Contract\Client
     */
    public function getClient($data = null){
        // Transaction details - these would usually come from the application
        return $client = \Eway\Rapid::createClient($this->apiKey, $this->apiPassword, $this->apiEndpoint);
    }

    /**
     * @param $data
     * @return array
     */
    public function call($data){
        $transaction = $this->buildTransaction($data);

        try{

            $response = $this->oClient->createTransaction(\Eway\Rapid\Enum\ApiMethod::RESPONSIVE_SHARED, $transaction);

            // Check for any errors
            // gọi API thành công
            if (!$response->getErrors()) {

                $resultApi = [
                    'AccessCode' => $response->AccessCode,
                    'PaymentUrl' => $response->SharedPaymentUrl,
                    'InvoiceNumber' => $response->Payment->InvoiceNumber,
                    'InvoiceReference' => $response->Payment->InvoiceReference,
                ];

                $result = $this->responseJson($this->CODE_SUCCESS, null, $this->requestData($resultApi));

                $arrResultLog = array_merge($resultApi, [
                    'Status' => !$this->CODE_SUCCESS,
                    'StatusPayment' => $this->_STATUS_PAYMENT_NEW,
                    'Worker' => $this->_WORKER_USER,
                    'Type' => $this->_TYPE_REQUEST,
                    'Code' => null,
                    'Message' => null,
                    'DataInput' => $data,
                    'DataOutput' => $response->toArray(),
                ]);

                $this->saveTransactionRequest($data, $arrResultLog);

            } else {
                // gọi API thất bại
                $error = current($response->getErrors());
                if($error){
                    $result = $this->responseJson($this->CODE_ERROR, \Eway\Rapid::getMessage($error));
                } else {
                    $result = $this->responseJson($this->CODE_ERROR, 'Unknow Error');
                }

                $arrResultLog = [
                    'Status' => !$this->CODE_ERROR,
                    'StatusPayment' => $this->_STATUS_PAYMENT_FAIL,
                    'Worker' => $this->_WORKER_USER,
                    'Type' => $this->_TYPE_REQUEST,
                    'Code' => null,
                    'Message' => $result['ErrorDescription'],
                    'DataInput' => $data,
                    'DataOutput' => $response->toArray(),
                ];

                $this->saveTransactionRequest($data, $arrResultLog);
            }

            return $result;

        }catch (\Exception $ex){
            $arrResultLog = [
                'Status' => !$this->CODE_ERROR,
                'StatusPayment' => $this->_STATUS_PAYMENT_FAIL,
                'Worker' => $this->_WORKER_USER,
                'Type' => $this->_TYPE_REQUEST,
                'Code' => -1,
                'Message' => $ex->getMessage(),
                'DataInput' => $data,
                'DataOutput' => [],
            ];

            $this->saveTransactionRequest($data, $arrResultLog);

            return $this->responseJson($this->CODE_ERROR, $ex->getMessage());
        }
    }

    public function callback($data){
        return $this->response($data);
    }

    /**
     * @param $data
     * @return array
     */
    public function response($data){
        try{
            if(!$data['AccessCode']){
                return $this->responseJson(CODE_ERROR, 'AccessCode is required');
            }

            $mDbTransaction = new PaymentTransactionTable();
            $arrTransaction = $mDbTransaction->getTransactionByAccessCode($data['AccessCode'], $this->TransactionType);

            if(!$arrTransaction){
                return $this->responseJson(CODE_ERROR, 'AccessCode is deny');
            }

            if($arrTransaction->Status == 'S'){
                $resultApi = [
                    'TransactionStatus' => $arrTransaction->Status,
                    'TransactionID' => $arrTransaction->TransactionID,
                    'AccessCode' => $data['AccessCode'],
                    'InvoiceNumber' => $arrTransaction->InvoiceNumber,
                    'InvoiceReference' => $arrTransaction->InvoiceReference,
                    'ResponseCode' => $arrTransaction->Code,
                    'ResponseMessage' => $arrTransaction->Message
                ];

                $result = $this->responseJson($this->CODE_SUCCESS, null, $this->responseData($resultApi));

                return  $result;
            }

            if(isset($data['Type']) && $data['Type'] == 'cancel'){
                $type = $data['Type'];
            } else {
                $type = $this->_TYPE_RESPONSE;
            }


            // Query the transaction result.
            $response = $this->oClient->queryTransaction($data['AccessCode']);

            if(!isset($response->Transactions[0])){
                return $this->responseJson(CODE_ERROR, 'AccessCode is deny');
            }

            $transactionResponse = $response->Transactions[0];


            // Display the transaction result
            if ($transactionResponse->TransactionStatus) {

                $resultApi = [
                    'TransactionStatus' => $transactionResponse->TransactionStatus,
                    'TransactionID' => $transactionResponse->TransactionID,
                    'AccessCode' => $data['AccessCode'],
                    'InvoiceNumber' => $transactionResponse->InvoiceNumber,
                    'InvoiceReference' => $transactionResponse->InvoiceReference,
                    'ResponseCode' => $transactionResponse->ResponseCode,
                    'ResponseMessage' => \Eway\Rapid::getMessage($transactionResponse->ResponseMessage)
                ];

                $result = $this->responseJson($this->CODE_SUCCESS, null, $this->responseData($resultApi));

                $arrResultLog = array_merge($resultApi, [
                    'Status' => !$this->CODE_SUCCESS,
                    'StatusPayment' => $this->_STATUS_PAYMENT_SUCCESS,
                    'Worker' => $this->_WORKER_USER,
                    'Type' => $this->_TYPE_RESPONSE,
                    'Code' => $transactionResponse->ResponseCode,
                    'Message' => \Eway\Rapid::getMessage($transactionResponse->ResponseMessage),
                    'DataInput' => $data,
                    'DataOutput' => $response->toArray(),
                ]);
                $this->saveTransactionResponse($data, $arrResultLog);

            } else {

                $resultApi = [
                    'AccessCode' => $data['AccessCode'],
                    'InvoiceNumber' => $arrTransaction->InvoiceNumber,
                    'InvoiceReference' => $arrTransaction->InvoiceReference,
                ];

                $result = $this->responseJson($this->CODE_ERROR, \Eway\Rapid::getMessage($transactionResponse->ResponseMessage), $this->responseData($resultApi));

                $arrResultLog = [
                    'Status' => !$this->CODE_ERROR,
                    'StatusPayment' => $this->_STATUS_PAYMENT_FAIL,
                    'Worker' => $this->_WORKER_USER,
                    'Type' => $type,
                    'Code' => -1,
                    'Message' => $result['ErrorDescription'],
                    'DataInput' => $data,
                    'DataOutput' => [],
                ];

                $this->saveTransactionResponse($data, $arrResultLog);
            }

            return $result;

        }catch (\Exception $ex){

            $arrResultLog = [
                'Status' => !$this->CODE_ERROR,
                'StatusPayment' => $this->_STATUS_PAYMENT_FAIL,
                'Worker' => $this->_WORKER_USER,
                'Type' => $type,
                'Code' => -1,
                'Message' => $ex->getMessage(),
                'DataInput' => $data,
                'DataOutput' => [],
            ];

            $this->saveTransactionResponse($data, $arrResultLog);

            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }

    public function rePay($data){
        
    }

    protected function buildTransaction($data){

        $data['Payment']['InvoiceReference'] = strtoupper('EWAY_'.uniqid());

        $eTransaction = new Transaction($data);

        $item = $eTransaction->toArray();

        if(empty($item['ShippingAddress'])){
            unset($item['ShippingAddress']);
        }

        if(empty($item['Options'])){
            unset($item['Options']);
        }

        return $item;

    }
}
