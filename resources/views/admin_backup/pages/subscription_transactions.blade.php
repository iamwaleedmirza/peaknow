@extends('admin.master', ['type' => 'admin'])
@section('title') Subscription Transactions @endsection
@section('content')

    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Subscription Transactions</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="{{route('admin.subscription.list')}}">Subscription List</a>
                </li>
                <li class="active">
                    Subscription Transactions
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="transaction-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Transaction Date</th>
                        <th>Transaction ID</th>
                        <th>Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($transactions as $transaction)
                        <tr>
                            <td>{{ ($transaction->created_at)?\Carbon\Carbon::parse($transaction->created_at)->format('F d, Y'):'' }}</td>
                            <td>{{ $transaction->transaction_id }}</td>
                            <td>${{ $transaction->amount }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#transaction-datatable').dataTable();
            var table = $('#datatable-fixed-header').DataTable({fixedHeader: true});
            var table = $('#datatable-fixed-col').DataTable({
                scrollY: "300px",
                scrollX: true,
                scrollCollapse: true,
                paging: false,
                fixedColumns: {
                    leftColumns: 1,
                    rightColumns: 1
                }
            });
        });
        TableManageButtons.init();
    </script>
@endsection
