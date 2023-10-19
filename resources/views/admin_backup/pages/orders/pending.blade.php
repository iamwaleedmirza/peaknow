@extends('admin.master', ['type' => 'admin'])
@section('title','Pending Orders')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Pending Orders</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="#">Orders</a>
                </li>
                <li class="active">
                    Pending Orders
                </li>
            </ol>
        </div>
    </div>
    @if(session()->has('success'))
        <script>
            Swal.fire('{{ session()->get('success') }}', '', 'success')
        </script>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="pending-orders-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Patient Name</th>
                        <th>Patient Email</th>
                        <th>Patient Phone</th>
                        <th>Plan Name</th>
                        <th>Total Amount</th>
                        <th>Order Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($pending_orders as $order)
                        <tr>
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                            <td>{{ $order->user->email }}</td>
                            <td>{{ $order->user->phone }}</td>
                            <td>{{ $order->product_name }}</td>
                            <td>${{ $order->total_price }}</td>
                            <td>{{ ($order->created_at)?\Carbon\Carbon::parse($order->created_at)->format('m-d-Y - H:i'):'-' }}</td>
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
            $('#pending-orders-datatable').dataTable({
                "order": [[4, "desc"]]
            });
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
