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
                                        'data' => (new Mdmetadata())->getProcessSubTypeListByAddMode(),
                                        'text' => 'notext',
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="external_tr pf-bp-wsurl">
                            <td style="width: 170px" class="left-padding">
                                <label for="wsUrl">
                                    <?php echo $this->lang->line('META_00067'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'wsUrl',
                                        'id' => 'wsUrl',
                                        'class' => 'form-control', 
                                        'value' => 'http://localhost:8080/erp-services/SoapWS?wsdl'
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
                                        'value' => '9'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="external_tr">
                            <td class="left-padding">
                                <label for="className">
                                    <?php echo $this->lang->line('META_00045'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'className',
                                        'id' => 'className',
                                        'class' => 'form-control'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="external_tr pf-bp-methodname">
                            <td class="left-padding">
                                <label for="methodName">
                                    <?php echo $this->lang->line('META_00027'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'methodName',
                                        'id' => 'methodName',
                                        'class' => 'form-control'
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
                                        <input id="systemMetaGroupId" name="systemMetaGroupId" type="hidden">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                        </span>     
                                    </div>
                                </div>     
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00046'); ?></td>
                            <td colspan="2">
                                <input id="inputMetaDataId" name="inputMetaDataId" type="hidden">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setParamAttributesNew(this);')); ?>
                                <div id="dialog-paramattributes-new" style="display: none"></div>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00104'); ?></td>
                            <td colspan="2">
                                <input id="outputMetaDataId" name="outputMetaDataId" type="hidden">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setOutputParamAttributesNew(this);')); ?>
                                <div id="dialog-outputparamattributes-new" style="display: none"></div>
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
                                        'class' => 'form-control select2'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="internal_tr">
                            <td class="left-padding">Action:</td>
                            <td colspan="2">
                                <?php
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
                                        'value' => 'insert'
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
                                        <input id="refMetaGroupId" name="refMetaGroupId" type="hidden">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                        </span>     
                                    </div>
                                </div>  
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
                                        'class' => 'form-control globeCodeInput'
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
                                        'name'  =>  'methodActionBtn',
                                        'id'    =>  'methodActionBtn',
                                        'class' =>  'form-control globeCodeInput', 
                                    )
                                );
                                ?>
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
                                        'class' => 'form-control select2'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                              <label for="methodActionBtn">Тheme:</label>
                            </td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'manageTheme(this);')); ?>
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
                                        'value' => 1
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
                                        'value' => 1
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
                                        'value' => 20
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
                                            'value' => '1'
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
                                        'class' => 'form-control select2'
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
                                        'class' => 'form-control select2'
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
                                        'class' => 'form-control longInit'
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
                                        'class' => 'form-control'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label>
                                    Skin:
                                </label>
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
                                        'class' => 'form-control select2'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label>
                                    ReportTemplate:
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$reportTemplateMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="reportTemplateId" name="reportTemplateId" type="hidden">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                        </span>     
                                    </div>
                                </div> 
                            </td>
                        </tr>
                        <tr class="external_tr">
                            <td class="left-padding">
                                <label for="helpContentId"><?php echo $this->lang->line('MET_99990359'); ?> (CONTENT ID):</label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'helpContentId',
                                        'id' => 'helpContentId',
                                        'placeholder' => 'Контентийн ID',
                                        'class' => 'form-control longInit',
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
                                            'value' => '1'
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label>
                                    Mobile theme:
                                </label>
                            </td>
                            <td colspan="2">
                                <?php 
                                echo Form::select(
                                    array(
                                        'name' => 'mobileTheme',
                                        'id' => 'mobileTheme',
                                        'data' => $this->widgetData,
                                        'op_value' => 'CODE',
                                        'op_text' => 'NAME',
                                        'class' => 'form-control select2'
                                    )
                                );
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
                                        'class' => 'form-control select2' 
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
                                            'value' => '1'
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
                                            'value' => '1'
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
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="display: none;">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddOnPhotoRequired',
                                                'id' => 'isAddOnPhotoRequired',
                                                'value' => '1',
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
                                    Файл
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
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="display: none;">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddOnFileRequired',
                                                'id' => 'isAddOnFileRequired',
                                                'value' => '1',
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
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="display: none;">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddOnCommentRequired',
                                                'id' => 'isAddOnCommentRequired',
                                                'value' => '1',
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
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="display: none;">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddOnLogRequired',
                                                'id' => 'isAddOnLogRequired',
                                                'value' => '1',
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
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="display: none;">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddonRelationRequired',
                                                'id' => 'isAddonRelationRequired',
                                                'value' => '1',
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
                                            'value' => '1'
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="display: none;">
                                        <?php
                                        echo Form::checkbox(
                                            array(
                                                'name' => 'isAddonMvRelationRequired',
                                                'id' => 'isAddonMvRelationRequired',
                                                'value' => '1'
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
                                <div class="checkbox-list mt0 mb0">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isAddonWfmLog',
                                            'id' => 'isAddonWfmLog',
                                            'class' => 'isAddonCheck',
                                            'value' => '1',
                                        )
                                    );
                                    ?>
                                    <label class="checkbox-inline addonRequiredLabel" style="display: none;">
                                        <?php
                                        echo Form::text(
                                            array(
                                                'name' => 'isAddonWfmLogDescription',
                                                'id' => 'isAddonWfmLogDescription',
                                                'value' => '',
                                                'placeholder' => 'exc: tab, bottom'
                                            )
                                        );
                                        ?>
                                    </label>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
