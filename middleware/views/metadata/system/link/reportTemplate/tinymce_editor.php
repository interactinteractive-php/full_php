<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'temp-content-form', 'method' => 'post')); ?>
<div class="row columns" id="childs">
    <div class="col-md-2" id="childList"> 
        <input type="hidden" name="dataModelId" id="dataModelId" value="<?php echo $this->metaDataId; ?>">
        <div class="clearfix w-100"></div>
        <ul class="tree mt0 mr0">
            <?php echo $this->paths; ?>
        </ul>
    </div>
    <div class="col-md-8 pr0">  
        
        <?php
        if (isset($this->pageOption)) {
        ?>
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#rt_link_main_tab" data-toggle="tab" class="nav-link pt0 active"><?php echo $this->lang->line('META_00008'); ?></a>
                </li>
                <li class="nav-item">
                    <a href="#st_link_headfoot_tab" data-toggle="tab" class="nav-link pt0">Header & Footer</a>
                </li>
            </ul>
            <div class="tab-content pb0">
                <div class="tab-pane active" id="rt_link_main_tab">
                    <div class="editor">
                        <textarea name="tempEditor" id="tempEditor"><?php echo $this->htmlContent; ?></textarea>
                    </div>
                </div>
                <div class="tab-pane" id="st_link_headfoot_tab">
                    <div class="editor">
                        <strong>HEADER</strong>
                        <textarea name="tempHeaderEditor"><?php echo $this->htmlHeaderContent; ?></textarea>
                        <strong>FOOTER</strong>
                        <textarea name="tempFooterEditor"><?php echo $this->htmlFooterContent; ?></textarea>
                    </div>
                </div>
            </div>
        </div>
        <?php
        } else {
        ?>
        <div class="editor">
            <textarea name="tempEditor" id="tempEditor"><?php echo $this->htmlContent; ?></textarea>
        </div>
        <?php
        }
        ?>
        
    </div>
    <div class="col-md-2">  
        <div class="row" id="childConstants" style="border-bottom: 1px #cacaca solid;">
            <div class="col-md-12">
                <p class="consts-title">Тогтмолууд</p>
                <ul id="constants">
                    <?php
                    foreach ($this->sysKeywords as $sysKeyword) {
                        if ($sysKeyword['KEY_TYPE'] == 'sys' || $sysKeyword['KEY_TYPE'] == 'session') {
                    ?>
                        <li><div title="<?php echo $sysKeyword['META_DATA_NAME']; ?>" class="method tag-method" data-metaData="#<?php echo $sysKeyword['META_DATA_CODE']; ?>#" draggable="true"><?php echo $sysKeyword['META_DATA_CODE']; ?></div></li>
                    <?php
                        }
                    }
                    ?>
                </ul>
                <p class="consts-title mt10">Тохиргооны утгууд</p>
                <ul id="configvalues">
                    <?php
                    foreach ($this->sysKeywords as $configValueKeyword) {
                        if ($configValueKeyword['KEY_TYPE'] == 'config') {
                    ?>
                    <li><div title="<?php echo $configValueKeyword['META_DATA_NAME']; ?>" class="method tag-method" data-metaData="#<?php echo $configValueKeyword['META_DATA_CODE']; ?>#" draggable="true"><?php echo $configValueKeyword['META_DATA_CODE']; ?></div></li> 
                    <?php
                        }
                    }
                    ?>
                </ul>
                <p class="method-title mt10">Функцууд</p>
                <ul id="functions">
                    <li><div class="method tag-method" data-metaData="#sum#" draggable="true">sum</div></li> 
                    <li><div class="method tag-method" data-metaData="#avg#" draggable="true">avg</div></li> 
                    <li><div class="method tag-method" data-metaData="#max#" draggable="true">max</div></li> 
                    <li><div class="method tag-method" data-metaData="#min#" draggable="true">min</div></li> 
                    <li><div class="method tag-method" data-metaData="#first#" draggable="true">first</div></li> 
                    <li><div class="method tag-method" data-metaData="#last#" draggable="true">last</div></li> 
                    <li><div class="method tag-method" data-metaData="#count#" draggable="true">count</div></li> 
                    <li><div class="method tag-method" data-metaData="#rownum#" draggable="true">rownum</div></li>
                </ul>
            </div>
        </div>
        <?php
        if (isset($this->pageOption)) {
            echo $this->pageOption;
        }
        ?>
    </div>
</div>
<?php 
echo $this->fields; 
echo Form::close(); 
?>

<style type="text/css">
#childList, #childConstants {
    overflow: auto;
    height:450px;
    padding-left:25px;
}
</style>

<script type="text/javascript">
$(document).on('focusin', function(e) {
    if ($(e.target).closest(".mce-window, .moxman-window").length) {
        e.stopImmediatePropagation();
    }
});
                  
$(function() {
    $("#childs").on("dblclick", "li", function(i){
        tinymce.activeEditor.execCommand('mceInsertContent', false, $(this).find("span").length > 0 ? $(this).find("span > div").attr('data-metaData') :  $(this).find("div").attr('data-metaData'));
        return false;
    });
    $('html, body').animate({scrollTop: 0}, 0); 
});
</script>