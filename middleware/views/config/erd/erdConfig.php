<form method="post" id="erdVisualConfigForm-<?php echo $this->uniqId; ?>" data-uniqid="<?php echo $this->uniqId; ?>">
    <div class="row">
        <div class="col pl0 pr0">
            <div class="mt10 mb10">
                <?php 
                echo html_tag('button', 
                    array(
                        'type' => 'button',
                        'class' => 'btn btn-sm bg-primary mr-2', 
                        'onclick' => 'erdConfigAddObject(this);'
                    ), 
                    '<i class="icon-plus2"></i> ' . $this->lang->line('add_btn'), 
                    !$this->isReadOnly    
                );
                
                echo html_tag('button', 
                    array(
                        'type' => 'button',
                        'class' => 'btn btn-sm green-meadow', 
                        'onclick' => 'saveErdConfig(this);'
                    ), 
                    '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn'), 
                    !$this->isReadOnly    
                );
                
                echo html_tag('button', 
                    array(
                        'type' => 'button',
                        'class' => 'btn btn-sm btn-secondary float-right', 
                        'title' => 'Fullscreen',
                        'onclick' => 'fullScreenErdConfig(this);'
                    ), 
                    '<i class="far fa-expand"></i>' 
                ); 
                ?>
            </div>
            <div class="heigh-editor" style="position: absolute;">
                <div class="css-editor" id="datamart-editor-<?php echo $this->uniqId; ?>" style="width: 4000px; height: 4000px; position: relative;"></div>
            </div>
        </div>
        <div style="display: none; flex: 0 0 350px; overflow: auto; padding-left: 10px" id="datamart-attributes" data-erd-id="<?php echo $this->erdId; ?>">
        </div>
    </div>
    <input type="hidden" name="erdId" data-dmart-serviceid="1" value="<?php echo $this->erdId; ?>"/>
</form> 

<script type="text/javascript">
var isRo_<?php echo $this->uniqId; ?> = <?php echo ($this->isReadOnly ? 'true' : 'false'); ?>;
</script>

<style type="text/css">
#erdVisualConfigForm-<?php echo $this->uniqId; ?> ._jsPlumb_overlay {
    display: none;
    width: 250px;
    background-color: rgba(223, 223, 223, 0.9);
    font-size: 11px;
    line-height: 12px;
    padding: 2px;
    border: 1px #b4b4b4 solid;
    color: #000;
    z-index: 2;
}
#erdVisualConfigForm-<?php echo $this->uniqId; ?> ._jsPlumb_overlay._jsPlumb_hover {
    display: block;
}
#erdVisualConfigForm-<?php echo $this->uniqId; ?> .dmart-object-attr {
    max-height: 400px;
    overflow: auto;
}
.erd-fullscreen {
    z-index: 100;
    margin: 0;
    position: fixed;
    top: 0;
    left: 0;
    bottom: 0;
    right: 0;
    width: 100%;
    height: 100%;
    background: #fff;
    padding: 4px 22px 10px 22px !important;
}
</style>