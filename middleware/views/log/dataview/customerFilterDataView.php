<?php
if (isset($this->dataViewHeaderData) && isset($this->filterArr) && $this->filterArr) {
    foreach ($this->dataViewHeaderData as $gKey => $param) {
        $lower = Str::lower($param['META_DATA_CODE']);
        if (isset($this->filterArr[$lower])) { ?>
            <div class="col-md-4 pl0 pr0 xs-form" >
                <strong><?php echo $this->lang->line($param['META_DATA_NAME']); ?>: </strong><?php echo Mdwebservice::renderViewParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->filterArr) ? $this->filterArr : false)); ?> 
            </div>  
        <?php
        }
    }
} else {
    echo html_tag('div', array('class' => 'alert alert-info'), 'Түүх олдсонгүй!', true); 
}
?>
