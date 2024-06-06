<?php 
echo Form::create(array('class' => 'form-horizontal', 'id' => 'internal-process-form', 'method' => 'post')); 
echo Form::hidden(array('name' => 'metaDataId', 'id' => 'metaDataId', 'value' => $this->metaDataId)); 
echo Form::hidden(array('name' => 'folderId', 'id' => 'folderId', 'value' => $this->folderId)); 
echo Form::hidden(array('name' => 'sendType', 'id' => 'sendType', 'value' => '0')); 
?>
<div class="row ml20">
    <div class="col-md-6">
        <div class="form-group row">
            <label class="checkbox-inline">
                <?php echo Form::checkbox(array('name' => 'action[create]', 'value' => '0', 'onclick'=>'setSendValue(this)')); ?> <?php echo $this->lang->line('META_00103'); ?>
            </label>
        </div>
        <div class="form-group row">
            <label class="checkbox-inline">
                <?php echo Form::checkbox(array('name' => 'action[update]', 'value' => '0', 'onclick'=>'setSendValue(this)')); ?> <?php echo $this->lang->line('META_00058'); ?>
            </label>
        </div>
        <div class="form-group row">
            <label class="checkbox-inline">
                <?php echo Form::checkbox(array('name' => 'action[get]', 'value' => '0', 'onclick'=>'setSendValue(this)')); ?> <?php echo $this->lang->line('MET_332580'); ?>
            </label>
        </div>
        <div class="form-group row">
            <label class="checkbox-inline">
                <?php echo Form::checkbox(array('name' => 'action[menu]', 'value' => '0', 'onclick'=>'setSendValue(this)')); ?> <?php echo $this->lang->line('MET_331959'); ?>
            </label>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group row">
            <label class="checkbox-inline">
                <?php echo Form::checkbox(array('name' => 'action[delete]', 'value' => '0', 'onclick'=>'setSendValue(this)')); ?> <?php echo $this->lang->line('META_00002'); ?>
            </label>
        </div>
        <div class="form-group row">
            <label class="checkbox-inline">
                <?php echo Form::checkbox(array('name' => 'action[consolidate]', 'value' => '0', 'onclick'=>'setSendValue(this)')); ?> <?php echo $this->lang->line('MET_332702'); ?>
            </label>
        </div>
        <div class="form-group row">
            <label class="checkbox-inline">
                <?php echo Form::checkbox(array('name' => 'action[list]', 'value' => '0', 'onclick'=>'setSendValue(this)')); ?> <?php echo $this->lang->line('META_00062'); ?>
            </label>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
function setSendValue(elem){
    var $this = $(elem);
    var sendType = '0';

    if ($this.attr('name') == 'action[update]') {
        var get = $('input[name="action[get]"]');
        
        if ($this.prop('checked')) {
            get.parents('div.checker').addClass('disabled');
            get.prop('disabled', true);
        } else {
            get.prop('disabled', false);
            get.parents('div.checker').removeClass('disabled');
        }
    }
    
    if ($this.prop('checked')) {
        $this.val('1');
    } else {
        $this.val('0');
    }
    
    $.each($('input[type="checkbox"]'), function(){
        if ($(this).prop('checked')) {
            sendType = '1';
        }
    });
    $("#sendType").val(sendType);
}
</script>