<?php
if (!isset($ws)) {
    $ws = new Mdwebservice();
}

$row = $this->row;

if ($row['isShowAdd'] == '1') {
?>
<div class="bp-detail-row-add bp-add-one-row">
    <?php 
    echo Form::file(array('multiple' => 'multiple', 'style' => 'display: none', 'onchange' => 'bpDetailAttachmentsChange_'.$this->methodId.'_'.$row['id'].'(this);'));
    echo Form::button(
        array(
            'data-action-path' => $row['code'], 
            'class' => 'btn bg-grey-300 btn-icon rounded-round',
            'style' => 'width: 40px; height: 40px; background-color: #e0e0e0', 
            'value' => '<i class="icon-attachment font-size-18"></i>', 
            'title' => $this->lang->line('select_file_btn'), 
            'onclick' => 'bpDetailAttachmentsChooseFiles_'.$this->methodId.'_'.$row['id'].'(this);'
        )
    );
    ?>
</div>

<?php    
$controls = array('<input type="hidden" name="param['.$row['code'].'.mainRowCount][]" value="0"/>');
$position = array();

foreach ($row['data'] as $ind => $val) {

    if ($val['IS_SHOW'] != '1' && !$val['RECORD_TYPE']) {

        $controls[] = Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $val['PARAM_REAL_PATH']);

    } else {

        if ($val['THEME_POSITION_NO']) {

            $position[$val['THEME_POSITION_NO']] = $val['PARAM_REAL_PATH'];

            if ($val['THEME_POSITION_NO'] == 4) {
                $val['IS_REQUIRED'] = 0;
            }
        } 

        $controls[] = '<div data-cell-path="' . $val['PARAM_REAL_PATH'] . '" class="d-none">';

            if ($val['RECORD_TYPE'] == 'row' || ($val['RECORD_TYPE'] == 'rows' && $val['IS_SHOW'] == '1')) {
                $arg = array('parentRecordType' => 'rows');
                $controls[] = $ws->buildTreeParam($this->uniqId, $this->methodId, $val['META_DATA_NAME'], $val['PARAM_REAL_PATH'], $val['RECORD_TYPE'], $val['ID'], null, '', $arg, $val['IS_BUTTON'], $val['COLUMN_COUNT']);
            } else {
                $controls[] = Mdwebservice::renderParamControl($this->methodId, $val, 'param[' . $val['PARAM_REAL_PATH'] . '][0][]', $val['PARAM_REAL_PATH']);
            }

        $controls[] = '</div>';
    }
}
?>
<script type="text/javascript">
function bpDetailAttachmentsChooseFiles_<?php echo $this->methodId.'_'.$row['id']; ?>(elem) {
    var $this = $(elem), $parent = $this.closest('.bp-detail-row-add');
    $parent.find('input[type="file"]').click();
}

function bpDetailAttachmentsChange_<?php echo $this->methodId.'_'.$row['id']; ?>(elem) {
    
    var $this = $(elem), $table = $this.closest('.bpdtl-widget-detail_attachments'), $tbody = $table.find('.tbody');
    var files = elem.files;
    var formData = new FormData();
    
    for (var i = 0; i < files.length; i++) { 
        formData.append('file_' + i, files[i]); 
    }
    
    $.ajax({
        type: 'post',
        url: 'mdprocess/bpTempFileUpload',
        data: formData,
        processData: false,
        contentType: false,
        dataType: 'json', 
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        }, 
        success: function (data) {
            
            if (data.hasOwnProperty('message')) {
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    addclass: pnotifyPosition,
                    sticker: false
                });
                
            } else if (isArray(data) && Object.keys(data).length) {
                
                for (var d in data) {
                    
                    var iconName = '<i class="icon-file-empty text-warning-400 mt2"></i>';
                    
                    if (data[d]['extension'] == 'pdf') {
                        iconName = '<i class="icon-file-pdf text-danger-600 mt2"></i>';
                    } else if (data[d]['extension'] == 'xls' || data[d]['extension'] == 'xlsx') {
                        iconName = '<i class="icon-file-excel text-success-600 mt2"></i>';
                    } else if (data[d]['extension'] == 'doc' || data[d]['extension'] == 'docx') {
                        iconName = '<i class="icon-file-word text-primary-600 mt2"></i>';
                    } else if (data[d]['extension'] == 'zip' || data[d]['extension'] == 'rar') {
                        iconName = '<i class="icon-file-zip text-danger-600 mt2"></i>';
                    } else if (data[d]['extension'] == 'mp3' || data[d]['extension'] == 'wav') {
                        iconName = '<i class="icon-file-music text-danger-600 mt2"></i>';
                    } else if (data[d]['extension'] == 'mp4' || data[d]['extension'] == 'mov') {
                        iconName = '<i class="icon-file-video text-danger-600 mt2"></i>';
                    } else if (data[d]['extension'] == 'png' || data[d]['extension'] == 'gif' || data[d]['extension'] == 'jpg' || data[d]['extension'] == 'jpeg') {
                        iconName = '<i class="icon-file-picture text-info-600 mt2"></i>';
                    } 
                    
                    $tbody.append('<div class="bp-detail-row">'+
                        '<div class="media mt0">'+
                            '<div class="mr-2">'+iconName+'</div>'+
                            '<div class="media-body">'+
                                '<div class="font-weight-bold line-height-normal" title="'+data[d]['name']+'">'+data[d]['name']+'</div>'+
                                '<span class="text-muted">'+bytesReadableFileSize(data[d]['size'], 2)+'</span>'+
                            '</div>'+
                        '</div>'+
                        '<?php ob_start('ob_html_compress'); echo implode('', $controls); ob_end_flush(); ?>'+
                        <?php if ($row['isShowDelete']) { ?>
                        '<a href="javascript:;" class="btn red btn-xs bp-remove-row" title="<?php echo $this->lang->line('delete_btn'); ?>"><i class="icon-cross3"></i></a>'+
                        <?php } ?>
                    '</div>').promise().done(function() {
                        
                        var $lastRow = $tbody.find('.bp-detail-row:last');
                        
                        <?php
                        foreach ($position as $pos => $path) {
                            
                            if ($pos == 1) {
                        ?>
                            $lastRow.find('[data-path="<?php echo $path; ?>"]').val(data[d]['extension']);
                            <?php
                            } elseif ($pos == 2) {
                            ?>
                            $lastRow.find('[data-path="<?php echo $path; ?>"]').val(data[d]['name']);
                            <?php
                            } elseif ($pos == 3) {
                            ?>
                            $lastRow.find('[data-path="<?php echo $path; ?>"]').val(data[d]['size']);
                            <?php 
                            } elseif ($pos == 4) {
                            ?>
                            $lastRow.append('<input type="hidden" name="param[<?php echo $path; ?>_tempFilePath][0][]" value="'+data[d]['path']+data[d]['newname']+'"/>');
                        <?php
                            }
                        }
                        ?>
                        
                        Core.initBPDtlInputType($lastRow);
                    });
                }
                
                bpSetRowIndex($table.parent());
            }
            
            Core.unblockUI();
        },
        error: function (data) {
            alert('ERROR - ' + data.responseText);
            Core.unblockUI();
        }
    });
}
</script>
<?php
}
?>