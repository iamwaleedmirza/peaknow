@extends('admin.master', ['type' => 'admin'])
@section('title','Past Orders')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Past Orders</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="#">Orders</a>
                </li>
                <li class="active">
                    Past Orders
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
                <table id="past-orders-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Product Name</th>
                        <th>Product Price</th>
                        <th>Order Amount</th>
                        <th>Order Date</th>
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($past_orders as $order)
                        <tr>
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $order->product_name }}</td>
                            <td>{{ $order->product_price }}</td>
                            <td>{{ $order->total_price }}</td>
                            <td>{{ ($order->created_at)?\Carbon\Carbon::parse($order->created_at)->format('m-d-Y'):'-' }}</td>
                            @php
                                if($order->status == 'Prescribed'){
                                  $class= "btn-success";
                                  $btn_text = "Prescribed";
                                }
                                else if($order->status == 'Declined'){
                                    $class= "btn-danger";
                                     $btn_text = "Declined";
                                }
                                else if($order->status == 'Cancelled'){
                                    $class= "btn-danger";
                                     $btn_text = "Cancelled";
                                }
                            @endphp
                            <td>
                                <button class="btn {{$class}}">{{$btn_text}}</button>
                            </td>
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
            $('#past-orders-datatable').dataTable();
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
