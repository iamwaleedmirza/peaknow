<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>Invoice</title>
    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
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

        .invoice-box table tr td:nth-child(2) {
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

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
            font-size: large;
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

        .invoice-box.rtl table tr td:nth-child(2) {
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
    </style>
</head>

<body>
<div class="invoice-box">
    <table cellpadding="0" cellspacing="0">
        <tr class="top">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <h1 style="margin: 0 0 20pt;">Invoice</h1> <br/>
                            <strong>Order No:</strong> #{{ $order->order_no }} <br/>
                            <strong>Order
                                Date:</strong> {{ \Carbon\Carbon::parse($order->created_at)->format('M d, Y') }}<br>
                                <strong>Transaction Date:</strong>
                                {{ \Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}
                        </td>
                        <td class="text-right">
                            <img class="comp-logo" src="images/png/peaks-logo-dark.png"/>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="information">
            <td colspan="2">
                <table>
                    <tr>
                        <td>
                            <div style="max-width: 180px;">
                                <strong>Billed To:</strong> <br/>
                                    {{ $tr_details->getPaymentProfile()->getbillTo()->getFirstName().' '.$tr_details->getPaymentProfile()->getbillTo()->getLastName() }}<br>
                                    {{ $tr_details->getPaymentProfile()->getbillTo()->getAddress() }},<br>
                                    {{ $tr_details->getPaymentProfile()->getbillTo()->getCity().', '.$tr_details->getPaymentProfile()->getbillTo()->getState() }},<br>
                                    {{ $tr_details->getPaymentProfile()->getbillTo()->getCountry().', '.$tr_details->getPaymentProfile()->getbillTo()->getZip() }}
                            </div>
                        </td>

                        <td class="text-right">
                            <strong>Seller:</strong> <br/>
                            Peaks Curative<br/>
                            Street address, <br>
                            City, State, <br>
                            Country, Zip code
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="heading">
            <td>Payment Method</td>
            <td>Transaction ID</td>
        </tr>

        <tr class="details">
            <td>{{ ($tr_details->getPaymentProfile()->getPayment()->getCreditCard() != null)?'Through Card':'Through Bank Account' }}</td>
            <td>{{ $transaction->transaction_id }}</td>
        </tr>

        <tr class="heading">
            <td>Item</td>
            <td>Price</td>
        </tr>

        <tr class="item">
            <td>{{ $order['product_name'] }}</td>
            <td>${{ $order['product_price'] }}</td>
        </tr>

        <tr class="item">
            <td>Subscription Plan Price</td>
            <td>${{$transaction->amount}}</td>
        </tr>

        <tr class="total">
            <td></td>
            <td>Total: ${{ $order->total_price }}</td>
        </tr>
    </table>
</div>
</body>
</html>
