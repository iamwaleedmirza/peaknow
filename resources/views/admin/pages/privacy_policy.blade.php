@extends('admin.master', ['type' => 'admin'])
@section('title','Admin | Privacy Policy Page Content')
@section('navbar')
@include('admin.includes.sidebar')
@endsection

@section('header')
@include('admin.includes.header')
@endsection


@section('content')
    <!-- Page-Title -->
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
                                    @if(in_array('pages-content.post',$permissions) || Auth::user()->u_type=='superadmin') 
                                    <div class="form-group text-end m-3">
                                        <button class="btn btn-primary btn-hover-scale me-5" data-style="slide-right"
                                                type="submit">
                                            Update
                                        </button>
                                    </div>
                                    @endif
                                </form>
                            </div>
        </div>
    </div>
@endsection
@section('js')
<!--CKEditor Build Bundles:: Only include the relevant bundles accordingly-->
<script src="{{asset('admin_assets/assets/plugins/custom/tinymce/tinymce.bundle.js')}}"></script>
    <script type="text/javascript">
        // $(document).ready(function() {
        //     $('#summernote').summernote({
        //         height: 250
        //     });
        //     $('#summernote2').summernote({
        //         height: 250
        //     });
        // });

const options = {
            selector: "#summernote",
            menubar: false,
            toolbar: ["styleselect fontselect fontsizeselect",
                "undo redo | cut copy paste | bold italic | link image | alignleft aligncenter alignright alignjustify",
                "bullist numlist | outdent indent | blockquote subscript superscript | advlist | autolink | lists charmap | print preview |  code"],
            plugins: "advlist autolink link image lists charmap print preview code"
        };

        if (KTApp.isDarkMode()) {
            options["skin"] = "oxide-dark";
            options["content_css"] = "dark";
        }
        tinymce.init(options);
    </script>
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
@endsection
@section('footer')
@include('admin.includes.footer')
@endsection