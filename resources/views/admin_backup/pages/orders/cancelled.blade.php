@extends('admin.master', ['type' => 'admin'])
@section('title','Cancelled Orders')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Cancelled Orders</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="#">Orders</a>
                </li>
                <li class="active">
                    Cancelled Orders
                </li>
            </ol>
        </div>
    </div>
    @if(session()->has('success'))
        <script>
            Swal.fire('{{ session()->get('success') }}', '', 'success')
        </script>
    @endif
    @if(session()->has('warning'))
        <script>
            Swal.fire('{{ session()->get('warning') }}', '', 'warning')
        </script>
    @endif
    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="cancelled-orders-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Patient Name</th>
                        <th>Patient Email</th>
                        <th>Patient Phone</th>
                        <th>Plan Name</th>
                        <th>Total Amount</th>
                        <th>Cancellation Reason</th>
                        <th>Order Type</th>
                        <th>Refund Status</th>
                        <th>Order Date</th>
                        <th>Cancelled Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($cancelled_orders as $order)
                        <tr>
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                            <td>{{ $order->user->email }}</td>
                            <td>{{ $order->user->phone }}</td>
                            <td>{{ $order->product_name }}</td>
                            <td>${{ $order->total_price }}</td>
                            <td>{{ $order->cancel_reason }}</td>
                            <td>{{ $order->is_subscription ? 'Subscription Pay' : 'One Time Pay' }}</td>
                            <td>
                                @if($order->status == 'Cancelled')
                                    <div class="">Pending</div>
                                @else
                                    <div class="">Refunded</div>
                                @endif
                            </td>
                            <td>{{ ($order->created_at)?\Carbon\Carbon::parse($order->created_at)->format('m-d-Y - H:i'):'-' }}</td>
                            <td>{{ ($order->updated_at)?\Carbon\Carbon::parse($order->updated_at)->format('m-d-Y - H:i'):'-' }}</td>
                            <td>

                            @if($order->status == 'Cancelled')
                                <a href="#" class="btn btn-danger" data-toggle="modal" data-target="#order_refund_modal" onclick="refundOrder('{{$order->id}}')">Refund</a>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{--payout reject model--}}
    <div class="modal fade" id="order_refund_modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header" style="border-bottom: none;padding-bottom:0px">
                    <h3 class="modal-title" id="exampleModalLongTitle" style="text-align: center;font-weight: 600;margin-top: -6px;">Refund Amount</h3>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" style="margin-top: -34px">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{route('admin.refund.order')}}" method="post">
                    @csrf
                    <input type="hidden" name="order_id" id="order_id" value="">
                    <div class="modal-body">
                        <h5>Order cancellation is being processed, Are you sure to complete this operation?</h5>
                    </div>
                    <div class="modal-footer">
                        <div class="form-group text-right m-b-0">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            <button class="ladda-button btn btn-primary" data-style="slide-right" type="submit" id="reject_btn">Proceed</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function () {
            $('#cancelled-orders-datatable').dataTable({
                "order": [[5, "desc"]]
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

        function refundOrder(order_id){
            $("#order_id").val(order_id);
        }
    </script>
@endsection
