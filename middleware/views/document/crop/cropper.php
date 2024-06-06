<div class="row">
    <div class="col-md-5">
        <?php
        if ($this->imagePath) {
        ?>
        <img src="<?php echo $this->imagePath; ?>" id="process-image-ocr" class="img-fluid"/>
        <?php
        } else {
            echo html_tag('div', array('class' => 'alert alert-danger'), 'Файл тодорхойгүй байна.');
        }
        ?>
    </div>
    <div class="col-md-7">
        <?php
        if (count($this->processList) > 0) {
            if (count($this->processList) > 1) {
                echo Form::select(
                    array(
                        'id' => 'ocrProcessId', 
                        'class' => 'form-control form-control-sm', 
                        'data' => $this->processList, 
                        'op_value' => 'META_DATA_ID', 
                        'op_text' => 'META_DATA_NAME', 
                        'text' => 'notext'
                    )
                );
            } else {
                echo Form::hidden(
                    array(
                        'id' => 'ocrProcessId', 
                        'value' => $this->processList[0]['META_DATA_ID']
                    )
                );
            }
        }
        ?>
        <div id="ocr-bp-load"></div>
    </div>
</div>

<input type="hidden" id="crop_image_path" value="<?php echo $this->imagePath; ?>"/>
<input type="hidden" id="crop_x" name="x"/>
<input type="hidden" id="crop_y" name="y"/>
<input type="hidden" id="crop_w" name="w"/>
<input type="hidden" id="crop_h" name="h"/>

<script type="text/javascript">
var contextMenuItems = {
    'imagetotext': {name: 'Хөрвүүлэх', icon: 'file-text-o'}
};

$(function(){
    
    if ($('#ocrProcessId').length > 0) {
        var ocrProcessId = $('#ocrProcessId').val();
        ocrBusinessProcessParams(ocrProcessId);
        ocrBusinessProcess(ocrProcessId);
    }
    
    $('#ocrProcessId').on('change', function(){
        var ocrProcessId = $(this).val();
        ocrBusinessProcessParams(ocrProcessId);
        ocrBusinessProcess(ocrProcessId);
    });
    
    if ($().Jcrop) {
        $('#process-image-ocr').Jcrop({onSelect: updateCoords, trueSize: [<?php echo $this->imageWidth; ?>, <?php echo $this->imageHeigth; ?>]});
    }
    
    if ($().contextMenu) {     
        $.contextMenu({
            selector: 'div.jcrop-holder > div > div > div.jcrop-tracker',
            build: function($trigger, e) {
                return {
                    callback: function(key, options) {
                        croppedOcrProcessing(key);
                    },
                    items: contextMenuItems
                };
            }
        });
    }
});    

function updateCoords(c) {
    $('#crop_x').val(c.x);
    $('#crop_y').val(c.y);
    $('#crop_w').val(c.w);
    $('#crop_h').val(c.h);
}
function croppedOcrProcessing(key) {
    $.ajax({
        type: 'post',
        url: 'mddoc/ocrApi',
        data: {
            x: $('#crop_x').val(),
            y: $('#crop_y').val(),
            w: $('#crop_w').val(),
            h: $('#crop_h').val(),
            image_path: $('#crop_image_path').val(),
            processId: $('#ocrProcessId').val(), 
            key: key
        },
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({
                message: 'Processing...', 
                boxed: true 
            });
        },
        success: function(data){
            $('#ocr-bp-load').find("[data-path='"+key+"']").val(data.text);
            Core.unblockUI();
        }
    });
}
function ocrBusinessProcess(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mdwebservice/callMethodByMeta',
        data: {metaDataId: metaDataId, isDialog: false, isSystemMeta: 'false'},
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({
                message: 'Loading...', 
                boxed: true
            });
        },
        success: function(data){
            $('#ocr-bp-load').empty().html(data.Html);
        },
        error: function(){
            alert('Error');
        }
    }).done(function(){
        Core.initBPAjax($('#ocr-bp-load'));
        Core.unblockUI();
    });
}
function ocrBusinessProcessParams(metaDataId) {
    $.ajax({
        type: 'post',
        url: 'mddoc/ocrBusinessProcessParams',
        data: {processMetaDataId: metaDataId},
        dataType: 'json',
        success: function(data){
            var newItem = {};
            contextMenuItems = {};
            
            $.each(data, function(k, v){
                if (v.RECORD_TYPE != 'rows') {
                    
                    newItem[v.META_DATA_CODE] = {
                        name: v.META_DATA_NAME,
                        icon: 'copy'
                    };
                    
                    if (typeof v.CHILD_PARAMS !== 'undefined') {
                        
                        var subItemData = v.CHILD_PARAMS;
                        var newSubItem = {};
                        
                        $.each(subItemData, function(sk, sv){
                            newSubItem[sv.META_DATA_CODE] = {
                                name: sv.META_DATA_NAME,
                                icon: 'copy'
                            };
                        });    
                        
                        newItem[v.META_DATA_CODE] = {
                            name: v.META_DATA_NAME,
                            icon: 'copy', 
                            items: newSubItem
                        };
                    }
                }
            });

            $.extend(contextMenuItems, newItem);
        },
        error: function(){
            alert('Error');
        }
    });
}
</script>