$(function(){
    $('#bp_process_type').on('change', function(){
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
    
    $("#action_type").on("change", function(){
        var _this = $(this);
        var _thisVal = _this.val();
        var className = 'MetaDataModelViewBean';
        var _thisProcessType = $("#bp_process_type").val();
        
        if (_this.find('option:selected').text() == 'Duplicate' && _thisProcessType == 'internal') {
            $("#className").val(className);
            $("#methodName").val("getDuplicateRow");
        } else if (_thisVal == 'insert' && _thisProcessType == 'internal') {
            $("#className").val(className);
            $("#methodName").val("createRow");
        } else if (_thisVal == 'update' && _thisProcessType == 'internal') {
            $("#className").val(className);
            $("#methodName").val("updateRow");
        } else if (_thisVal == 'delete' && _thisProcessType == 'internal') {
            $("#className").val(className);
            $("#methodName").val("deleteRow");
        } else if (_thisVal == 'get' && _thisProcessType == 'internal') {
            $("#className").val(className);
            $("#methodName").val("getRow");
        } else if (_thisVal == 'exist' && _thisProcessType == 'internal') {
            $("#className").val(className);
            $("#methodName").val("existRow");
        } else if (_thisVal == 'consolidate' && _thisProcessType == 'internal') {
            $("#className").val(className);
            $("#methodName").val("getConsolidateRow");
        }
    });
    $("#action_type").trigger("change");
    
    $("#windowSize").on("change", function(){
        visibleWindowSizeAttr();
    });
    
    $('.isAddonCheck').click(function() {
        var $this = $(this),
            $addonRequiredLabel = $this.closest('.checkbox-list').find('.addonRequiredLabel');
        if (!$this.closest('span').hasClass('checked')) {
            $addonRequiredLabel.show();
        } else {
            $addonRequiredLabel.hide();
            $addonRequiredLabel.find('input[type=checkbox]').attr('checked', false);
            $addonRequiredLabel.find('input[type=checkbox]').closest('span').removeClass('checked');
        }
    });
});    
function visibleWindowSizeAttr(){
    var windowSize = $("#windowSize").val();
    if (windowSize === 'custom') {
        $(".datamodel-window-height, .datamodel-window-width").show();
    } else {
        $(".datamodel-window-height, .datamodel-window-width").hide();
    }
}   
function manageTheme(elem) {
    var $dialogName = 'dialog-manage-theme';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }

    var inputMetaDataId = $("#inputMetaDataId").val();

    $.ajax({
        type: 'post',
        url: 'mdmetadata/getThemeManageDialog',
        data: {
            inputMetaDataId: inputMetaDataId
        },
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function(data){
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
                    close: function(){
                        $("#" + $dialogName).dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function(){
                            var $appendToForm = $(appendToForm);
                            $appendToForm.find("#metaThemeInput").remove();
                            $appendToForm.find("#isMultiLang").remove();
                            $appendToForm.append("<input type='hidden' id='metaThemeInput' name='metaThemeInput' value='" + JSON.stringify(metaThemeArray) + "'>");
                            $appendToForm.append($("#" + $dialogName).find('#isMultiLang')[0]);
                            $appendToForm.find("#isMultiLang").addClass('hide');
                            $("#"+$dialogName).dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function(){
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
        },
        error: function(){
          alert("Error");
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
    
    if ($("form#addMetaSystemForm").length > 0) {
        var appendToForm = 'form#addMetaSystemForm';
    } else {
        var appendToForm = 'form#editMetaSystemForm';
    }

    if ($("#" + $dialogName).children().length > 0) {

        var $dialogContainer = $("#" + $dialogName);
        var $detachedChildren = $dialogContainer.children().detach();

        $dialogContainer.dialog({
            appendTo: appendToForm,
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
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
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
            data: {metaDataId: ''},
            dataType: 'json',
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
                
                var $dialogContainer = $("#" + $dialogName);
                
                $dialogContainer.empty().append(data.Html);

                var $detachedChildren = $dialogContainer.children().detach();

                $dialogContainer.dialog({
                    appendTo: appendToForm,
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
            Core.initNumber($("#" + $dialogName));
        });
    }
}
function setOutputParamAttributesNew(elem) {

    Core.blockUI({
        message: 'Loading...', 
        boxed: true
    });

    var $dialogName = 'dialog-outputparamattributes-new';
    
    if ($("form#addMetaSystemForm").length > 0) {
        var appendToForm = 'form#addMetaSystemForm';
    } else {
        var appendToForm = 'form#editMetaSystemForm';
    }

    if ($("#" + $dialogName).children().length > 0) {

        var $dialogContainer = $("#" + $dialogName);
        var $detachedChildren = $dialogContainer.children().detach();

        $dialogContainer.dialog({
            appendTo: appendToForm,
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
            "maximize" : function() { 
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
            data: {metaDataId: ''},
            dataType: 'json',
            success: function (data) {

                $("#" + $dialogName).empty().append(data.Html);

                var $dialogContainer = $("#" + $dialogName);
                var $detachedChildren = $dialogContainer.children().detach();

                $dialogContainer.dialog({
                    appendTo: appendToForm,
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
                    "maximize" : function() { 
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
</script>