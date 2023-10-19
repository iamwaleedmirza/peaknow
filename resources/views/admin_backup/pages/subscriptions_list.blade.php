@extends('admin.master', ['type' => 'admin'])
@section('title','Subscription List')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Subscription List</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li class="active">
                    Subscription List
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="subscription-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>S.No</th>
                        <th>User Name</th>
                        <th>User Email</th>
                        <th>Plan Name</th>
                        <th>Subscription ID</th>
                        <th>Subscription Status</th>
                        <th>Subscribed Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    @php $s_no = 1;  @endphp
                    <tbody>
                    @foreach ($subscriptions as $subscriber)
                        <tr>
                            <td>{{ $s_no++ }}</td>
                            <td>{{$subscriber->user->first_name.' '.$subscriber->user->last_name }}</td>
                            <td>{{ $subscriber->user->email }}</td>
                            <td>{{ $subscriber->plan->title }}</td>
                            <td>{{ $subscriber->subscription_id}}</td>
                            <td>
                                @if($subscriber->active_status == 1)
                                    <button class="btn btn-success">Active</button>
                                @else
                                <button class="btn btn-danger">Inactive</button>
                                 @endif
                            </td>
                            <td>{{ ($subscriber->created_at)?\Carbon\Carbon::parse($subscriber->created_at)->format('m-d-Y'):'-' }}</td>
                            <td><a href="{{route('admin.subscription.transactions',$subscriber->order_no)}}" class="btn btn-primary">Transactions</a></td>
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
            $('#subscription-datatable').dataTable();
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
