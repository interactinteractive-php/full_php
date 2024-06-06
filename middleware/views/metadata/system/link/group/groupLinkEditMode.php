<div class="tabbable-line">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#group_link_main_tab" data-toggle="tab" class="nav-link active" class="pt-0"><?php echo $this->lang->line('META_00008'); ?></a>
        </li>
        <li class="nav-item">
            <a href="#group_link_other_tab" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00098'); ?></a>
        </li>
        <li class="nav-item">
            <a href="#group_link_links_tab" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00151'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="group_link_main_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>
                        <tr>
                            <td style="width: 170px" class="left-padding"><?php echo $this->lang->line('META_00145'); ?></td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'groupType',
                                        'id' => 'groupType',
                                        'data' => array(
                                            array(
                                                'code' => 'parameter',
                                                'name' => 'Parameter'
                                            ),
                                            array(
                                                'code' => 'dataview',
                                                'name' => 'DataView'
                                            ),
                                            array(
                                                'code' => 'tablestructure',
                                                'name' => 'TableStructure'
                                            )
                                        ),
                                        'op_value' => 'code',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2',
                                        'value' => $this->gRow['GROUP_TYPE'],
                                        'text' => 'notext'
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px;" class="left-padding">Params:</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum metagroup-params-btn', 'value' => '...', 'onclick' => 'setParamAttributes(this);')); ?>
                                <div id="dialog-paramattributes"></div>
                            </td>
                        </tr>
                        <tr class="datamodel-rep-meta-groupid d-none">
                            <td class="left-padding">Ref Group:</td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'repMetaGroupId',
                                        'id' => 'repMetaGroupId',
                                        'class' => 'form-control select2'
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
                                        <input id="repStructureId" name="repStructureId" type="hidden" value="<?php echo Arr::get($this->gRow, 'REF_STRUCTURE_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->gRow, 'REF_STRUCTURE_CODE'); ?>" title="<?php echo Arr::get($this->gRow, 'REF_STRUCTURE_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->gRow, 'REF_STRUCTURE_NAME'); ?>" title="<?php echo Arr::get($this->gRow, 'REF_STRUCTURE_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                        </span> 
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-listname">
                            <td class="left-padding">
                                <label for="listName">
                                    <?php echo $this->lang->line('META_00127'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'listName',
                                        'id' => 'listName',
                                        'class' => 'form-control textInit globeCodeInput', 
                                        'value' => $this->gRow['LIST_NAME']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-tablename">
                            <td class="left-padding">
                                <label for="tableName">
                                    Object:
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="input-group">
                                    <?php
                                    echo Form::textArea(
                                        array(
                                            'name' => 'tableName',
                                            'id' => 'tableName',
                                            'class' => 'form-control', 
                                            'style' => 'min-height: 31px; height: 31px; resize:vertical; display: block',
                                            'value' => (new Mdmetadata())->objectDeCompress($this->gRow['TABLE_NAME'])
                                        )
                                    );
                                    echo Form::textArea(
                                        array(
                                            'name' => 'postgreSql',
                                            'id' => 'postgreSql', 
                                            'style' => 'display: none',
                                            'value' => (new Mdmetadata())->objectDeCompress($this->gRow['POSTGRE_SQL'])
                                        )
                                    );
                                    echo Form::textArea(
                                        array(
                                            'name' => 'msSql',
                                            'id' => 'msSql', 
                                            'style' => 'display: none',
                                            'value' => (new Mdmetadata())->objectDeCompress($this->gRow['MS_SQL'])
                                        )
                                    );
                                    ?>
                                    <div class="input-group-append">
                                        <button type="button" class="btn blue btn-icon" onclick="dvSqlViewEditor(this);" title="Query editor"><i class="far fa-edit"></i></button>
                                        <button type="button" class="btn green btn-icon" onclick="dvChildSql('<?php echo $this->metaDataId; ?>');" title="Sub query"><i class="far fa-bars"></i></button>
                                        <button type="button" class="btn purple-plum btn-icon" onclick="setParamAttributes(this, 1);" title="Query - ээс талбар үүсгэх"><i class="far fa-sort-alpha-down"></i></button>
                                        <div id="dialog-dv-childquery"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-process-dtl">
                            <td style="height: 32px;" class="left-padding">Process:</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setDataModelProcessDtl(this);')); ?>
                                <div id="dialog-process-dtl"></div>
                            </td>
                        </tr>
                        <tr class="datamodel-grid-option">
                            <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00034'); ?></td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setDataModelGridOption(this);')); ?>
                                <div id="dialog-dv-grid-option"></div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">Search Type:</td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'searchType',
                                        'id' => 'searchType',
                                        'class' => 'form-control select2',
                                        'op_value' => 'value',
                                        'op_text' => 'name',
                                        'value' => $this->gRow['SEARCH_TYPE'],
                                        'data' => Info::searchType()
                                    )
                                );
                                ?>
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
                                        'onclick' => 'dvCacheClear(\''.$this->metaDataId.'\');'
                                    )
                                ); 
                                ?>
                            </td>
                        </tr> 
                        <tr>
                            <td style="height: 32px;" class="left-padding"><?php echo $this->lang->line('META_00059'); ?></td>
                            <td colspan="2">
                                <?php 
                                echo Form::button(
                                    array(
                                        'class' => 'btn btn-sm purple-plum', 
                                        'value' => '<i class="fa fa-copy"></i>', 
                                        'onclick' => 'metaCopy(\''.$this->metaDataId.'\');'
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
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isTreeview',
                                            'id' => 'isTreeview',
                                            'class' => 'notuniform', 
                                            'value' => '1',
                                            'saved_val' => $this->gRow['IS_TREEVIEW']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-is-entity">
                            <td style="width: 170px" class="left-padding">
                                <label for="isEntity">
                                    Entity:
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isEntity',
                                            'id' => 'isEntity',
                                            'class' => 'notuniform', 
                                            'value' => '1',
                                            'saved_val' => $this->gRow['IS_ENTITY']
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px" class="left-padding">
                                <label for="isSkipUniqueError"><?php echo $this->lang->line('META_00165'); ?></label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isSkipUniqueError',
                                            'id' => 'isSkipUniqueError',
                                            'class' => 'notuniform', 
                                            'value' => '1',
                                            'saved_val' => $this->gRow['IS_SKIP_UNIQUE_ERROR']
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
                            <td colspan="2">
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isNotGroupBy',
                                        'id' => 'isNotGroupBy',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_NOT_GROUPBY']
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="isAllNotSearch">
                                    <?php echo $this->lang->line('META_00188'); ?>
                                </label>
                            </td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAllNotSearch',
                                        'id' => 'isAllNotSearch',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_ALL_NOT_SEARCH']
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
                            <td colspan="2">
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseRtConfig',
                                        'id' => 'isUseRtConfig',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_USE_RT_CONFIG']
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
                            <td colspan="2">
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseWorkFlow',
                                        'id' => 'isUseWorkFlow',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_USE_WFM_CONFIG']
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
                            <td colspan="2">
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseSidebar',
                                        'id' => 'isUseSidebar',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_USE_SIDEBAR']
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
                            <td colspan="2">
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseQuickSearch',
                                        'id' => 'isUseQuickSearch',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_USE_QUICKSEARCH']
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
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseResult',
                                        'id' => 'isUseResult',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_USE_RESULT']
                                    )
                                );
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
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseCompanyDepartmentId',
                                        'id' => 'isUseCompanyDepartmentId',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_USE_COMPANY_DEPARTMENT_ID']
                                    )
                                );
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
                            <td colspan="2">
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isExportText',
                                        'id' => 'isExportText',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_EXPORT_TEXT'] 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-grid-option">
                            <td style="height: 32px;" class="left-padding">
                                <?php echo $this->lang->line('META_00106'); ?>
                            </td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setDvHeaderFooterEditor(this);')); ?>
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
                                        'value' => isset($this->gRow['BUTTON_BAR_STYLE']) ? $this->gRow['BUTTON_BAR_STYLE'] : ''
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
                                        <input id="calculateProcessId" name="calculateProcessId" type="hidden" value="<?php echo Arr::get($this->gRow, 'CALCULATE_PROCESS_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->gRow, 'CALCULATE_PROCESS_CODE'); ?>" title="<?php echo Arr::get($this->gRow, 'CALCULATE_PROCESS_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->gRow, 'CALCULATE_PROCESS_NAME'); ?>" title="<?php echo Arr::get($this->gRow, 'CALCULATE_PROCESS_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
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
                                        <input id="quickSearchDvId" name="quickSearchDvId" type="hidden" value="<?php echo Arr::get($this->gRow, 'QS_META_DATA_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->gRow, 'QS_META_DATA_CODE'); ?>" title="<?php echo Arr::get($this->gRow, 'QS_META_DATA_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->gRow, 'QS_META_DATA_NAME'); ?>" title="<?php echo Arr::get($this->gRow, 'QS_META_DATA_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                        </span>     
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding"><?php echo $this->lang->line('META_00053'); ?></td>
                            <td colspan="2">
                                <input type="text" id="criteriaColCount" name="criteriaColCount" class="form-control longInit" data-maxlength="true" maxlength="2" value="<?php echo isset($this->gRow['M_CRITERIA_COL_COUNT']) ? $this->gRow['M_CRITERIA_COL_COUNT'] : '' ?>">
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding"><?php echo $this->lang->line('MET_999991067'); ?></td>
                            <td colspan="2">
                                <input type="text" id="criteriaGroupColCount" name="criteriaGroupColCount" class="form-control longInit" data-maxlength="true" maxlength="2" value="<?php echo isset($this->gRow['M_GROUP_CRITERIA_COL_COUNT']) ? $this->gRow['M_GROUP_CRITERIA_COL_COUNT'] : '' ?>">
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
                                        'class' => 'notuniform', 
                                        'value' => '1',
                                        'saved_val' => $this->gRow['USE_BASKET'] 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="datamodel-search-type-meta">
                            <td class="left-padding">
                                <label for="lookupTheme">
                                    Is Lookup theme:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'lookupTheme',
                                        'id' => 'lookupTheme',
                                        'class' => 'notuniform', 
                                        'value' => '1',
                                        'saved_val' => $this->gRow['IS_LOOKUP_BY_THEME'] 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_COUNTCARD_OPEN'] 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_IGNORE_EXCEL_EXPORT'] 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_USE_DATAMART'] 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_CRITERIA_ALWAYS_OPEN'] 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_ENTER_FILTER'] 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_FILTER_LOG'] 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_IGNORE_SORTING'] 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isIgnoreWfmHistory">
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_IGNORE_WFM_HISTORY'] 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_DIRECT_PRINT'] 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isClearDrillCriteria">
                                    Is clear drill criteria:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isClearDrillCriteria',
                                        'id' => 'isClearDrillCriteria',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_CLEAR_DRILL_CRITERIA'] 
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
                                        'value' => '1', 
                                        'saved_val' => $this->gRow['IS_SHOW_FILTER_TEMPLATE'] 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding">Refresh timer:</td>
                            <td colspan="2">
                                <input type="text" id="refreshTimer" name="refreshTimer" class="form-control longInit" value="<?php echo isset($this->gRow['REFRESH_TIMER']) ? $this->gRow['REFRESH_TIMER'] : '' ?>" placeholder="<?php echo $this->lang->line('META_00189'); ?>">
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding">Layout Meta:</td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$layoutMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="layoutMetaId" name="layoutMetaId" type="hidden" value="<?php echo Arr::get($this->gRow, 'LAYOUT_META_DATA_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->gRow, 'LAYOUT_META_DATA_CODE'); ?>" title="<?php echo Arr::get($this->gRow, 'LAYOUT_META_DATA_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->gRow, 'LAYOUT_META_DATA_NAME'); ?>" title="<?php echo Arr::get($this->gRow, 'LAYOUT_META_DATA_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                        </span>     
                                    </div>
                                </div>
                            </td>
                        </tr>        
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding"><?php echo $this->lang->line('cfg_menu_name'); ?>:</td>
                            <td colspan="2">
                                <input type="text" id="listMenuName" name="listMenuName" class="form-control stringInit" value="<?php echo Arr::get($this->gRow, 'LIST_MENU_NAME'); ?>">
                            </td>
                        </tr>                                        
                        <tr class="ddatamodel-grid-option">
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
                                        'class' => 'form-control form-control-sm',
                                        'value' => Arr::get($this->gRow, 'SHOW_POSITION')
                                    )
                                );                              
                              ?>
                            </td>
                        </tr>     
                        <tr>
                            <td class="left-padding">External meta id:</td>
                            <td colspan="2">
                                <input type="text" id="externalMetaDataId" name="externalMetaDataId" class="form-control longInit" value="<?php echo Arr::get($this->gRow, 'EXTERNAL_META_DATA_ID'); ?>">
                            </td>
                        </tr>
                        <tr class="pf-dv-wsurl" style="display: none">
                            <td class="left-padding"><?php echo $this->lang->line('META_00067'); ?>:</td>
                            <td colspan="2">
                                <input type="text" id="wsUrl" name="wsUrl" class="form-control stringInit" value="<?php echo Arr::get($this->gRow, 'WS_URL'); ?>">
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding">Rule process:</td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="ruleProcessId" name="ruleProcessId" type="hidden" value="<?php echo Arr::get($this->gRow, 'RULE_PROCESS_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->gRow, 'RULE_META_DATA_CODE'); ?>" title="<?php echo Arr::get($this->gRow, 'RULE_META_DATA_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->gRow, 'RULE_META_DATA_NAME'); ?>" title="<?php echo Arr::get($this->gRow, 'RULE_META_DATA_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
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
                                        <input id="legendDvId" name="legendDvId" type="hidden" value="<?php echo Arr::get($this->gRow, 'DATA_LEGEND_DV_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->gRow, 'LEGEND_META_DATA_CODE'); ?>" title="<?php echo Arr::get($this->gRow, 'LEGEND_META_DATA_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->gRow, 'LEGEND_META_DATA_NAME'); ?>" title="<?php echo Arr::get($this->gRow, 'LEGEND_META_DATA_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
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
                                        'class' => 'form-control form-control-sm', 
                                        'value' => Arr::get($this->gRow, 'PANEL_TYPE')
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->gRow, 'IS_PARENT_FILTER') 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->gRow, 'IS_USE_SEMANTIC') 
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
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->gRow, 'IS_USE_BUTTON_MAP') 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isFirstColFilter">
                                    Is first column filter:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isFirstColFilter',
                                        'id' => 'isFirstColFilter',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->gRow, 'IS_FIRST_COL_FILTER') 
                                    )
                                );
                                ?>
                                </div>
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
                                        'class' => 'form-control longInit',
                                        'value' => $this->gRow['COLUMN_COUNT']
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
                                        'class' => 'form-control select2',
                                        'value' => $this->gRow['LABEL_POSITION']
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
                                        'class' => 'form-control',
                                        'value' => $this->gRow['LABEL_WIDTH']
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
                                        'class' => 'form-control select2',
                                        'value' => $this->gRow['WINDOW_TYPE']
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
                                        'class' => 'form-control select2',
                                        'value' => $this->gRow['WINDOW_SIZE']
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
                                        'class' => 'form-control longInit',
                                        'value' => $this->gRow['WINDOW_WIDTH']
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
                                        'class' => 'form-control',
                                        'value' => $this->gRow['WINDOW_HEIGHT']
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="datamodel-visible-workflow">
                            <td style="height: 32px;" class="left-padding">Workflow:</td>
                            <td>
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'lcWorkflowConfig(this);')); ?>
                                <div id="dialog-lc-workflow-config"></div>
                            </td>
                        </tr>
                        <tr class="datamodel-lifecycle-book">
                            <td style="height: 32px;" class="left-padding">Lifecycle book:</td>
                            <td>
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'lifecycleBook(this);')); ?>
                                <div id="dialog-lifecycle-book-config"></div>
                            </td>
                        </tr>            
                        <tr class="datamodel-banner-manager">
                            <td style="height: 32px;" class="left-padding">Banner manager:</td>
                            <td>
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'bannerManager(this);')); ?>
                                <div id="dialog-banner-manager-config"></div>
                            </td>
                        </tr>   
                        <tr>
                            <td class="left-padding">
                                <label for="isGmapUserLocation">
                                    Is gmap user location:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isGmapUserLocation',
                                        'id' => 'isGmapUserLocation',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->gRow, 'IS_GMAP_USERLOCATION') 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isIgnoreClearFilter">
                                    Request-гүй цэвэрлэх эсэх:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isIgnoreClearFilter',
                                        'id' => 'isIgnoreClearFilter',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->gRow, 'IS_IGNORE_CLEAR_FILTER') 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">
                                <label for="isCustom">
                                    Is custom ws:
                                </label>
                            </td>
                            <td>
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isCustom',
                                        'id' => 'isCustom',
                                        'class' => 'notuniform', 
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->gRow, 'IS_CUSTOM') 
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr class="pf-iscustom-wsurl" style="display: none">
                            <td class="left-padding">Class name:</td>
                            <td colspan="2">
                                <input type="text" id="className" name="className" class="form-control stringInit" value="<?php echo Arr::get($this->gRow, 'CLASS_NAME'); ?>">
                            </td>
                        </tr>
                        <tr class="pf-iscustom-wsurl" style="display: none">
                            <td class="left-padding">Method name:</td>
                            <td colspan="2">
                                <input type="text" id="methodName" name="methodName" class="form-control stringInit" value="<?php echo Arr::get($this->gRow, 'METHOD_NAME'); ?>">
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
                                        'value' => $this->gRow['COLOR_SCHEMA'],
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
                                        'value' => $this->gRow['FORM_CONTROL'],
                                        'data' => Info::formControlType()
                                    )
                                );
                                ?>
                            </td>
                        </tr>
                        <tr class="ddatamodel-grid-option">
                            <td class="left-padding"><?php echo $this->lang->line('Drag rows process'); ?></td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="dragRowsRunProcessId" name="dragRowsRunProcessId" type="hidden" value="<?php echo Arr::get($this->gRow, 'DRAG_ROWS_RUN_PROCESS_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->gRow, 'DRAG_ROWS_RUN_PROCESS_CODE'); ?>" title="<?php echo Arr::get($this->gRow, 'DRAG_ROWS_RUN_PROCESS_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->gRow, 'DRAG_ROWS_RUN_PROCESS_NAME'); ?>" title="<?php echo Arr::get($this->gRow, 'DRAG_ROWS_RUN_PROCESS_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                        </span>     
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="tab-pane" id="group_link_links_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>    
                        <?php
                        $refStructureId = Arr::get($this->gRow, 'REF_STRUCTURE_ID');
                        $refStructureHideClass = '';
                        if (!$refStructureId) {
                            $refStructureHideClass = ' d-none';
                        }
                        ?>
                        <tr class="datamodel-refstructure<?php echo $refStructureHideClass; ?>">
                            <td style="width: 170px; height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('MET_330111'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1469764796675829&dv[metadataid][]=<?php echo $refStructureId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px; height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00012'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1456486793436&dv[processntfid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00013'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1470288034989&dv[srcmetadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00152'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1456925359425&dv[metadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00014'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1469942766992&dv[mainmetadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label><?php echo $this->lang->line('META_00036'); ?></label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1530880780126&dv[dvmetadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td style="height: 32px" class="left-padding">
                                <label>First ignore load</label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1530154069209705&dv[dvmetadataid][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
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
<?php echo Form::hidden(array('name' => 'groupLinkId', 'value' => $this->gRow['ID'])); ?>

<script type="text/javascript">
$(function () {
    visibleGroupTypeAttr();
    visibleWindowSizeAttr();
    checkIsEntity($("#isEntity"));

    $("#groupType").on("change", function () {
        visibleGroupTypeAttr();
    });
    $("#windowSize").on("change", function () {
        visibleWindowSizeAttr();
    });
    $("#repMetaGroupId").on("change", function () {
        var data = $(this).select2('data');
        if (data != null) {
            var result = data.text.split(/[\s-]+/);
            if (data.id.length > 0) {
                $("#tableName").val(result[result.length-1]);
            } else {
                $("#tableName").val('');
            }
        } else {
            $("#tableName").val('');
        }
    });
    $("#isEntity").on("click", function () {
        checkIsEntity($(this));
    });      
    $('#externalMetaDataId').on('change', function () {
        var externalMetaDataId = $(this).val();
        if (externalMetaDataId) {
            $('.pf-dv-wsurl').show();
        } else {
            $('.pf-dv-wsurl').hide();
            $('#wsUrl').val('');
        }
    });
    $('#externalMetaDataId').trigger('change');
    
    $('#isCustom').on('change', function () {
        if ($(this).is(':checked')) {
            $('.pf-iscustom-wsurl').show();
        } else {
            $('.pf-iscustom-wsurl').hide();
        }
    });
    $('#isCustom').trigger('change');
    
    $('#repStructureId').on('change', function () {
        var refStructureId = $(this).val();
        var $tr = $('.datamodel-refstructure');
        
        if (refStructureId) {
            $tr.removeClass('d-none');
            $tr.find('a').attr('href', 'mdobject/dataview/1469764796675829&dv[metadataid][]='+refStructureId);
        } else {
            $tr.addClass('d-none');
        }
    });
});

function checkIsEntity(elem) {
    var $this = elem;
    if ($this.prop('checked')) {
        $this.val('1');
        $(".datamodel-visible-workflow").show();
    } else {
        $this.val('0');
        $(".datamodel-visible-workflow").hide();
    }
}
function visibleGroupTypeAttr() {
    var groupType = $("#groupType").val();

    if (groupType === 'dataview') {
        $(".datamodel-column-count, .datamodel-label-position, .datamodel-label-width, .datamodel-window-type, .datamodel-window-height").hide();
        $(".datamodel-rep-meta-groupid, .datamodel-lifecycle-book, .datamodel-visible-workflow, .datamodel-tablename, .datamodel-listname, .datamodel-is-entity, .datamodel-process-dtl, .datamodel-grid-option, .datamodel-is-treeview").show();
        if (groupType === 'tablestructure' && $("#tableName").val() === '') {
            $("#tableName").val("PT_");
        }
    } else if (groupType === 'tablestructure') {
        $(".datamodel-column-count, .datamodel-label-position, .datamodel-label-width, .datamodel-window-type, .datamodel-window-height, .datamodel-is-treeview").hide();
        $(".datamodel-rep-meta-groupid, .datamodel-lifecycle-book, .datamodel-visible-workflow, .datamodel-tablename, .datamodel-listname, .datamodel-is-entity, .datamodel-process-dtl, .datamodel-grid-option").show();
        if (groupType === 'tablestructure' && $("input#tableName").val() === '') {
            $("#tableName").val("PT_");
        }
    } else {
        $(".datamodel-rep-meta-groupid, .datamodel-lifecycle-book, .datamodel-visible-workflow, .datamodel-tablename, .datamodel-listname, .datamodel-visible-workflow, .datamodel-is-entity, .datamodel-process-dtl, .datamodel-grid-option").hide();
        $(".datamodel-column-count, .datamodel-label-position, .datamodel-label-width, .datamodel-is-treeview, .datamodel-window-size, .datamodel-window-type, .datamodel-window-height").show();
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
function setDataModelProcessDtl(elem) {
    var $dialogName = 'dialog-process-dtl';

    if ($("#" + $dialogName).children().length > 0) {
        $("#" + $dialogName).dialog({
            appendTo: "form#editMetaSystemForm",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'DataModel Process',
            width: 1200,
            minWidth: 1000,
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
            
        $.ajax({
            type: 'post',
            url: 'mdmetadata/setDataModelProcessEditMode',
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
                if (!$().iconpicker) {
                    $.cachedScript('assets/custom/addon/plugins/bootstrap-iconpicker/js/bootstrap-iconpicker.min.js?v=1').done(function() {      
                        $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/bootstrap-iconpicker/css/bootstrap-iconpicker.min.css"/>');
                    });
                }
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
                    width: 1200,
                    minWidth: 1000,
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
            }
        }).done(function () {
            Core.initAjax($("#" + $dialogName));
        });
    }
}
function setDataModelGridOption(elem) {
    var $dialogName = 'dialog-dv-grid-option';
    var $dialog = $('#'+$dialogName);

    if ($dialog.children().length > 0) {
        $dialog.dialog({
            appendTo: "form#editMetaSystemForm",
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
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                    $dialog.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $dialog.empty().dialog('close');
                }},
                {text: "<?php echo $this->lang->line('META_00002'); ?>", class: 'btn btn-sm red', click: function () {
                    $dialog.empty().dialog('close');
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
            data: {metaDataId: '<?php echo $this->metaDataId; ?>'},
            dataType: "json",
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    appendTo: "form#editMetaSystemForm",
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
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function () {
                            $dialog.dialog('close');
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function () {
                            $dialog.empty().dialog('close');
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
        }).done(function () {
            Core.initNumber($dialog);
        });
    }
}
function lcWorkflowConfig(elem) {
    var $dialogName = 'dialog-lc-workflow-config';
    $.ajax({
        type: 'post',
        url: 'mdmeta/lcWorkflowConfig',
        dataType: "json",
        data: {metaDataId: $("input[name='metaDataId']").val(), metaDataCode: $("input[name='metaDataCode']").val(), metaDataName: $("textarea[name='META_DATA_NAME']").val()},
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
                width: 600,
                minWidth: 600,
                height: "auto",
                modal: true,
                buttons: [
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
function lifecycleBook(elem) {
    var $dialogName = 'dialog-lifecycle-book-config';
    $.ajax({
        type: 'post',
        url: 'mdmeta/lifecycleBookConfig',
        dataType: "json",
        data: {metaDataId: $("input[name='metaDataId']").val(), metaDataName: $("textarea[name='META_DATA_NAME']").val()},
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
                width: 800,
                minWidth: 800,
                height: "auto",
                modal: true,
                buttons: [
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
function initRepMetaGroup() {
    $.ajax({
        type: 'post',
        url: 'mdmeta/initRepMetaGroupData',
        data: {groupType: $("#groupType").val()},
        dataType: "json",
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...',
                boxed: true
            });
        },
        success: function (data) {
            var _select = $("#repMetaGroupId");
            $("option:gt(0)", _select).remove();
            $.each(data, function () {
                _select.append($("<option />").val(this.TABLE_NAME).text(this.TABLE_NAME));
            });
            Core.initSelect2();
            _select.trigger("change");
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
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
function setDvHeaderFooterEditor(elem) {
        
    var $dialogName = 'dialog-tmceTemplateEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var dvExportHeader = '', dvExportFooter = '', dvExportHtml = '0';
    
    if ($("form#editMetaSystemForm").find("textarea#dvExportHeader").length) {
        dvExportHeader = $("form#editMetaSystemForm").find("textarea#dvExportHeader").val();
        dvExportFooter = $("form#editMetaSystemForm").find("textarea#dvExportFooter").val();
        dvExportHtml = '1';
    }
    
    $.ajax({
        type: 'post',
        url: 'mdmeta/setDvHeaderFooterEditor',
        data: {
            dvExportHeader: dvExportHeader, 
            dvExportFooter: dvExportFooter, 
            dvExportHtml: dvExportHtml, 
            metaDataId: '<?php echo $this->metaDataId; ?>'
        },
        dataType: "json",
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 1200,
                minWidth: 1200,
                height: 600,
                modal: false,
                open: function(){
                    tinymce.dom.Event.domLoaded = true;
                    tinymce.baseURL = URL_APP+'assets/custom/addon/plugins/tinymce';
                    tinymce.suffix = ".min";
                    tinymce.init({
                        selector: '.tempEditor',
                        height: '150px',
                        plugins: [
                            'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                            'searchreplace wordcount visualblocks visualchars code fullscreen',
                            'insertdatetime media nonbreaking save table contextmenu directionality codemirror',
                            'emoticons template paste textcolor colorpicker textpattern imagetools moxiemanager mention lineheight'
                        ],
                        toolbar1: 'undo redo | styleselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image',
                        toolbar2: 'print preview | forecolor backcolor | fontselect | fontsizeselect | lineheightselect | table | fullscreen | code',
                        fontsize_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 36px 8pt 9pt 10pt 11pt 12pt 13pt 14pt 15pt 16pt 17pt 18pt 19pt 20pt 21pt 22pt 23pt 24pt 25pt 36pt', 
                        lineheight_formats: '8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 1.0 1.15 1.5 2.0 2.5 3.0',
                        image_advtab: true, 
                        force_br_newlines: true,
                        force_p_newlines: false, 
                        apply_source_formatting: true, 
                        remove_linebreaks: false,
                        forced_root_block: '', 
                        paste_data_images: true, 
                        table_toolbar: '', 
                        table_class_list: [
                            {title: 'None', value: ''}, 
                            {title: 'No border', value: 'pf-report-table-none'}, 
                            {title: 'Dotted', value: 'pf-report-table-dotted'}, 
                            {title: 'Dashed', value: 'pf-report-table-dashed'},  
                            {title: 'Solid', value: 'pf-report-table-solid'}
                        ], 
                        paste_word_valid_elements: 'b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font',
                        mentions: {
                            delimiter: '#',
                            delay: 0, 
                            queryBy: 'META_DATA_CODE', 
                            source: function (query, process, delimiter) {
                                $.ajax({
                                    type: "post",
                                    url: "mdstatement/getAllVariablesByJson",
                                    data: {reportType: $("#reportType").val(), dataViewId: $("#dataViewId").val()}, 
                                    dataType: 'json', 
                                    success: function(data){
                                        process(data);
                                    }
                                });
                            }, 
                            render: function(item) {
                                return '<li>' +
                                           '<a href="javascript:;">' + item.META_DATA_CODE + ' - '+item.META_DATA_NAME+'</a>' +
                                       '</li>';
                            },
                            insert: function(item) {
                                return '#'+item.meta_data_code+'#';
                            }
                        }, 
                        codemirror: {
                            indentOnInit: true, 
                            fullscreen: false,   
                            path: 'codemirror', 
                            config: {           
                                mode: 'text/html',
                                styleActiveLine: true,
                                lineNumbers: true, 
                                lineWrapping: true,
                                matchBrackets: true,
                                autoCloseBrackets: true,
                                indentUnit: 4, 
                                foldGutter: true,
                                gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"], 
                                extraKeys: {
                                    "F11": function(cm) {
                                        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                                    },
                                    "Esc": function(cm) {
                                        if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                                    }, 
                                    "Ctrl-Q": function(cm) { 
                                        cm.foldCode(cm.getCursor()); 
                                    }, 
                                    "Ctrl-Space": "autocomplete"
                                }
                            },
                            width: 950,        
                            height: 600,        
                            saveCursorPosition: false,    
                            jsFiles: [          
                                'mode/clike/clike.js',
                                'mode/htmlmixed/htmlmixed.js', 
                                'mode/css/css.js', 
                                'mode/xml/xml.js', 
                                'addon/fold/foldcode.js', 
                                'addon/fold/foldgutter.js', 
                                'addon/fold/brace-fold.js', 
                                'addon/fold/xml-fold.js', 
                                'addon/fold/indent-fold.js', 
                                'addon/fold/comment-fold.js', 
                                'addon/hint/show-hint.js', 
                                'addon/hint/xml-hint.js', 
                                'addon/hint/html-hint.js', 
                                'addon/hint/css-hint.js'
                            ]
                        }, 
                        setup: function(editor) {
                            editor.on('init', function() {
                                $('textarea.tempEditor').prev('.mce-container').find('.mce-edit-area')
                                .droppable({
                                    drop: function(event, ui) {
                                        tinymce.activeEditor.execCommand('mceInsertContent', false, '#'+ui.draggable.text()+'#');
                                    }
                                });
                            });
                            editor.on('keydown', function(evt) {    
                                if (evt.keyCode == 9) {
                                    editor.execCommand('mceInsertContent', false, '&emsp;&emsp;');
                                    evt.preventDefault();
                                    return false;
                                }
                            });
                        },  
                        document_base_url: URL_APP, 
                        content_css: URL_APP+'assets/custom/css/print/tinymce.css'
                    });
                }, 
                close: function () {
                    tinymce.remove('textarea');
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                        tinymce.triggerSave();

                        var reportHeaderValue = tinymce.get('tempHeader').getContent();
                        var reportFooterValue = tinymce.get('tempFooter').getContent();
                        
                        if ($("form#editMetaSystemForm").find("textarea#dvExportHeader").length) {
                            $("form#editMetaSystemForm").find("textarea#dvExportHeader").val(reportHeaderValue);
                            $("form#editMetaSystemForm").find("textarea#dvExportFooter").val(reportFooterValue);
                        } else {
                            $("form#editMetaSystemForm").append('<textarea name="dvExportHeader" id="dvExportHeader" class="display-none"></textarea>');
                            $("form#editMetaSystemForm").append('<textarea name="dvExportFooter" id="dvExportFooter" class="display-none"></textarea>');
                            $("form#editMetaSystemForm").find("textarea#dvExportHeader").val(reportHeaderValue);
                            $("form#editMetaSystemForm").find("textarea#dvExportFooter").val(reportFooterValue);
                        }
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
                    $dialog.find("div.report-tags").css("height", dialogHeight);
                    $dialog.find("div.report-tags").css("max-height", dialogHeight);
                }, 
                "restore" : function() { 
                    var dialogHeight = $dialog.height();
                    $dialog.find("div.report-tags").css("height", dialogHeight);
                    $dialog.find("div.report-tags").css("max-height", dialogHeight);
                }
            });
            $dialog.dialog('open');
            $dialog.dialogExtend('maximize');
            Core.unblockUI();
        }
    });
}
function setParamAttributes(elem, isQueryToParams) {

    var $dialogName = 'dialog-paramattributes';
    var $dialogContainer = $('#' + $dialogName);
    
    if ($("#" + $dialogName).children().length > 0) {
        
        var $detachedChildren = $dialogContainer.children().detach();
        
        $dialogContainer.dialog({
            appendTo: 'form#editMetaSystemForm',
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
        
        var postData = {metaDataId: '<?php echo $this->metaDataId; ?>'};
        
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
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
                if (!$("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length) {
                    $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                    $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js');
                }
            },
            success: function(data) {
                
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
                    
                    $dialogContainer.empty().append(data.Html);

                    var $detachedChildren = $dialogContainer.children().detach();

                    $dialogContainer.dialog({
                        appendTo: 'form#editMetaSystemForm',
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
                    $dialogContainer.dialogExtend('maximize');
                }
            }
        }).done(function () {
            Core.initNumber($dialogContainer);
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
                query: $parent.find('textarea[name*="tableName"]').val(), 
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
                            
                            $parent.find('textarea[name*="tableName"]').val(dvSqlQueryEditor.getValue()).trigger('change');
                            $parent.find('textarea#postgreSql').val(postgreSqlEditor.getValue());
                            $parent.find('textarea#msSql').val(msSqlEditor.getValue());
                            
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
                $dialog.dialogExtend('maximize');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
                Core.unblockUI();
            }
        });
    });
}
function dvChildSql(elem) {
    var $dialogName = 'dialog-dv-childquery';

    if ($('#' + $dialogName).children().length > 0) {
        $('#' + $dialogName).dialog({
            appendTo: 'form#editMetaSystemForm',
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Dataview sub query',
            width: 950,
            minWidth: 950,
            height: 'auto',
            modal: true,
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
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
        $("#" + $dialogName).dialogExtend('maximize');
    } else {
        $.ajax({
            type: 'post',
            url: 'mdmeta/dvChildSql',
            data: {id: '<?php echo $this->gRow['ID']; ?>',editMode: true},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function (data) {
                $("#" + $dialogName).empty().append(data.html);
                $("#" + $dialogName).dialog({
                    appendTo: 'form#editMetaSystemForm',
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 950,
                    minWidth: 950,
                    height: 'auto',
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
                $("#" + $dialogName).dialogExtend('maximize');
                Core.unblockUI();
            },
            error: function () {
                alert("Error");
            }
        }).done(function () {
            Core.initNumber($("#" + $dialogName));
            Core.initUniform($("#" + $dialogName));
        });
    }
}
</script>
