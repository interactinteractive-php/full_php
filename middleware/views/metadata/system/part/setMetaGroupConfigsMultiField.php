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
    <div class="form-group row" data-path="sidebarName" data-sidebar="1">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_sidebarName"><?php echo $this->lang->line('META_00122'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="text" id="dv_sidebarName" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('META_00122'); ?>" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="searchGroupName" data-sidebar="1">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_searchGroupName"><?php echo $this->lang->line('metadata_group_name'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <input type="text" id="dv_searchGroupName" class="form-control form-control-sm" placeholder="<?php echo $this->lang->line('metadata_group_name'); ?>" disabled="disabled"/>
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
    <div class="form-group row" data-path="textWeight" data-sidebar="0">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_textWeight">Текстийн өргөн:</label>
        <div class="col-md-6" data-control="1">
            <select id="dv_textWeight" class="form-control form-control-sm" disabled="disabled">
                <option value="">- Сонгох -</option>
                <option value="normal">Normal</option>
                <option value="bold">Bold</option>
                <option value="600">600</option>
                <option value="700">700</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="headerAlign" data-sidebar="0">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_headerAlign">Толгой зэрэгцүүлэлт:</label>
        <div class="col-md-6" data-control="1">
            <select id="dv_headerAlign" class="form-control form-control-sm" disabled="disabled">
                <option value="">- Сонгох -</option>
                <option value="center">Center</option>
                <option value="left">Left</option>
                <option value="right">Right</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="bodyAlign" data-sidebar="0">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_bodyAlign">Утгын зэрэгцүүлэлт:</label>
        <div class="col-md-6" data-control="1">
            <select id="dv_bodyAlign" class="form-control form-control-sm" disabled="disabled">
                <option value="">- Сонгох -</option>
                <option value="center">Center</option>
                <option value="left">Left</option>
                <option value="right">Right</option>
                <option value="justify">Justify</option>
            </select>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
    <div class="form-group row" data-path="columnAggregate" data-sidebar="0">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_columnAggregate"><?php echo $this->lang->line('META_00124'); ?>:</label>
        <div class="col-md-6" data-control="1">
            <?php 
            echo Form::select(
                array(
                    'id' => 'dv_columnAggregate', 
                    'class' => 'form-control form-control-sm',
                    'data' => array(
                        array(
                            'code' => 'sum', 
                            'name' => $this->lang->line('META_00031')
                        ), 
                        array(
                            'code' => 'avg', 
                            'name' => $this->lang->line('META_00157')
                        ),
                        array(
                            'code' => 'min', 
                            'name' => $this->lang->line('META_00078')
                        ),
                        array(
                            'code' => 'max', 
                            'name' => $this->lang->line('META_00184')
                        )
                    ), 
                    'op_value' => 'code', 
                    'op_text' => 'name', 
                    'disabled' => 'disabled'
                )
            ); 
            ?>
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
    <div class="form-group row" data-path="isShowMobile" data-sidebar="1">
        <label class="col-form-label col-md-4 text-right pr0" for="dv_isShowMobile">Is mobile:</label>
        <div class="col-md-6" data-control="1">
            <input type="checkbox" id="dv_isShowMobile" class="notuniform" disabled="disabled"/>
        </div>
        <div class="col-md-2">
            <input type="checkbox" value="1" class="notuniform row-control-toggle"/>
        </div>
    </div>
</div>