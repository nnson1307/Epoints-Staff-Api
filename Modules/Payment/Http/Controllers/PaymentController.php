<?php

namespace Modules\Payment\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
require_once base_path('vendor/eway-rapid/include_eway.php');

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * @return Response
     */
    public function index()
    {
        try{

//            $apiKey = 'C3AB9Cdru4ZKAJ3DgGAUNmm1m/Mdn8z4O85WND7JDGtB5f2Z/KctOqoUgTWEYA4H/pLOO/';
//            $apiPassword = 'gt0CvdIt';
//            $apiEndpoint = \Eway\Rapid\Client::MODE_SANDBOX;
//            $client = \Eway\Rapid::createClient($apiKey, $apiPassword, $apiEndpoint);
//
//            $transaction = [
//                'Customer' => [
//                    'CardDetails' => [
//                        'Name' => 'John Smith',
//                        'Number' => '4444333322221111',
//                        'ExpiryMonth' => '12',
//                        'ExpiryYear' => '25',
//                        'CVN' => '123',
//                    ]
//                ],
//                'Payment' => [
//                    'TotalAmount' => 1000,
//                ],
//                'TransactionType' => \Eway\Rapid\Enum\TransactionType::PURCHASE,
//            ];
//
//            $response = $client->createTransaction(\Eway\Rapid\Enum\ApiMethod::DIRECT, $transaction);
//            echo "<pre>";
//            print_r($response);
//            echo "</pre>";
//            die;
//            if ($response->TransactionStatus) {
//                echo 'Payment successful! ID: '.$response->TransactionID;
//            }
//
//            die;

            $apiKey = 'C3AB9Cdru4ZKAJ3DgGAUNmm1m/Mdn8z4O85WND7JDGtB5f2Z/KctOqoUgTWEYA4H/pLOO/';
            $apiPassword = 'gt0CvdIt';
            $apiEndpoint = 'Sandbox';

            // Create the eWAY Client
            $client = \Eway\Rapid::createClient($apiKey, $apiPassword, $apiEndpoint);

            // Transaction details - these would usually come from the application
            $client = \Eway\Rapid::createClient($apiKey, $apiPassword, $apiEndpoint);

            $transaction = [
                'Customer' => [
                    'Reference' => 'A12345',
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
                'ShippingAddress' => [
                    'ShippingMethod' => \Eway\Rapid\Enum\ShippingMethod::NEXT_DAY,
                    'FirstName' => 'John',
                    'LastName' => 'Smith',
                    'Street1' => 'Level 5',
                    'Street2' => '369 Queen Street',
                    'City' => 'Sydney',
                    'State' => 'NSW',
                    'Country' => 'au',
                    'PostalCode' => '2000',
                    'Phone' => '09 889 0986',
                ],
                'Items' => [
                    [
                        'SKU' => '12345678901234567890',
                        'Description' => 'Item Description 1',
                        'Quantity' => 1,
                        'UnitCost' => 400,
                        'Tax' => 100,
                        // Total is calculated automatically
                    ],
                    [
                        'SKU' => '123456789012',
                        'Description' => 'Item Description 2',
                        'Quantity' => 1,
                        'UnitCost' => 400,
                        'Tax' => 100,
                    ],
                ],
                'Options' => [
                    [
                        'Value' => 'Option1',
                    ],
                    [
                        'Value' => 'Option2',
                    ],
                ],
                'Payment' => [
                    'TotalAmount' => 1000,
                    'InvoiceNumber' => 'Inv 21540',
                    'InvoiceDescription' => 'Individual Invoice Description',
                    'InvoiceReference' => '513456',
                    'CurrencyCode' => 'AUD',
                ],
                'RedirectUrl' => 'http://bibica.qc.retailpro.io',
                'CancelUrl' => "http://bibica.qc.retailpro.io",
                'DeviceID' => 'D1234',
                'CustomerIP' => '127.0.0.1',
                'PartnerID' => 'ID',
                'TransactionType' => \Eway\Rapid\Enum\TransactionType::PURCHASE,
                'Capture' => true,
                'LogoUrl' => 'https://mysite.com/images/logo4eway.jpg',
                'HeaderText' => 'My Site Header Text',
                'Language' => 'EN',
                'CustomerReadOnly' => true
            ];

            $response = $client->createTransaction(\Eway\Rapid\Enum\ApiMethod::RESPONSIVE_SHARED, $transaction);


            // Check for any errors
            if (!$response->getErrors()) {
                $sharedURL = $response->SharedPaymentUrl;

                echo $sharedURL;
            } else {
                foreach ($response->getErrors() as $error) {
                    echo "Error: ".\Eway\Rapid::getMessage($error)."
";
                }
                die();
            }

        }catch (\Exception $ex){
            echo "<pre>";
            print_r($ex->getMessage());
            echo "</pre>";
            die;
        }



        return view('payment::index');
    }

    /**
     * Show the form for creating a new resource.
     * @return Response
     */
    public function create()
    {
        return view('payment::create');
    }

    /**
     * Store a newly created resource in storage.
     * @param Request $request
     * @return Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Show the specified resource.
     * @param int $id
     * @return Response
     */
    public function show($id)
    {
        return view('payment::show');
    }

    /**
     * Show the form for editing the specified resource.
     * @param int $id
     * @return Response
     */
    public function edit($id)
    {
        return view('payment::edit');
    }

    /**
     * Update the specified resource in storage.
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     * @param int $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
}
