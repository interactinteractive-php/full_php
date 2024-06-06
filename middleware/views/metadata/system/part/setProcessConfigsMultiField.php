<div class="col-md-12 xs-form">
    <div class="form-group row" data-path="columnWidth" data-sidebar="1">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_columnWidth"><?php echo $this->lang->line('META_00048'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="text" id="dv_columnWidth" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('META_00048'); ?>" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="tabName" data-sidebar="0">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_tabName"><?php echo $this->lang->line('META_00156'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="text" id="dv_tabName" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('META_00156'); ?>" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="minValue" data-sidebar="1">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_minValue"><?php echo $this->lang->line('META_00101'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="text" id="dv_minValue" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('META_00101'); ?>" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="maxValue" data-sidebar="1">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_maxValue"><?php echo $this->lang->line('META_00182'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="text" id="dv_maxValue" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('META_00182'); ?>" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="patternId" data-sidebar="1">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_patternId"><?php echo $this->lang->line('META_00183'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <?php
            echo Form::select(
                array(
                    'id' => 'dv_patternId',
                    'class' => 'form-control form-control-sm',
                    'data' => $this->maskData,
                    'op_value' => 'PATTERN_ID',
                    'op_text' => 'PATTERN_NAME',
                    'disabled' => 'disabled'
                )
            );
            ?>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="isShow" data-sidebar="0">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_isShow"><?php echo $this->lang->line('META_00003'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="checkbox" id="dv_isShow" class="notuniform" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="isRequired" data-sidebar="0">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_isRequired"><?php echo $this->lang->line('META_00121'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="checkbox" id="dv_isRequired" class="notuniform" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="isRefresh" data-sidebar="1">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_isRefresh"><?php echo $this->lang->line('META_00006'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="checkbox" id="dv_isRefresh" class="notuniform" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
</div>