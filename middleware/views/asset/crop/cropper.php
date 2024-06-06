<div class="row crop<?php echo $this->uniqId ?>">
    <div class="col">
        <img src="<?php echo $this->imagePath; ?>" id="image_crop<?php echo $this->uniqId ?>" class="img"/>
    </div>
</div>

<input type="hidden" id="crop_image_path_<?php echo $this->uniqId ?>" value="<?php echo $this->imagePath; ?>"/>
<input type="hidden" id="main_image_path_<?php echo $this->uniqId ?>" value="<?php echo $this->imagePath; ?>"/>
<input type="hidden" id="crop_x_<?php echo $this->uniqId ?>" name="x"/>
<input type="hidden" id="crop_y_<?php echo $this->uniqId ?>" name="y"/>
<input type="hidden" id="crop_w_<?php echo $this->uniqId ?>" name="w"/>
<input type="hidden" id="crop_h_<?php echo $this->uniqId ?>" name="h"/>

<script type="text/javascript">
    var cropWidth<?php echo $this->uniqId ?> = '<?php echo issetParamZero($this->width); ?>',
        cropHeight<?php echo $this->uniqId ?> = '<?php echo issetParamZero($this->height); ?>';

    $(function() {

        if ($().Jcrop) {
            <?php if (issetParam($this->width) != '' && issetParam($this->height) != '') { ?>
                $('#image_crop<?php echo $this->uniqId ?>').Jcrop({setSelect: [0, 0, cropWidth<?php echo $this->uniqId ?>, cropHeight<?php echo $this->uniqId ?>], onSelect: updateCoords, trueSize: [<?php echo $this->imageWidth; ?>, <?php echo $this->imageHeigth; ?>]});
            <?php } else { ?>
                $('#image_crop<?php echo $this->uniqId ?>').Jcrop({onSelect: updateCoords, trueSize: [<?php echo $this->imageWidth; ?>, <?php echo $this->imageHeigth; ?>]});
            <?php } ?>
        }

        if ($().contextMenu) {     
            $.contextMenu({
                selector: 'div.jcrop-holder > div > div > div.jcrop-tracker',
                build: function($trigger, e) {
                    return {
                        callback: function(key, options) {
                            croppedImgProcessing();
                        },
                        items: {
                            'imagetotext': {name: plang.get('crop'), icon: 'file-text-o'}
                        }
                    };
                }
            });
        }

    });

    function updateCoords(c) {
        $('#crop_x_<?php echo $this->uniqId ?>').val(c.x);
        $('#crop_y_<?php echo $this->uniqId ?>').val(c.y);
        $('#crop_w_<?php echo $this->uniqId ?>').val(c.w);
        $('#crop_h_<?php echo $this->uniqId ?>').val(c.h);
    }

    function croppedImgProcessing() {
        $.ajax({
            type: 'post',
            url: 'mddoc/cropImg',
            data: {
                x: $('#crop_x_<?php echo $this->uniqId ?>').val(),
                y: $('#crop_y_<?php echo $this->uniqId ?>').val(),
                w: $('#crop_w_<?php echo $this->uniqId ?>').val(),
                h: $('#crop_h_<?php echo $this->uniqId ?>').val(),
                image_path: $('#crop_image_path_<?php echo $this->uniqId ?>').val(),
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...', 
                    boxed: true 
                });
            },
            success: function(data) {
                if (typeof data.status !== 'undefined' && data.status === 'success') {
                    $('.crop<?php echo $this->uniqId ?>').find('.jcrop-holder img').attr('src', data.image);
                    $('#crop_image_path_<?php echo $this->uniqId ?>').val(data.image);
                    
                } else {
                    console.log(data);
                }
                Core.unblockUI();
            }
        });
    }
    
</script>