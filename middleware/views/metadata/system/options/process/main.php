<div class="main-param">
    <span class="text-blue mb-2"><?php echo $this->lang->line('setting_main'); ?></span>
    <div class="row mb-2">
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00066'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'bp_process_type',
                            'id' => 'bp_process_type',
                            'data' => array(
                                array(
                                    'code' => 'external',
                                    'name' => 'External'
                                ),
                                array(
                                    'code' => 'internal',
                                    'name' => 'Internal'
                                ), 
                                array(
                                    'code' => 'interface', 
                                    'name' => 'Interface'
                                ),
                                array(
                                    'code' => 'endtoend', 
                                    'name' => 'End to end'
                                )                                
                            ),
                            'text' => 'notext',
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => $this->bpRow['SUB_TYPE']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 external_tr pf-bp-wsurl">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00067'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'wsUrl', 'id' => 'wsUrl', 'class' => 'form-control', 'value' => $this->bpRow['WS_URL'])); ?>
                </div>
            </div>
        </div>            
        <div class="col-4 mb-2 external_tr">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00175'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'serviceLanguageId',
                            'id' => 'serviceLanguageId',
                            'data' => (new Mdmetadata())->getWebServiceLanguage(),
                            'op_value' => 'SERVICE_LANGUAGE_ID',
                            'op_text' => 'SERVICE_LANGUAGE_CODE',
                            'class' => 'form-control',
                            'value' => $this->bpRow['SERVICE_LANGUAGE_ID']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>          
        <div class="col-4 mb-2 external_tr">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00045'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'className', 'id' => 'className', 'class' => 'form-control', 'value' => $this->bpRow['CLASS_NAME'])); ?>
                </div>
            </div>
        </div>          
        <div class="col-4 mb-2 external_tr pf-bp-methodname">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00027'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'methodName', 'id' => 'methodName', 'class' => 'form-control', 'value' => $this->bpRow['METHOD_NAME'])); ?>
                </div>
            </div>
        </div>  
        <div class="col-4 mb-2 system-meta-group-id">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00203'); ?></label>
                <div class="col-lg-8 p-0">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview|parameter&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="systemMetaGroupId" name="systemMetaGroupId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'SYSTEM_META_GROUP_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'SYSTEM_META_GROUP_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'SYSTEM_META_GROUP_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'SYSTEM_META_GROUP_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'SYSTEM_META_GROUP_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>     
                </div>
            </div>
        </div>     
        <div class="col-4 mb-2 external_tr">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_sub_type'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'external_action_type',
                            'id' => 'external_action_type',
                            'data' => array(
                                array(
                                    'code' => 'console',
                                    'name' => 'Console'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => $this->bpRow['ACTION_TYPE']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>         
        <div class="col-4 mb-2 internal_tr">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_sub_type'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    if ($this->bpRow['METHOD_NAME'] == 'getDuplicateRow') {
                        $this->bpRow['ACTION_TYPE'] = 'duplicate';
                    }

                    echo Form::select(
                        array(
                            'name' => 'action_type',
                            'id' => 'action_type',
                            'data' => array(
                                array(
                                    'code' => 'insert',
                                    'name' => 'Create'
                                ),
                                array(
                                    'code' => 'update',
                                    'name' => 'Update'
                                ),
                                array(
                                    'code' => 'delete',
                                    'name' => 'Delete'
                                ),
                                array(
                                    'code' => 'get',
                                    'name' => 'Get'
                                ),
                                array(
                                    'code' => 'exist',
                                    'name' => 'Exist'
                                ),
                                array(
                                    'code' => 'consolidate',
                                    'name' => 'Consolidate'
                                ), 
                                array(
                                    'code' => 'view', 
                                    'name' => 'View'
                                ), 
                                array(
                                    'code' => 'duplicate', 
                                    'name' => 'Duplicate'
                                )
                            ),
                            'text' => 'notext',
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => $this->bpRow['ACTION_TYPE']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>          
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00028'); ?></label>
                <div class="col-lg-8 p-0">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=tablestructure&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="refMetaGroupId" name="refMetaGroupId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'REF_META_GROUP_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'REF_META_GROUP_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'REF_META_GROUP_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown" data-isworkflow="1">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'REF_META_GROUP_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'REF_META_GROUP_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </div>
            </div>
        </div>          
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00029'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'processName', 'id' => 'processName', 'class' => 'form-control globeCodeInput', 'value' => $this->bpRow['PROCESS_NAME'])); ?>
                </div>
            </div>
        </div>             
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00176'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'windowType',
                            'id' => 'windowType',
                            'data' => array(
                                array(
                                    'code' => 'main',
                                    'name' => 'Main window'
                                ),
                                array(
                                    'code' => 'standart',
                                    'name' => 'Standart'
                                ),
                                array(
                                    'code' => 'notepaper1',
                                    'name' => 'Notepaper 1'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => $this->bpRow['WINDOW_TYPE']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>            
        <div class="col-4 mb-2 datamodel-window-size">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00204'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'windowSize',
                            'id' => 'windowSize',
                            'data' => array(
                                array(
                                    'code' => 'standart',
                                    'name' => 'Standart'
                                ),
                                array(
                                    'code' => 'fullscreen',
                                    'name' => 'Fullscreen'
                                ),
                                array(
                                    'code' => 'custom',
                                    'name' => 'Custom'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => $this->bpRow['WINDOW_SIZE']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>          
        <div class="col-4 mb-2 datamodel-window-width">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00148'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'windowWidth', 'id' => 'windowWidth', 'class' => 'form-control longInit', 'value' => $this->bpRow['WINDOW_WIDTH'])); ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-window-height">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00100'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'windowHeight', 'id' => 'windowHeight', 'class' => 'form-control', 'value' => $this->bpRow['WINDOW_HEIGHT'])); ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00069'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'methodActionBtn', 'id' => 'methodActionBtn', 'class' => 'form-control globeCodeInput', 'value' => $this->bpRow['ACTION_BTN'])); ?>
                </div>
            </div>
        </div>           
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00117'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'columnCount', 'id' => 'columnCount', 'class' => 'form-control longInit', 'value' => $this->bpRow['COLUMN_COUNT'])); ?>
                </div>
            </div>
        </div>            
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00070'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'tabColumnCount', 'id' => 'tabColumnCount', 'class' => 'form-control longInit', 'value' => $this->bpRow['TAB_COLUMN_COUNT'])); ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_label_width'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'labelWidth', 'id' => 'labelWidth', 'class' => 'form-control', 'value' => $this->bpRow['LABEL_WIDTH'])); ?>
                </div>
            </div>
        </div>
        <div class="w-100"></div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00024'); ?></label>
                <div class="col-lg-8 p-0">
                    <div style="position: relative; width: 70vw;">
                        <div class="meta-folder-tags">
                            <?php echo $this->folderIdsNames; ?>
                        </div>
                        <a href="javascript:;" class="btn btn-sm purple-plum float-left" onclick="commonFolderDataGrid('multi', '', 'chooseMetaParentFolderV2', this);">...</a>
                        <input type="hidden" name="isFolderManage" value="0"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<hr class="mt-2 mb-2">
<div class="other-param">
    <span class="text-blue mb-2"><?php echo $this->lang->line('setting_other'); ?></span>
    <div class="row mb-2">
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_run_mode'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::text(array('name' => 'runMode', 'id' => 'runMode', 'class' => 'form-control', 'value' => $this->bpRow['RUN_MODE'])); ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_skin'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'skin',
                            'id' => 'skin',
                            'data' => array(
                                array(
                                    'code' => 'bp-skin-1',
                                    'name' => 'Skin #1'
                                ),
                                array(
                                    'code' => 'bp-skin-2',
                                    'name' => 'Skin #2'
                                ),
                                array(
                                    'code' => 'bp-skin-3',
                                    'name' => 'Skin #3'
                                ),
                                array(
                                    'code' => 'bp-skin-3',
                                    'name' => 'Skin #3'
                                ),
                                array(
                                    'code' => 'min-formcontrol',
                                    'name' => 'Skin #4 small'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => $this->bpRow['SKIN']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_default_get'); ?></label>
                <div class="col-lg-8 p-0 mdo-cell">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="getDataProcessId" name="getDataProcessId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'GETDATA_PROCESS_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'GETDATA_PROCESS_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'GETDATA_PROCESS_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                            <span class="input-group-btn">
                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                            </span>     
                            <span class="input-group-btn not-group-btn">
                                <div class="btn-group pf-meta-manage-dropdown">
                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                </div>
                            </span>  
                            <span class="input-group-btn flex-col-group-btn">
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'GETDATA_PROCESS_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'GETDATA_PROCESS_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>   
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-sm purple-plum" onclick="setGetDataProcessParam(this);" title="Параметрын тохиргоо"><i class="icon-cogs"></i></button>
                            </span>  
                        </div>
                    </div>
                    <div id="dialog-getdata-process-param"></div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_mobile_theme'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'mobileTheme',
                            'id' => 'mobileTheme',
                            'data' => $this->widgetData,
                            'op_value' => 'CODE',
                            'op_text' => 'NAME',
                            'class' => 'form-control', 
                            'value' => $this->bpRow['MOBILE_THEME']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_theme'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'groupTheme',
                            'id' => 'groupTheme',
                            'data' => array(
                                array(
                                    'code' => 'bootstrap',
                                    'name' => 'Bootstrap'
                                ),
                                array(
                                    'code' => 'material',
                                    'name' => 'Material'
                                ),
                                array(
                                    'code' => 'web',
                                    'name' => 'Web'
                                ),
                                array(
                                    'code' => 'disableoption',
                                    'name' => 'disableOption'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => $this->bpRow['THEME']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00197'); ?></label>
                <div class="col-lg-8 p-0 mdo-cell">
                    <div class="metaChoosedIcon">
                        <div class="iconpath">
                            <?php
                            if (!empty($this->metaRow['META_ICON_ID'])) {
                                echo '<img src="assets/core/global/img/metaicon/small/' . $this->metaRow['META_ICON_CODE'] . '">';
                            }
                            ?>
                        </div>
                        <?php echo Form::hidden(array('name' => 'metaIconId', 'value' => $this->metaRow['META_ICON_ID'])); ?>
                    </div>
                    <a href="javascript:;" class="btn btn-sm purple-plum" onclick="metaIconChoose(this);">...</a>
                </div>
            </div>
        </div> 
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('setting_tag'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'workinType',
                            'id' => 'workinType',
                            'data' => array(
                                array(
                                    'code' => 'xyp',
                                    'name' => 'XYP'
                                ), 
                                array(
                                    'code' => 'bank',
                                    'name' => $this->lang->line('META_00116')
                                ), 
                                array(
                                    'code' => 'zms',
                                    'name' => 'ZMS'
                                ) 
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control', 
                            'value' => $this->bpRow['WORKIN_TYPE']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('MET_99990359') ?> (content id)</label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::text([
                        'name' => 'helpContentId',
                        'id' => 'helpContentId',
                        'placeholder' => $this->lang->line('setting_content'),
                        'class' => 'form-control longInit',
                        'value' => $this->bpRow['HELP_CONTENT_ID']
                    ]);
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="isRule"><?php echo $this->lang->line('setting_is_rule'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php echo Form::checkbox(array('name' => 'isRule', 'id' => 'isRule', 'value' => '1', 'saved_val' => $this->bpRow['IS_RULE'], 'class' => 'notuniform')); ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
        </div>   
    </div>
</div>
<hr class="mb-2">
<div class="additional-param">
    <span class="text-blue mb-2"><?php echo $this->lang->line('setting_additional'); ?></span>
    <div class="row mt-2">
        <div style="width: 250px;" class="px-2">
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto"><?php echo $this->lang->line('setting_photo'); ?></label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isAddOnPhoto',
                        'id' => 'isAddOnPhoto',
                        'class' => 'isAddonCheck notuniform',
                        'value' => '1',
                        'saved_val' => ($this->bpRow['IS_ADDON_PHOTO'] == '2' ? 1 : $this->bpRow['IS_ADDON_PHOTO'])
                    )
                );
                ?>
                <div style="width:100px;">
                    <div class="<?php //echo (!$this->bpRow['IS_ADDON_PHOTO']) ? 'd-none' : 'd-flex'; ?> ml-2">
                        <div class="form-check form-check-switchery">
                            <label class="form-check-label w-100">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAddOnPhotoRequired',
                                        'id' => 'isAddOnPhotoRequired',
                                        'value' => '1',
                                        'class' => 'form-check-input-switchery notuniform', 
                                        'saved_val' => ($this->bpRow['IS_ADDON_PHOTO'] == '2' ? 1 : 0)
                                    )
                                );
                                ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto"><?php echo $this->lang->line('setting_file'); ?></label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isAddOnFile',
                        'id' => 'isAddOnFile',
                        'class' => 'isAddonCheck notuniform',
                        'value' => '1',
                        'saved_val' => ($this->bpRow['IS_ADDON_FILE'] == '2' ? 1 : $this->bpRow['IS_ADDON_FILE'])
                    )
                );
                ?>
                <div style="width:100px;">
                    <div class="<?php //echo (!$this->bpRow['IS_ADDON_FILE']) ? 'd-none' : 'd-flex'; ?> ml-2">
                        <div class="form-check form-check-switchery">
                            <label class="form-check-label w-100">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAddOnFileRequired',
                                        'id' => 'isAddOnFileRequired',
                                        'value' => '1',
                                        'class' => 'form-check-input-switchery notuniform', 
                                        'saved_val' => ($this->bpRow['IS_ADDON_FILE'] == '2' ? 1 : 0)
                                    )
                                );
                                ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto">Is bpmn tool</label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isBpmnTool',
                        'id' => 'isBpmnTool',
                        'value' => '1',
                        'class' => 'notuniform', 
                        'saved_val' => $this->bpRow['IS_BPMN_TOOL']
                    )
                );
                ?>
                <div style="width: 100px;"></div>
            </div>            
        </div>
        <div style="width: 250px;" class="px-2">
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto"><?php echo $this->lang->line('setting_comment'); ?></label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isAddOnComment',
                        'id' => 'isAddOnComment',
                        'class' => 'isAddonCheck notuniform',
                        'value' => '1',
                        'saved_val' => ($this->bpRow['IS_ADDON_COMMENT'] == '2' ? 1 : $this->bpRow['IS_ADDON_COMMENT'])
                    )
                );
                ?>
                <div style="width:100px;">
                    <div class="<?php //echo (!$this->bpRow['IS_ADDON_COMMENT']) ? 'd-none' : 'd-flex'; ?> ml-2">
                        <div class="form-check form-check-switchery">
                            <label class="form-check-label w-100">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAddOnCommentRequired',
                                        'id' => 'isAddOnCommentRequired',
                                        'value' => '1',
                                        'class' => 'form-check-input-switchery notuniform', 
                                        'saved_val' => ($this->bpRow['IS_ADDON_COMMENT'] == '2' ? 1 : 0)
                                    )
                                );
                                ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto"><?php echo $this->lang->line('setting_log'); ?></label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isAddOnLog',
                        'id' => 'isAddOnLog',
                        'class' => 'isAddonCheck notuniform',
                        'value' => '1',
                        'saved_val' => ($this->bpRow['IS_ADDON_LOG'] == '2' ? 1 : $this->bpRow['IS_ADDON_LOG'])
                    )
                );
                ?>
                <div style="width:100px;">
                    <div class="<?php //echo (!$this->bpRow['IS_ADDON_LOG']) ? 'd-none' : 'd-flex'; ?> ml-2">
                        <div class="form-check form-check-switchery">
                            <label class="form-check-label w-100">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAddOnLogRequired',
                                        'id' => 'isAddOnLogRequired',
                                        'value' => '1',
                                        'class' => 'form-check-input-switchery notuniform', 
                                        'saved_val' => ($this->bpRow['IS_ADDON_LOG'] == '2' ? 1 : 0)
                                    )
                                );
                                ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto"><?php echo $this->lang->line('ea_meta_0012'); ?></label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isAddonMvRelation',
                        'id' => 'isAddonMvRelation',
                        'class' => 'isAddonCheck notuniform',
                        'value' => '1',
                        'saved_val' => ($this->bpRow['IS_ADDON_MV_RELATION'] == '2' ? 1 : $this->bpRow['IS_ADDON_MV_RELATION'])
                    )
                );
                ?>
                <div style="width:100px;">
                    <div class="ml-2">
                        <div class="form-check form-check-switchery">
                            <label class="form-check-label w-100">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAddonMvRelationRequired',
                                        'id' => 'isAddonMvRelationRequired',
                                        'value' => '1',
                                        'class' => 'form-check-input-switchery notuniform', 
                                        'saved_val' => ($this->bpRow['IS_ADDON_MV_RELATION'] == '2' ? 1 : 0)
                                    )
                                );
                                ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div style="width: 250px;" class="px-2">
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto"><?php echo $this->lang->line('setting_relation'); ?></label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isAddonRelation',
                        'id' => 'isAddonRelation',
                        'class' => 'isAddonCheck notuniform',
                        'value' => '1',
                        'saved_val' => ($this->bpRow['IS_ADDON_RELATION'] == '2' ? 1 : $this->bpRow['IS_ADDON_RELATION'])
                    )
                );
                ?>
                <div style="width:100px;">
                    <div class="ml-2">
                        <div class="form-check form-check-switchery">
                            <label class="form-check-label w-100">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAddonRelationRequired',
                                        'id' => 'isAddonRelationRequired',
                                        'value' => '1',
                                        'class' => 'form-check-input-switchery notuniform', 
                                        'saved_val' => ($this->bpRow['IS_ADDON_RELATION'] == '2' ? 1 : 0)
                                    )
                                );
                                ?>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto"><?php echo $this->lang->line('setting_widget'); ?></label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isWidget',
                        'id' => 'isWidget',
                        'value' => '1',
                        'class' => 'notuniform', 
                        'saved_val' => $this->bpRow['IS_WIDGET']
                    )
                );
                ?>
                <div style="width:100px;"></div>
            </div>
        </div>
        <div style="width: 350px;" class="px-2">
            <div class="form-group d-flex flex-row align-items-center mb-2">
                <label class="mr-auto"><?php echo $this->lang->line('setting_status_log'); ?></label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isAddonWfmLog',
                        'id' => 'isAddonWfmLog',
                        'class' => 'isAddonCheck notuniform',
                        'value' => '1',
                        'saved_val' => $this->bpRow['IS_ADDON_WFM_LOG']
                    )
                );
                ?>
                <div style="width:230px;">
                    <div class="<?php //echo (!$this->bpRow['IS_ADDON_WFM_LOG']) ? '' : 'd-flex'; ?> ml-2">
                        <?php
                        echo Form::text(
                            array(
                                'name' => 'isAddonWfmLogType',
                                'id' => 'isAddonWfmLogType',
                                'class' => 'form-control',
                                'value' => $this->bpRow['IS_ADDON_WFM_LOG_TYPE'],
                                'placeholder' => 'Etc: tab, bottom'
                            )
                        );
                        ?>
                    </div>
                </div>
            </div>
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto">Is tools btn</label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isToolsBtn',
                        'id' => 'isToolsBtn',
                        'value' => '1',
                        'class' => 'notuniform', 
                        'saved_val' => $this->bpRow['IS_TOOLS_BTN']
                    )
                );
                ?>
                <div style="width: 230px;"></div>
            </div>
            <div class="form-group d-flex flex-row align-items-center">
                <label class="mr-auto">Is view log</label>
                <?php
                echo Form::checkbox(
                    array(
                        'name' => 'isSaveViewLog',
                        'id' => 'isSaveViewLog',
                        'value' => '1',
                        'class' => 'notuniform', 
                        'saved_val' => $this->bpRow['IS_SAVE_VIEW_LOG']
                    )
                );
                ?>
                <div style="width: 230px;"></div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function () {
    
    $("#bp_process_type").on("change", function () {
        var val = $(this).val();
        
        $('label[for="methodName"]').text('<?php echo $this->lang->line('META_00027'); ?>');
        
        if (val == 'internal') {
            $("#className").prop("readonly", true);
            $("#methodName").prop("readonly", true);
            $(".internal_tr, .system-meta-group-id").show();
            $('.external_tr').hide();
            $("#action_type").trigger("change");
        } else if (val == 'interface') {
            $("#className").prop("readonly", false);
            $("#methodName").prop("readonly", false);
            $(".internal_tr, .system-meta-group-id, .external_tr").hide();
            $('.pf-bp-wsurl, .pf-bp-methodname').show();
            $('label[for="methodName"]').text('Коммандын нэр');
        } else {
            $("#className").prop("readonly", false);
            $("#methodName").prop("readonly", false);
            $(".internal_tr, .system-meta-group-id").hide();
            $('.external_tr').show();
        }
    });
    $("#bp_process_type").trigger("change");

    $("#action_type").on("change", function () {
        var _this = $(this);
        var _thisVal = _this.val();
        var className = 'MetaDataModelViewBean';
        var _thisProcessType = $("#bp_process_type").val();
        var methodName = '';

        if (_this.find('option:selected').text() == 'Duplicate' && _thisProcessType == 'internal') {
            methodName = 'getDuplicateRow';
        } else if (_thisVal == 'insert' && _thisProcessType == 'internal') {
            methodName = 'createRow';
        } else if (_thisVal == 'update' && _thisProcessType == 'internal') {
            methodName = 'updateRow';
        } else if (_thisVal == 'delete' && _thisProcessType == 'internal') {
            methodName = 'deleteRow';
        } else if (_thisVal == 'get' && _thisProcessType == 'internal') {
            methodName = 'getRow';
        } else if (_thisVal == 'exist' && _thisProcessType == 'internal') {
            methodName = 'existRow';
        } else if (_thisVal == 'consolidate' && _thisProcessType == 'internal') {
            methodName = 'getConsolidateRow';
        }

        if (methodName != '') {
            if ($("#className").val() == '') {
                $("#className").val(className);
            }
            $("#methodName").val(methodName);
        }
    });
    $("#action_type").trigger("change");

    $("#windowSize").on('change', function() {
        visibleWindowSizeAttr();
    });
    $("#windowSize").trigger('change');

    $('.isAddonCheck').on('click', function() {
        var $this = $(this), $addonRequiredLabel = $this.closest('.form-group').find('.align-items-center');
        if ($this.is(':checked')) {
            $addonRequiredLabel.removeClass('d-none').addClass('d-flex');
        } else {
            $addonRequiredLabel.addClass('d-none').removeClass('d-flex');
            var $checkbox = $addonRequiredLabel.find('input[type=checkbox]');
            if ($checkbox.length) {
                var switchery = new Switchery($checkbox[0]);
                switchery.element.checked = false;
                switchery.handleOnchange();
            }
        }
    });
});

