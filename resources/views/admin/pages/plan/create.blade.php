@extends('admin.master', ['type' => 'admin'])
@section('title', 'Admin | Plan')
@section('navbar')
    @include('admin.includes.sidebar')
@endsection

@section('header')
    @include('admin.includes.header')
@endsection

@section('content')
    <!-- Page-Title -->
    <div class="row">
        <div class="col-lg-12">
            <ol class="breadcrumb breadcrumb-line text-muted fs-6 fw-bold mb-4">
                <li class="breadcrumb-item pe-3"><a href="{{ route('admin.home') }}" class="pe-3">Home</a></li>
                <li class="breadcrumb-item pe-3"><a href="{{route('admin.plan.list')}}" class="pe-3">List of Plans</a>
                </li>
                <li class="breadcrumb-item px-3 text-muted">{{$title}}</li>

                
            </ol>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="tab-content">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="card-box">
                            <form id="data_form" method="post" enctype="multipart/form-data">
                                @csrf

                                <input type="hidden" name="id" id="id" value="{{(@$row['data']->id) ? $row['data']->id : ''}}"/>
                                
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Select Product <span class="error">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="product" id="product" class="form-select">
                                            <option value="">Select Product </option>
                                            @foreach($row['product'] as $product)
                                            <option value="{{$product->id}}" @if(@$row['data']->product_id==$product->id) selected @endif>{{$product->name}}</option>
                                            @endforeach                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Select Plan Type <span class="error">*</span></label>
                                    </div>
                                    <div class="col-md-10 ">
                                        <select name="plan" id="plan" class="form-select">
                                            <option value="">Select Plan</option>
                                            @foreach($row['plan'] as $plan)
                                            <option value="{{$plan->id}}" data-type="{{$plan->subscription_type}}" @if(@$row['data']->plan_type_id==$plan->id) selected @endif>{{$plan->name}} ({{($plan->subscription_type==0) ? 'One Time' : 'Monthly Subscription'}})</option>
                                            @endforeach                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-4 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Select Medicine Variant <span class="error">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <select name="medicine" id="medicine" class="form-select">
                                            <option value="">Select Medicine Variant</option>
                                            @foreach($row['medicine'] as $medicine)
                                            <option value="{{$medicine->id}}" @if(@$row['data']->medicine_varient_id==$medicine->id) selected @endif>{{$medicine->name}}</option>
                                            @endforeach                                            
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-3 mb-4 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Enter Plan Title (Active,Playful,Frisky)<span class="error">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" placeholder="Enter Plan Title" class="form-control" maxlength="50" name="plan_title" value="{{@$row['data']->plan_title}}">
                                    </div>
                                </div>
                                <div class="row mt-3 mb-4 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Plan Image <span class="error">*</span></label>
                                    </div>
                                    <div class="col-md-9">
                                        <input type="file" placeholder="Select Plan Image" class="form-control" name="plan_image" accept="image/png,image/jpg,image/jpeg" id="image">
                                    </div>
                                    <div class="col-md-1">
                                        @if(@$row['data']->plan_image)
                                            <img src="{{getImage(@$row['data']->plan_image)}}" id="imgPreview" class="img-fluid preview-img">
                                        @else
                                            <img src="" id="imgPreview" class="img-fluid preview-img hidden">
                                        @endif
                                        
                                    </div>
                                </div>
                                <div class="row mt-3 mb-4 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Strength (mg)<span class="error">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" placeholder="Enter Strength" class="form-control" maxlength="3" name="strength" value="{{@$row['data']->strength}}" onkeypress="return onlyNumeric(event)">
                                    </div>
                                </div>
                                <div class="row mt-3 mb-4 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Product NDC<span class="error">*</span></label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" placeholder="Enter Product NDC" class="form-control" maxlength="50" name="product_ndc" value="{{@$row['data']->product_ndc}}">
                                    </div>
                                </div>
                                <div class="row mt-3 mb-4 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Product NDC 2</label>
                                    </div>
                                    <div class="col-md-10">
                                        <input type="text" placeholder="Enter Product NDC 2" class="form-control" maxlength="50" name="product_ndc_2" value="{{@$row['data']->product_ndc_2}}">
                                    </div>
                                </div>
                                <div class="row mt-3 fv-row">
                                    <div class="col-md-2 align-self-center">
                                        <label class="p-t-10" for="">Select Plan Status <span class="error">*</span></label>
                                    </div>
                                    <div class="col-md-10 ">
                                        <select name="is_active" id="is_active" class="form-select">
                                            <option value="">Select Plan Status</option>
                                            <option value="1" @if(@$row['data']->is_active==1 || @$row['data']->is_active=='') selected @endif>Active</option><option value="0" @if(@$row['data']->is_active==0 && @$row['data']->is_active!='') selected @endif>Inactive</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mt-5 fv-row">
                                    <div class="col-md-6">
                                        <div class="col-md-12 mt-3">
                                            <div class="col-md-12 align-self-center">
                                                <label class="p-t-10" for="">Enter Quantity <span class="error">*</span></label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" placeholder="Enter Quantity" class="form-control" maxlength="2" name="quantity1" onkeypress="return onlyNumeric(event)" value="{{@$row['data']->category_plan1['quantity']}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="col-md-12 align-self-center">
                                                <label class="p-t-10" for="">Enter Plan Price ($)<span class="error">*</span></label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" placeholder="Enter Price" class="form-control" maxlength="10" name="price1" id="price1" onkeypress="return IsNumeric(event)" value="{{@$row['data']->category_plan1['price']}}">
                                            </div>
                                        </div>
                                        <div class="col-md-10 mt-3">
                                            <label class="p-t-10" for="">Shipping & Handling Cost ($) </label>
                                            <input type="text" placeholder="Enter Shipping & Handling Cost" class="form-control" maxlength="10" name="sh1" id="sh1" onkeypress="return IsNumeric(event)" value="{{@$row['data']->category_plan1['shipping_cost']}}">
                                        </div>
                                        <div class="col-md-10 mt-3 discount">
                                                <label class="p-t-10" for="">Discount Amount ($)</label>
                                                <input type="text" placeholder="Enter Discount" class="form-control discount_amount" maxlength="10" name="discount1" id="discount1" onkeypress="return IsNumeric(event)" value="{{@$row['data']->category_plan1['discount']}}">
                                                <span class="error" id="discount_error1"></span>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="col-md-12 align-self-center">
                                                <label class="p-t-10" for="">Total ($)</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" placeholder="Enter Total" id="total1" class="form-control" readonly name="total1" value="{{@$row['data']->category_plan1['total']}}">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="col-md-12 mt-3">
                                            <div class="col-md-12 align-self-center">
                                                <label class="p-t-10" for="">Enter Quantity <span class="error">*</span></label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" placeholder="Enter Quantity" class="form-control" maxlength="2" name="quantity2" onkeypress="return onlyNumeric(event)" value="{{@$row['data']->category_plan2['quantity']}}">
                                            </div>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="col-md-12 align-self-center">
                                                <label class="p-t-10" for="">Enter Plan Price ($)<span class="error">*</span></label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" placeholder="Enter Price" class="form-control" maxlength="10" name="price2" id="price2" onkeypress="return IsNumeric(event)" value="{{@$row['data']->category_plan2['price']}}">
                                            </div>
                                        </div>
                                        <div class="col-md-10 mt-3">
                                            <label class="p-t-10" for="">Shipping & Handling Cost ($)</label>
                                            <input type="text" placeholder="Enter Shipping & Handling Cost" class="form-control" maxlength="10" name="sh2" id="sh2" onkeypress="return IsNumeric(event)" value="{{@$row['data']->category_plan2['shipping_cost']}}">
                                        </div>
                                        <div class="col-md-10 mt-3 discount">
                                                <label class="p-t-10" for="">Discount Amount ($)</label>
                                                <input type="text" placeholder="Enter Discount" class="form-control discount_amount" maxlength="10" name="discount2" id="discount2" onkeypress="return IsNumeric(event)" value="{{@$row['data']->category_plan2['discount']}}">
                                                <span class="error" id="discount_error2"></span>
                                        </div>
                                        <div class="col-md-12 mt-3">
                                            <div class="col-md-12 align-self-center">
                                                <label class="p-t-10" for="">Total ($)</label>
                                            </div>
                                            <div class="col-md-10">
                                                <input type="text" placeholder="Enter Total" id="total2" class="form-control" readonly name="total2" value="{{@$row['data']->category_plan2['total']}}">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group mt-5">
                                    <button id="edit-plan-btn" class="btn btn-primary btn-hover-scale me-5" type="submit">
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('footer')
    @include('admin.includes.footer')
@endsection
@section('js')
<script type="text/javascript">
    console.log($("#plan option:selected").attr('data-type'));
    if($("#plan option:selected").attr('data-type')==1){
        $(".discount").show();
    } else {
        $(".discount").hide();
    }
</script>
    <script src="{{ asset('admin_assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('admin_assets/js/additional-methods.min.js') }}"></script>
    <script src="{{ asset('admin_assets/assets/js/common.js') }}"></script>
    <script src="{{ asset('admin_assets/assets/js/plan.js') }}"></script>
@endsection
