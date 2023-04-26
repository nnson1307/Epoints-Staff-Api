<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{__('In hóa đơn')}}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon"
          href="{{isset(config()->get('config.logo')->value) ? config()->get('config.logo')->value : ''}}"/>
    <link rel="stylesheet" href="{{asset('static/backend/css/print-bill.css')}}">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css"
          integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">

    <style>
        body {
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            background-color: #FFF;
            font: 10pt "Times New Roman";
        }

        * {
            box-sizing: border-box;
            -moz-box-sizing: border-box;
        }

        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            border: 1px #FFF solid;
            border-radius: 5px;
            background: white;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        .subpage {
            padding: 1cm;
            /*border: 5px red solid;*/
            height: 257mm;
            outline: 2cm #FFF solid;
        }

        .form-group-print {
            margin-bottom: 1rem;
        }

        .font-size-15 {
            font-size: 15px;
        }

        .font-size-14 {
            font-size: 14px;
            line-height: 1.2rem;
        }

        .row_custom {
            flex-wrap: wrap;
            box-sizing: border-box;
            position: relative;
            width: 100%;
        }

        .col-print-1 {
            width: 8%;
            float: left;
        }

        .col-print-2 {
            width: 16%;
            float: left;
        }

        .col-print-3 {
            width: 25%;
            float: left;
        }

        .col-print-4 {
            width: 33%;
            float: left;
        }

        .col-print-5 {
            width: 42%;
            float: left;
        }

        .col-print-6 {
            width: 50%;
            float: left;
        }

        .col-print-7 {
            width: 58%;
            float: left;
        }

        .col-print-8 {
            width: 66%;
            float: left;
        }

        .col-print-9 {
            width: 75%;
            float: left;
        }

        .col-print-10 {
            width: 83%;
            float: left;
        }

        .col-print-11 {
            width: 92%;
            float: left;
        }

        .col-print-12 {
            width: 100%;
            float: left;
        }

        .clearfix {
            overflow: auto;
        }

        .img_print {
            width: 25mm;
            height: 13mm;
        }

        .widhtss {
            margin: 0 auto;
            width: 98%;
            height: 98%;
        }

        @page {
            size: A4;
            margin: 0;
        }

        @media print {
            html, body {
                width: 210mm;
                height: 297mm;
            }

            .page {
                /*margin: 0;*/
                /*border: initial;*/
                /*border-radius: initial;*/
                /*width: initial;*/
                /*min-height: initial;*/
                /*box-shadow: initial;*/
                /*background: initial;*/
                /*page-break-after: always;*/
            }

            .div_button_print {
                display: none;
            }
        }
    </style>
</head>
<body>

