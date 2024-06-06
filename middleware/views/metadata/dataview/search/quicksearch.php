<?php
if ($this->row['IS_USE_QUICKSEARCH'] == '1' && $this->dataViewHeaderRealData) {
    
    $_this = $this;
    
    array_walk($this->dataViewHeaderRealData, function(&$value) use (&$_this) {          
        $value['META_DATA_NAME'] = $_this->lang->line($value['META_DATA_NAME']);
        $value['rowData'] = htmlentities(json_encode($value), ENT_QUOTES, 'UTF-8');
    }); 
?>
<div class="dv-quicksearch float-left mr-3 <?php echo ($this->dataViewCriteriaType === 'left web' || $this->dataViewCriteriaType === 'left web civil') ? 'hidden' : '' ?>" data-placeholder-text="<?php echo $this->lang->line('fin_00513'); ?>">
    <div class="row">
        <div class="col-md-5 p-0 pr5">
            <?php
            echo Form::select(
                array(
                    'class' => 'select2 form-control form-control-sm quicksearch-combo', 
                    'data' => $this->dataViewHeaderRealData, 
                    'op_value' => 'PARAM_REAL_PATH', 
                    'op_text' => 'META_DATA_NAME', 
                    'op_param' => 'rowData'
                )
            );
            ?>
        </div>
        <div class="col-md-7 p-0 quicksearch-control">
            <?php echo Form::text(array('class' => 'form-control form-control-sm')); ?>
        </div>
    </div>
</div>
<?php
}
?>