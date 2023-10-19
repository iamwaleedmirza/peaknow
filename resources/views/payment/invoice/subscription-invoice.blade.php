<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Peaks Invoice</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 10px;
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(3) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            /*padding-bottom: 20px;*/
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(1) {
            border-top: 1px solid #eee;
            font-weight: bold;
            font-size: large;
        }
        .invoice-box table tr.topBorder td {
            border-top: 1px solid #eee;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(3) {
            text-align: left;
        }

        .t-bold {
            font-weight: bold;
        }

        .text-right {
            text-align: right;
        }

        .comp-logo {
            width: 100%;
            max-width: 200px;
            margin-top: -30pt;
            margin-right: -20pt;
        }
        .billPeriod{
            width: 220px;
        }
        .exclude{
            width: 100%;
        }
        .w-60px {
            width: 60px;
        }
    </style>
</head>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="3">
                <table>
                    <tr>
                        <td>
                            <h1 style="margin: 0 0 20pt;">Invoice</h1> <br/>
                            <strong>Invoice:</strong> #{{ $order['invoice_no'] }} <br/>
                            <strong>Invoice Date:</strong> {{ isset($order['trans_date'])? \Carbon\Carbon::parse($order['trans_date'])->format('M d, Y') : \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}
                            <br/>
                            <strong>Order:</strong> #PC-{{ $order['order_no'] }} <br/>
                            <strong>Order Date:</strong> {{  \Carbon\Carbon::parse($order['created_at'])->format('M d, Y') }}
                        </td>
                        <td class="text-right">
                            <img class="comp-logo" src="images/png/peaks-logo-dark.png"/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="3">
                <table>
                    <tr>
                        <td>
                            <div style="max-width: 200px;">
                                <strong>Billed To:</strong> <br/>
                                {{-- {{ $order['first_name'].' '.$order['last_name'] }}<br> --}}
                                {{ $order['shipping_fullname'] }} <br>
                                {{ $order['shipping_address_line2']?$order['shipping_address_line'].', '.$order['shipping_address_line2']:$order['shipping_address_line'] }} {{ $order['shipping_city'].', '.$order['shipping_state'].' - '.$order['shipping_zipcode'] }}.
                                <br>
                                <strong>Phone #:</strong> {{ $order['shipping_phone'] }}
                            </div>
                        </td>
                        <td class="text-right">
                            <strong>Seller:</strong> <br/>
                            Peaks Curative<br/>
                            8950 SW 74th Court <br>
                            Suite 102B <br>
                            Miami, FL 33156
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Payment Method</td>
            @if($order['transaction_id'])
                <td colspan="2" class="text-right">Transaction ID</td>
            @endif
        </tr>

        <tr class="details">
            <td>{{ $order['payment_method'] }}</td>

            @if($order['transaction_id'])
                <td colspan="2" class="text-right">{{ $order['transaction_id'] }}</td>
            @endif
        </tr>

        <tr class="heading">
            <td>Item</td>
            <td>Billing Period</td>
            <td>Price</td>
        </tr>

        <tr class="item">
            <td class="exclude">
                <span>{{ $order['product_name'] }} ({{ $order['medicine_variant'] }})</span> <br> 
                <span style="font-size: 12px">{{ $order['plan_name'] }} ({{ $order['plan_title'] }})</span><br>
                <span style="font-size: 12px">{{ $order['product_quantity'] }} X {{ $order['strength' ]}}mg </span>
            </td>
            <td class="billPeriod">{{ \Carbon\Carbon::parse($order['created_at'])->format('F d, Y') }} to {{\Carbon\Carbon::parse($order['billing_end_date'])->format('F d, Y') }}</td>
            <td class="exclude text-right">${{ $order['product_price'] }}</td>
        </tr>

        <tr>
            <td colspan="3" style="padding: 0;">
                <table>
                    <tr>
                        <td class="text-right">Telemedicine Consultation Fee:</td>
                        <td class="text-right t-bold w-60px">${{ $order['telemedConsult'] }}</td>
                    </tr>

                    <tr class="topBorder">
                        <td class="text-right">Shipping & Handling Cost:</td>
                        <td class="text-right t-bold w-60px">@if($order['shippingCost'] == 0)<span style="color: #096036;">FREE</span>@else${{ $order['shippingCost'] }}@endif</td>
                    </tr>

                    <tr class="topBorder">
                        <td class="text-right">Subtotal:</td>
                        <td class="text-right t-bold w-60px">${{sprintf('%0.2f',$order['product_price'] + $order['telemedConsult'] + $order['shippingCost'])}}</td>
                    </tr>

                    <tr class="topBorder">
                        <td class="text-right">Peaks Loyalty Program Member Discount:</td>
                        <td class="text-right t-bold w-60px">-${{ $order['telemedConsult'] }}</td>
                    </tr>

                    @if($order['plan_discount']>0)
                    <tr class="topBorder">
                        <td class="text-right">Subscribe & Save Discount:</td>
                        <td class="text-right t-bold w-60px">-${{$order['plan_discount']}}</td>
                    </tr>
                    @endif

                    @if ($order['is_promo_active'] == 1)
                    <tr class="topBorder">
                        <td class="text-right">Promo code discount [<span class="t-bold">{{$order['promo_code']}} ({{$order['promo_discount_percent']}}%)</span>]:</td>
                        <td class="text-right t-bold w-60px">-${{ $order['promo_discount'] }}</td>
                    </tr>
                    @endif

                    <tr class="total topBorder">
                        <td class="text-right">Total:</td>
                        <td class="text-right t-bold w-60px">${{ $order['total_price'] }}</td>
                    </tr>

                </table>
            </td>
        </tr>

    </table>
</div>
</body>
</html>