<div class="book">
    <div class="page">
        <div class="subpage">
            @if($configPrintBill['is_show_logo']==1)
                <div class="form-group text-center">
                    <img class="img_print"
                         src="{{asset($spaInfo['logo'])}}">
                </div>
            @endif
            <div class="form-group">
                @if($configPrintBill['is_show_unit']==1)
                    <strong>{{$branchInfo['branch_name']}}</strong> <br>
                @endif
                @if($configPrintBill['is_show_address']==1)
                    <span class="font-size-15">
                    @lang('Địa chỉ'): &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; {{$branchInfo['address']}}
                </span> <br>
                @endif
                @if($configPrintBill['is_show_phone']==1)
                    <span class="font-size-15">
                    @lang('Điện thoại'): &nbsp;&nbsp;&nbsp; {{$branchInfo['hot_line']}}
                </span>
                @endif
            </div>
            <div class="form-group text-center">
                <span style="font-size: 23px; font-weight: bold;">@lang('HOÁ ĐƠN THANH TOÁN')</span>
            </div>
            <div class="form-group row_custom clearfix">
                <div class="col-print-7">
                    <span class="font-size-14">
                        <strong>@lang('Mã hồ sơ'):</strong> {{$order['profile_code']}}
                    </span> <br>
                    <span class="font-size-14">
                        <strong>@lang('Mã khách hàng'):</strong> {{$order['customer_code']}}
                    </span> <br>
                    <span class="font-size-14">
                        <strong>@lang('Khách hàng'):</strong> {{$order['full_name']}}
                    </span> <br>
                    <span class="font-size-14">
                        <strong>@lang('Điện thoại'):</strong> {{$order['phone']}}
                    </span> <br>
                    <span class="font-size-14">
                        <strong>@lang('Địa chỉ'):</strong> {{$order['address']}}
                    </span>
                </div>
                <div class="col-print-5">
                    <span class="font-size-14">
                        <strong>@lang('Nhân viên'):</strong> {{$order['staff_name']}}
                    </span> <br>
                    <span class="font-size-14">
                        <strong>@lang('Ngày giờ bán'):</strong> {{\Carbon\Carbon::parse($order['created_at'])->format('d/m/Y H:i:s')}}
                    </span> <br>
                    <span class="font-size-14">
                        <strong>@lang('Số hoá đơn'):</strong> {{$order['order_code']}}
                    </span>
                </div>
            </div>

            <div class="form-group">
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th>@lang('STT')</th>
                        <th>@lang('Mã vạch - Tên sản phẩm')</th>
                        <th>@lang('ĐVT')</th>
                        <th>@lang('SL')</th>
                        <th>@lang('Đ.Giá')</th>
                        <th>@lang('KM')</th>
                        <th>@lang('T.Tiền')</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if (isset($order_detail) && count($order_detail) > 0)
                        @foreach($order_detail as $key => $item)
                            <tr>
                                <td class="text-center">{{$key+1}}</td>
                                <td>{{$item['object_name']}}</td>
                                <td>{{$item['unit_name']}}</td>
                                <td>{{$item['quantity']}}</td>
                                <td>{{number_format($item['price'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                <td>{{number_format($item['discount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                                <td>{{number_format($item['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</td>
                            </tr>
                        @endforeach
                        <tr>
                            <td></td>
                            <td></td>
                            <td>
                                <strong>@lang('Tổng')</strong>
                            </td>
                            <td>
                                <strong>{{$totalQuantity}}</strong>
                            </td>
                            <td></td>
                            <td>
                                <strong>{{number_format($totalDiscountDetail, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>
                            </td>
                            <td>
                                <strong>{{number_format($order['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}</strong>
                            </td>
                        </tr>
                    @endif
                    @if ($configPrintBill['is_total_bill'] == 1)
                        <tr>
                            <td colspan="7">
                                <div class="row_custom">
                                    <div class="col-print-8" style="font-size: 14px;">
                                        @lang('Tổng tiền') (@lang('VNĐ'))
                                    </div>
                                    <div class="col-print-4 text-right" style="font-size: 14px; font-weight: bold;">
                                        {{number_format($order['total'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if ($configPrintBill['is_total_discount'] == 1)
                        <tr>
                            <td colspan="7">
                                <div class="row_custom">
                                    <div class="col-print-8" style="font-size: 14px;">
                                        @lang('Tổng tiền khuyến mãi') (@lang('VNĐ'))
                                    </div>
                                    <div class="col-print-4 text-right" style="font-size: 14px; font-weight: bold;">
                                        {{number_format($totalDiscount, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if ($configPrintBill['is_total_amount'] == 1)
                        <tr>
                            <td colspan="7">
                                <div class="row_custom">
                                    <div class="col-print-8" style="font-size: 14px;">
                                        @lang('Phải thanh toán') (@lang('VNĐ'))
                                    </div>
                                    <div class="col-print-4 text-right" style="font-size: 14px; font-weight: bold;">
                                        {{number_format($order['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if ($configPrintBill['is_total_receipt'] == 1)
                        <tr>
                            <td colspan="7">
                                <div class="row_custom">
                                    <div class="col-print-8" style="font-size: 14px;">
                                        @lang('Khách hàng trả') (@lang('VNĐ'))
                                    </div>
                                    <div class="col-print-4 text-right" style="font-size: 14px; font-weight: bold;">
                                        {{number_format($totalCustomerPaid, isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    @if ($configPrintBill['is_amount_return'] == 1)
                        <tr>
                            <td colspan="7">
                                <div class="row_custom">
                                    <div class="col-print-8" style="font-size: 14px;">
                                        @lang('Tiền trả lại') (@lang('VNĐ'))
                                    </div>
                                    <div class="col-print-4 text-right" style="font-size: 14px; font-weight: bold;">
                                        {{number_format(($totalCustomerPaid)-$receipt['amount'], isset(config()->get('config.decimal_number')->value) ? config()->get('config.decimal_number')->value : 0)}}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif
                    {{--<tr>--}}
                        {{--<td colspan="7" style="font-weight: bold;">--}}
                            {{--@lang('Số tiền viết bàng chữ'):--}}
                            {{--<span style="font-style: italic;">{{$convertNumberToWords}}</span>--}}
                            {{--<span style="font-style: italic;">@lang('VNĐ')</span>--}}
                        {{--</td>--}}
                    {{--</tr>--}}
                    </tbody>
                </table>
            </div>
            <div class="form-group" style="font-style: italic">
                @lang('Ghi chú'): <span style="font-weight: bold;">{{$order['note']}}</span>
            </div>
            <div class="form-group">
                @if ($configPrintBill['is_show_footer'] == 1)
                    {{$configPrintBill['note_footer']}}
                @endif
            </div>
            <div class="form-group-print row_custom">
                <div class="col-print-3">
                    <span style="font-weight: bold;">@lang('Người lập phiếu')</span>
                </div>
                <div class="col-print-3">
                    <span style="font-weight: bold;">@lang('Người giao hàng')</span>
                </div>
                <div class="col-print-3">
                    <span style="font-weight: bold;">@lang('Thủ kho')</span>
                </div>
                <div class="col-print-3">
                    <span style="font-weight: bold;">@lang('Người nhận hàng')</span>
                </div>
            </div>
            <br><br>
            <div class="form-group text-center div_button_print">
                <div style="color: red; margin-bottom: 5px;">
                    <span class="error-print-bill font-size-15"></span>
                </div>

                <a class="btn btn-primary" onclick="PrintBill.back()">
                    {{__('THOÁT')}}
                </a>

                <a onclick="PrintBill.printBill()" class="btn btn-success" style="margin-left: 10px">
                    <span>
                        <i class="la la-calendar-check-o"></i>
                        <span>
                            {{__('IN HÓA ĐƠN')}}
                        </span>
                    </span>
                </a>
            </div>
        </div>
    </div>
    {{--<div class="page">--}}
    {{--<div class="subpage">Page 2/2</div>--}}
    {{--</div>--}}
</div>

</body>
</html>

<input type="hidden" id="orderId" value="{{$id}}">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script>
    const name = $('.first-text-upper').text();
    const nameCapitalized = name.charAt(0).toUpperCase() + name.slice(1);
    $('.first-text-upper').text(nameCapitalized);
</script>
<script type="text/javascript">
</script>
<script src="{{asset('js/laroute.js') . '?t=' . time()}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/general/jquery.printPage.js')}}" type="text/javascript"></script>
<script src="{{asset('static/backend/js/admin/order/save-log-print-bill.js')}}" type="text/javascript"></script>
