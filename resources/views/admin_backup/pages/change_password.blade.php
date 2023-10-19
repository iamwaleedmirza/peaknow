@extends('admin.master', ['type' => 'admin'])
@section('title','Change Password')
@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-sm-12">
            <h4 class="page-title">Change Password</h4>
            <ol class="breadcrumb">
                <li>
                    <a href="{{route('admin.home')}}">Home</a>
                </li>
                <li class="active">
                    Change Password
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
        <div class="col-lg-12">
            <div class="card-box">
                <form action="{{url('/admin/change-password/submit')}}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="">Old password <span class="required-field"> *</span></label>
                        <input type="password" name="old_password" required placeholder="Enter old password"
                               class="form-control">
                        @error('old_password')
                        <span class="invalid-feedback" role="alert">
                                          <small>{{ $message }}</small>
                                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">New password <span class="required-field"> *</span></label>
                        <input type="password" name="password" required placeholder="Enter new password"
                               class="form-control">
                        @error('password')
                        <span class="invalid-feedback" role="alert">
                                          <small>{{ $message }}</small>
                                            @if($message == 'The password format is invalid.')
                                <br>
                                <small>The new password must contain at least one lowercase letter.</small><br>
                                <small>The new password must contain at least one uppercase letter.</small><br>
                                <small>The new password must contain at least one digit.</small>
                            @endif
                                        </span>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">Confirm New password <span class="required-field"> *</span></label>
                        <input type="password" name="password_confirmation" required placeholder="Confirm Password"
                               class="form-control">
                    </div>
                    <div class="form-group text-right m-b-0">
                        <button class="ladda-button btn btn-primary" data-style="slide-right" type="submit">
                            Change Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
