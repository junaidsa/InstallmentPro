<div class="row">
    <!-- Camera Preview -->
    <div class="col-md-6 col-sm-12 text-center">
        <div class="form-group">
            <div id="my_camera" class="camera-preview mb-2"></div>
            <input type="button" value="Capture" class="btn btn-success btn-md mt-2" onClick="take_snapshot()">
        </div>
    </div>

    <!-- Captured Image -->
    <div class="col-md-6 col-sm-12 text-center">
        <div class="form-group">
            <div id="results" class="camera-preview"></div>
        </div>
    </div>
</div>

<input type="hidden" name="camera_image_path" class="image-tag">

<!-- WebcamJS Library -->
<script src="{{ asset('plugins/webcam/webcam.min.js') }}"></script>

<script type="text/javascript">
    Webcam.set({
        width: 250,
        height: 190,
        image_format: 'png',
        jpeg_quality: 90
    });

    Webcam.attach('#my_camera');

    function take_snapshot() {
    Webcam.snap(function(data_uri) {
        $(".image-tag").val(data_uri); // This sets the base64 image to the hidden input
        console.log("Captured Image Data:", data_uri); // Log the captured image data
        document.getElementById('results').innerHTML = '<img src="' + data_uri + '" class="img-fluid"/>';
    });
}
</script>
