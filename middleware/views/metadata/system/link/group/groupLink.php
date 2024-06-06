<div class="tabbable-line">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#group_link_main_tab" data-toggle="tab" class="nav-link active" class="pt-0"><?php echo $this->lang->line('META_00008'); ?></a>
        </li>
        <li class="nav-item">
            <a href="#group_link_other_tab" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00098'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="group_link_main_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>
                        <tr>
                            <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('META_00145'); ?></td>
                            <td>
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'groupType',
                                        'id' => 'groupType',
                                        'data' => (new Mdmetadata())->getGroupSubTypeListByAddMode(),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2', 
                                        'text' => 'notext'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px;" class="left-padding">Params:</td>
                            <td>
                                <?php echo Form::button(array('class'=>'btn btn-sm purple-plum','value'=>'...','onclick'=>'setParamAttributes(this, 0);')); ?>
                                <div id="dialog-paramattributes"></div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">Search Type:</td>
                            <td>
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'searchType',
                                        'id' => 'searchType',
                                        'class' => 'form-control select2',
                                        'op_value' => 'value',
                                        'op_text' => 'name',
                                        'value' => '2',
                                        'data' => Info::searchType()
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-tablename">
                            <td class="left-padding">
                                <label for="tableName">
                                    Объектын нэр:
                                </label>
                            </td>
                            <td>
                                <div class="input-group">
                                    <?php
                                    echo Form::textArea(
                                        array(
                                            'name' => 'tableName',
                                            'id' => 'tableName',
                                            'class' => 'form-control', 
                                            'style' => 'height: 31px; resize:none; display: block'
                                        )
                                    );
                                    echo Form::textArea(
                                        array(
                                            'name' => 'postgreSql',
                                            'id' => 'postgreSql', 
                                            'style' => 'display: none'
                                        )
                                    );
                                    echo Form::textArea(
                                        array(
                                            'name' => 'msSql',
                                            'id' => 'msSql', 
                                            'style' => 'display: none'
                                        )
                                    );
                                    ?>
                                    <div class="input-group-append">
                                        <button type="button" class="btn blue btn-icon" onclick="dvSqlViewEditor(this);" title="Query editor"><i class="far fa-edit"></i></button>
                                        <button type="button" class="btn purple-plum btn-icon" onclick="setParamAttributes(this, 1);" title="Query - ээс талбар үүсгэх"><i class="far fa-sort-alpha-down"></i></button>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-grid-option">
                            <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00034'); ?></td>
                            <td>
                                <?php echo Form::button(array('class'=>'btn btn-sm purple-plum','value'=>'...','onclick'=>'setDataModelGridOption(this);')); ?>
                                <div id="dialog-dv-grid-option"></div>
                            </td>
                        </tr>
                        <tr class="datamodel-listname">
                            <td class="left-padding">
                                <label for="listName">
                                    <?php echo $this->lang->line('META_00127'); ?>
                                </label>
                            </td>
                            <td>
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'listName',
                                        'id' => 'listName',
                                        'class' => 'form-control textInit globeCodeInput'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-column-count">
                            <td class="left-padding">
                                <label for="columnCount">
                                    <?php echo $this->lang->line('META_00117'); ?>
                                </label>
                            </td>
                            <td>
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'columnCount',
                                        'id' => 'columnCount',
                                        'class' => 'form-control longInit'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-label-position">
                            <td class="left-padding">Label Position:</td>
                            <td>
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'labelPosition',
                                        'id' => 'labelPosition',
                                        'data' => array(
                                            array(
                                                'code' => 'top', 
                                                'name' => 'Top'
                                            ),
                                            array(
                                                'code' => 'left', 
                                                'name' => 'Left'
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
                        <tr class="datamodel-label-width">
                            <td class="left-padding">
                                <label for="labelWidth">
                                    Label width:
                                </label>
                            </td>
                            <td>
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'labelWidth',
                                        'id' => 'labelWidth',
                                        'class' => 'form-control'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-window-type">
                            <td class="left-padding"><?php echo $this->lang->line('META_00176'); ?></td>
                            <td>
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
                                                'code' => 'notepaper1', 
                                                'name' => 'Notepaper 1'
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
                            <td>
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
                            <td>
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
                            <td>
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
                    </tbody>
                </table>
            </div>
        </div> 
        <div class="tab-pane" id="group_link_other_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>
                        <tr class="datamodel-is-treeview">
                            <td style="width: 170px" class="left-padding">
                                <label for="isTreeview">
                                    Tree view:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isTreeview',
                                            'id' => 'isTreeview',
                                            'value' => '1'
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px" class="left-padding">
                                <label for="isSkipUniqueError">
                                    <?php echo $this->lang->line('META_00165'); ?>
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isSkipUniqueError',
                                        'id' => 'isSkipUniqueError',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isNotGroupBy">
                                    <?php echo $this->lang->line('META_00049'); ?>
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isNotGroupBy',
                                        'id' => 'isNotGroupBy',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isAllNotSearch">
                                    <?php echo $this->lang->line('META_00062'); ?> бүгд эсэх:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAllNotSearch',
                                        'id' => 'isAllNotSearch',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isUseRtConfig">
                                    <?php echo $this->lang->line('META_00050'); ?>
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseRtConfig',
                                        'id' => 'isUseRtConfig',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isUseWorkFlow">
                                    <?php echo $this->lang->line('META_00166'); ?>
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseWorkFlow',
                                        'id' => 'isUseWorkFlow',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isUseSidebar">
                                    <?php echo $this->lang->line('META_00051'); ?>
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseSidebar',
                                        'id' => 'isUseSidebar',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isUseQuickSearch">
                                    <?php echo $this->lang->line('META_00129'); ?>
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseQuickSearch',
                                        'id' => 'isUseQuickSearch',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isUseResult">
                                    <?php echo $this->lang->line('META_00130'); ?>
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(array('name' => 'isUseResult', 'id' => 'isUseResult', 'value' => '1'));
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isUseCompanyDepartmentId">
                                    Компаниар шүүх эсэх
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(array('name' => 'isUseCompanyDepartmentId', 'id' => 'isUseCompanyDepartmentId', 'value' => '1'));
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isExportText">
                                    <?php echo $this->lang->line('META_00105'); ?>
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isExportText',
                                        'id' => 'isExportText',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding">
                                <label for="buttonBarStyle">
                                    Button bar style:
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'buttonBarStyle',
                                        'id' => 'buttonBarStyle',
                                        'data' => array(
                                            array(
                                                'code' => 'blue',
                                                'name' => 'Blue'
                                            ),
                                            array(
                                                'code' => 'dropdown',
                                                'name' => 'Dropdown'
                                            )
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2',
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding"><?php echo $this->lang->line('META_00052'); ?></td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="calculateProcessId" name="calculateProcessId" type="hidden">
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
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding">QuickSearch DV:</td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="quickSearchDvId" name="quickSearchDvId" type="hidden">
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
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding"><?php echo $this->lang->line('META_00053'); ?></td>
                            <td colspan="2">
                                <input type="text" id="criteriaColCount" name="criteriaColCount" class="form-control longInit" data-maxlength="true" maxlength="2" value="">
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="useBasket">
                                    Use Basket:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'useBasket',
                                        'id' => 'useBasket',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isCountCartOpen">
                                    CountCart Open:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isCountCartOpen',
                                        'id' => 'isCountCartOpen',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isIgnoreExcelExport">
                                    Is Ignore ExcelExport:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isIgnoreExcelExport',
                                        'id' => 'isIgnoreExcelExport',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isUseDataMart">
                                    Is use datamart:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseDataMart',
                                        'id' => 'isUseDataMart',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isCriteriaAlwaysOpen">
                                    <?php echo $this->lang->line('META_00208'); ?>:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isCriteriaAlwaysOpen',
                                        'id' => 'isCriteriaAlwaysOpen',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isEnterFilter">
                                    Enter key filter:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isEnterFilter',
                                        'id' => 'isEnterFilter',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isFilterLog">
                                    Is filter log:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isFilterLog',
                                        'id' => 'isFilterLog',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isIgnoreSorting">
                                    Is ignore sort:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isIgnoreSorting',
                                        'id' => 'isIgnoreSorting',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isIgnoreSorting">
                                    Is ignore wfm history:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isIgnoreWfmHistory',
                                        'id' => 'isIgnoreWfmHistory',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isDirectPrint">
                                    Is direct print:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isDirectPrint',
                                        'id' => 'isDirectPrint',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isShowFilterTemplate">
                                    <?php echo $this->lang->line('pf_is_show_filter_template'); ?>:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isShowFilterTemplate',
                                        'id' => 'isShowFilterTemplate',
                                        'class' => 'notuniform', 
                                        'value' => '1' 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding">Refresh timer:</td>
                            <td colspan="2">
                              <input type="text" id="refreshTimer" name="refreshTimer" class="form-control longInit" placeholder="<?php echo $this->lang->line('META_00189'); ?>">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding"><?php echo $this->lang->line('cfg_menu_name'); ?>:</td>
                            <td colspan="2">
                              <input type="text" id="listMenuName" name="listMenuName" class="form-control stringInit" placeholder="">
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Sub DV position:</td>
                            <td colspan="2">
                              <?php
                                echo Form::select(
                                    array(
                                        'name' => 'showPosition',
                                        'id' => 'showPosition',
                                        'data' => array(
                                            array(
                                                'code' => 'top', 
                                                'name' => 'Top'
                                            ),
                                            array(
                                                'code' => 'left', 
                                                'name' => 'Left'
                                            )
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control form-control-sm'
                                    )
                                );                              
                              ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">External meta id:</td>
                            <td colspan="2">
                              <input type="text" id="externalMetaDataId" name="externalMetaDataId" class="form-control longInit">
                            </td>
                        </tr>
                        <tr class="pf-dv-wsurl" style="display: none">
                            <td class="left-padding"><?php echo $this->lang->line('META_00067'); ?>:</td>
                            <td colspan="2">
                              <input type="text" id="wsUrl" name="wsUrl" class="form-control stringInit">
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding">Rule process:</td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="ruleProcessId" name="ruleProcessId" type="hidden">
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
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding">Legend DV:</td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="legendDvId" name="legendDvId" type="hidden">
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
                            <td class="left-padding">Panel type:</td>
                            <td colspan="2">
                              <?php
                                echo Form::select(
                                    array(
                                        'name' => 'panelType',
                                        'id' => 'panelType',
                                        'data' => array(
                                            array(
                                                'code' => 'oneColumn', 
                                                'name' => 'One column'
                                            ), 
                                            array(
                                                'code' => 'twoColumn', 
                                                'name' => 'Two column'
                                            ),
                                            array(
                                                'code' => 'menuView', 
                                                'name' => 'Menu view'
                                            )
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control form-control-sm'
                                    )
                                );                              
                              ?>
                            </td>
                        </tr>    
                        <tr>
                            <td class="left-padding">
                                <label for="isParentFilter">
                                    Is parent filter:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isParentFilter',
                                        'id' => 'isParentFilter',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isUseSemantic">
                                    Is use semantic:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseSemantic',
                                        'id' => 'isUseSemantic',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isUseButtonMap">
                                    Is use button map:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseButtonMap',
                                        'id' => 'isUseButtonMap',
                                        'value' => '1'
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">Color schema:</td>
                            <td>
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'colorSchema',
                                        'id' => 'colorSchema',
                                        'class' => 'form-control',
                                        'op_value' => 'id',
                                        'op_text' => 'name',
                                        'data' => array(
                                            array(
                                                'id' => 'orange', 
                                                'name' => 'Orange'
                                            )
                                        )
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">Form control type:</td>
                            <td>
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'formControl',
                                        'id' => 'formControl',
                                        'class' => 'form-control',
                                        'op_value' => 'value',
                                        'op_text' => 'name',
                                        'data' => Info::formControlType()
                                    )
                                );
                                ?>
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
    visibleGroupTypeAttr();
    visibleWindowSizeAttr();
    $("#groupType").on("change", function(){
        visibleGroupTypeAttr();
    });
    $("#windowSize").on("change", function(){
        visibleWindowSizeAttr();
    });
    $('#externalMetaDataId').on('change', function(){
        var externalMetaDataId = $(this).val();
        if (externalMetaDataId) {
            $('.pf-dv-wsurl').show();
        } else {
            $('.pf-dv-wsurl').hide();
            $('#wsUrl').val('');
        }
    });
});
function visibleGroupTypeAttr(){
    var groupType = $("#groupType").val();
    if (groupType === 'dataview' || groupType === 'tablestructure') {
        $(".datamodel-column-count, .datamodel-label-position, .datamodel-label-width, .datamodel-is-treeview, .datamodel-window-size, .datamodel-window-type, .datamodel-window-height").hide();
        $(".datamodel-tablename, .datamodel-process-dtl, .datamodel-grid-option, .datamodel-listname").show();
        if (groupType === 'tablestructure' && $("#tableName").val() === '') {
            $("#tableName").val("PT_");
        }
    } else {
        $(".datamodel-tablename, .datamodel-process-dtl, .datamodel-grid-option, .datamodel-listname").hide();
        $(".datamodel-column-count, .datamodel-label-position, .datamodel-label-width, .datamodel-is-treeview, .datamodel-window-size, .datamodel-window-type, .datamodel-window-height").show();
    }
}
function visibleWindowSizeAttr(){
    var windowSize = $("#windowSize").val();
    if (windowSize === 'custom') {
        $(".datamodel-window-height, .datamodel-window-width").show();
    } else {
        $(".datamodel-window-height, .datamodel-window-width").hide();
    }
}
function setParamAttributes(elem, isQueryToParams) {

    var $dialogName = 'dialog-paramattributes';

    if ($("#" + $dialogName).children().length > 0) {
        
        var $dialogContainer = $("#" + $dialogName);
        var $detachedChildren = $dialogContainer.children().detach();
        
        $dialogContainer.dialog({
            appendTo: "form#addMetaSystemForm",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Param Attributes',
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
            "maximize" : function() { 
                var dialogHeight = $dialogContainer.height();
                $dialogContainer.find("div#fz-process-params-option").css({"height": (dialogHeight - 41)+'px'});
                $dialogContainer.find("div.params-addon-config").css({"height": (dialogHeight - 41)+'px'});
            }
        });
        $dialogContainer.dialog('open');
        $dialogContainer.dialogExtend("maximize");
        
    } else {
        
        var postData = {metaDataId: ''};
        
        if (isQueryToParams) {
            var queryVal = $(elem).closest('table').find('textarea#tableName').val().trim();
            if (queryVal != '') {
                postData['query'] = queryVal;
            }
        }
        
        $.ajax({
            type: 'post',
            url: 'mdmetadata/setGroupParamAttributesNew',
            data: postData,
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
                if (!$("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length){
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js');
                }
            },
            success: function (data) {
                
                PNotify.removeAll();
                
                if (data.hasOwnProperty('status')) {
                    
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });
                    Core.unblockUI();
                    
                } else {
                    
                    var $dialogContainer = $("#" + $dialogName);
                    $dialogContainer.empty().append(data.Html);

                    var $detachedChildren = $dialogContainer.children().detach();

                    $dialogContainer.dialog({
                        appendTo: "form#addMetaSystemForm",
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
                                $dialogContainer.empty().dialog('close');
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
                            $dialogContainer.find("div#fz-process-params-option").css({"height": (dialogHeight - 41)+'px'});
                            $dialogContainer.find("div.params-addon-config").css({"height": (dialogHeight - 41)+'px'});
                        }
                    });
                    $dialogContainer.dialog('open');
                    $dialogContainer.dialogExtend("maximize");
                }
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initNumber($("#" + $dialogName));
        });
    }
}
function setDataModelGridOption(elem){
    var $dialogName = 'dialog-dv-grid-option';
    var $dialog = $('#'+$dialogName);
    
    if ($dialog.children().length > 0) {
        $dialog.dialog({
            appendTo: "form#addMetaSystemForm",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Grid Option',
            width: 950,
            minWidth: 950,
            height: "auto",
            modal: true,      
            buttons: [
                {text: plang.get('save_btn'), class:'btn btn-sm green bp-btn-subsave', click: function(){
                    $dialog.dialog('close');
                }},
                {text: plang.get('close_btn'), class:'btn btn-sm blue-hoki', click: function(){
                    $dialog.empty().dialog('close');
                }},
                {text: "<?php echo $this->lang->line('META_00002'); ?>", class:'btn btn-sm red', click: function(){
                    $dialog.empty().dialog('close');
                }}
            ]        
        }).dialogExtend({
            "closable" : true,
            "maximizable" : true,
            "minimizable" : true,
            "collapsable" : true,
            "dblclick" : "maximize",
            "minimizeLocation" : "left",
            "icons" : {
                "close" : "ui-icon-circle-close",
                "maximize" : "ui-icon-extlink",
                "minimize" : "ui-icon-minus",
                "collapse" : "ui-icon-triangle-1-s",
                "restore" : "ui-icon-newwin"
            }, 
            "maximize" : function() { 
                var dialogHeight = $dialog.height();
                $dialog.find("div#fz-grid-option").css("height", (dialogHeight - 10)+'px');
            }, 
            "restore" : function() { 
                var dialogHeight = $dialog.height();
                $dialog.find("div#fz-grid-option").css("height", (dialogHeight - 10)+'px');
            }
        });
        $dialog.dialog('open');
    } else {
        $.ajax({
            type: 'post',
            url: 'mdobject/setDataModelGridOption',
            data: {
                groupChildData: $("ul#group-meta-sortable").find("input").serialize()
            },
            dataType: "json",
            beforeSend:function(){
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success:function(data){
                $dialog.empty().append(data.Html);  
                $dialog.dialog({
                    appendTo: "form#addMetaSystemForm",
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.Title,
                    width: 950,
                    minWidth: 950,
                    height: "auto",
                    modal: true,      
                    buttons: [
                        {text: data.save_btn, class:'btn btn-sm green bp-btn-subsave', click: function(){
                            $dialog.dialog('close');
                        }},
                        {text: data.close_btn, class:'btn btn-sm blue-hoki', click: function(){
                            $dialog.empty().dialog('close');
                        }}
                    ]        
                }).dialogExtend({
                    "closable" : true,
                    "maximizable" : true,
                    "minimizable" : true,
                    "collapsable" : true,
                    "dblclick" : "maximize",
                    "minimizeLocation" : "left",
                    "icons" : {
                        "close" : "ui-icon-circle-close",
                        "maximize" : "ui-icon-extlink",
                        "minimize" : "ui-icon-minus",
                        "collapse" : "ui-icon-triangle-1-s",
                        "restore" : "ui-icon-newwin"
                    }, 
                    "maximize" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find("div#fz-grid-option").css("height", (dialogHeight - 10)+'px');
                    }, 
                    "restore" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find("div#fz-grid-option").css("height", (dialogHeight - 10)+'px');
                    }
                });
                $dialog.dialog('open');
                Core.unblockUI();
            }
        }).done(function(){
            Core.initNumber($dialog);
        });
    }
}
function dvSqlViewEditor(elem) {

    var $dialogName = 'dialog-dvSqlViewEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var $parent = $(elem).closest('td');
    
    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
        $.ajax({
            type: 'post',
            url: 'mdmeta/dvSqlViewEditor',
            data: {
                query: $parent.find('textarea#tableName').val(), 
                postgreSql: $parent.find('textarea#postgreSql').val(), 
                msSql: $parent.find('textarea#msSql').val(), 
                dialogId: $dialogName
            },
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
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 1000,
                    minWidth: 1000,
                    height: 600,
                    modal: false,
                    open: function() {
                        disableScrolling();
                    }, 
                    close: function() {
                        enableScrolling();
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.format_btn, class: 'btn btn-sm purple-plum float-left', click: function() {
                            
                            var $activeTab = $('.dbdriver-tabs [aria-expanded="true"]'), 
                                hrefUrl = $activeTab.attr('href'), sqlQuerySql = '', dbDriverTab = '';
                        
                            if (hrefUrl == '#default-tab') {
                                
                                dvSqlQueryEditor.save();
                                sqlQuerySql = dvSqlQueryEditor.getValue();
                                dbDriverTab = 'default';
                                
                            } else if (hrefUrl == '#postgresql-tab') {
                                
                                postgreSqlEditor.save();
                                sqlQuerySql = postgreSqlEditor.getValue();
                                dbDriverTab = 'postgresql';
                                
                            } else if (hrefUrl == '#mssql-tab') {
                                
                                msSqlEditor.save();
                                sqlQuerySql = msSqlEditor.getValue();
                                dbDriverTab = 'mssql';
                            }
                            
                            $.ajax({
                                type: 'post',
                                url: 'mdmeta/sqlFormatting',
                                data: {query: sqlQuerySql},
                                beforeSend: function() {
                                    Core.blockUI({
                                        message: 'Formatting...',
                                        boxed: true
                                    });
                                },
                                success: function(content) {
                                    
                                    if (dbDriverTab == 'default') {
                                        dvSqlQueryEditor.setValue(content);
                                        dvSqlQueryEditor.focus();
                                    } else if (dbDriverTab == 'postgresql') {
                                        postgreSqlEditor.setValue(content);
                                        postgreSqlEditor.focus();
                                    } else if (dbDriverTab == 'mssql') {
                                        msSqlEditor.setValue(content);
                                        msSqlEditor.focus();
                                    }
                                    
                                    Core.unblockUI();
                                }
                            });
                        }}, 
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {

                            dvSqlQueryEditor.save();
                            postgreSqlEditor.save();
                            msSqlEditor.save();
                            
                            $parent.find('textarea#tableName').val(dvSqlQueryEditor.getValue()).trigger('change');
                            $parent.find('textarea#postgreSql').val(postgreSqlEditor.getValue()).trigger('change');
                            $parent.find('textarea#msSql').val(msSqlEditor.getValue()).trigger('change');
                            
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
                    "maximize" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.CodeMirror').css('height', (dialogHeight - 50)+'px');
                    }, 
                    "restore" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find('.CodeMirror').css('height', (dialogHeight - 50)+'px');
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend("maximize");
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    });
}
</script>