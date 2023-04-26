<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>In hóa đơn</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="{{asset('static/backend/css/print-bill.css')}}">
    <link rel="shortcut icon" href="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}"/>

    <style>
        .receipt {
            /*font-family: "Times New Roman", Times, serif;*/
            font-family: Arial, Helvetica, sans-serif;
            width: 210mm;
            margin: 0 auto;
        }

        .widhtss {
            margin: 0 auto;
            width: 98%;
            height: 98%;
        }

        .mm-mauto {
            margin: 0 auto !important;
        }

        /*@page {*/
        /*width: 10%;*/
        /*height: 10%;*/
        /*!*margin: 0 auto;*!*/
        /*}*/

        /* output size */
        .receipt .sheet {
            width: 210mm;
            height: 148mm;
            /*margin: 0*/
            /*float: left;*/
        }

        /* sheet size */
        @media print {
            #PrintArea {
                width: 213mm;
                height: 148mm;
                font-family: Arial, Helvetica, sans-serif;
                /*float: right;*/
                float: left;

                /*Canh giữa trên trình duyệt nhưng in ra lệch phải*/

                /*position:absolute;*/
                /*width: 300px;*/
                /*height: 100%;*/
                /*z-index:15;*/
                /*top:42mm;*/
                /*left:50%;*/
                /*margin:-150px 0 0 -150px;*/
            }

            .width-table {
                width: 200mm;
            }

            hr {
                border: 1px solid !important;
            }
        }

        .hr2 {
            border-bottom-width: 1px;
            border-bottom-style: dotted;
        }

        .border-bottom {
            border-bottom: 1px dashed;
        }

        .roww {
            flex-wrap: wrap;
            box-sizing: border-box;
            position: relative;
            width: 100%;
        }

        .roww:before, .roww:after {
            display: table;
            content: " ";
        }

        .roww:after {
            clear: both;
        }

        .coll-7 {
            width: 70%;
            float: left;
        }

        .coll-3 {
            width: 30%;
            float: left;
        }

        .coll-2 {
            width: 20%;
            float: left;
        }

        .coll-8 {
            width: 80%;
            float: left;
        }

        .coll-6 {
            width: 60%;
            float: left;
        }

        .coll-32 {
            width: 30%;
            float: right;
        }

        .text-align-right {
            text-align: right !important;
        }

        .imgss {
            width: 25mm;
            height: 13mm;
        }

        .tientong:after {
            clear: both;
        }

        .tientong strong:first-child {
            text-align: left;
            font-size: 11px;
            float: left;
        }

        .tientong strong:last-child {
            text-align: right;
            font-size: 11px;
            float: right;
        }

        .ss-font-size-10 {
            font-size: 14px !important;
        }

        .font-size-15 {
            font-size: 15px !important;
        }

        .tks {
            font-size: 15px;
            text-align: center;
            width: 100%;
            display: block;
        }

        .text-center {
            text-align: center !important;
        }

        .text-left {
            text-align: left !important;
        }

        .text-right {
            text-align: right !important;
        }

        h4 {
            font-size: 12px;
            text-align: center;
            font-weight: bold;
            margin: 3px 0;
        }

        h5 {
            font-size: 10px;
            margin: 3px 0;
            font-weight: bold;
        }

        .width-collumn-16 {
            width: 16% !important;
        }

        .width-collumn-11 {
            width: 11% !important;
        }

        .width-collumn-55 {
            width: 20mm !important;
            max-width: 20mm !important;
        }

        .ss-nowap {
            white-space: nowrap;
        }

        .width-table {
            width: 149mm;
        }

        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }

        .row-right {
            flex-wrap: wrap;
            box-sizing: border-box;
            position: relative;
            width: 50%;
            float: right;
        }

        .coll-7-right {
            width: 70%;
            float: right;
        }

        .coll-3-right {
            width: 30%;
            float: right;
        }

        .fontw-200 {
            font-weight: 200 !important;
        }

        /*.first-text-upper {*/
            /*text-transform: lowercase;*/
        /*}*/

        .first-text-upper:first-letter {
            text-transform: uppercase;
        }
    </style>
