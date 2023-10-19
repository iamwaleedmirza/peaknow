@extends('admin.master', ['type' => 'admin'])
@section('title','Admin Dashboard')
@section('content')
  <style>
    .pull-left img{
      margin-top: 16px;
    }
    .social_icons img{
      margin-top: 23px;
    }
  </style>
<div class="row">
  <div class="col-sm-12">
    <h4 class="page-title">Admin Dashboard</h4>
    <p class="text-muted page-title-alt">Welcome to admin panel !</p>
  </div>
</div>

  <div class="row">
      <div class="col-lg-6">
          <div class="card-box">
              <h4 class="text-dark header-title m-t-0">Users</h4>
              <div id="simple-line-chart" class="ct-chart ct-golden-section"></div>
          </div>
      </div>
      <div class="col-lg-6">
          <div class="card-box">
              <h4 class="text-dark header-title m-t-0">Revenue</h4>
              <div id="morris-bar-stacked" style="height: 300px;"></div>
          </div>
      </div>
  </div>

<div class="row">
  <div class="col-md-4 col-lg-4">
    <div class="widget-bg-color-icon card-box fadeInDown animated">
      <div class="bg-icon bg-icon-info pull-left">
        <img src = "{{ asset('/images/icons/consultation.png') }}" class="text-info" height="46">
      </div>
      <div class="text-right">
        <h3 class="text-dark"><b class="counter">20</b></h3>
        <p class="text-muted">Total Orders</p>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>

  <div class="col-md-4 col-lg-4">
    <div class="widget-bg-color-icon card-box">
      <div class="bg-icon bg-icon-pink pull-left">
        <img src = "{{ asset('/images/icons/medical-care.png') }}" class="text-pink" height="46">
      </div>
      <div class="text-right">
        <h3 class="text-dark"><b class="counter">2</b></h3>
        <p class="text-muted">Pending Orders</p>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>

  <div class="col-md-4 col-lg-4">
    <div class="widget-bg-color-icon card-box">
      <div class="bg-icon bg-icon-purple pull-left">
        <img src = "{{ asset('/images/icons/health-check.png') }}" class="text-purple" height="46">
      </div>
      <div class="text-right">
        <h3 class="text-dark"><b class="counter">30</b></h3>
        <p class="text-muted">Total Patients</p>
      </div>
      <div class="clearfix"></div>
    </div>
  </div>
</div>
  <!-- end row -->
@endsection
