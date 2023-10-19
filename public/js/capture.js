const eventRefreshPage = new Event('refreshPage');

$(function() {
    function b64toBlob(b64Data, contentType, sliceSize) {
        contentType = contentType || '';
        sliceSize = sliceSize || 512;

        var byteCharacters = atob(b64Data);
        var byteArrays = [];

        for (var offset = 0; offset < byteCharacters.length; offset += sliceSize) {
            var slice = byteCharacters.slice(offset, offset + sliceSize);

            var byteNumbers = new Array(slice.length);
            for (var i = 0; i < slice.length; i++) {
                byteNumbers[i] = slice.charCodeAt(i);
            }

            var byteArray = new Uint8Array(byteNumbers);

            byteArrays.push(byteArray);
        }

        var blob = new Blob(byteArrays, { type: contentType });
        return blob;
    }
    if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        Webcam.set({
            width: 720,
            height: 1280,
            flip_horiz: true,
            image_format: 'jpeg',
            jpeg_quality: 100
        });
        $(window).on("orientationchange", function() {
            if (window.orientation == 0) {
                Webcam.reset();
                Webcam.set({
                    width: 720,
                    height: 1280,
                    flip_horiz: true,
                    image_format: 'jpeg',
                    jpeg_quality: 100
                });
                Webcam.attach('#webcam');
                console.log('portrait orientation!!!');
            } else {
                Webcam.reset();
                Webcam.set({
                    width: 1280,
                    height: 720,
                    dest_width: 1280,
                    dest_height: 720,
                    flip_horiz: true,
                    image_format: 'jpeg',
                    jpeg_quality: 100
                });
                Webcam.attach('#webcam');
                console.log('landscape orientation!!!');
            }
        });
    } else {
        Webcam.set({
            width: 1280,
            height: 720,
            // crop_width: 720,
            // crop_height: 720,
            image_format: 'jpeg',
            jpeg_quality: 100
        });
    }

    Webcam.set('constraints', {
        width: 1920,
        height: 1080
    });
    $("#btn-capture-selfie").click(function() {
        Webcam.attach('#webcam');
        let error = false;
        Webcam.on('error', function(err) {
            $('#capture-modal').modal('hide');
            showToast('error', err);
            error = true;
            return err
        });
        setTimeout(() => {
            if (error === false) {
                $('#capture-modal').modal('show');
                $('#gov-capture-modal').modal('hide');
                $('#selfieModal').modal('hide');
            }
        }, 100);

    });
    $("#btn-capture-govt-id").click(function() {
        Webcam.attach('#webcam2');
        let error = false;
        Webcam.on('error', function(err) {
            $('#gov-capture-modal').modal('hide');
            showToast('error', err);
            error = true;
            return false
        });
        setTimeout(() => {
            if (error === false) {
                $('#gov-capture-modal').modal('show');
                $('#govtIdModal').modal('hide');
            }
        }, 100);


    });
    $("#cancel-capture").click(function() {
        Webcam.reset();
    });
    $("#govt-cancel-capture").click(function() {
        Webcam.reset();
    });

    // Webcam.on('live', function() {

        $("#capture-btn").click(function() {
            $(this).attr('disabled', true);
            Webcam.snap(function(data_uri) {
                $("#selfie-preview")[0].src = data_uri;
                var htmlForm = $('#btn-capture-selfie').attr('data-form');
                var form = document.getElementById(htmlForm);

                var ImageURL = data_uri;
                // Split the base64 string in data and contentType
                var block = ImageURL.split(";");
                // Get the content type
                var contentType = block[0].split(":")[1]; // In this case "image/gif"
                // get the real base64 content of the file
                var realData = block[1].split(",")[1]; // In this case "iVBORw0KGg...."

                // Convert to blob
                var blob = b64toBlob(realData, contentType);

                var fd = new FormData(form);
                fd.append("selfie", blob);
                fd.append("_token", $('meta[name="csrf-token"]').attr('content'));
                // Submit Form and upload file
                $.ajax({
                    url: form.getAttribute('action'),
                    data: fd, // the formData function is available in almost all new browsers.
                    type: "POST",
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function(jqXHR, options) {
                        // setting a timeout
                        $('.loaderElement').show();
                    },
                    error: function(err) {
                        showToast('error', err);
                        console.error(err);
                    },
                    success: function(response) {
                        $("#capture-btn").attr('disabled', false);
                        if (response.status === 'success') {
                            showToast('success', response.message);
                        } else {
                            showToast('error', response.message);
                        }
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 500);
                        document.dispatchEvent(eventRefreshPage);
                    },
                    complete: function() {
                        $('#capture-modal').modal('hide');
                        Webcam.reset();

                        $('.loaderElement').hide();
                        // console.log("Request finished.");
                    }
                });

            });
        });
        $("#gov-capture-btn").click(function() {
            $(this).attr('disabled', true);
            Webcam.snap(function(data_uri) {
                $("#gov-id-preview")[0].src = data_uri;
                var htmlForm = $('#btn-capture-selfie').attr('data-form');
                var form = document.getElementById(htmlForm);

                var ImageURL = data_uri;
                // Split the base64 string in data and contentType
                var block = ImageURL.split(";");
                // Get the content type
                var contentType = block[0].split(":")[1]; // In this case "image/gif"
                // get the real base64 content of the file
                var realData = block[1].split(",")[1]; // In this case "iVBORw0KGg...."

                // Convert to blob
                var blob = b64toBlob(realData, contentType);

                var fd = new FormData(form);
                fd.append("govt_id", blob);
                fd.append("_token", $('meta[name="csrf-token"]').attr('content'));
                // Submit Form and upload file
                $.ajax({
                    url: form.getAttribute('action'),
                    data: fd, // the formData function is available in almost all new browsers.
                    type: "POST",
                    contentType: false,
                    processData: false,
                    cache: false,
                    beforeSend: function(jqXHR, options) {
                        // setting a timeout
                        $('.loaderElement').show();
                    },
                    error: function(err) {
                        showToast('error', err);
                        console.error(err);
                    },
                    success: function(response) {
                        $("#gov-capture-btn").attr('disabled', false);
                        if (response.status === 'success') {
                            showToast('success', response.message);
                        } else {
                            showToast('error', response.message);
                        }
                        // setTimeout(() => {
                        //     location.reload();
                        // }, 500);
                        document.dispatchEvent(eventRefreshPage);
                    },
                    complete: function() {
                        $('#gov-capture-modal').modal('hide');
                        Webcam.reset();

                        $('.loaderElement').hide();
                        // console.log("Request finished.");
                    }
                });

            });
        });

    // });


    $("#btnUpload").click(function() {
        $.ajax({
            type: "POST",
            url: "CS.aspx/SaveCapturedImage",
            data: "{data: '" + $("#imgCapture")[0].src + "'}",
            contentType: "application/json; charset=utf-8",
            dataType: "json",
            success: function(r) {}
        });
    });
});