function visibleWindowSizeAttr() {
    var windowSize = $("#windowSize").val();
    if (windowSize === 'custom') {
        $(".datamodel-window-height, .datamodel-window-width").show();
    } else {
        $(".datamodel-window-height, .datamodel-window-width").hide();
    }
}
function setGetDataProcessParam(elem) {
    var $dialogName = 'dialog-getdata-process-param';

    if ($("#" + $dialogName).children().length > 0) {
        $("#" + $dialogName).dialog({
            appendTo: "form#meta-form-v2",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'GetData Process Param',
            width: 750,
            minWidth: 750,
            height: "auto",
            modal: true,
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $("#" + $dialogName).empty().dialog('close');
                }},
                {text: "<?php echo $this->lang->line('META_00002'); ?>", class: 'btn btn-sm red', click: function () {
                    $("#" + $dialogName).empty().dialog('close');
                }}
            ]
        }).dialogExtend({
            "closable": true,
            "maximizable": true,
            "minimizable": true,
            "collapsable": true,
            "dblclick": "maximize",
            "minimizeLocation": "left",
            "icons": {
                "close": "ui-icon-circle-close",
                "maximize": "ui-icon-extlink",
                "minimize": "ui-icon-minus",
                "collapse": "ui-icon-triangle-1-s",
                "restore": "ui-icon-newwin"
            }
        });
        $("#" + $dialogName).dialog('open');

    } else {

        if ($("input[name='getDataProcessId']").val() !== '') {
            $.ajax({
                type: 'post',
                url: 'mdmeta/setGetDataProcessParam',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>', getProcessId: $("input[name='getDataProcessId']").val()},
                dataType: "json",
                beforeSend: function () {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                },
                success: function (data) {
                    $("#" + $dialogName).empty().append(data.html);
                    $("#" + $dialogName).dialog({
                        appendTo: "form#meta-form-v2",
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 750,
                        minWidth: 750,
                        height: "auto",
                        modal: true,
                        buttons: [
                            {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                                $("#" + $dialogName).dialog('close');
                            }},
                            {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                                $("#" + $dialogName).empty().dialog('close');
                            }}
                        ]
                    }).dialogExtend({
                        "closable": true,
                        "maximizable": true,
                        "minimizable": true,
                        "collapsable": true,
                        "dblclick": "maximize",
                        "minimizeLocation": "left",
                        "icons": {
                            "close": "ui-icon-circle-close",
                            "maximize": "ui-icon-extlink",
                            "minimize": "ui-icon-minus",
                            "collapse": "ui-icon-triangle-1-s",
                            "restore": "ui-icon-newwin"
                        }
                    });
                    $("#" + $dialogName).dialog('open');
                    Core.unblockUI();
                },
                error: function () {
                    alert("Error");
                }
            }).done(function(){
                Core.initSelect2($("#" + $dialogName));
            });
        }
    }
}

function manageHelpContent(elem) {
    if (typeof contentHtmlList === 'undefined') {
        $.getScript(URL_APP + 'middleware/assets/js/contentui/contentHtmlList.js', function() {
            $.getStylesheet(URL_APP + 'middleware/assets/css/contentui/contentHtmlList.css');
            contentHtmlList.initDecimalAdjust();
            contentHtmlList.initContentHtmlList(function($dialogName, $appendToForm, data){
                contentHtmlList.showContentHtmlListDialog($dialogName, $appendToForm, data, elem);    
            });
        });
    } else {
        contentHtmlList.initContentHtmlList(function($dialogName, $appendToForm, data){
            contentHtmlList.showContentHtmlListDialog($dialogName, $appendToForm, data, elem);    
        });
    }
}
</script>
