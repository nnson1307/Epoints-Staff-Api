<?php
/**
 * Eway payment
 * @Author : VuND
 */
namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Modules\Payment\Repositories\PaymentFactory;

class EwayController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function indexAction()
    {
        try{
            $transaction = [
                'Customer' => [
                    'Reference' => '123', // ID khách hàng
                    'Title' => 'Mr.',
                    'FirstName' => 'John',
                    'LastName' => 'Smith',
                    'CompanyName' => 'Demo Shop 123',
                    'JobDescription' => 'Developer',
                    'Street1' => 'Level 5',
                    'Street2' => '369 Queen Street',
                    'City' => 'Sydney',
                    'State' => 'NSW',
                    'PostalCode' => '2000',
                    'Country' => 'au',
                    'Phone' => '09 889 0986',
                    'Mobile' => '09 889 6542',
                    'Email' => 'demo@example.org',
                    "Url" => "http://www.ewaypayments.com",
                ],
                'Items' => [
                    [
                        'Reference' => 1,// ID sản phẩm
                        'SKU' => '12345678901234567890',
                        'Description' => 'Item Description 1',
                        'Quantity' => 2,
                        'UnitCost' => 4,
                        // Total is calculated automatically
                    ],
                    [
                        'Reference' => 1, // ID sản phẩm
                        'SKU' => '123456789012',
                        'Description' => 'Item Description 2',
                        'Quantity' => 2,
                        'UnitCost' => 4,
                    ],
                ],
                'Payment' => [
                    'TotalAmount' => 8,
                    'InvoiceNumber' => 'Inv 21540',
                    'InvoiceDescription' => 'Individual Invoice Description',
                    'CurrencyCode' => 'AUD',
                ],
//                'Options' => [
//                    [
//                        'Value' => 'Option1',
//                    ],
//                    [
//                        'Value' => 'Option2',
//                    ],
//                ],
                'RedirectUrl' => route('payment.eway.response'),
                'CancelUrl' => route('payment.eway.response'),
                'DeviceID' => 'D1234',
                'CustomerIP' => '127.0.0.1',
                'Capture' => true,
                'LogoUrl' => 'https://mysite.com/images/logo4eway.jpg',
                'HeaderText' => 'My Site Header Text',
                'Language' => 'EN',
                'CustomerReadOnly' => true
            ];

            $oPayment = PaymentFactory::getInstance(PaymentFactory::EWAY);
            $response = $oPayment->call($transaction);

            echo "<pre>";
            print_r($response);
            echo "</pre>";
            die;

        }catch (\Exception $ex){
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }



        return view('payment::index');
    }

    public function callbackAction(){

    }

    public function responseAction(){

        try{

            $oPayment = PaymentFactory::getInstance(PaymentFactory::EWAY);
            $response = $oPayment->response(['AccessCode' => $_GET['AccessCode'], 'Type' => 'cancel']);

            echo "<pre>";
            print_r($response);
            echo "</pre>";
            die;
        }catch (\Exception $ex){
            return $this->responseJson(CODE_ERROR, $ex->getMessage());
        }
    }
}
