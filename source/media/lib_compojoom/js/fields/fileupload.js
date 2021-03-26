function initFileUpload(options) {


    jQuery(document).ready(function () {
        var $ = jQuery;

        document.getElementById("file-upload-fake").addEventListener("click", function () {
            document.getElementById("file-upload-real").click();  // trigger the click of actual file upload button
        });
        // Initialize the jQuery File Upload widget:
        $('#fileupload').fileupload({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            formData: {},
            autoUpload: true,
            maxFileSize: options.maxFileSize,
            maxNumberOfFiles: options.maxNumberOfFiles,
            url: options.url,
            disableImageResize: false,
            imageMaxWidth: options.imageMaxWidth,
            imageMaxHeight: options.imageMaxHeight,
            finished: function (e, data) {
                if ($(this).fileupload('option').getNumberOfFiles() >= options.maxNumberOfFiles) {
                    $('.compojoom-max-number-files').removeClass('hide d-none');
                } else {
                    $('.compojoom-max-number-files').addClass('hide d-none');
                }
            },
            destroyed: function (e, data) {
                if ($(this).fileupload('option').getNumberOfFiles() >= options.maxNumberOfFiles) {
                    $('.compojoom-max-number-files').removeClass('hide d-none');
                } else {
                    $('.compojoom-max-number-files').addClass('hide d-none');
                }
            }
        }).on('destroyed', function (e, data) {
            if ($(this).fileupload('option').getNumberOfFiles() >= options.maxNumberOfFiles) {
                $('.compojoom-max-number-files').removeClass('hide d-none');
            } else {
                $('.compojoom-max-number-files').addClass('hide d-none');
            }
        }).on('fileuploadadd', function (e, data) {
            $('.fileupload-progress.hide').removeClass('hide d-none');
        });

        // Enable iframe cross-domain access via redirect option:
        $('#fileupload').fileupload(
            'option',
            'redirect',
            window.location.href.replace(
                /\/[^\/]*$/,
                '/cors/result.html?%s'
            )
        );

        // Load existing files:
        $('#fileupload').addClass('fileupload-processing');
        $.ajax({
            // Uncomment the following to send cross-domain cookies:
            //xhrFields: {withCredentials: true},
            url: options.urlWithExistingFiles,
            dataType: 'json',
            context: $('#fileupload')[0]
        }).always(function () {
            $(this).removeClass('fileupload-processing');
        }).done(function (result) {
            $(this).fileupload('option', 'done')
                .call(this, $.Event('done'), {result: result});
        });

    });
}
