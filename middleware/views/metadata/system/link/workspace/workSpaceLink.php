<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding"><?php echo Form::label(array('text' => 'Theme', 'for' => 'themeCode')); ?></td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'themeCode',
                            'id' => 'themeCode',
                            'data' => array(
                                array(
                                    'code' => 'theme1',
                                    'name' => 'Theme1'
                                ),
                                array(
                                    'code' => 'theme2',
                                    'name' => 'Theme2'
                                ),
                                array(
                                    'code' => 'theme3',
                                    'name' => 'Theme3'
                                ),
                                array(
                                    'code' => 'theme4',
                                    'name' => 'Theme4 - HR'
                                ),
                                array(
                                    'code' => 'theme5',
                                    'name' => 'Theme5 - Salary'
                                ),
                                array(
                                    'code' => 'theme6',
                                    'name' => 'Theme6 - Blank'
                                ),
                                array(
                                    'code' => 'theme7',
                                    'name' => 'Theme7 - Activity'
                                ),
                                array(
                                    'code' => 'theme8',
                                    'name' => 'Theme8 - Plan'
                                ),
                                array(
                                    'code' => 'theme9',
                                    'name' => 'Theme9 - Teso'
                                ),
                                array(
                                    'code' => 'theme10',
                                    'name' => 'Theme10'
                                ), 
                                array(
                                    'code' => 'theme11',
                                    'name' => 'Theme11'
                                ), 
                                array(
                                    'code' => 'theme12',
                                    'name' => 'Theme12'
                                ), 
                                array(
                                    'code' => 'theme13',
                                    'name' => 'Theme13'
                                ), 
                                array(
                                    'code' => 'theme14',
                                    'name' => 'Theme14'
                                ), 
                                array(
                                    'code' => 'theme15',
                                    'name' => 'Theme15'
                                ), 
                                array(
                                    'code' => 'theme16',
                                    'name' => 'Theme16 Blank'
                                ), 
                                array(
                                    'code' => 'theme17',
                                    'name' => 'Theme17'
                                ), 
                                array(
                                    'code' => 'theme18',
                                    'name' => 'Theme18'
                                ), 
                                array(
                                    'code' => 'theme19',
                                    'name' => 'Theme19'
                                ), 
                                array(
                                    'code' => 'theme20',
                                    'name' => 'Theme20'
                                ), 
                                array(
                                    'code' => 'theme24',
                                    'name' => 'Theme24'
                                ),     
                                array(
                                    'code' => 'theme27',
                                    'name' => 'Theme27 - Засгийн газар'
                                ),                               
                                array(
                                    'code' => 'theme28',
                                    'name' => 'Theme28 - Хаан банк'
                                ),
                                array(
                                    'code' => 'theme29',
                                    'name' => 'Theme29 - Засгийн газар 2'
                                ),  
                                array(
                                    'code' => 'theme31',
                                    'name' => 'Theme31'
                                ), 
                                array(
                                    'code' => 'theme34',
                                    'name' => 'Theme34'
                                ), 
                                array(
                                    'code' => 'theme35',
                                    'name' => 'Theme35 - Profile'
                                ), 
                                array(
                                    'code' => 'shop',
                                    'name' => 'Shop'
                                ), 
                                array(
                                    'code' => 'wizard',
                                    'name' => 'Wizard step'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control select2'
                        )
                    );
                    ?>
                </td>
                <td style="width: 15px; text-align: right">
                    <button type="button" class="btn btn-sm purple-plum" onclick="viewTheme(this);">...</button></div>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo Form::label(array('text' => 'Mobile theme', 'for' => 'mobileTheme')); ?></td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'mobileTheme',
                            'id' => 'mobileTheme',
                            'data' => array(
                                array(
                                    'code' => 'WS_theme1',
                                    'name' => 'WS theme1'
                                ),
                                array(
                                    'code' => 'WS_theme2',
                                    'name' => 'WS theme2'
                                ),
                                array(
                                    'code' => 'WS_theme3',
                                    'name' => 'WS theme3'
                                ),
                                array(
                                    'code' => 'WS_theme4',
                                    'name' => 'WS theme4'
                                ),
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
                <td class="left-padding"><?php echo Form::label(array('text' => 'Dataview', 'for' => 'Dataview')); ?></td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="groupMetaDataId" name="groupMetaDataId" type="hidden">
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
                <td class="left-padding"><?php echo Form::label(array('text' => 'Menu', 'for' => 'Root Menu')); ?></td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$menuMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="menuActionMetaDataId" name="menuActionMetaDataId" type="hidden">
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
                <td class="left-padding"><?php echo Form::label(array('text' => 'Sub Menu', 'for' => 'Sub Menu')); ?></td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$menuMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="menuQuickMetaDataId" name="menuQuickMetaDataId" type="hidden">
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
                <td class="left-padding"><?php echo Form::label(array('text' => 'Default Menu', 'for' => 'Default Menu')); ?></td>
                <td colspan="2">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$menuMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="defaultMenuId" name="defaultMenuId" type="hidden">
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
                <td class="left-padding"><?php echo Form::label(array('text' => $this->lang->line('META_00176'), 'for' => $this->lang->line('META_00176'))); ?></td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'windowType',
                            'id' => 'windowType',
                            'data' => array(
                                array(
                                    'code' => 'standart',
                                    'name' => 'Standart'
                                ),
                                array(
                                    'code' => 'main',
                                    'name' => 'Main window'
                                ), 
                                array(
                                    'code' => 'newtab',
                                    'name' => 'NewTab'
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
                <td class="left-padding"><?php echo Form::label(array('text' => $this->lang->line('META_00204'), 'for' => $this->lang->line('META_00204'))); ?></td>
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
                    <?php echo Form::label(array('text' => $this->lang->line('META_00148'), 'for' => 'windowWidth')); ?>
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
                    <?php echo Form::label(array('text' => $this->lang->line('META_00100'), 'for' => 'windowHeight')); ?>
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
                    <?php echo Form::label(array('text' => 'Action type', 'for' => 'actionType')); ?>
                </td>
                <td colspan="2">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'actionType',
                            'id' => 'actionType',
                            'data' => array(
                                array(
                                    'ID' => 'add',
                                    'NAME' => $this->lang->line('META_00057')
                                ),
                                array(
                                    'ID' => 'edit',
                                    'NAME' => $this->lang->line('META_00058')
                                )
                            ),
                            'op_value' => 'ID',
                            'op_text' => 'NAME',
                            'class' => 'form-control select2'
                        )
                    );
                    ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
    $(function () {
        $("#windowType").on("change", function () {
            visibleWindowTypeAttr();
        });
        $("#windowSize").on("change", function () {
            visibleWindowSizeAttr();
        });
        
    });
    function visibleWindowTypeAttr() {
        var windowType = $("#windowType").val();
        if (windowType === 'standart') {
            $(".datamodel-window-height, .datamodel-window-width, .datamodel-window-size").show();
        } else {
            $(".datamodel-window-height, .datamodel-window-width, .datamodel-window-size").hide();
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
    function viewTheme(elem) {
        var _row = $(elem).closest("tr");
        var _themeCode = _row.find("select[name='themeCode']").val();
        
        if (_themeCode.length > 0) {
            
            var $dialogName = 'dialog-themeview';
            if (!$($dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo('body');
            }
            
            $("#" + $dialogName).empty().html("<div class=\"col-md-12\"><img src=\"middleware/views/workspace/themes/" + _themeCode + "/thumb.png\" style=\"max-width:100%;\"></div>");
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
                close: function() {
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                }, 
                buttons: [
                    {text: '<?php echo $this->lang->line('META_00033'); ?>', class: 'btn btn-sm blue-hoki', click: function () {
                        $("#" + $dialogName).dialog('close');
                    }}
                ]
            });
            $("#" + $dialogName).dialog('open');
        }
    }
</script>