<div class="dv-main-opts">
    <span class="d-block text-blue mb-2"><?php echo $this->lang->line('dv_setting_group_main'); ?></span>
    <div class="row mb-2">
        <div class="col-4 mb-2 datamodel-tablename">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_object'); ?></label>
                <div class="col-lg-8 p-0 object-parent-cell">
                    <div class="input-group">
                        <?php
                        echo Form::textArea(
                            array(
                                'name' => 'tableName',
                                'id' => 'tableName',
                                'class' => 'form-control', 
                                'style' => 'min-height: 31px; height: 31px; resize:vertical; display: block',
                                'value' => (new Mdmetadata())->objectDeCompress($this->bpRow['TABLE_NAME'])
                            )
                        );
                        echo Form::textArea(
                            array(
                                'name' => 'postgreSql',
                                'id' => 'postgreSql', 
                                'style' => 'display: none',
                                'value' => (new Mdmetadata())->objectDeCompress($this->bpRow['POSTGRE_SQL'])
                            )
                        );
                        echo Form::textArea(
                            array(
                                'name' => 'msSql',
                                'id' => 'msSql', 
                                'style' => 'display: none',
                                'value' => (new Mdmetadata())->objectDeCompress($this->bpRow['MS_SQL'])
                            )
                        );
                        ?>
                        <span class="input-group-append input-group-btn-vertical">
                            <button type="button" class="btn btn-sm blue mr0" onclick="dvSqlViewEditor(this);" title="Query editor"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-sm green mr0" onclick="dvChildSql('<?php echo $this->metaDataId; ?>');" title="Sub query"><i class="fa fa-reorder"></i></button>
                            <div id="dialog-dv-childquery"></div>
                        </span>
                    </div>
                </div>
            </div>
        </div>   
        <div class="col-4 mb-2 datamodel-listname">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_listname'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::text(
                        array(
                            'name'  => 'listName',
                            'id'    => 'listName',
                            'class' => 'form-control textInit globeCodeInput', 
                            'value' => $this->bpRow['LIST_NAME'],
                            'title' => $this->lang->line($this->bpRow['LIST_NAME'])
                        )
                    );
                    ?>
                </div>
            </div>
        </div>     
        <div class="col-4 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_searchtype'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'searchType',
                            'id' => 'searchType',
                            'class' => 'form-control select2',
                            'op_value' => 'value',
                            'op_text' => 'name',
                            'value' => $this->bpRow['SEARCH_TYPE'],
                            'data' => Info::searchType()
                        )
                    );
                    ?>
                </div>
            </div>
        </div>  
        <div class="col-4 mb-2 datamodel-rep-meta-groupid d-none">
            <div class="form-group">
                <label class="col-form-label col-lg-4">Ref Group</label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'repMetaGroupId',
                            'id' => 'repMetaGroupId',
                            'class' => 'form-control select2'
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_refstructure'); ?></label>
                <div class="col-lg-8 p-0">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=tablestructure&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="repStructureId" name="repStructureId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'REF_STRUCTURE_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'REF_STRUCTURE_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'REF_STRUCTURE_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'REF_STRUCTURE_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'REF_STRUCTURE_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span> 
                        </div>
                    </div>
                </div>
            </div>
        </div> 
        <div class="col-4 mb-2 datamodel-column-count">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00117'); ?></label>
                <div class="col-lg-8 p-0">
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
                </div>
            </div>
        </div>  
        <div class="col-4 mb-2 datamodel-label-position">
            <div class="form-group">
                <label class="col-form-label col-lg-4">Label Position</label>
                <div class="col-lg-8 p-0">
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
                            'class' => 'form-control',
                            'value' => $this->bpRow['LABEL_POSITION']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-label-width">
            <div class="form-group">
                <label class="col-form-label col-lg-4">Label width</label>
                <div class="col-lg-8 p-0">
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
                </div>
            </div>
        </div>  
        <div class="col-4 mb-2 datamodel-window-type d-none">
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
        <div class="col-4 mb-2 datamodel-window-size d-none">
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
                </div>
            </div>
        </div>  
        <div class="col-4 mb-2 datamodel-window-height">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('META_00100'); ?></label>
                <div class="col-lg-8 p-0">
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
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="isSkipUniqueError"><?php echo $this->lang->line('dv_setting_integrate'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isSkipUniqueError',
                            'id' => 'isSkipUniqueError',
                            'class' => 'notuniform', 
                            'value' => '1',
                            'saved_val' => $this->bpRow['IS_SKIP_UNIQUE_ERROR']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="isUseRtConfig"><?php echo $this->lang->line('dv_setting_showprint'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseRtConfig',
                            'id' => 'isUseRtConfig',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_USE_RT_CONFIG']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_subtype'); ?></label>
                <div class="col-lg-8 p-0">
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
                            'class' => 'form-control',
                            'value' => $this->bpRow['GROUP_TYPE'],
                            'text' => 'notext'
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="isUseWorkFlow"><?php echo $this->lang->line('dv_setting_showstatus'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseWorkFlow',
                            'id' => 'isUseWorkFlow',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_USE_WFM_CONFIG']
                        )
                    );
                    ?>
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

