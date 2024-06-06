<?php
if ($this->params) {
?>
<form class="xs-form">
    <?php
    foreach ($this->params as $param) {
        if ($param['IS_SHOW'] == '1') {
            $labelName = $this->lang->line($param['META_DATA_NAME']);
    ?>
    <div class="form-group row fom-row">
        <label class="col-form-label panel-title"><?php echo $labelName; ?></label>
        <?php 
        echo Mdwebservice::renderParamControl($this->processId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $this->fillData);
        ?>
    </div>
    <?php
        }
    }
    ?>
    
    <div class="form-actions mt20">
        <button type="button" class="btn btn-circle blue" onclick="erlSaveContentParams(this);"><i class="fa fa-check"></i> Хадгалах</button>
    </div>
</form>
<?php
}
?>