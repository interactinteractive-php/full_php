<?php 
$row = $this->getMetaDataValueOneFile; 
echo Form::create(array('class' => 'form-horizontal', 'id' => 'update-attach-file-form', 'method' => 'post', 'enctype' => 'multipart/form-data')); 
echo Form::hidden(array('name' => 'attachId', 'id' => 'attachId', 'value' => $this->attachId)); 
?>
<div class="col-md-12">
    <table class="table table-hover table-light">
        <tbody>
            <tr>
                <td style="width: 210px">
                    <?php 
                    $file = 'assets/core/global/img/filetype/64/'.$row['FILE_EXTENSION'].'.png';
                    if ($row['FILE_EXTENSION'] == 'png' or $row['FILE_EXTENSION'] == 'gif' or $row['FILE_EXTENSION'] == 'jpeg' or $row['FILE_EXTENSION'] == 'pjpeg' or $row['FILE_EXTENSION'] == 'jpg' or $row['FILE_EXTENSION'] == 'x-png' or $row['FILE_EXTENSION'] == 'bmp') {
                        if (file_exists($row['ATTACH'])) {
                            $file = $row['ATTACH'];
                        }
                    }
                    echo '<img src="'.$file.'" style="max-width:100%;">';
                    ?>
                </td>
                <td style="width: 550px">
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Файлын хэмжээ', 'for' => 'Файлын хэмжээ', 'class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8"><?php echo Number::format_bytes($row['FILE_SIZE']); ?></div>
                    </div>    
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Файл', 'for' => 'Файл', 'class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php 
                            $attachExplode = explode('/', $row['ATTACH']);
                            echo end($attachExplode); 
                            ?>
                        </div>
                    </div>    
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Үүсгэсэн огноо', 'for' => 'Үүсгэсэн огноо', 'class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8"><?php echo $row['CREATED_DATE']; ?></div>
                    </div>    
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Файл сонгох', 'for' => 'Файл сонгох', 'class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo Form::file(array('name' => 'bp_file', 'id' => 'bp_file', 'class' => 'fileinput-button')); ?>
                        </div>
                        
                    </div>    
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => 'Файлын тайлбар', 'for' => 'Файлын тайлбар', 'class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo Form::textArea(array('name' => 'bp_file_name', 'id' => 'bp_file_name', 'class' => 'form-control', 'value' => $row['ATTACH_NAME'])); ?>
                        </div>
                        
                    </div>
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => $this->lang->line('sendmail'), 'for' => 'bp_file_sendmail', 'class' => 'col-md-4 text-right')); ?>
                        <div class="col-md-8">
                            <?php echo Form::checkbox(array('name' => 'bp_file_sendmail', 'id' => 'bp_file_sendmail', 'value' => '1', 'saved_val' => $row['IS_EMAIL'], 'class' => '')); ?>
                        </div>
                        
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function(){
    Core.initUniform();
    
    <?php
    if (isset($this->fileSize) && $this->fileSize) {
    ?>
    $('#update-attach-file-form').on('change', 'input#bp_file', function() {
        var $this = $(this);
        
        if (Number($this[0].files[0].size) > <?php echo $this->fileSize; ?>) {
            new PNotify({
                title: 'Info',
                text: plang.getVar('PF_FILE_SIZE_EXCEEDED_MSG', {
                    'fileName': $this[0].files[0].name, 
                    'maxFileSize': parseInt(<?php echo $this->fileSize; ?> / 1000000) + 'mb'
                }),
                type: 'info',
                addclass: 'pnotify-center',
                sticker: false, 
                delay: 10000000000
            });    
            $this.val('');
            return false;
        }
    });
    <?php
    }
    ?>
});
</script>