</head>
<body>
<div id="divToPrint">
    <div class="receipt">
        <section class="sheet">
            <div id="PrintArea">
                <div class="widhtss">
                    <div class="roww">
                        @if($configPrintBill['is_show_logo']==1)
                            <div class="form-group m-form__group text-center coll-2">
                                <img class="imgss" src="{{asset($spaInfo['logo'])}}">
                            </div>
                        @endif
                        <div class="text-right coll-6" style="margin: 0 auto">
                            @if($configPrintBill['is_show_unit']==1)
                                <h5 class="text-center ss-font-size-10">
                                    {{$branchInfo['branch_name']}}
                                </h5>
                            @endif
                            @if($configPrintBill['is_show_address']==1)
                                <h5 class="text-center ss-font-size-10">
                                        <span class="ss-font-size-10 text-center">
                                            {{$branchInfo['address'].' '.$branchInfo['district_type'].' '
                                            .$branchInfo['district_name'].' '.$branchInfo['province_name']}}
                                        </span>
                                </h5>
                            @endif
                            @if($configPrintBill['is_show_phone']==1)
                                <h4 class="text-center ss-font-size-10">
                                        <span>
                                           {{$branchInfo['hot_line']}}
                                       </span>
                                </h4>
                            @endif
                        </div>
                        <div class="form-group m-form__group text-center coll-2">
                            <h4 class="text-left ss-font-size-10">
                                        <span>
                                           {{__('Ký hiệu')}}: {{$configPrintBill['symbol']}}
                                       </span>
                            </h4>
                            <h4 class="text-left ss-font-size-10">
                                        <span>
                                           {{__('Số')}}: {{$STT+1}}
                                       </span>
                            </h4>
                            <h4 class="text-left ss-font-size-10">
                                        <span>
                                           MST: {{$spaInfo['tax_code']}}
                                       </span>
                            </h4>
                        </div>
                    </div>
                    <hr>

                    <div class="mm-mauto">
                        <h4 class="text-center font-size-15">{{__('HÓA ĐƠN BÁN HÀNG')}}</h4>
                        <div class="text-center" style="font-size: 9px;">{{$printTime}}</div>
                    </div>

                    <div class="mm-mauto tientong roww">
                        <div class="coll-7">
                            @if($configPrintBill['is_show_order_code']==1)
                                <div class="roww text-left">
                                    <div>
                                        <span class="ss-font-size-10">{{__('Mã hóa đơn')}}: {{$order['order_code']}}
                                        </span>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="coll-3">
                            <span style="margin-left: 20px" class="text-center font-size-13">{{__('Ngày')}} {{date('d')}}
                                {{__('tháng')}} {{date('m')}} {{__('năm')}} {{date('Y')}} </span>
                        </div>
                    </div>
                    <div class="mm-mauto tientong roww">
                        <div>
                            <span class="ss-font-size-10 roww">{{__('Mã hồ sơ')}}: {{$order['profile_code']}}
                            </span>
                        </div>
                        <div>
                            <span class="ss-font-size-10 roww">{{__('Mã khách hàng')}}: {{$order['customer_code']}}
                            </span>
                        </div>
                    </div>
                    <div class="mm-mauto tientong roww">
                        @if($configPrintBill['is_show_customer']==1)
                            <span class="ss-font-size-10 coll-7">
                                {{__('Khách hàng')}}: @if($order['customer_id']!=1)
                                    {{$order['full_name']}}
                                @else
                                    {{__('Khách hàng vãng lai')}}
                                @endif
                            </span>
                        @endif
                        @if($configPrintBill['is_show_cashier']==1)
                            <strong class="ss-font-size-10 coll-32" style="float:right;">
                                <strong class="ss-font-size-10"></strong>
                            </strong>
                        @endif
                    </div>
                    <div class="mm-mauto tientong roww">
                            <span class="ss-font-size-10 coll-7">
                                {{__('Thu ngân')}}: {{$receipt['full_name']}}
                            </span>
                        @if($configPrintBill['is_show_cashier']==1)
                            <strong class="ss-font-size-10 coll-32" style="float:right;">
                                <strong class="ss-font-size-10"></strong>
                            </strong>
                        @endif
                    </div>
                    <br>
                    @php
                        $km=0;
                        $count=0;
                        $stt=1;
                    @endphp
                    {{--<div class="tientong roww" style="font-weight: bold;">--}}
                    {{--<span class="coll-7">{{__('Tên SP/DV')}}</span>--}}
                    {{--<span class="coll-32 text-align-right">Tổng tiền</span>--}}
                    {{--</div>--}}
                    <table style="width:100%">
                        <tr>
                            <th>{{__('STT')}}</th>
                            <th>{{__('Tên mặt hàng')}}</th>
                            <th>{{__('Số lượng')}}</th>
                            <th>{{__('Đơn giá')}}</th>
                            <th>{{__('Giảm giá')}}</th>
                            <th>{{__('Tổng tiền')}}</th>
                        </tr>
                        @foreach($oder_detail as $item)
                            @php
                                $km+=$item['discount'];
                                $count++;
                            @endphp
                            <tr>
                                <td class="text-center">{{$stt++}}</td>
                                <td>{{$item['object_name']}}</td>
                                <td class="text-center">{{$item['quantity']}}</td>
                                <td class="text-center">{{number_format($item['price'])}}</td>
                                <td class="text-center">
                                    @if($item['discount']!=0)
                                        -{{number_format($item['discount'])}}
                                    @else
                                        0
                                    @endif
                                </td>
                                <td class="text-center">
                                    {{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                </td>
                            </tr>
                        @endforeach
                    </table>
                    <br>
                    <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                        <strong class="coll-7 font-size-15">{{__('CHIẾT KHẤU THÀNH VIÊN')}}:</strong>
                        <strong class="coll-3 font-size-15">-{{number_format($order['discount_member']+$km, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('VNĐ')}}</strong>
                    </div>
                    <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                        <strong class="coll-7 font-size-15">{{__('TỔNG TIỀN ĐÃ GIẢM')}}:</strong>
                        <strong class="coll-3 font-size-15">-{{number_format($order['discount']+$km, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('VNĐ')}}</strong>
                    </div>
                    <div class="mm-mauto tientong font-size-15 roww">
                        <strong class="coll-7 font-size-15">{{__('TỔNG TIỀN PHẢI THANH TOÁN')}}:</strong>
                        <strong class="coll-3 font-size-15">
                            {{number_format($order['amount']+$km, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('VNĐ')}}</strong>
                    </div>
                    <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                        <strong class="coll-7 font-size-15">{{__('TỔNG TIỀN KHÁCH TRẢ')}}:</strong>
                        <strong class="coll-3 font-size-15">
                            {{number_format($totalCustomerPaid, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('VNĐ')}}
                        </strong>
                    </div>
                    @if (isset($receipt_detail) && $receipt_detail != null)
                        @foreach($receipt_detail as $paymentMethod)
                            <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                                <strong class="coll-7 font-size-15 fontw-200">{{$paymentMethod['payment_method_name']}}:</strong>
                                <strong class="coll-3 font-size-15 fontw-200">
                                    {{number_format($paymentMethod['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('VNĐ')}}
                                </strong>
                            </div>
                        @endforeach
                    @endif

                    @if(($order['amount']-($totalCustomerPaid))>0)
                        <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                            <strong class="coll-7 font-size-15">{{__('KHÁCH NỢ')}}:</strong>
                            <strong class="coll-3 font-size-15">
                                {{number_format($order['amount']-($totalCustomerPaid), isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}} {{__('VNĐ')}}
                            </strong>
                    </div>
                    @endif
                    <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                        {{__('TỔNG TIỀN KHÁCH TRẢ')}} {{__('VIẾT BẰNG CHỮ')}}: <span class="first-text-upper">{{$text_total_amount_paid}}
                            {{__('đồng')}}.</span>


                    </div>
                    @if($order['note']!='' && $order['note']!=null)
                        <div class="mm-mauto tientong font-size-15 roww" style="margin-top: 2px !important;">
                            {{__('Ghi chú')}}: {{$order['note']}}
                        </span>


                    </div>
                    @endif

                    <hr>
                    <div class="mm-mauto tientong font-size-15 roww">
                        <strong class="coll-5 font-size-15" style="margin-left: 50px">{{__('Người mua hàng')}}</strong>
                        <strong class="coll-5 font-size-15" style="margin-right: 50px">{{__('Người bán hàng')}}</strong>
                    </div>
                    <div class="mm-mauto tientong font-size-15 roww">
                        <strong class="coll-5 font-size-13" style="font-weight: 300;font-size: 13px;margin-left: 60px">{{__('(Ký, ghi rõ họ tên)')}}</strong>
                        <strong class="coll-5 font-size-13" style="font-weight: 300;font-size: 13px;margin-right: 25px">{{__('(Ký, đóng dấu, ghi rõ họ tên)')}}</strong>
                    </div>
                    <br>
                    <br>
                    <br>
                </div>
            </div>
            <div class="widhtss" style="margin-top: 15px; text-align: right">
                <div style="color: red;margin-bottom: 5px;">
                    <span class="error-print-bill font-size-15"></span>
                </div>
                <a class="btn btn-metal btn-sm" onclick="PrintBill.back()">{{__('THOÁT')}}</a>
                <a onclick="PrintBill.printBill()" class="btn btn-success btn-sm" style="margin-left: 10px">
            <span>
                <i class="la la-calendar-check-o"></i>
                <span>
                    {{__('IN HÓA ĐƠN')}}
                </span>
            </span>
                </a>
            </div>
        </section>
    </div>
</div>
<input type="hidden" id="orderId" value="{{$id}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    const name = $('.first-text-upper').text();
    const nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1);
    $('.first-text-upper').text(nameCapitalized);
</script>
<script type="text/javascript">
    // $(window).on('load', function () {
    //     $('body').removeClass('m-page--loading');
    // });
    // $.ajaxSetup({
    //     headers: {
    //         'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    //     }
    // });
</script>
<script src="{{asset('js/laroute.js') . '?t=' . time()}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/order/save-log-print-bill.js')}}" type="text/javascript"></script>
</body>
</html>