<div class="dv-filter-opts">
    <span class="d-block text-blue mb-2"><?php echo $this->lang->line('dv_setting_group_filter'); ?></span>
    <div class="row">
        <div class="col-2 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isEnterFilter"><?php echo $this->lang->line('dv_setting_enterkeyfilter'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isEnterFilter',
                            'id' => 'isEnterFilter',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_ENTER_FILTER'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isAllNotSearch"><?php echo $this->lang->line('dv_setting_ignoreseeall'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isAllNotSearch',
                            'id' => 'isAllNotSearch',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_ALL_NOT_SEARCH']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="useBasket"><?php echo $this->lang->line('dv_setting_usebasket'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'useBasket',
                            'id' => 'useBasket',
                            'class' => 'notuniform', 
                            'value' => '1',
                            'saved_val' => $this->bpRow['USE_BASKET'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isCriteriaAlwaysOpen"><?php echo $this->lang->line('dv_setting_openfilter'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isCriteriaAlwaysOpen',
                            'id' => 'isCriteriaAlwaysOpen',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_CRITERIA_ALWAYS_OPEN'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="criteriaColCount"><?php echo $this->lang->line('dv_setting_filtercolumn'); ?></label>
                <div class="col-lg-2 p-0">
                    <input type="text" id="criteriaColCount" name="criteriaColCount" class="form-control longInit" data-maxlength="true" maxlength="2" value="<?php echo issetParam($this->bpRow['M_CRITERIA_COL_COUNT']); ?>">
                </div>
            </div>
        </div>
        <div class="col-2 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="criteriaGroupColCount"><?php echo $this->lang->line('dv_setting_filtergroupcolumn'); ?></label>
                <div class="col-lg-2 p-0">
                    <input type="text" id="criteriaGroupColCount" name="criteriaGroupColCount" class="form-control longInit" data-maxlength="true" maxlength="2" value="<?php echo issetParam($this->bpRow['M_GROUP_CRITERIA_COL_COUNT']); ?>">
                </div>
            </div>
        </div>
        <div class="col-2 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isFilterLog"><?php echo $this->lang->line('dv_setting_filterlog'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isFilterLog',
                            'id' => 'isFilterLog',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_FILTER_LOG'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isUseQuickSearch"><?php echo $this->lang->line('dv_setting_quicksearch'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseQuickSearch',
                            'id' => 'isUseQuickSearch',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_USE_QUICKSEARCH']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isClearDrillCriteria"><?php echo $this->lang->line('dv_setting_cleardrillcriteria'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isClearDrillCriteria',
                            'id' => 'isClearDrillCriteria',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_CLEAR_DRILL_CRITERIA'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isCountCartOpen"><?php echo $this->lang->line('dv_setting_opencountcard'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isCountCartOpen',
                            'id' => 'isCountCartOpen',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_COUNTCARD_OPEN'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isParentFilter"><?php echo $this->lang->line('dv_setting_parentfilternull'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isParentFilter',
                            'id' => 'isParentFilter',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => Arr::get($this->bpRow, 'IS_PARENT_FILTER') 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-2 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isUseCompanyDepartmentId">Компаниар шүүх эсэх</label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseCompanyDepartmentId',
                            'id' => 'isUseCompanyDepartmentId',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => Arr::get($this->bpRow, 'IS_USE_COMPANY_DEPARTMENT_ID') 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="mt-1 mb-2">

<div class="dv-ignore-opts">
    <span class="d-block text-blue mb-2"><?php echo $this->lang->line('dv_setting_group_ignore'); ?></span>
    <div class="row mb-2">
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isIgnoreWfmHistory"><?php echo $this->lang->line('dv_setting_ignorewfmhistory'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isIgnoreWfmHistory',
                            'id' => 'isIgnoreWfmHistory',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_IGNORE_WFM_HISTORY'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isIgnoreExcelExport"><?php echo $this->lang->line('dv_setting_ignoreexport'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isIgnoreExcelExport',
                            'id' => 'isIgnoreExcelExport',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_IGNORE_EXCEL_EXPORT'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isIgnoreSorting"><?php echo $this->lang->line('dv_setting_ignoresortorder'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isIgnoreSorting',
                            'id' => 'isIgnoreSorting',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_IGNORE_SORTING'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="mt-2 mb-2">

<div class="dv-button-opts">
    <span class="d-block text-blue mb-2"><?php echo $this->lang->line('dv_setting_group_button'); ?></span>
    <div class="row mb-2">
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isUseSemantic"><?php echo $this->lang->line('dv_setting_usesemantic'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseSemantic',
                            'id' => 'isUseSemantic',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => Arr::get($this->bpRow, 'IS_USE_SEMANTIC') 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isDirectPrint"><?php echo $this->lang->line('dv_setting_usedirectprint'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isDirectPrint',
                            'id' => 'isDirectPrint',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_DIRECT_PRINT'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isUseButtonMap"><?php echo $this->lang->line('dv_setting_usebuttonmap'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseButtonMap',
                            'id' => 'isUseButtonMap',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => Arr::get($this->bpRow, 'IS_USE_BUTTON_MAP') 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="mt-2 mb-2">

<div class="dv-exp-opts">
    <span class="d-block text-blue mb-2"><?php echo $this->lang->line('dv_setting_group_export'); ?></span>
    <div class="row mb-2">
        <div class="col-4 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isExportText"><?php echo $this->lang->line('dv_setting_exporttextfile'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isExportText',
                            'id' => 'isExportText',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_EXPORT_TEXT'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-10"><?php echo $this->lang->line('dv_setting_exportheaderfooter'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setDvHeaderFooterEditor(this);')); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<hr class="mt-2 mb-2">

<div class="dv-design-opts">
    <span class="d-block text-blue mb-2"><?php echo $this->lang->line('dv_setting_group_design'); ?></span>
    <div class="row mb-2">
        <div class="col-3 mb-2 datamodel-is-treeview">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isTreeview"><?php echo $this->lang->line('dv_setting_treeview'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php echo Form::checkbox(array('name' => 'isTreeview', 'id' => 'isTreeview', 'value' => '1', 'saved_val' => $this->bpRow['IS_TREEVIEW'], 'class' => 'notuniform')); ?>
                </div>
            </div>
        </div>
        <div class="col-3 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="listMenuName"><?php echo $this->lang->line('dv_setting_menuname'); ?></label>
                <div class="col-lg-8 p-0">
                    <input type="text" id="listMenuName" name="listMenuName" class="form-control stringInit" value="<?php echo Arr::get($this->bpRow, 'LIST_MENU_NAME'); ?>">
                </div>
            </div>
        </div>
        <div class="col-3 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_buttonstyle'); ?></label>
                <div class="col-lg-8 p-0">
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
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control',
                            'value' => issetParam($this->bpRow['BUTTON_BAR_STYLE'])
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col-3 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="showPosition"><?php echo $this->lang->line('dv_setting_subdvposition'); ?></label>
                <div class="col-lg-8 p-0">
                    <?php
                    echo Form::select(
                        array(
                            'name' => 'showPosition',
                            'id' => 'showPosition',
                            'data' => array(
                                array(
                                    'code' => 'top', 
                                    'name' => 'Top'
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control form-control-sm',
                            'value' => Arr::get($this->bpRow, 'SHOW_POSITION')
                        )
                    );                              
                    ?>
                </div>
            </div>
        </div>
        <div class="col-3 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="panelType"><?php echo $this->lang->line('dv_setting_paneltype'); ?></label>
                <div class="col-lg-8 p-0">
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
                                )
                            ),
                            'op_value' => 'code',
                            'op_text' => 'name',
                            'class' => 'form-control form-control-sm', 
                            'value' => Arr::get($this->bpRow, 'PANEL_TYPE')
                        )
                    );                              
                    ?>
                </div>
            </div>
        </div>
        <div class="col-3 mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="lookupTheme"><?php echo $this->lang->line('dv_setting_lookuptheme'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'lookupTheme',
                            'id' => 'lookupTheme',
                            'class' => 'notuniform', 
                            'value' => '1',
                            'saved_val' => $this->bpRow['IS_LOOKUP_BY_THEME'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="dv-adv-opts">
    <span class="d-block text-blue mb-2 cursor-pointer"><?php echo $this->lang->line('dv_setting_group_advanced'); ?> <i class="icon-diff-added"></i></span>
    <div class="row mb-2" id="dv-adv-opts" style="display: none">
        <div class="col mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isUseResult"><?php echo $this->lang->line('dv_setting_useresult'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseResult',
                            'id' => 'isUseResult',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_USE_RESULT']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col mb-2 datamodel-is-entity">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isEntity"><?php echo $this->lang->line('dv_setting_entity'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isEntity',
                            'id' => 'isEntity',
                            'class' => 'notuniform', 
                            'value' => '1',
                            'saved_val' => $this->bpRow['IS_ENTITY']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isNotGroupBy"><?php echo $this->lang->line('dv_setting_ignoregroupby'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isNotGroupBy',
                            'id' => 'isNotGroupBy',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_NOT_GROUPBY']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="col mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isUseDataMart"><?php echo $this->lang->line('dv_setting_usedatamart'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseDataMart',
                            'id' => 'isUseDataMart',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_USE_DATAMART'] 
                        )
                    );
                    ?>
                </div>
            </div>
        </div>     
        <div class="col mb-2 datamodel-search-type-meta">
            <div class="form-group">
                <label class="col-form-label col-lg-10" for="isUseSidebar"><?php echo $this->lang->line('dv_setting_usesidebar'); ?></label>
                <div class="col-lg-2 p-0">
                    <?php
                    echo Form::checkbox(
                        array(
                            'name' => 'isUseSidebar',
                            'id' => 'isUseSidebar',
                            'class' => 'notuniform', 
                            'value' => '1', 
                            'saved_val' => $this->bpRow['IS_USE_SIDEBAR']
                        )
                    );
                    ?>
                </div>
            </div>
        </div>
        <div class="w-100"></div>
        <div class="col-4 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-8" for="refreshTimer"><?php echo $this->lang->line('dv_setting_refreshtimer'); ?></label>
                <div class="col-lg-4 p-0">
                    <input type="text" id="refreshTimer" name="refreshTimer" class="form-control longInit" value="<?php echo issetParam($this->bpRow['REFRESH_TIMER']); ?>" placeholder="<?php echo $this->lang->line('META_00189'); ?>">
                </div>
            </div>
        </div>
    </div>
