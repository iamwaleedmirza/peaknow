function IsNumeric(e) {
    var key = e.keyCode;
    var verified = (e.which == 8 || e.which == undefined || e.which == 0) ? null : String.fromCharCode(e.which).match(/[^0-9.]/);
            if (verified) {e.preventDefault();}
}

function onlyNumeric(e) {
    var key = e.keyCode;
    if ((event.which < 48 || event.which > 57)) {
        e.preventDefault();
    }
}

function onlyAlphaNumeric(e) {
    if (e.shiftKey || e.ctrlKey || e.altKey) {
       e.preventDefault();
    } 
    else {
        var key = e.keyCode;
        if (!((key == 8) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90) || (key >= 48 && key <= 57) || (key >= 96 && key <= 105))) {
           e.preventDefault();
        }
    }    
}

$(document).ready(()=>{
  $('#image').change(function(){
    const file = this.files[0];
    if (file){
      let reader = new FileReader();
      reader.onload = function(event){
        $('#imgPreview').attr('src', event.target.result);
      }
      reader.readAsDataURL(file);
      $("#imgPreview").show();
    } else {
        $("#imgPreview").hide();
    }
  });
});