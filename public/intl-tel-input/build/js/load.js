$('#phone').mask('000 000-0000');
$('#new-phone').mask('000 000-0000');
$('#edit_phone').mask('000 000-0000');


var reset = function() {
    input.classList.remove("error");
    errorMsg.innerHTML = "";
    errorMsg.classList.add("hide");
    // validMsg.classList.add("hide");
};

// on blur: validate
input.addEventListener('keyup', function() {
    reset();
    if (input.value.trim()) {
        if (iti.isValidNumber()) {
            $('.submitBTN').attr('type', 'submit')
            $('.submitBTN').removeAttr('disabled')
                // validMsg.classList.remove("hide");
        } else {
            $('.submitBTN').attr('type', 'button')
            $('.submitBTN').attr('disabled', 'true')

            input.classList.add("error");
            var errorCode = iti.getValidationError();
            errorMsg.innerHTML = errorMap[errorCode];
            // if (errorCode == -99) {
            //     errorMsg.innerHTML = 'Please enter valid mobile number'
            // }

            errorMsg.classList.remove("hide");
        }
    }
});
// on keyup / change flag: reset
// input.addEventListener('change', reset);