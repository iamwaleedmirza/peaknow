@extends('admin.master', ['type' => 'admin'])
@section('title','Privacy Policy Page Content')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Privacy Policy Page Content</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li>
                    <a href="#">Site Settings</a>
                </li>
                <li class="active">
                    Privacy Policy Page Content
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
        <div class="col-lg-12">
                            <div class="card-box" style="border: none">
                                <form action="{{route('pages-content.post','privacy_policy')}}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="passWord2">Page Content</label>
                                        <textarea class="form-control" id="summernote" name="page_content">{{($content)?$content->privacy_policy_page:''}}</textarea>
                                    </div>
                                    <div class="form-group text-right m-b-0">
                                        <button class="ladda-button btn btn-primary" data-style="slide-right" type="submit">
                                            Update
                                        </button>
                                    </div>
                                </form>
                            </div>
        </div>
    </div>
@endsection
@section('js')
    <script type="text/javascript">
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 250
            });
            $('#summernote2').summernote({
                height: 250
            });
        });
    </script>
@endsection
