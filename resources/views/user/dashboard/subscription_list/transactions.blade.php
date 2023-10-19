@extends('user.dashboard.dashboard')

@section('title') Transactions @endsection

@section('content')

    <div class="row justify-content-center mx-0 px-0">
        <div class="col-12 col-md-12 px-0">
            <div class="order-tabs">
                <h4 class="mb-4">Transactions</h4>
                <div class="table-responsive address">
                    <div class="table-responsive">
                        <table id="user-order-table" class="table">
                            <thead>
                            <tr>
                                <th scope="col">Transaction Date</th>
                                <th scope="col">Transaction ID</th>
                                <th scope="col">Amount</th>
                                <th scope="col">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($transactions as $transaction)
                                <tr>
                                    <td>{{ ($transaction->created_at)?\Carbon\Carbon::parse($transaction->created_at)->format('F d, Y'):'' }}</td>
                                    <td>{{ $transaction->transaction_id }}</td>
                                    <td>${{ $transaction->amount }}</td>
                                    <td style="white-space: nowrap">
                                      <a href="{{route('view.invoice',$transaction->order_no)}}" target="_blank" class="btn btn-outline-primary btn-small">Invoice</a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        $(document).ready(function () {
            $('#user-order-table').DataTable();
        });
    </script>
@endsection
