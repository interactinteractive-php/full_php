<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="clearfix w-100"></div>
<?php
if(!empty($this->attachFiles)) {
    $fileIndex = 0;

    foreach ($this->attachFiles as $attach) { 
        if(file_exists($attach['path'])) { ?>
            <div class="mb10">
                <input type="hidden" name="activity_file_edit[]" class="" value="<?php echo Arr::encode($attach); ?>">                                                                
                <input type="hidden" name="activity_file_action[]" class="">                                                                
       <?php        
                echo $attach['attachname'];
                echo html_tag('a', array('href' => 'mdobject/downloadFile?file=' . $attach['path'], 'title' => 'Файл татах', 'class' => 'dg-custom-tooltip ml10', 'style' => 'margin-top:-23px; margin-right:4px;'), '<i class="fa fa-file text-success"></i>');
                echo html_tag('a', array('href' => 'javascript:;', 'onclick' => 'amactivityObj.editFileRemove(this)', 'title' => 'Файл устгах', 'class' => 'dg-custom-tooltip', 'style' => 'margin-top:-23px; margin-right:4px;'), '<i class="fa fa-lg fa-remove text-danger"></i>');
            echo '</div><div class="clearfix w-100"></div>';    
        }
        $fileIndex++;
    }
}
?>
<div>
    <input type="file" name="activity_file[]" class="float-left" onchange="hasFileExtension(this);">
    <a href="javascript:;" class="btn btn-xs btn-success" title="Нэмэх" onclick="amactivityObj.addFileActivity(this);">
        <i class="icon-plus3 font-size-12"></i>
    </a>
</div>      