</div>

<div class="other-param" style="display: none">
    <span class="d-block text-blue mb-2"><?php echo $this->lang->line('dv_setting_group_lookups'); ?></span>
    <div class="row mb-2">
        <div class="col-4 mb-2">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="externalMetaDataId"><?php echo $this->lang->line('dv_setting_externaldv'); ?></label>
                <div class="col-lg-8 p-0">
                    <input type="text" id="externalMetaDataId" name="externalMetaDataId" class="form-control longInit" value="<?php echo Arr::get($this->bpRow, 'EXTERNAL_META_DATA_ID'); ?>">
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 pf-dv-wsurl">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="wsUrl"><?php echo $this->lang->line('META_00067'); ?></label>
                <div class="col-lg-8 p-0">
                    <input type="text" id="wsUrl" name="wsUrl" class="form-control stringInit" value="<?php echo Arr::get($this->bpRow, 'WS_URL'); ?>">
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_calcprocess'); ?></label>
                <div class="col-lg-8 p-0">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="calculateProcessId" name="calculateProcessId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'CALCULATE_PROCESS_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'CALCULATE_PROCESS_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'CALCULATE_PROCESS_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'CALCULATE_PROCESS_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'CALCULATE_PROCESS_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_quicksearchdv'); ?></label>
                <div class="col-lg-8 p-0">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="quickSearchDvId" name="quickSearchDvId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'QS_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'QS_META_DATA_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'QS_META_DATA_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'QS_META_DATA_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'QS_META_DATA_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4"><?php echo $this->lang->line('dv_setting_layoutmeta'); ?></label>
                <div class="col-lg-8 p-0">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$layoutMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="layoutMetaId" name="layoutMetaId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'LAYOUT_META_DATA_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'LAYOUT_META_DATA_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'LAYOUT_META_DATA_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'LAYOUT_META_DATA_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'LAYOUT_META_DATA_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="wsUrl"><?php echo $this->lang->line('dv_setting_legend'); ?></label>
                <div class="col-lg-8 p-0">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="legendDvId" name="legendDvId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'DATA_LEGEND_DV_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'LEGEND_META_DATA_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'LEGEND_META_DATA_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'LEGEND_META_DATA_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'LEGEND_META_DATA_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>     
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2 datamodel-grid-option">
            <div class="form-group">
                <label class="col-form-label col-lg-4" for="wsUrl"><?php echo $this->lang->line('Rule process'); ?></label>
                <div class="col-lg-8 p-0">
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <input id="ruleProcessId" name="ruleProcessId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'RULE_PROCESS_ID'); ?>">
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'RULE_META_DATA_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'RULE_META_DATA_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'RULE_META_DATA_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'RULE_META_DATA_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                            </span>      
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
    
    $('.dv-adv-opts > span').on('click', function () {
        var $this = $(this), $icon = $this.find('i');
        
        if ($icon.hasClass('icon-diff-added')) {
            
            $icon.removeClass('icon-diff-added').addClass('icon-diff-removed');
            $('#dv-adv-opts, .other-param').show();
            $('.pf-metav2-content').animate({
                scrollTop: 500
            }, 300);
            
        } else {
            $icon.removeClass('icon-diff-removed').addClass('icon-diff-added');
            $('#dv-adv-opts, .other-param').hide();
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

function setDvHeaderFooterEditor(elem) {
        
    var $dialogName = 'dialog-tmceTemplateEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var dvExportHeader = '', dvExportFooter = '', dvExportHtml = '0';
    
    if ($("form#meta-form-v2").find("textarea#dvExportHeader").length) {
        dvExportHeader = $("form#meta-form-v2").find("textarea#dvExportHeader").val();
        dvExportFooter = $("form#meta-form-v2").find("textarea#dvExportFooter").val();
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
                    $.cachedScript('assets/custom/addon/plugins/tinymce/tinymce.min.js').done(function(script, textStatus) {   
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
                    });
                }, 
                close: function () {
                    tinymce.remove('textarea');
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn btn-sm green-meadow bp-btn-subsave', click: function() {
                        tinymce.triggerSave();

                        var reportHeaderValue = tinymce.get('tempHeader').getContent();
                        var reportFooterValue = tinymce.get('tempFooter').getContent();
                        
                        if ($("form#meta-form-v2").find("textarea#dvExportHeader").length) {
                            $("form#meta-form-v2").find("textarea#dvExportHeader").val(reportHeaderValue);
                            $("form#meta-form-v2").find("textarea#dvExportFooter").val(reportFooterValue);
                        } else {
                            $("form#meta-form-v2").append('<textarea name="dvExportHeader" id="dvExportHeader" class="display-none"></textarea>');
                            $("form#meta-form-v2").append('<textarea name="dvExportFooter" id="dvExportFooter" class="display-none"></textarea>');
                            $("form#meta-form-v2").find("textarea#dvExportHeader").val(reportHeaderValue);
                            $("form#meta-form-v2").find("textarea#dvExportFooter").val(reportFooterValue);
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
function dvSqlViewEditor(elem) {

    var $dialogName = 'dialog-dvSqlViewEditor';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
    }
    var $dialog = $('#' + $dialogName);
    var $parent = $(elem).closest('.object-parent-cell');
                                            
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
                Core.blockUI({message: 'Loading...', boxed: true});
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
                                    Core.blockUI({message: 'Formatting...', boxed: true});
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
                        {text: data.save_btn, class: 'btn btn-sm green-meadow bp-btn-subsave', click: function() {

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
    var $dialog = $('#' + $dialogName);
    
    if ($dialog.children().length > 0) {
        $dialog.dialog({
            appendTo: 'form#meta-form-v2',
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
                {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-subsave', click: function () {
                    $dialog.dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
                    $dialog.dialog('close');
                }},
                {text: plang.get('META_00002'), class: 'btn btn-sm red', click: function () {
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
            }
        });
        $dialog.dialog('open');
        $dialog.dialogExtend('maximize');
    } else {
        $.ajax({
            type: 'post',
            url: 'mdmeta/dvChildSql',
            data: {id: '<?php echo $this->bpRow['ID']; ?>', editMode: true},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                $dialog.empty().append(data.html);
                $dialog.dialog({
                    appendTo: 'form#meta-form-v2',
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
                        {text: plang.get('save_btn'), class: 'btn btn-sm green-meadow bp-btn-subsave', click: function () {
                            $dialog.dialog('close');
                        }},
                        {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function () {
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
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                Core.unblockUI();
            },
            error: function () { alert("Error"); }
        }).done(function () {
            Core.initNumber($dialog);
            Core.initUniform($dialog);
        });
    }
}
</script>
