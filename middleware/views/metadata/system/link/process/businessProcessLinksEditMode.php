<div class="tabbable-line">
    <ul class="nav nav-tabs ">
        <li class="nav-item">
            <a href="#bp_link_main_tab" data-toggle="tab" class="nav-link active"><?php echo $this->lang->line('META_00008'); ?></a>
        </li>
        <li class="nav-item">
            <a href="#bp_link_other_tab" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00098'); ?></a>
        </li>
        <li class="nav-item">
            <a href="#bp_link_addons_tab" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00047'); ?></a>
        </li>
        <li class="nav-item">
            <a href="#bp_link_links_tab" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00151'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="bp_link_main_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>
                        <tr>
                            <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('META_00066'); ?></td>
                            <td colspan="2">
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
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['SUB_TYPE']
                                    )
                                );
                                ?>
                            </td>
                        </tr>            
                        <tr class="external_tr pf-bp-wsurl">
                            <td style="width: 170px" class="left-padding">
                                <label for="wsUrl"><?php echo $this->lang->line('META_00067'); ?></label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'wsUrl',
                                        'id' => 'wsUrl',
                                        'class' => 'form-control',
                                        'value' => $this->bpRow['WS_URL']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="external_tr">
                            <td class="left-padding"><?php echo $this->lang->line('META_00175'); ?></td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'serviceLanguageId',
                                        'id' => 'serviceLanguageId',
                                        'data' => (new Mdmetadata())->getWebServiceLanguage(),
                                        'op_value' => 'SERVICE_LANGUAGE_ID',
                                        'op_text' => 'SERVICE_LANGUAGE_CODE',
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['SERVICE_LANGUAGE_ID']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="external_tr">
                            <td class="left-padding">
                                <label for="className"><?php echo $this->lang->line('META_00045'); ?></label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'className',
                                        'id' => 'className',
                                        'class' => 'form-control',
                                        'value' => $this->bpRow['CLASS_NAME']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="external_tr pf-bp-methodname">
                            <td class="left-padding">
                                <label for="methodName"><?php echo $this->lang->line('META_00027'); ?></label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'methodName',
                                        'id' => 'methodName',
                                        'class' => 'form-control',
                                        'value' => $this->bpRow['METHOD_NAME']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="system-meta-group-id">
                            <td class="left-padding"><?php echo $this->lang->line('META_00203'); ?></td>
                            <td colspan="2">
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
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00046'); ?></td>
                            <td colspan="2">
                                <input id="inputMetaDataId" name="inputMetaDataId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'INPUT_META_DATA_ID'); ?>">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setParamAttributesNew(this);')); ?>
                                <div id="dialog-paramattributes-new" style="display: none"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00104'); ?></td>
                            <td colspan="2">
                                <input id="outputMetaDataId" name="outputMetaDataId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'OUTPUT_META_DATA_ID'); ?>">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setOutputParamAttributesNew(this);')); ?>
                                <div id="dialog-outputparamattributes-new" style="display: none"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px;" class="left-padding">Full expression:</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'fullExpressionCode(this);')); ?>
                            </td>                            
                        </tr> 
                        <tr>
                            <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00137'); ?></td>
                            <td colspan="2">
                                <?php 
                                echo Form::button(
                                    array(
                                        'class' => 'btn btn-sm red-sunglo', 
                                        'value' => '<i class="fa fa-history"></i>', 
                                        'onclick' => 'bpExpressionCacheClear(\''.$this->metaDataId.'\');'
                                    )
                                ); 
                                ?>
                            </td>
                        </tr> 
                        <tr class="external_tr">
                            <td class="left-padding">Action:</td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'external_action_type',
                                        'id' => 'external_action_type',
                                        'data' => array(
                                            array(
                                                'code' => 'console',
                                                'name' => 'Console'
                                            ), 
                                            array(
                                                'code' => 'delete',
                                                'name' => 'Delete'
                                            )
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['ACTION_TYPE']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="internal_tr">
                            <td class="left-padding">Action:</td>
                            <td colspan="2">
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
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['ACTION_TYPE']
                                    )
                                );
                                ?>
                            </td>
                        </tr>              
                        <tr>
                            <td class="left-padding"><?php echo $this->lang->line('META_00028'); ?></td>
                            <td colspan="2">
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
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px;" class="left-padding">Notification:</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setNotification(this);')); ?>
                                <div id="dialog-setNotification" style="display: none"></div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="bp_link_other_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>            
                        <tr>
                            <td style="width: 170px" class="left-padding">
                                <label for="processName"><?php echo $this->lang->line('META_00029'); ?></label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'processName',
                                        'id' => 'processName',
                                        'class' => 'form-control globeCodeInput',
                                        'value' => $this->bpRow['PROCESS_NAME']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="methodActionBtn"><?php echo $this->lang->line('META_00069'); ?></label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'methodActionBtn',
                                        'id' => 'methodActionBtn',
                                        'class' => 'form-control globeCodeInput',
                                        'value' => $this->bpRow['ACTION_BTN']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-banner-manager">
                            <td class="left-padding">Banner manager:</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'bannerManager(this);')); ?>
                                <div id="dialog-banner-manager-config"></div>
                            </td>
                        </tr> 
                        <tr>
                            <td class="left-padding">Theme:</td>
                            <td colspan="2">
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
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['THEME']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="columnCount">
                                    <?php echo $this->lang->line('META_00117'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'columnCount',
                                        'id' => 'columnCount',
                                        'class' => 'form-control longInit',
                                        'value' => $this->bpRow['COLUMN_COUNT']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="tabColumnCount">
                                    <?php echo $this->lang->line('META_00070'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'tabColumnCount',
                                        'id' => 'tabColumnCount',
                                        'class' => 'form-control longInit',
                                        'value' => $this->bpRow['TAB_COLUMN_COUNT']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="labelWidth">
                                    Label width:
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'labelWidth',
                                        'id' => 'labelWidth',
                                        'class' => 'form-control',
                                        'value' => $this->bpRow['LABEL_WIDTH']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isTreeview">
                                    Tree view:
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isTreeview',
                                            'id' => 'isTreeview',
                                            'class' => 'notuniform', 
                                            'value' => '1',
                                            'saved_val' => $this->bpRow['IS_TREEVIEW']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding"><?php echo $this->lang->line('META_00176'); ?></td>
                            <td colspan="2">
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
                                            )
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['WINDOW_TYPE']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-window-size">
                            <td class="left-padding"><?php echo $this->lang->line('META_00204'); ?></td>
                            <td colspan="2">
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
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['WINDOW_SIZE']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-window-width">
                            <td class="left-padding">
                                <label for="windowWidth">
                                    <?php echo $this->lang->line('META_00148'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'windowWidth',
                                        'id' => 'windowWidth',
                                        'class' => 'form-control longInit',
                                        'value' => $this->bpRow['WINDOW_WIDTH']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-window-height">
                            <td class="left-padding">
                                <label for="windowHeight">
                                    <?php echo $this->lang->line('META_00100'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'windowHeight',
                                        'id' => 'windowHeight',
                                        'class' => 'form-control',
                                        'value' => $this->bpRow['WINDOW_HEIGHT']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label>Default get process:</label>
                            </td>
                            <td colspan="2">
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
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label>Process theme:</label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'themeCode',
                                        'id' => 'themeCode',
                                        'data' => array(
                                            array(
                                                'code' => 'paperclip1',
                                                'name' => 'Paper clip 1'
                                            ), 
                                            array(
                                                'code' => 'paperclip2',
                                                'name' => 'Paper clip 2'
                                            ), 
                                            array(
                                                'code' => 'paperclip3',
                                                'name' => 'Paper clip 3'
                                            )
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['THEME_CODE']
                                    )
                                );
                                ?>
                            </td>
                            <?php 
                            //echo Form::button(array('class' => 'btn btn-sm blue-madison', 'value' => '<i class="fa fa-eye"></i>', 'onclick' => 'viewTheme(this);')); 
                            //echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'themeParamAttributes(this);')); 
                            ?>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label>Skin:</label>
                            </td>
                            <td colspan="2">
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
                                                'code' => 'min-formcontrol',
                                                'name' => 'Skin #4 small'
                                            )
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2',
                                        'value' => $this->bpRow['SKIN']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Run Mode:</td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'runMode',
                                        'id' => 'runMode',
                                        'class' => 'form-control',
                                        'value' => $this->bpRow['RUN_MODE']
                                    )
                                );
                                ?>
                            </td>                           
                        </tr> 
                        <tr>
                            <td class="left-padding">
                                <label for="helpContentId"><?php echo $this->lang->line('MET_99990359') ?> (CONTENT ID):</label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'helpContentId',
                                        'id' => 'helpContentId',
                                        'placeholder' => 'Контентийн ID',
                                        'class' => 'form-control longInit',
                                        'value' => $this->bpRow['HELP_CONTENT_ID']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isShowPrevNext">
                                    <?php echo $this->lang->line('META_00071'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isShowPrevNext',
                                            'id' => 'isShowPrevNext',
                                            'class' => 'notuniform', 
                                            'value' => '1',
                                            'saved_val' => $this->bpRow['IS_SHOW_PREVNEXT']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label>
                                    <?php echo $this->lang->line('setting_mobile_theme'); ?>:
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::select(array(
                                    'name' => 'mobileTheme',
                                    'id' => 'mobileTheme',
                                    'data' => $this->widgetData,
                                    'op_value' => 'CODE',
                                    'op_text' => 'NAME',
                                    'class' => 'form-control select2', 
                                    'value' => $this->bpRow['MOBILE_THEME']
                                ));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label>
                                    Tag:
                                </label>
                            </td>
                            <td colspan="2">
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
                                            ), 
                                            array(
                                                'code' => 'flm',
                                                'name' => 'FLM'
                                            ) 
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2', 
                                        'value' => $this->bpRow['WORKIN_TYPE']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isRule">Is Rule</label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isRule',
                                            'id' => 'isRule',
                                            'class' => 'notuniform', 
                                            'value' => '1',
                                            'saved_val' => $this->bpRow['IS_RULE']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isOfflineMode">Is offline mode:</label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isOfflineMode',
                                            'id' => 'isOfflineMode',
                                            'class' => 'notuniform', 
                                            'value' => '1',
                                            'saved_val' => $this->bpRow['IS_OFFLINE_MODE']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isOfflineMode">JSON config:</label>
                            </td>
                            <td colspan="2">
                                <button type="button" class="btn btn-sm purple-plum" onclick="bpJsonConfig(this);">...</button>
                                <?php 
                                $savedOpt = $this->bpRow['JSON_CONFIG'];
                                ?>
                                <input type="hidden" name="jsonConfig" value="<?php echo $savedOpt ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="bp_link_addons_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>
                       <tr>
                            <td style="width: 170px" class="left-padding">
                                <label for="isAddOnPhoto">
                                    <?php echo $this->lang->line('META_00072'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isAddOnPhoto',
                                            'id' => 'isAddOnPhoto',
                                            'class' => 'isAddonCheck',
                                            'value' => '1',
                                            'saved_val' => ($this->bpRow['IS_ADDON_PHOTO'] == '2' ? 1 : $this->bpRow['IS_ADDON_PHOTO'])
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="<?php echo ($this->bpRow['IS_ADDON_PHOTO'] == '0' || is_null($this->bpRow['IS_ADDON_PHOTO'])) ? 'display: none;' : ''; ?>">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddOnPhotoRequired',
                                                'id' => 'isAddOnPhotoRequired',
                                                'value' => '1',
                                                'saved_val' => ($this->bpRow['IS_ADDON_PHOTO'] == '2' ? 1 : 0)
                                            )
                                        );
                                        ?>
                                        <?php echo $this->lang->line('metadata_required'); ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                       <tr>
                            <td class="left-padding">
                                <label for="isAddOnFile">
                                    <?php echo $this->lang->line('META_00149'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isAddOnFile',
                                            'id' => 'isAddOnFile',
                                            'class' => 'isAddonCheck',
                                            'value' => '1',
                                            'saved_val' => ($this->bpRow['IS_ADDON_FILE'] == '2' ? 1 : $this->bpRow['IS_ADDON_FILE'])
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="<?php echo ($this->bpRow['IS_ADDON_FILE'] == '0' || is_null($this->bpRow['IS_ADDON_FILE'])) ? 'display: none;' : ''; ?>">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddOnFileRequired',
                                                'id' => 'isAddOnFileRequired',
                                                'value' => '1',
                                                'saved_val' => ($this->bpRow['IS_ADDON_FILE'] == '2' ? 1 : 0)
                                            )
                                        );
                                        ?>
                                        <?php echo $this->lang->line('metadata_required'); ?>
                                    </label>
                                </div>
                            </td>
                         
                        </tr>
                       <tr>
                            <td class="left-padding">
                                <label for="isAddOnComment">
                                    <?php echo $this->lang->line('META_00150'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isAddOnComment',
                                            'id' => 'isAddOnComment',
                                            'class' => 'isAddonCheck',
                                            'value' => '1',
                                            'saved_val' => ($this->bpRow['IS_ADDON_COMMENT'] == '2' ? 1 : $this->bpRow['IS_ADDON_COMMENT'])
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="<?php echo ($this->bpRow['IS_ADDON_COMMENT'] == '0' || is_null($this->bpRow['IS_ADDON_COMMENT'])) ? 'display: none;' : ''; ?>">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddOnCommentRequired',
                                                'id' => 'isAddOnCommentRequired',
                                                'value' => '1',
                                                'saved_val' => ($this->bpRow['IS_ADDON_COMMENT'] == '2' ? 1 : 0)
                                            )
                                        );
                                        ?>
                                        <?php echo $this->lang->line('metadata_required'); ?>
                                        <?php
                                            echo Form::text(
                                                array(
                                                    'name' => 'isAddOnCommentType',
                                                    'id' => 'isAddOnCommentType',
                                                    'value' => $this->bpRow['IS_ADDON_COMMENT_TYPE'],
                                                    'placeholder' => 'exc: tab, bottom'
                                                )
                                            );
                                            ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        
                       <tr>
                            <td class="left-padding">
                                <label for="isAddOnLog">
                                    <?php echo $this->lang->line('META_00205'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isAddOnLog',
                                            'id' => 'isAddOnLog',
                                            'class' => 'isAddonCheck',
                                            'value' => '1',
                                            'saved_val' => ($this->bpRow['IS_ADDON_LOG'] == '2' ? 1 : $this->bpRow['IS_ADDON_LOG'])
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="<?php echo ($this->bpRow['IS_ADDON_LOG'] == '0' || is_null($this->bpRow['IS_ADDON_LOG'])) ? 'display: none;' : ''; ?>">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddOnLogRequired',
                                                'id' => 'isAddOnLogRequired',
                                                'value' => '1',
                                                'saved_val' => ($this->bpRow['IS_ADDON_LOG'] == '2' ? 1 : 0)
                                            )
                                        );
                                        ?>
                                        <?php echo $this->lang->line('metadata_required'); ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                       <tr>
                            <td class="left-padding">
                                <label for="isAddonRelation">
                                    <?php echo $this->lang->line('META_00206'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isAddonRelation',
                                            'id' => 'isAddonRelation',
                                            'class' => 'isAddonCheck',
                                            'value' => '1',
                                            'saved_val' => ($this->bpRow['IS_ADDON_RELATION'] == '2' ? 1 : $this->bpRow['IS_ADDON_RELATION'])
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="<?php echo ($this->bpRow['IS_ADDON_RELATION'] == '0' || is_null($this->bpRow['IS_ADDON_RELATION'])) ? 'display: none;' : ''; ?>">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddonRelationRequired',
                                                'id' => 'isAddonRelationRequired',
                                                'value' => '1',
                                                'saved_val' => ($this->bpRow['IS_ADDON_RELATION'] == '2' ? 1 : 0)
                                            )
                                        );
                                        ?>
                                        <?php echo $this->lang->line('metadata_required'); ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isAddonMvRelation">
                                    <?php echo $this->lang->line('ea_meta_0012'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isAddonMvRelation',
                                            'id' => 'isAddonMvRelation',
                                            'class' => 'isAddonCheck',
                                            'value' => '1',
                                            'saved_val' => ($this->bpRow['IS_ADDON_MV_RELATION'] == '2' ? 1 : $this->bpRow['IS_ADDON_MV_RELATION'])
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="<?php echo ($this->bpRow['IS_ADDON_MV_RELATION'] == '0' || is_null($this->bpRow['IS_ADDON_MV_RELATION'])) ? 'display: none;' : ''; ?>">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddonMvRelationRequired',
                                                'id' => 'isAddonMvRelationRequired',
                                                'value' => '1',
                                                'saved_val' => ($this->bpRow['IS_ADDON_MV_RELATION'] == '2' ? 1 : 0)
                                            )
                                        );
                                        ?>
                                        <?php echo $this->lang->line('metadata_required'); ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isAddonWfmLog">
                                    <?php echo $this->lang->line('META_00207'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isAddonWfmLog',
                                            'id' => 'isAddonWfmLog',
                                            'class' => 'isAddonCheck',
                                            'value' => '1',
                                            'saved_val' => ($this->bpRow['IS_ADDON_WFM_LOG'] == '1' ? 1 : $this->bpRow['IS_ADDON_WFM_LOG'])
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="<?php echo ($this->bpRow['IS_ADDON_WFM_LOG'] == '0' || is_null($this->bpRow['IS_ADDON_WFM_LOG'])) ? 'display: none;' : ''; ?>">
                                        <?php
                                        echo Form::text(
                                            array(
                                                'name' => 'isAddonWfmLogType',
                                                'id' => 'isAddonWfmLogType',
                                                'value' => $this->bpRow['IS_ADDON_WFM_LOG_TYPE'],
                                                'placeholder' => 'exc: tab, bottom'
                                            )
                                        );
                                        ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isWidget">
                                    Is widget
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isWidget',
                                            'id' => 'isWidget',
                                            'value' => '1',
                                            'saved_val' => $this->bpRow['IS_WIDGET']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isToolsBtn">
                                    Is tools btn
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isToolsBtn',
                                            'id' => 'isToolsBtn',
                                            'value' => '1',
                                            'saved_val' => $this->bpRow['IS_TOOLS_BTN']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isBpmnTool">
                                    Is bpmn tool
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isBpmnTool',
                                            'id' => 'isBpmnTool',
                                            'value' => '1',
                                            'saved_val' => $this->bpRow['IS_BPMN_TOOL']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isSaveViewLog">
                                    Is view log
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isSaveViewLog',
                                            'id' => 'isSaveViewLog',
                                            'value' => '1',
                                            'saved_val' => $this->bpRow['IS_SAVE_VIEW_LOG']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="bp_link_links_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>
                        <tr>
                            <td style="width: 170px; height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00152'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1456925359425&dv[metadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00074'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1466666886047&dv[metadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00001'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1477923010065109&dv[metadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00153'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdprocessflow/metaProcessWorkflow/<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('Layout'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1605706545016&dv[metadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('metadata_hide_column'); ?>/<?php echo $this->lang->line('META_00006'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1517559996496802&dv[id][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                Rule
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1544590518677&dv[mainProcessId][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>  
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                Customer field config
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1599817470960&dv[metadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00120'); ?></label>
                            </td>
                            <td colspan="2">
                                <button type="button" class="btn btn-sm purple-plum" onclick="metaPHPExportById('<?php echo $this->metaDataId; ?>');"><i class="far fa-download"></i></button>
                            </td>
                        </tr>
                    </tbody>    
                </table>
            </div>    
        </div>
    </div>
</div>
<?php echo Form::hidden(array('name' => 'oldMethodName', 'value' => $this->bpRow['METHOD_NAME'])); ?>

<script type="text/javascript">

$(function () {
    $("#bp_process_type").on("change", function () {
        var val = $(this).val();
        
        $('label[for="methodName"]').text('<?php echo $this->lang->line('META_00027'); ?>');
        
        if (val == 'internal' || val == 'endtoend') {
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

    $("#windowSize").on("change", function () {
        visibleWindowSizeAttr();
    });
    $("#windowSize").trigger("change");

    $('.isAddonCheck').click(function() {
        var $this = $(this), $addonRequiredLabel = $this.closest('.checkbox-list').find('.addonRequiredLabel');
        if (!$this.closest('span').hasClass('checked')) {
            $addonRequiredLabel.show();
        } else {
            $addonRequiredLabel.hide();
            $addonRequiredLabel.find('input[type=checkbox]').attr('checked', false);
            $addonRequiredLabel.find('input[type=checkbox]').closest('span').removeClass('checked');
        }
    });
});

function setGetDataProcessParam(elem) {
    var $dialogName = 'dialog-getdata-process-param';

    if ($("#" + $dialogName).children().length > 0) {
        $("#" + $dialogName).dialog({
            appendTo: "form#editMetaSystemForm",
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
                    Core.blockUI({message: 'Loading...', boxed: true});
                },
                success: function (data) {
                    $("#" + $dialogName).empty().append(data.html);
                    $("#" + $dialogName).dialog({
                        appendTo: "form#editMetaSystemForm",
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
function visibleWindowSizeAttr() {
    var windowSize = $("#windowSize").val();
    if (windowSize === 'custom') {
        $(".datamodel-window-height, .datamodel-window-width").show();
    } else {
        $(".datamodel-window-height, .datamodel-window-width").hide();
    }
}
function bannerManager() {
    var $dialogName = 'dialog-banner-manager-config';
    $.ajax({
        type: 'post',
        url: 'mdmeta/bannerManagerList',
        dataType: "json",
        data: {metaDataId: $("input[name='metaDataId']").val()},
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            $("#" + $dialogName).empty().append(data.html);
            $("#" + $dialogName).dialog({
                appendTo: "form#editMetaSystemForm",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 1200,
                minWidth: 1200,
                height: "auto",
                modal: true,
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                        $.ajax({
                            type: 'post',
                            url: 'mdmeta/saveProcessContent',
                            data: $("#process-content-form", "#" + $dialogName).serialize(),
                            dataType: "json",
                            beforeSend: function () {
                                Core.blockUI({
                                    message: 'Loading...',
                                    boxed: true
                                });
                            },
                            success: function (data) {
                                if (data.status === 'success') {
                                    new PNotify({
                                        title: 'Success',
                                        text: data.message,
                                        type: 'success',
                                        sticker: false
                                    });
                                    $("#" + $dialogName).empty().dialog('close');
                                }
                                Core.unblockUI();
                            },
                            error: function () {
                                alert("Error");
                            }
                        });
                    }},
                    {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                        $("#" + $dialogName).empty().dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function () {
        Core.initAjax($("#" + $dialogName));
    });
}

function viewTheme(elem) {
    var _row = $(elem).closest("tr");
    var _themeCode = _row.find("select[name='themeCode']").val();
    if (_themeCode.length > 0) {
        var $dialogName = 'dialog-theme-view';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $("#" + $dialogName).empty().append("<div class=\"col-md-12\"><img src=\"middleware/views/webservice/themes/" + _themeCode + "/thumb.png\" style=\"max-width:100%;\"></div>");
        $("#" + $dialogName).dialog({
            appendTo: "body",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: _themeCode,
            width: 600,
            minWidth: 600,
            height: 500,
            modal: false,
            buttons: [
                {text: '<?php echo $this->lang->line('META_00033'); ?>', class: 'btn btn-sm blue-hoki', click: function () {
                    $("#" + $dialogName).empty().dialog('close');
                    $("#" + $dialogName).dialog('destroy').remove();
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');
    }
}

function themeParamAttributes(elem) {
    var _row = $(elem).closest("tr");
    var _themeCode = _row.find("select[name='themeCode']").val();
    if (_themeCode.length > 0) {
        var $dialogName = 'dialog-theme-param';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        $.ajax({
            type: 'post',
            url: 'mdmeta/themeFieldMap',
            dataType: "json",
            data: {metaDataId: $("input[name='metaDataId']").val()},
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().append(data.html);
                $("#" + $dialogName).dialog({
                    appendTo: "body",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    height: 570,
                    modal: false,
                    buttons: [
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $("#" + $dialogName).empty().dialog('close');
                            $("#" + $dialogName).dialog('destroy').remove();
                        }}
                    ]
                });
                $("#" + $dialogName).dialog('open');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initAjax($("#" + $dialogName));
        });
    }
}

function fullExpressionCode(elem) {

    var $dialogName = 'dialog-fullExpcriteria-<?php echo $this->metaDataId; ?>';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('form#editMetaSystemForm');
    }
    var $dialog = $('#' + $dialogName);

    if ($dialog.children().length > 0) {
        var $detachedChildren = $dialog.children().detach();
        $dialog.dialog({
            appendTo: "form#editMetaSystemForm",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Full expression criteria',
            width: 1200,
            minWidth: 1200,
            height: 'auto',
            modal: false,
            open: function(){
                $detachedChildren.appendTo($dialog);
            },
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                    fullExpressionEditor.save();
                    fullExpressionOpenEditor.save();
                    fullExpressionVarFncEditor.save();
                    fullExpressionSaveEditor.save();  
                    fullExpressionAfterSaveEditor.save();

                    $dialog.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $dialog.dialog('close');
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
        $dialog.dialog('open');
        $dialog.dialogExtend('maximize');

    } else {

        $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
            $.ajax({
                type: 'post',
                url: 'mdmeta/setProcessFullExpressionCriteria',
                data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
                dataType: 'json',
                beforeSend: function() {
                    Core.blockUI({
                        message: 'Loading...',
                        boxed: true
                    });
                    if ($("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length == 0) {
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                    }
                },
                success: function(data) {
                    $dialog.empty().append(data.Html);
                    var $detachedChildren = $dialog.children().detach();
                    $dialog.dialog({
                        appendTo: "form#editMetaSystemForm",
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.Title,
                        width: 1200,
                        minWidth: 1200,
                        height: 'auto',
                        modal: false,
                        open: function(){
                            $detachedChildren.appendTo($dialog);
                        },
                        buttons: [
                            {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {

                                fullExpressionEditor.save();
                                fullExpressionOpenEditor.save();
                                fullExpressionVarFncEditor.save();
                                fullExpressionSaveEditor.save();
                                fullExpressionAfterSaveEditor.save();

                                $dialog.dialog('close');
                            }},
                            {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                                $dialog.dialog('close');
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
                        }, 
                        "maximize": function() { 
                            var dialogHeight = $dialog.height();
                            $dialog.find("div.table-scrollable").css({"height": (dialogHeight-22)+'px', "max-height": (dialogHeight-22)+'px'});
                            $dialog.find(".CodeMirror").css("height", (dialogHeight - 53)+'px');
                        }, 
                        "restore": function() { 
                            var dialogHeight = $dialog.height();
                            $dialog.find("div.table-scrollable").css({"height": (dialogHeight-25)+'px', "max-height": (dialogHeight-25)+'px'});
                            $dialog.find(".CodeMirror").css("height", (dialogHeight - 54)+'px');
                        }
                    });
                    $dialog.dialog('open');
                    $dialog.dialogExtend('maximize');
                    Core.unblockUI();
                }
            });
        });
    }
}

function setNotification(elem) {
    var outputMetaDataId = $("#outputMetaDataId").val();
    var $dialogName = 'dialog-setNotification';
    if ($("#" + $dialogName).children().length > 0) {
        $("#" + $dialogName).dialog({
            appendTo: "form#editMetaSystemForm",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Set notification',
            width: 1200,
            minWidth: 1200,
            height: "auto",
            modal: false,
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                    saveNotificaitonConfig();
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $("#" + $dialogName).dialog('close');
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
        $("#" + $dialogName).dialogExtend("maximize");
    } else {
        var processMetaDataId = '<?php echo $this->metaDataId; ?>';
        $.ajax({
            type: 'post',
            url: 'mdnotification/notificationConfigWindow',
            data: {'processMetaDataId' : processMetaDataId, 'outputMetaDataId' : outputMetaDataId},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().append(data.Html);
                $("#" + $dialogName).dialog({
                    appendTo: "form#editMetaSystemForm",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 800,
                    minWidth: 800,
                    height: "auto",
                    modal: false,
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                            saveNotificaitonConfig($dialogName);
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $("#" + $dialogName).dialog('close');
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
                $("#" + $dialogName).dialogExtend("maximize");
                Core.unblockUI();
            }
        }).done(function () {
            Core.initAjax($("#" + $dialogName));
        });
    }
}

function saveNotificaitonConfig($dialogName) {
    var processId = '<?php echo $this->metaDataId; ?>';
    var data = JSON.stringify($('#notificationForm').serializeArray());
    if (data.length > 0) {
        $.ajax({
            type: 'post',
            url: 'mdnotification/saveNotificaitonConfig',
            data: {'data' : data, 'processId' : processId},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    animate: true
                });
            },
            success: function (data) {
                Core.unblockUI();
                $("#" + $dialogName).dialog('close');
            }
        }).done(function () {
            $("#" + $dialogName).dialog('close');
        });
    }
}

function manageTheme(elem){
    var $dialogName = 'dialog-manage-theme';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }

    var inputMetaDataId = $("#inputMetaDataId").val();

    $.ajax({
        type: 'post',
        url: 'mdmetadata/getThemeManageDialog',
        data: {inputMetaDataId: inputMetaDataId, metaDataId: '<?php echo $this->metaDataId; ?>'},
        dataType: "json",
        beforeSend: function() {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data) {
            if (typeof data.errorMessage === 'undefined') {

                if ($("form#addMetaSystemForm").length > 0) {
                    var appendToForm = "form#addMetaSystemForm";
                } else {
                    var appendToForm = "form#editMetaSystemForm";
                }

                $("#" + $dialogName).empty().append(data.Html);
                $("#" + $dialogName).dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1200,
                    minWidth: 1200,
                    height: 600,
                    modal: false,
                    close: function () {                
                        $("#"+$dialogName).empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            var $appendToForm = $(appendToForm);
                            $appendToForm.find("#metaThemeInput").remove();
                            $appendToForm.find("#isMultiLang").remove();
                            $appendToForm.append("<input type='hidden' id='metaThemeInput' name='metaThemeInput' value='" + JSON.stringify(metaThemeArray) + "'>");
                            $appendToForm.append($("#" + $dialogName).find('#isMultiLang')[0]);
                            $appendToForm.find("#isMultiLang").addClass('hide');
                            $("#"+$dialogName).dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $("#"+$dialogName).dialog('close');
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
                $("#"+$dialogName).dialog('open');
                $("#"+$dialogName).dialogExtend("maximize");

            } else {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.errorMessage,
                    type: 'error',
                    sticker: false
                });
            }
            Core.unblockUI();
        }
    }).complete(function(){
        Core.initAjax($("#" + $dialogName));
        Core.unblockUI();
    });
}
function setParamAttributesNew(elem) {

    Core.blockUI({
        message: 'Loading...', 
        boxed: true
    });

    var $dialogName = 'dialog-paramattributes-new';
    var $dialogContainer = $("#" + $dialogName);
    
    if ($dialogContainer.children().length > 0) {

        var $detachedChildren = $dialogContainer.children().detach();

        $dialogContainer.dialog({
            appendTo: "form#editMetaSystemForm",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: '<?php echo $this->lang->line('META_00046'); ?>',
            width: 1200,
            minWidth: 1200,
            height: "auto",
            modal: false,
            open: function(){
                $detachedChildren.appendTo($dialogContainer);
                Core.unblockUI();
            }, 
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                    $dialogContainer.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $dialogContainer.dialog('close');
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
            }, 
            "maximize": function() { 
                var dialogHeight = $dialogContainer.height();
                $dialogContainer.find("div#fz-process-params-option").css({"height": (dialogHeight - 41)+'px'});
                $dialogContainer.find("div.params-addon-config").css({"height": (dialogHeight - 41)+'px'});
            }
        });
        $dialogContainer.dialog('open');
        $dialogContainer.dialogExtend('maximize');

    } else {

        $.ajax({
            type: 'post',
            url: 'mdmetadata/setParamAttributesEditModeNew',
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
            dataType: "json",
            beforeSend: function () {
                if (!$("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length){
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js');
                }
                
                if (!$("link[href='assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css']").length) {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                    $.cachedScript("assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1");
                }
            },
            success: function (data) {

                $dialogContainer.empty().append(data.Html);

                var $detachedChildren = $dialogContainer.children().detach();

                $dialogContainer.dialog({
                    appendTo: "form#editMetaSystemForm",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1200,
                    minWidth: 1200,
                    height: "auto",
                    modal: false,
                    open: function(){
                        $detachedChildren.appendTo($dialogContainer);
                        Core.unblockUI();
                    }, 
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                            $dialogContainer.dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $dialogContainer.dialog('close');
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
                    }, 
                    "maximize": function() { 
                        var dialogHeight = $dialogContainer.height();
                        $dialogContainer.find("div#fz-process-params-option").css({"height": (dialogHeight - 41)+'px'});
                        $dialogContainer.find("div.params-addon-config").css({"height": (dialogHeight - 41)+'px'});
                    }
                });
                $dialogContainer.dialog('open');
                $dialogContainer.dialogExtend('maximize');
            }
            
        }).done(function () {
            Core.initNumber($dialogContainer);
        });
    }
}
function setOutputParamAttributesNew(elem) {

    Core.blockUI({
        message: 'Loading...', 
        boxed: true
    });
    var $dialogName = 'dialog-outputparamattributes-new';

    if ($("#" + $dialogName).children().length > 0) {

        var $dialogContainer = $("#" + $dialogName);
        var $detachedChildren = $dialogContainer.children().detach();

        $dialogContainer.dialog({
            appendTo: 'form#editMetaSystemForm',
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: '<?php echo $this->lang->line('META_00104'); ?>',
            width: 1200,
            minWidth: 1200,
            height: 'auto',
            modal: false,
            open: function(){
                $detachedChildren.appendTo($dialogContainer);
                Core.unblockUI();
            }, 
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                    $dialogContainer.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $dialogContainer.dialog('close');
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
            }, 
            "maximize": function() { 
                var dialogHeight = $dialogContainer.height();
                $dialogContainer.find('div#fz-process-output-params-option').css({'height': (dialogHeight - 41)+'px'});
                $dialogContainer.find('div.params-addon-config').css({'height': (dialogHeight - 41)+'px'});
            }
        });
        $dialogContainer.dialog('open');
        $dialogContainer.dialogExtend('maximize');

    } else {

        $.ajax({
            type: 'post',
            url: 'mdmetadata/setOutputParamAttributesEditModeNew',
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
            dataType: 'json',
            success: function (data) {

                $("#" + $dialogName).empty().append(data.Html);

                var $dialogContainer = $("#" + $dialogName);
                var $detachedChildren = $dialogContainer.children().detach();

                $dialogContainer.dialog({
                    appendTo: "form#editMetaSystemForm",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 1200,
                    minWidth: 1200,
                    height: "auto",
                    modal: false,
                    open: function(){
                        $detachedChildren.appendTo($dialogContainer);
                        Core.unblockUI();
                    }, 
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                            $dialogContainer.dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $dialogContainer.dialog('close');
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
                    }, 
                    "maximize": function() { 
                        var dialogHeight = $dialogContainer.height();
                        $dialogContainer.find('div#fz-process-output-params-option').css({'height': (dialogHeight - 41)+'px'});
                        $dialogContainer.find('div.params-addon-config').css({'height': (dialogHeight - 41)+'px'});
                    }
                });
                $dialogContainer.dialog('open');
                $dialogContainer.dialogExtend('maximize');
            },
            error: function () {
                alert("Error");
            }
        });
    }
}
var formatExpOpts = {
    indent_size: 4,
    indent_char: ' ',
    max_preserve_newlines: 5,
    preserve_newlines: true,
    keep_array_indentation: false,
    break_chained_methods: false,
    indent_scripts: 'normal',
    brace_style: 'collapse',
    space_before_conditional: true, 
    unescape_strings: false, 
    jslint_happy: false,
    end_with_newline: false,
    wrap_line_length: 0,
    indent_inner_html: false,
    comma_first: false,
    e4x: false,
    indent_empty_lines: false
};
function bpJsonConfig(elem) {

    var $dialogName = 'dialog-bpJsonConfigExpcriteria';

    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }

    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.css"/>');

        $("#" + $dialogName).empty().html(
            '<div class="row">'+
                '<div class="col-md-12">'+
                '<?php
                echo Form::textArea(
                    array(
                        'name' => 'jsonConfigRowExpressionString_set',
                        'id' => 'jsonConfigRowExpressionString_set',
                        'class' => 'form-control ace-textarea',
                        'value' => '',
                        'spellcheck' => 'false',
                        'style' => 'width: 100%;'
                    )
                );
                ?>'+
                '</div>'+
            '</div>'
        );

        $("#" + $dialogName).find('#jsonConfigRowExpressionString_set').val($(elem).closest('tr').find('input[name="jsonConfig"]').val());

        $("#" + $dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'JSON CONFIG /Alt+T/',
            width: 900,
            minWidth: 900,
            height: "auto",
            modal: true,
            position: {my:'top', at:'top+50'},
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green', click: function() {
                    jsonConfigExpressionRowEditor.save();
                    
                    $(elem).closest('tr').find('input[name="jsonConfig"]').val($('#jsonConfigRowExpressionString_set').val());
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $("#" + $dialogName).dialog('close');
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

        var jsonConfigExpressionRowEditor = CodeMirror.fromTextArea(document.getElementById("jsonConfigRowExpressionString_set"), {
            mode: 'javascript',
            styleActiveLine: true,
            lineNumbers: true,
            lineWrapping: true,
            matchBrackets: true,
            autoCloseBrackets: true,
            indentUnit: 4,
            theme: 'material', 
            extraKeys: {
                "Alt-T": function(cm){ 
                    var formattedExpression = js_beautify(cm.getValue(), formatExpOpts);
                    cm.setValue(formattedExpression);
                },                 
                "F11": function(cm) {
                    cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                },
                "Esc": function(cm) {
                    if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                }
            }
        });
        setTimeout(function() {
            jsonConfigExpressionRowEditor.refresh();
        }, 1);        

        $("#" + $dialogName).dialog('open');
    });
}
</script>
