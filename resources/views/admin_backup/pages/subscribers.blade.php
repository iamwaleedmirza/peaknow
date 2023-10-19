@extends('admin.master', ['type' => 'admin'])
@section('title','All Subscribers')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">All Subscribers</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li class="active">
                    All Subscribers
                </li>
            </ol>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="card-box table-responsive">
                <table id="subscribers-datatable" class="table table-striped table-bordered">
                    <thead>
                    <tr>
                        <th>S.No</th>
                        <th>UserName</th>
                        <th>Phone</th>
                        <th>Email</th>
                        <th>Agreed To Agreement</th>
                        <th>Subscribed Date</th>
                    </tr>
                    </thead>
                    @php $s_no = 1;  @endphp
                    <tbody>
                    @foreach ($subscribers as $subscriber)
                        <tr>
                            <td>{{ $s_no++ }}</td>
                            <td>{{$subscriber->full_name }}</td>
                            <td>{{ $subscriber->phone }}</td>
                            <td>{{ $subscriber->email }}</td>
                            <td>{{ ($subscriber->agreement == 1)?'Yes':'No' }}</td>
                            <td>{{ ($subscriber->created_at)?\Carbon\Carbon::parse($subscriber->created_at)->format('m-d-Y'):'-' }}</td>
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
            $('#subscribers-datatable').dataTable();
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
