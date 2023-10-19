const eventRefreshPage = new Event('refreshPage');

const selfieWebcamElement = document.getElementById('webcam');
const selfieCanvasElement = document.getElementById('canvasSelfie');
const selfieWebcam = new Webcam(selfieWebcamElement, "user", selfieCanvasElement);

$('#btn-capture-selfie').click(function () {
    $('#capture-modal').modal('show');
    $(".webcamLoading").removeClass('d-none');

    selfieWebcam.start()
        .then(response => {
            $(".webcamLoading").addClass('d-none');
            console.log('webcam started...');
        })
        .catch(error => {
            console.log(error);
            $('#capture-modal').modal('hide');
            showToast('error', 'Failed to start camera.');
            selfieWebcam.stop();
        });
})

$("#cancel-capture").click(function () {
    selfieWebcam.stop();
})

$("#capture-btn").click(function () {
    $(this).attr('disabled', true);

    let picture = selfieWebcam.snap();

    $("#selfie-preview")[0].src = picture;

    const htmlForm = $('#btn-capture-selfie').attr('data-form');
    const form = document.getElementById(htmlForm);

    // Split the base64 string in data and contentType
    let block = picture.split(";");
    // Get the content type
    let contentType = block[0].split(":")[1]; // In this case "image/gif"
    // get the real base64 content of the file
    let realData = block[1].split(",")[1]; // In this case "iVBORw0KGg...."

    // Convert to blob
    let blob = b64toBlob(realData, contentType);

    let formData = new FormData(form);
    formData.append("selfie", blob);
    formData.append("_token", $('meta[name="csrf-token"]').attr('content'));

    // Submit Form and upload file
    $.ajax({
        url: form.getAttribute('action'),
        data: formData,
        type: "POST",
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('.loaderElement').show();
        },
        error: function (err) {
            showToast('error', err);
            console.error(err);
        },
        success: function (response) {
            $("#capture-btn").attr('disabled', false);
            if (response.status === 'success') {
                showToast('success', response.message);
            } else {
                showToast('error', response.message);
            }
            document.dispatchEvent(eventRefreshPage);
        },
        complete: function () {
            $('#capture-modal').modal('hide');
            $('.loaderElement').hide();
            selfieWebcam.stop();
        }
    });
});

function b64toBlob(b64Data, contentType, sliceSize) {
    contentType = contentType || '';
    sliceSize = sliceSize || 512;

    const byteCharacters = atob(b64Data);
    const byteArrays = [];

    for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
        const slice = byteCharacters.slice(offset, offset + sliceSize);
        const byteNumbers = new Array(slice.length);

        for (let i = 0; i < slice.length; i++) {
            byteNumbers[i] = slice.charCodeAt(i);
        }

        const byteArray = new Uint8Array(byteNumbers);
        byteArrays.push(byteArray);
    }

    return new Blob(byteArrays, {type: contentType});
}


// Govt ID
const govtWebcamElement = document.getElementById('webcam2');
const govtCanvasElement = document.getElementById('canvasGovt');
let govtWebcam;

// if( /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ) {

    govtWebcam = new Webcam(govtWebcamElement, "environment", govtCanvasElement);

// } else {
//     govtWebcam = new Webcam(govtWebcamElement, "user", govtCanvasElement);
// }

$('#btn-capture-govt-id').click(function () {
    $('#gov-capture-modal').modal('show');
    $('#govtIdModal').modal('hide');
    $(".webcamLoading").removeClass('d-none');

    startGovtWebCam();
})

function startGovtWebCam() {
    govtWebcam.start()
        .then(response => {
            $(".webcamLoading").addClass('d-none');
            console.log('webcam started...');
        })
        .catch(error => {
            console.log(error);
            $('#gov-capture-modal').modal('hide');
            $('#govtIdModal').modal('show');
            showToast('error', 'Failed to start camera.');
            govtWebcam.stop();
        });
}

$("#govt-cancel-capture").click(function () {
    govtWebcam.stop();
})

$("#gov-capture-btn").click(function () {
    $(this).attr('disabled', true);

    let picture = govtWebcam.snap();

    $("#gov-id-preview")[0].src = picture;

    const htmlForm = $('#btn-capture-selfie').attr('data-form');
    const form = document.getElementById(htmlForm);

    // Split the base64 string in data and contentType
    const block = picture.split(";");
    // Get the content type
    const contentType = block[0].split(":")[1]; // In this case "image/gif"
    // get the real base64 content of the file
    const realData = block[1].split(",")[1]; // In this case "iVBORw0KGg...."
    // Convert to blob
    const blob = b64toBlob(realData, contentType);

    const formData = new FormData(form);
    formData.append("govt_id", blob);
    formData.append("_token", $('meta[name="csrf-token"]').attr('content'));

    // Submit Form and upload file
    $.ajax({
        url: form.getAttribute('action'),
        data: formData, // the formData function is available in almost all new browsers.
        type: "POST",
        contentType: false,
        processData: false,
        cache: false,
        beforeSend: function () {
            $('.loaderElement').show();
        },
        error: function (err) {
            showToast('error', err);
            console.error(err);
        },
        success: function (response) {
            $("#gov-capture-btn").attr('disabled', false);
            if (response.status === 'success') {
                showToast('success', response.message);
            } else {
                showToast('error', response.message);
            }
            document.dispatchEvent(eventRefreshPage);
        },
        complete: function () {
            $('#gov-capture-modal').modal('hide');
            govtWebcam.stop();
            $('.loaderElement').hide();
        }
    });
})

$("#btnFlipCamera").click(function () {
    govtWebcam.flip();
    startGovtWebCam();
})
