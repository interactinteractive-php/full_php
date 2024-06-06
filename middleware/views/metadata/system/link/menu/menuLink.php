<div class="panel panel-default bg-inverse">
    <table class="table sheetTable">
        <tbody>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="globeCode">
                        <?php echo $this->lang->line('metadata_globeCode'); ?>:
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'globeCode',
                            'id' => 'globeCode',
                            'class' => 'form-control textInit globeCodeInput'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo $this->lang->line('metadata_menuPosition'); ?>:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'menuPosition',
                            'id' => 'menuPosition',
                            'data' => array(
                                array(
                                    'code' => 'horizontal', 
                                    'name' => 'Хэвтээ'
                                ), 
                                array(
                                    'code' => 'vertical', 
                                    'name' => 'Босоо'
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
                <td class="left-padding"><?php echo $this->lang->line('metadata_menuAlign'); ?>:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'menuAlign',
                            'id' => 'menuAlign',
                            'data' => array(
                                array(
                                    'code' => 'meta-menu-center', 
                                    'name' => 'Төв'
                                ), 
                                array(
                                    'code' => 'meta-menu-left', 
                                    'name' => $this->lang->line('META_00082')
                                ), 
                                array(
                                    'code' => 'meta-menu-right', 
                                    'name' => $this->lang->line('META_00055')
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
                <td class="left-padding">Theme:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'menuTheme',
                            'id' => 'menuTheme',
                            'data' => $this->widgetData,
                            'op_value' => 'ID',
                            'op_text' => 'NAME',
                            'class' => 'form-control select2'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Target:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'urlTarget',
                            'id' => 'urlTarget',
                            'data' => array(
                                array(
                                    'code' => '_self', 
                                    'name' => $this->lang->line('metadata_urlTarget1')
                                ), 
                                array(
                                    'code' => '_blank', 
                                    'name' => $this->lang->line('metadata_urlTarget2')
                                ), 
                                array(
                                    'code' => '_alwaysself', 
                                    'name' => $this->lang->line('metadata_urlTarget3')
                                ),
                                array(
                                    'code' => '_alwaysblank', 
                                    'name' => $this->lang->line('metadata_urlTarget4')
                                ), 
                                array(
                                    'code' => 'iframe', 
                                    'name' => 'Iframe'
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
                <td class="left-padding"><?php echo $this->lang->line('metadata_webUrl'); ?></td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'webUrl',
                            'id' => 'webUrl',
                            'class' => 'form-control'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td style="width: 170px" class="left-padding">
                    <label for="menuTooltip">
                        Tooltip:
                    </label>
                </td>
                <td>
                    <?php
                    echo Form::text(
                        array(
                            'name' => 'menuTooltip',
                            'id' => 'menuTooltip',
                            'class' => 'form-control textInit globeCodeInput'
                        )
                    );
                    ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">Action Meta:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1">
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
                <td class="left-padding">Count Meta:</td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId.'|'.Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="menuCountMetaDataId" name="menuCountMetaDataId" type="hidden">
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
                <td class="left-padding"><?php echo $this->lang->line('META_00197'); ?></td>
                <td>
                    <?php echo Form::hidden(array('name' => 'menuIconName')); ?>
                    <button id="menu-iconpicker" class="btn btn-secondary btn-sm" data-search-text="<?php echo $this->lang->line('META_00109'); ?>" data-placement="top" data-iconset="fontawesome5" data-cols="6" data-rows="6" role="iconpicker"></button>
                </td>
            </tr>
            <tr>
                <td class="left-padding"><?php echo $this->lang->line('META_00072'); ?></td>
                <td>
                    <?php echo Form::file(array('name' => 'menuPhotoName', 'class' => 'form-control', 'onchange' => 'hasPhotoExtension(this)')); ?>
                </td>
            </tr>
            <tr>
                <td class="left-padding">View type:</td>
                <td>
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'viewType',
                            'id' => 'viewType',
                            'data' => array(
                                array(
                                    'code' => 'newarea', 
                                    'name' => 'Шинэ талбар'
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
            <tr class="datamodel-search-type-meta">
                <td class="left-padding">
                    <label for="isShowCard">
                        <?php echo $this->lang->line('metadata_isshowcard'); ?>:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isShowCard',
                            'id' => 'isShowCard',
                            'value' => '1',
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr class="datamodel-search-type-meta">
                <td class="left-padding">
                    <label for="isContentUi">
                        <?php echo $this->lang->line('metadata_iscontentui'); ?>:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isContentUi',
                            'id' => 'isContentUi',
                            'value' => '1',
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isModuleSidebar">
                        Is module sidebar:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isModuleSidebar',
                            'id' => 'isModuleSidebar',
                            'value' => '1',
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isDefaultOpen">
                        Is default open:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isDefaultOpen',
                            'id' => 'isDefaultOpen',
                            'value' => '1',
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
            <tr>
                <td class="left-padding">
                    <label for="isOfflineMode">
                        Is offline mode:
                    </label>
                </td>
                <td colspan="2">
                    <div class="checkbox-list">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isOfflineMode',
                            'id' => 'isOfflineMode',
                            'value' => '1',
                        )
                    );
                    ?>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</div>

<script type="text/javascript">
$(function(){
    $('button[role="iconpicker"]').iconpicker({
        arrowPrevIconClass: 'fa fa-arrow-left',
        arrowNextIconClass: 'fa fa-arrow-right'
    });
    $('#menu-iconpicker').on('change', function(e){ 
        if (e.icon === 'empty' || e.icon === 'fa-empty') {
            $("input[name='menuIconName']").val("");
        } else {
            $("input[name='menuIconName']").val(e.icon);
        }
    });
});    
</script>