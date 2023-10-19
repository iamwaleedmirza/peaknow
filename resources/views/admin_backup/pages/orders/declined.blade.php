@extends('admin.master', ['type' => 'admin'])
@section('title','Declined Orders')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Declined Orders</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="#">Orders</a>
                </li>
                <li class="active">
                    Declined Orders
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
                <table id="declined-orders-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Patient Name</th>
                        <th>Patient Email</th>
                        <th>Patient Phone</th>
                        <th>Plan Name</th>
                        <th>Total Amount</th>
                        <th>Doctor Name</th>
                        <th>Doctor Reason</th>
                        <th>Order Date</th>
                        <th>Declined Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($declined_orders as $order)
                        <tr>
                            <td>{{ $order->order_no }}</td>
                            <td>{{ $order->user->first_name }} {{ $order->user->last_name }}</td>
                            <td>{{ $order->user->email }}</td>
                            <td>{{ $order->user->phone }}</td>
                            <td>{{ $order->product_name }}</td>
                            <td>${{ $order->total_price }}</td>
                            <td>{{ $order->doctor_name }}</td>
                            <td>{{ $order->doctor_response }}</td>
                            <td>{{ ($order->created_at)?\Carbon\Carbon::parse($order->created_at)->format('m-d-Y - H:i'):'-' }}</td>
                            <td>{{ ($order->updated_at)?\Carbon\Carbon::parse($order->updated_at)->format('m-d-Y - H:i'):'-' }}</td>
                            <td>
                                <a href="#" class="btn btn-danger order_refund_modal_btn" data-toggle="modal" data-target="#order_refund_modal" data-order_id="{{ $order->order_no }}">Refund</a>
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
                <form action="{{ route('admin.refund.order') }}" method="post">
                    @csrf
                <div class="modal-body">
                    <div class="form-group ">
                        <label>Select amount *</label>
                        <select name="order_amount_type" id="order_amount_type" class="form-control" required>
                            <option value="">.....</option>
                            <option value="total_amount">Total Amount</option>
                            <option value="partial_amount">Partial Amount</option>
                            <option value="custom_amount">Custom Amount</option>
                        </select>
                    </div>
                    <div class="order_data"></div>
                    <div class="form-group order_amount_data">
                       
                    </div>
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
            $('#declined-orders-datatable').dataTable({
                "order": [[6, "desc"]]
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
            $(document).on('click', '.order_refund_modal_btn', function() {
               
                $('.order_data').html('<input type="text" name="order_id" value="'+ $('.order_refund_modal_btn').attr('data-order_id')+'" hidden/>');
            });

            $('#order_amount_type').on('change', function() {
                if(this.value == "total_amount"){
                    $('.order_amount_data').html('<input type="text" name="order_amount" value="100%" hidden/>');
                }else if(this.value == "partial_amount"){
                    $('.order_amount_data').html('<input type="text" name="order_amount" value="50%" hidden/>');
                }else if(this.value == "custom_amount"){
                    $('.order_amount_data').html('<label>Enter Custom Amount *</label><input type="text" class="form-control" name="order_amount" value="" />');
                }
            });
        });
        TableManageButtons.init();
    </script>
@endsection
