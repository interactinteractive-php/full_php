<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>
<?php
echo Form::create(array('class' => 'form-horizontal', 'id' => 'bpWebcam-form', 'method' => 'post', 'enctype' => 'multipart/form-data'));
?>
    <div class="row">
        <div class="col-6">
            <video id="video" width="620" height="480" autoplay></video>
        </div>
        <div class="col-6">
            <canvas style="border: 1px #666 solid; margin-bottom: 20px; background-color: #e9e9e9" id="bp-webcam-image" width="620" height="480"></canvas>
            <button type="button" class="btn blue" onclick="bpPhotoCapture();"><i class="fa fa-camera"></i> Зураг авах</button>
        </div>
        <?php
        echo Form::hidden(array('name' => 'metaDataId', 'value' => $this->metaDataId));
        echo Form::hidden(array('name' => 'metaValueId', 'value' => $this->metaValueId));
        echo Form::hidden(array('name' => 'base64Photo', 'value' => ''));    
        ?>
    </div>    
<?php echo Form::close(); ?>
<script type="text/javascript">
    var canvas = document.getElementById('bp-webcam-image');
    var context = canvas.getContext('2d');
    var video = document.getElementById('video');
    var mediaConfig =  { video: true };
    var errBack = function(e) {
        console.log('An error has occurred!', e)
    };

    // Put video listeners into place
    if(navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
        navigator.mediaDevices.getUserMedia(mediaConfig).then(function(stream) {
            //video.src = window.URL.createObjectURL(stream);
            video.srcObject = stream;
            video.play();
        });
    }

    /* Legacy code below! */
    else if(navigator.getUserMedia) { // Standard
        navigator.getUserMedia(mediaConfig, function(stream) {
            video.src = stream;
            video.play();
        }, errBack);
    } else if(navigator.webkitGetUserMedia) { // WebKit-prefixed
        navigator.webkitGetUserMedia(mediaConfig, function(stream){
            video.src = window.webkitURL.createObjectURL(stream);
            video.play();
        }, errBack);
    } else if(navigator.mozGetUserMedia) { // Mozilla-prefixed
        navigator.mozGetUserMedia(mediaConfig, function(stream){
            video.src = window.URL.createObjectURL(stream);
            video.play();
        }, errBack);
    }

    function bpPhotoCapture() {
        context.drawImage(video, 0, 0, 640, 480);
        var pngUrl = canvas.toDataURL();
        pngUrl = pngUrl.split(';base64,');
        $("input[name='base64Photo']").val(pngUrl[1]);
    }; 
</script>