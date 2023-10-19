@extends('admin.master', ['type' => 'admin'])
@section('title','Refund History')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Refund History</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li class="active">
                    Refund History
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
                <table id="refund-orders-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>Order No</th>
                        <th>Refund Amount</th>
                        <th>Transaction Date</th>
                        <th>Transaction ID</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($refund_data as $data)
                        <tr>
                            <td>{{$data->order_no}}</td>
                            <td>${{$data->amount}}</td>
                            <td>{{date('d-m-Y',strtotime($data->created_at))}}</td>
                            <td>{{$data->transaction_id}}</td>
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
            $('#refund-orders-datatable').dataTable();
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
