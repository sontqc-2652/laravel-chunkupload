<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Chunk File Upload in Laravel</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/css/toastr.css" rel="stylesheet"/>
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card w-50 m-auto">
                    <div class="card-header bg-info text-white">
                        <h4>Chunk File Upload in Laravel</h4>  
                    </div>
                    <div class="card-body">
                        <div class="form-group" id="file-input">
                            <input type="file" id="pickfiles" class="form-control">
                            <div id="filelist"></div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/2.0.1/js/toastr.js"></script>
    <script src="{{ asset('/plupload/js/plupload.full.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            var path = "{{ asset('/plupload/js/') }}";
            var container = $('#file-input');

            var uploader = new plupload.Uploader({
            browse_button: 'pickfiles',
            container: this.container,
            url: '{{ route("chunk.store") }}',
            chunk_size: '2000kb', // 2 MB
            max_retries: 0,
            filters: {
                max_file_size: '200mb'
            },
            multipart_params : {
                "_token" : "{{ csrf_token() }}"
            },
            init: {
                PostInit: function () {
                    $('#filelist').html('');
                },
                FilesAdded: function (up, files) {
                    plupload.each(files, function (file) {
                        // console.log(file);
                        // console.log('FilesAdded');
                        $('#filelist').html('<div id="' + file.id + '">' + file.name + ' (' + plupload.formatSize(file.size) + ') <b></b></div>');
                    });
                    uploader.start();
                },
                UploadProgress: function (up, file) {
                    // console.log('UploadProgress');
                    // console.log(file);
                    $(`#${file.id}`).ready(function() {
                        $('b').eq(0).html('<span>' + file.percent + "%</span>");
                    })
                },
                FileUploaded: function(up, file, result){

                    // console.log('FileUploaded');
                    // console.log(file);
                    // console.log(JSON.parse(result.response));
                    // responseResult = JSON.parse(result.response);

                    // if (responseResult.ok == 0) {
                    //     toastr.error(responseResult.info, 'Error Alert', {timeOut: 5000});
                    // }
                    // if (result.status != 200) {
                    //     toastr.error('Your File Uploaded Not Successfully!!', 'Error Alert', {timeOut: 5000});
                    // }
                    // if (responseResult.ok == 1 && result.status == 200) {
                    //     toastr.success('Your File Uploaded Successfully!!', 'Success Alert', {timeOut: 5000});
                    // }
                },
                UploadComplete: function(up, file){
                    toastr.success('Your File Uploaded Successfully!!', 'Success Alert', {timeOut: 5000});
                },
                Error: function (up, err) {
                    toastr.error('Your File Uploaded Not Successfully!!', 'Error Alert', {timeOut: 5000});
                    // console.log(err);
                }
            }
        });
        uploader.init();
    });
</script>
</body>
</html>