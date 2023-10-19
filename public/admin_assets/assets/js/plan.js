// $(".discount").hide();
$(document).ready(function() {
    $('#plan').change(function(e) {
        if($("#plan option:selected").attr('data-type')==0){
            $(".discount").hide();
        } else {
            $(".discount").show();
        }
        $(".discount_amount").val('');
        calculation(1);
        calculation(2);
    })

    $('#price1').keyup(function(e) {
        calculation(1)
    })

    $('#discount1').keyup(function(e) {
        calculation(1)
    })

    $('#sh1').keyup(function(e) {
        calculation(1)
    })

    $('#price2').keyup(function(e) {
        calculation(2)
    })

    $('#discount2').keyup(function(e) {
        calculation(2)
    })
    
    $('#sh2').keyup(function(e) {
        calculation(2)
    })

    function calculation(id){
        var price = parseFloat($("#price"+id).val());
        var discount = parseFloat($("#discount"+id).val());
        var sh = parseFloat($("#sh"+id).val());
        if(price>0){
            if(sh>0){
                // var newamount = parseFloat(price) + parseFloat(sh);    
            }
            $("#discount_error"+id).text('');
            // console.log(discount+':'+price);
            if(discount >= price){
                $("#discount_error"+id).text('Discount amount should be less than plan price');
            }
            var amount = 0;
            if(sh>0){
                amount = price + sh;
            }
            if($("#plan option:selected").attr('data-type')==1 && discount>0){
                amount = amount - discount;
            }   
            if(isNaN(amount)){
                $("#total"+id).val('');
            } else {
                if(amount<0){
                    amount = 0;
                }
                $("#total"+id).val(amount.toFixed(2));    
            }
        } else {
            // $("#discount"+id).val('');
            // $("#sh"+id).val('');
        }
    }
});

var targetOrderView = document.querySelector("#kt_body");
var blockUIOrderView = new KTBlockUI(targetOrderView, {
    message: '<div class="blockui-message"><span class="spinner-border text-primary"></span> Loading...</div>',
});

$("#data_form").validate({
    rules: {
        product: {
            required: true
        },
        plan:{
            required: true  
        },
        medicine:{
            required: true  
        },
        plan_image:{
            required: {
                depends: function(elem) {
                    if($("#id").val()<=0){
                        return true;
                    } else {
                        return false;
                    }
                }
            },
        },
        plan_title:{
            required: true  
        },
        strength:{
            required: true  
        },
        product_ndc:{
            required: true  
        },
        // product_ndc_2:{
        //     required: true  
        // },
        quantity1:{
            required: true  
        },
        price1:{
            required: true  
        },
        quantity2:{
            required: true  
        },
        price2:{
            required: true  
        }
    },
    messages: {
        product: {
            required: "Product is required",
        },
        plan:{
            required: "Plan is required",
        },
        medicine:{
            required: "Medicine variant is required",
        },
        plan_image:{
            required: "Plan image is required",
        },
        plan_title:{
            required: "Plan title is required"
        },
        strength:{
            required: "Strength is required",
        },
        product_ndc:{
            required: "Product NDC is required",
        },
        product_ndc_2:{
            required: "Product NDC is required",
        },
        quantity1:{
            required: "Quantity is required",
        },
        sh1:{
            required: "Shipping & Handling cost is required",
        },
        sh2:{
            required: "Shipping & Handling cost is required",
        },
        price1:{
            required: "Plan Price is required",
        },
        quantity2:{
            required: "Quantity is required",
        },
        price2:{
            required: "Plan Price is required",
        },
    },
    errorPlacement: function (error, element) {
        error.insertAfter(element);
    },
    submitHandler: function (form, event) { // for demo
        event.preventDefault();
        var formData = new FormData(form);
        formData.append('plan_id',$("#plan option:selected").text());
        if($("#discount_error1").text()!='' || $("#discount_error2").text()!=''){
            return false;
        }
        blockUIOrderView.block();
        $.ajax({
            type: "POST",
            url: "/admin/store-plan-data",
            data: formData,
            dataType: "json",
            cache: false,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status){
                    $("#kt_datatable_example_1").DataTable().ajax.reload();
                    $("#add-edit-modal").modal('hide');
                    Swal.fire({
                        icon: 'success',
                        text: response.message,
                        showConfirmButton: true,
                    }).then(function() {
                        window.location.href = '/admin/plan';
                    });
                    
                    
                } else {
                    Swal.fire({
                        icon: 'error',
                        text: response.message,
                        showConfirmButton: true,
                    });
                }
                blockUIOrderView.release();
            },
            error: function ()  {
                Swal.fire({
                    icon: 'error',
                    text: 'Something went wrong',
                    showConfirmButton: true,
                });
                blockUIOrderView.release();
            }
        });
    }
});