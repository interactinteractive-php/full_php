<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'temp-content-form', 'method' => 'post')); ?>
<div class="row columns">
    <div class="col-md-9 pr0">  
        <div class="editor">
            <strong>HEADER</strong>
            <textarea name="tempHeaderEditor" id="tempHeaderEditor"><?php echo $this->htmlHeaderContent; ?></textarea>
            <strong>FOOTER</strong>
            <textarea name="tempFooterEditor" id="tempFooterEditor"><?php echo $this->htmlFooterContent; ?></textarea>
        </div>
    </div>
    <div class="col-md-3" id="childConstants">  
        <p class="consts-title"><?php echo $this->lang->line('metadata_rt_const'); ?></p>
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
        <hr>
        <p class="consts-title"><?php echo $this->lang->line('metadata_rt_config_value'); ?></p>
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
        <hr>
        <p class="method-title"><?php echo $this->lang->line('metadata_rt_function'); ?></p>
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
echo $this->fields; 
echo Form::close(); 
?>

<style type="text/css">
#childConstants {
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
    $('html, body').animate({scrollTop: 0}, 0);
});
</script>