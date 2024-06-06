<div class="tabbable-line">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#st_link_main_tab" data-toggle="tab" class="nav-link pt0 active"><?php echo $this->lang->line('META_00008'); ?></a>
        </li>
        <?php
        if (defined('CONFIG_REPORT_SERVER_ADDRESS') && CONFIG_REPORT_SERVER_ADDRESS) {
        ?>
        <li class="nav-item">
            <a href="#st_link_template_tab" data-toggle="tab" class="nav-link pt0"><?php echo $this->lang->line('MET_99990923'); ?></a>
        </li>
        <?php
        }
        ?>
        <li class="nav-item">
            <a href="#st_link_other_tab" data-toggle="tab" class="nav-link pt0"><?php echo $this->lang->line('META_00098'); ?></a>
        </li>
        <li class="nav-item">
            <a href="#st_link_links_tab" data-toggle="tab" class="nav-link pt0"><?php echo $this->lang->line('META_00151'); ?></a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="st_link_main_tab">
            <div class="panel panel-default bg-inverse">
                <?php
                echo Form::hidden(array('name' => 'reportType', 'id' => 'reportType', 'value' => Arr::get($this->bpRow, 'REPORT_TYPE')));
                ?> 
                <table class="table sheetTable">
                    <tbody>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><?php echo $this->lang->line('report_name'); ?>:</td>
                            <td colspan="2">
                                <?php echo Form::text(array('name' => 'reportName', 'id' => 'reportName', 'class' => 'form-control globeCodeInput', 'value' => Arr::get($this->bpRow, 'REPORT_NAME'))); ?>  
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Data View:</td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="dataViewId" name="dataViewId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'DATA_VIEW_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->bpRow, 'DATA_VIEW_CODE'); ?>">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->bpRow, 'DATA_VIEW_NAME'); ?>">      
                                        </span>     
                                    </div>
                                </div>  
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Бодолтын процесс:</td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="calcProcessId" name="calcProcessId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'PROCESS_META_DATA_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" value="<?php echo Arr::get($this->bpRow, 'PROCESS_CODE'); ?>">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" value="<?php echo Arr::get($this->bpRow, 'PROCESS_NAME'); ?>">      
                                        </span>     
                                    </div>
                                </div>  
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Бодолтын дараалал:</td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'calcOrderNum',
                                        'id' => 'calcOrderNum',
                                        'class' => 'form-control',
                                        'value' => Arr::get($this->bpRow, 'CALC_ORDER_NUM')
                                    )
                                );
                                ?>  
                            </td>
                        </tr>
                        <?php
                        if (defined('CONFIG_REPORT_SERVER_ADDRESS') && CONFIG_REPORT_SERVER_ADDRESS) {
                        ?>
                        <tr>
                            <td class="left-padding">Iframe report</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'iframeReportDesigner(this, \''.$this->metaDataId.'\', \''.$this->bpRow['DATA_VIEW_ID'].'\');')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Iframe report загвар хуулах</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'iframeReportTemplateCopy(\''.$this->metaDataId.'\', \''.$this->bpRow['DATA_VIEW_ID'].'\');')); ?>
                            </td>
                        </tr>
                        <tr class="system-meta-group-id">
                            <td class="left-padding">Expand Data View:</td>
                            <td colspan="2">
                                <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=dataview&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                                    <div class="input-group double-between-input">
                                        <input id="groupDataViewId" name="groupDataViewId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'GROUP_DATA_VIEW_ID'); ?>">
                                        <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo Arr::get($this->bpRow, 'GROUP_DATA_VIEW_CODE'); ?>" title="<?php echo Arr::get($this->bpRow, 'GROUP_DATA_VIEW_CODE'); ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
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
                                            <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo Arr::get($this->bpRow, 'GROUP_DATA_VIEW_NAME'); ?>" title="<?php echo Arr::get($this->bpRow, 'GROUP_DATA_VIEW_NAME'); ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                        </span>     
                                    </div>
                                </div>      
                            </td>
                        </tr>   
                        <?php
                        } else {
                        ?>
                        <tr class="system-meta-group-id hide">
                            <td></td>
                            <td colspan="2">
                                <input id="groupDataViewId" name="groupDataViewId" type="hidden" value="<?php echo Arr::get($this->bpRow, 'GROUP_DATA_VIEW_ID'); ?>">  
                            </td>
                        </tr>
                        <?php
                        }
                        ?>
                        <tr>
                            <td class="left-padding">Report expression</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'pageHeader', 'value' => '...', 'onclick' => 'reportExpressionCode(this);')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Page header</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'pageHeader', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Report header</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'reportHeader', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                            </td>
                        </tr>
                        <tr class="dataview">
                            <td class="left-padding">Report Grouping</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'value' => '...', 'onclick' => 'setReportGrouping(this);')); ?>
                                <div id="dialog-report-grouping"></div>
                            </td>
                        </tr>
                        <tr class="dataview">
                            <td class="left-padding">Report detail</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'reportDetail', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Report footer</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'reportFooter', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Page footer</td>
                            <td colspan="2">
                                <?php echo Form::button(array('class' => 'btn btn-sm purple-plum', 'name' => 'pageFooter', 'value' => '...', 'onclick' => 'setTmceReportEditor(this);')); ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="left-padding">Page size</td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'pageSize',
                                        'id' => 'pageSize',
                                        'data' => array(
                                            array(
                                                'id' => 'a4',
                                                'name' => 'A4'
                                            ),
                                            array(
                                                'id' => 'a3',
                                                'name' => 'A3'
                                            ),
                                            array(
                                                'id' => 'custom',
                                                'name' => 'Custom'
                                            )
                                        ),
                                        'op_value' => 'id',
                                        'op_text' => 'name',
                                        'value' => Arr::get($this->bpRow, 'PAGE_SIZE'), 
                                        'class' => 'form-control select2'
                                    )
                                );
                                ?>     
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding">Page orientation</td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'pageOrientation',
                                        'id' => 'pageOrientation',
                                        'data' => array(
                                            array(
                                                'id' => 'portrait',
                                                'name' => 'Босоо'
                                            ),
                                            array(
                                                'id' => 'landscape',
                                                'name' => 'Хэвтээ'
                                            )
                                        ),
                                        'op_value' => 'id',
                                        'op_text' => 'name',
                                        'value' => Arr::get($this->bpRow, 'PAGE_ORIENTATION'), 
                                        'class' => 'form-control select2'
                                    )
                                );
                                ?>     
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding">Margin top</td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'pageMarginTop',
                                        'id' => 'pageMarginTop',
                                        'class' => 'form-control',
                                        'value' => Arr::get($this->bpRow, 'PAGE_MARGIN_TOP')
                                    )
                                );
                                ?>  
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding">Margin left</td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'pageMarginLeft',
                                        'id' => 'pageMarginLeft',
                                        'class' => 'form-control',
                                        'value' => Arr::get($this->bpRow, 'PAGE_MARGIN_LEFT')
                                    )
                                );
                                ?>  
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding">Margin right</td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'pageMarginRight',
                                        'id' => 'pageMarginRight',
                                        'class' => 'form-control',
                                        'value' => Arr::get($this->bpRow, 'PAGE_MARGIN_RIGHT')
                                    )
                                );
                                ?>  
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding">Margin bottom</td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'pageMarginBottom',
                                        'id' => 'pageMarginBottom',
                                        'class' => 'form-control',
                                        'value' => Arr::get($this->bpRow, 'PAGE_MARGIN_BOTTOM')
                                    )
                                );
                                ?>  
                            </td>
                        </tr>
                        <tr class="statement-page-width">
                            <td style="width: 170px;" class="left-padding">Page width</td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'pageWidth',
                                        'id' => 'pageWidth',
                                        'class' => 'form-control',
                                        'value' => Arr::get($this->bpRow, 'PAGE_WIDTH')
                                    )
                                );
                                ?>  
                            </td>
                        </tr>
                        <tr class="statement-page-height">
                            <td style="width: 170px;" class="left-padding">Page height</td>
                            <td colspan="2">
                                <?php
                                echo Form::text(
                                    array(
                                        'name' => 'pageHeight',
                                        'id' => 'pageHeight',
                                        'class' => 'form-control',
                                        'value' => Arr::get($this->bpRow, 'PAGE_HEIGHT')
                                    )
                                );
                                ?>  
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding">Font family</td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'fontFamily',
                                        'id' => 'fontFamily',
                                        'data' => array(
                                            array(
                                                'id' => 'arial, helvetica, sans-serif',
                                                'name' => 'Arial'
                                            ),
                                            array(
                                                'id' => "'times new roman', times, serif",
                                                'name' => 'Times new roman'
                                            )
                                        ),
                                        'op_value' => 'id',
                                        'op_text' => 'name',
                                        'value' => Arr::get($this->bpRow, 'FONT_FAMILY'), 
                                        'class' => 'form-control select2', 
                                        'text' => 'notext'
                                    )
                                );
                                ?>     
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding">Render Type</td>
                            <td colspan="2">
                                <?php
                                echo Form::select(
                                    array(
                                        'name' => 'renderType',
                                        'id' => 'renderType',
                                        'data' => array(
                                            array(
                                                'id' => 'list',
                                                'name' => 'List'
                                            ),
                                            array(
                                                'id' => 'card',
                                                'name' => 'Card'
                                            ),
                                            array(
                                                'id' => 'notloop',
                                                'name' => 'Not loop'
                                            )
                                        ),
                                        'op_value' => 'id',
                                        'op_text' => 'name',
                                        'class' => 'form-control select2',
                                        'text' => 'notext', 
                                        'value' => Arr::get($this->bpRow, 'RENDER_TYPE')
                                    )
                                );
                                ?>     
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isHdrRepeatPage">Is Header Repeat Page</label></td>
                            <td colspan="2"> 
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isHdrRepeatPage',
                                        'id' => 'isHdrRepeatPage',
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->bpRow, 'IS_HDR_REPEAT_PAGE')
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <textarea name="reportHeader" id="reportHeader" class="display-none"><?php echo Arr::get($this->bpRow, 'REPORT_HEADER'); ?></textarea>
                <textarea name="pageHeader" id="pageHeader" class="display-none"><?php echo Arr::get($this->bpRow, 'PAGE_HEADER'); ?></textarea>
                <textarea name="reportDetail" id="reportDetail" class="display-none"><?php echo Arr::get($this->bpRow, 'REPORT_DETAIL'); ?></textarea>
                <textarea name="pageFooter" id="pageFooter" class="display-none"><?php echo Arr::get($this->bpRow, 'PAGE_FOOTER'); ?></textarea>
                <textarea name="reportFooter" id="reportFooter" class="display-none"><?php echo Arr::get($this->bpRow, 'REPORT_FOOTER'); ?></textarea>
            </div>
        </div>
        <?php
        if (defined('CONFIG_REPORT_SERVER_ADDRESS') && CONFIG_REPORT_SERVER_ADDRESS) {
        ?>
        <div class="tab-pane" id="st_link_template_tab">
            <table class="table table-hover" id="rp-template-tbl">
                <thead>
                    <tr>
                        <th style="width: 15px;">№</th>
                        <th>Тайлангийн нэр</th>
                        <th style="width: 100px;" class="text-center"><i class="fa fa-cogs"></i></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($this->templateList) {
                        foreach ($this->templateList as $n => $tempRow) {
                    ?>
                    <tr>
                        <td><?php echo ++$n; ?></td>
                        <td>
                            <div style="float: left">
                                <?php echo $tempRow['META_DATA_CODE']; ?> 
                            </div>
                            <div style="float: right">
                                <?php echo $tempRow['TRG_META_DATA_ID']; ?>
                            </div>
                            <div class="clearfix w-100"></div>
                            <strong><?php echo $tempRow['REPORT_NAME']; ?></strong>
                        <input type="hidden" name="templateStatementMetaId[]" value="<?php echo $tempRow['TRG_META_DATA_ID']; ?>">
                        </td>
                        <td class="text-right">
                            <button type="button" class="btn btn-sm purple-plum" onclick="iframeReportTemplateDesigner('<?php echo $tempRow['TRG_META_DATA_ID']; ?>', '<?php echo $tempRow['GROUP_DATA_VIEW_ID']; ?>');" title="Загвар засах"><i class="fa fa-edit"></i></button>
                            <button type="button" class="btn btn-sm red" onclick="iframeReportTemplateRemove(this);" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php
                        }
                    }
                    ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3">
                            <a href="javascript:;" class="btn green btn-xs" onclick="addReportTemplateDtl(this);"><i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?></a>
                        </td>
                    </tr>
                </tfoot>
            </table>    
        </div>
        <?php
        }
        ?>
        <div class="tab-pane" id="st_link_other_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isNotPageBreak">Not PageBreak</label></td>
                            <td colspan="2"> 
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isNotPageBreak',
                                        'id' => 'isNotPageBreak',
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->bpRow, 'IS_NOT_PAGE_BREAK')
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isArchive"><?php echo $this->lang->line('MET_330203'); ?></label></td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isArchive',
                                            'id' => 'isArchive',
                                            'value' => '1', 
                                            'saved_val' => Arr::get($this->bpRow, 'IS_ARCHIVE')
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isBlank">Is blank</label></td>
                            <td colspan="2">
                                <div class="checkbox-list">
                                    <?php
                                    echo Form::checkbox(
                                        array(
                                            'name' => 'isBlank',
                                            'id' => 'isBlank',
                                            'value' => '1', 
                                            'saved_val' => Arr::get($this->bpRow, 'IS_BLANK')
                                        )
                                    );
                                    ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isShowDvBtn">Is show dv button</label></td>
                            <td colspan="2"> 
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isShowDvBtn',
                                        'id' => 'isShowDvBtn',
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->bpRow, 'IS_SHOW_DV_BTN')
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isUseSelfDv">Is use self dv</label></td>
                            <td colspan="2"> 
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isUseSelfDv',
                                        'id' => 'isUseSelfDv',
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->bpRow, 'IS_USE_SELF_DV')
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isAutoFilter">Is auto filter</label></td>
                            <td colspan="2"> 
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isAutoFilter',
                                        'id' => 'isAutoFilter',
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->bpRow, 'IS_AUTO_FILTER')
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isExportNoFooter">Is export no footer</label></td>
                            <td colspan="2"> 
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isExportNoFooter',
                                        'id' => 'isExportNoFooter',
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->bpRow, 'IS_EXPORT_NO_FOOTER')
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isGroupMerge">Груп нийлүүлэх эсэх</label></td>
                            <td colspan="2"> 
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isGroupMerge',
                                        'id' => 'isGroupMerge',
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->bpRow, 'IS_GROUP_MERGE')
                                    )
                                );
                                ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td style="width: 170px;" class="left-padding"><label for="isTimetable"><?php echo Lang::line('isTimetable') ?></label></td>
                            <td colspan="2"> 
                                <div class="checkbox-list">
                                <?php
                                echo Form::checkbox(
                                    array(
                                        'name' => 'isTimetable',
                                        'id' => 'isTimetable',
                                        'value' => '1', 
                                        'saved_val' => Arr::get($this->bpRow, 'IS_TIMETABLE')
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
        <div class="tab-pane" id="st_link_links_tab">
            <div class="panel panel-default bg-inverse">
                <table class="table sheetTable">
                    <tbody>
                        <tr>
                            <td style="width: 170px; height: 32px" class="left-padding">
                                <label>Дрилл:</label>
                            </td>
                            <td colspan="2">
                                <a href="mdobject/dataview/1463102784977177&dv[id][]=<?php echo $this->metaDataId; ?>" class="btn btn-sm purple-plum" target="_blank"><i class="fa fa-external-link-square"></i></a>
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

<script type="text/javascript">
    function addReportTemplateDtl(elem) {
        commonMetaDataGrid('multi', 'metaObject', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$statementMetaTypeId; ?>', 'reportTemplateAddRow', elem);
    }
    function reportTemplateAddRow(chooseType, elem, params, _this) {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        
        if (metaBasketNum > 0) {
            
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            var processDtlTbl = $("table#rp-template-tbl tbody");
            
            for (var i = 0; i < rows.length; i++) {
                
                var row = rows[i];
                var isAddRow = true;
                
                processDtlTbl.find("> tr").each(function() {
                    if ($(this).find("input[name*='templateStatementMetaId[']").val() == row.META_DATA_ID) {
                        isAddRow = false;
                    }
                });

                if (isAddRow) {
                    processDtlTbl.append('<tr>'+
                        '<td>1</td>'+
                        '<td>'+
                        '<div style="float: left">'+
                            row.META_DATA_CODE+ 
                        '</div>' +
                        '<div style="float: right">'+
                            row.META_DATA_ID+ 
                        '</div>' +
                        '<div class="clearfix w-100"></div>'+
                        '<strong>'+
                        row.META_DATA_NAME +
                        '</strong>'+
                        '<input type="hidden" name="templateStatementMetaId[]" value="'+row.META_DATA_ID+'">'+
                        '</td>'+
                        '<td class="text-right">'+
                            '<button type="button" class="btn btn-sm purple-plum" onclick="iframeReportTemplateDesigner(\''+row.META_DATA_ID+'\');" title="Загвар засах"><i class="fa fa-edit"></i></button>'+
                            '<button type="button" class="btn btn-sm red" onclick="iframeReportTemplateRemove(this);" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></button>'+
                        '</td>'+
                    '</tr>');
                }
            }
            
            iframeReportTemplateNumbering();
        }
    }
    
    function iframeReportTemplateRemove(elem) {
        $(elem).closest('tr').remove();
        iframeReportTemplateNumbering();
    }
    
    function iframeReportTemplateNumbering() {
        var el = $('#rp-template-tbl > tbody > tr'), len = el.length, i = 0;
        for (i; i < len; i++) { 
            $(el[i]).find('td:first').text(i + 1);
        }
    }
    
    $(function() {
        visibleCheck();
        
        $(document).on('focusin', function(e) {
            if ($(e.target).closest(".mce-window, .moxman-window").length) {
                e.stopImmediatePropagation();
            }
        });
    });
    function visibleCheck() {
        var pageSize = $("#pageSize");
        if (pageSize.val() === 'custom') {
            $(".statement-page-width, .statement-page-height").show();
        } else {
            $(".statement-page-width, .statement-page-height").hide();
        }
        pageSize.on("change", function() {
            if ($(this).val() == 'custom') {
                $(".statement-page-width, .statement-page-height").show();
            } else {
                $(".statement-page-width, .statement-page-height").hide();
            }
        });
    }
    
    function reportEditorInitInstance(inst) {
        $("body").find('#tempEditor_ifr').css('height', '900px');
    }
    
    function setTmceReportEditor(elem) {
        var textAreaName = $(elem).attr('name');
        
        var previewWidth, defaultTableWidth, pageInnerHeight;
        var pageSize = $("#pageSize").val();
        var pageOrientation = $("#pageOrientation").val();
        
        var pageMarginLeftLower = $("#pageMarginLeft").val().toLowerCase();
        var pageMarginRightLower = $("#pageMarginRight").val().toLowerCase();
        var pageMarginTopLower = $("#pageMarginTop").val().toLowerCase();
        var pageMarginBottomLower = $("#pageMarginBottom").val().toLowerCase();
        
        var pageMarginLeft = pageMarginLeftLower;
        var pageMarginRight = pageMarginRightLower;
        var pageMarginTop = pageMarginTopLower;
        var pageMarginBottom = pageMarginBottomLower;
        
        if (pageMarginLeftLower.indexOf('cm') !== -1) {
            pageMarginLeft = parseFloat(pageMarginLeftLower.replace('cm', '')) * 37.795275591;
        }
        
        if (pageMarginLeftLower.indexOf('mm') !== -1) {
            pageMarginLeft = parseFloat(pageMarginLeftLower.replace('mm', '')) * 3.7795275591;
        }
        
        if (pageMarginLeftLower.indexOf('px') !== -1) {
            pageMarginLeft = parseFloat(pageMarginLeftLower.replace('px', ''));
        }
        
        if (pageMarginRightLower.indexOf('cm') !== -1) {
            pageMarginRight = parseFloat(pageMarginRightLower.replace('cm', '')) * 37.795275591;
        }
        
        if (pageMarginRightLower.indexOf('mm') !== -1) {
            pageMarginRight = parseFloat(pageMarginRightLower.replace('mm', '')) * 3.7795275591;
        }
        
        if (pageMarginRightLower.indexOf('px') !== -1) {
            pageMarginRight = parseFloat(pageMarginRightLower.replace('px', ''));
        }
        
        if (pageMarginTopLower.indexOf('cm') !== -1) {
            pageMarginTop = parseFloat(pageMarginTopLower.replace('cm', '')) * 37.795275591;
        }
        
        if (pageMarginTopLower.indexOf('mm') !== -1) {
            pageMarginTop = parseFloat(pageMarginTopLower.replace('mm', '')) * 3.7795275591;
        }
        
        if (pageMarginTopLower.indexOf('px') !== -1) {
            pageMarginTop = parseFloat(pageMarginTopLower.replace('px', ''));
        }
        
        if (pageMarginBottomLower.indexOf('cm') !== -1) {
            pageMarginBottom = parseFloat(pageMarginBottomLower.replace('cm', '')) * 37.795275591;
        }
        
        if (pageMarginBottomLower.indexOf('mm') !== -1) {
            pageMarginBottom = parseFloat(pageMarginBottomLower.replace('mm', '')) * 3.7795275591;
        }
        
        if (pageMarginBottomLower.indexOf('px') !== -1) {
            pageMarginBottom = parseFloat(pageMarginBottomLower.replace('px', ''));
        }
        
        if (pageSize === 'a4') {
            
            var marginLeft = pageMarginLeft;
            var marginRight = pageMarginRight;
            
            if (pageOrientation === 'portrait') {
                var width = parseFloat(1000 + 15);
                defaultTableWidth = 1000 - marginLeft - marginRight;
                pageInnerHeight = 1310 - pageMarginTop - pageMarginBottom;
            } else {
                var width = parseFloat(1210 + 15); 
                defaultTableWidth = 1210 - marginLeft - marginRight;
                pageInnerHeight = 900 - pageMarginTop - pageMarginBottom;
            }
            
            previewWidth = width - marginLeft - marginRight;
        }
        
        var $dialogName = 'dialog-tmceTemplateEditor'; 
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '" class="display-none"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        var htmlContent = '';
        if ($("form#editMetaSystemForm").find("textarea#" + textAreaName).length) {
            htmlContent = base64_encode($("form#editMetaSystemForm").find("textarea#" + textAreaName).val());
        }      
        
        $.ajax({
            type: 'post',
            url: 'mdmetadata/setTmceStatementEditor',
            data: {
                dialogName: $dialogName, 
                htmlContent: htmlContent, 
                editorName: textAreaName, 
                reportType: $('#reportType').val(), 
                metaDataId: $('#dataViewId').val(),
                pageSize: pageSize, 
                pageOrientation: pageOrientation, 
                pageMarginLeft: pageMarginLeft, 
                pageMarginRight: pageMarginRight, 
                pageMarginTop: pageMarginTop, 
                pageMarginBottom: pageMarginBottom, 
                pageInnerHeight: pageInnerHeight
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                $dialog.empty().append(data.Html);
                $dialog.dialog({
                    appendTo: "form#editMetaSystemForm",
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
                        var _tinymceHeight = $(window).height() - 250;
                        _tinymceHeight = (_tinymceHeight <= 100) ? '400px' : _tinymceHeight+ 'px';
                            
                        tinymce.dom.Event.domLoaded = true;
                        tinymce.baseURL = URL_APP+'assets/custom/addon/plugins/tinymce';
                        tinymce.suffix = ".min";
                        tinymce.init({
                            selector: 'textarea#tempEditor',
                            height: _tinymceHeight,
                            plugins: [
                                'advlist autolink lists link image charmap print preview hr anchor pagebreak',
                                'searchreplace wordcount visualblocks visualchars code fullscreen',
                                'insertdatetime media nonbreaking save table contextmenu directionality importcss codemirror',
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
                            importcss_append: true, 
                            table_toolbar: '', 
                            font_formats: "Andale Mono=andale mono,monospace;"+
                                "Arial=arial,helvetica,sans-serif;"+
                                "Arial Black=arial black,sans-serif;"+
                                "Book Antiqua=book antiqua,palatino,serif;"+
                                "Comic Sans MS=comic sans ms,sans-serif;"+
                                "Courier New=courier new,courier,monospace;"+
                                "Georgia=georgia,palatino,serif;"+
                                "Helvetica=helvetica,arial,sans-serif;"+
                                "Impact=impact,sans-serif;"+
                                "Symbol=symbol;"+
                                "Tahoma=tahoma,arial,helvetica,sans-serif;"+
                                "Terminal=terminal,monaco,monospace;"+
                                "Times New Roman=times new roman,times,serif;"+
                                "Calibri=Calibri, sans-serif;"+
                                "Trebuchet MS=trebuchet ms,geneva,sans-serif;"+
                                "Verdana=verdana,geneva,sans-serif;"+
                                "Webdings=webdings;"+
                                "Wingdings=wingdings,zapf dingbats;"+
                                "<?php echo Mdcommon::addCustomFonts('editorFamily'); ?>",
                            table_default_styles: {
                                width: defaultTableWidth + 'px'
                            }, 
                            table_class_list: [
                                {title: 'None', value: ''}, 
                                {title: 'No border', value: 'pf-report-table-none'}, 
                                {title: 'Dotted', value: 'pf-report-table-dotted'}, 
                                {title: 'Dashed', value: 'pf-report-table-dashed'},  
                                {title: 'Solid', value: 'pf-report-table-solid'}
                            ], 
                            object_resizing: 'img',
                            paste_word_valid_elements: 'b,p,br,strong,i,em,h1,h2,h3,h4,ul,li,ol,table,span,div,font,page',
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
                                    indentUnit: 2, 
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
                                        "Ctrl-S": function(cm) { 
                                            if ($('body').find('.mce-bp-btn-subsave').length > 0 && $('body').find('.mce-bp-btn-subsave').is(':visible')) {
                                                var $buttonElement = $('body').find('.mce-bp-btn-subsave:visible:last');
                                                if (!$buttonElement.is(':disabled')) {
                                                    $buttonElement.click();
                                                }
                                            }
                                        }, 
                                        "Ctrl-Space": "autocomplete"
                                    }
                                },
                                width: ($(window).width() - 20),        
                                height: ($(window).height() - 120),        
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
                                    $('textarea#tempEditor').prev('.mce-container').find('.mce-edit-area')
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
                                editor.shortcuts.add('ctrl+s', '', function() { 
                                    if ($('body').find('.mce-bp-btn-subsave').length > 0 && $('body').find('.mce-bp-btn-subsave').is(':visible')) {
                                        var $buttonElement = $('body').find('.mce-bp-btn-subsave:visible:last');
                                        if (!$buttonElement.is(':disabled')) {
                                            $buttonElement.click();
                                            return false;
                                        }
                                    }
                                    
                                    if ($('body').find('button.bp-btn-subsave').length > 0 && $('body').find('button.bp-btn-subsave').is(':visible')) {
                                        var $buttonElement = $('body').find('button.bp-btn-subsave:visible:last');
                                        if (!$buttonElement.is(':disabled')) {
                                            $buttonElement.click();
                                        }
                                    }
                                    return false;
                                });
                            },  
                            plugin_preview_width: previewWidth,
                            document_base_url: URL_APP, 
                            content_css: [
                                URL_APP+'assets/custom/css/print/tinymce_statement.css', 
                                <?php echo Mdcommon::addCustomFonts('jsCommaPath'); ?>
                            ]
                        });
                    }, 
                    close: function () {
                        tinymce.remove('textarea');
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                            tinymce.triggerSave();
                            
                            var reportValueHtml = tinymce.get('tempEditor').getContent();
                            var $html = $('<div />', {html: reportValueHtml});
                            
                            $html.find('table:has(thead)').each(function(){
                                var _table = $(this);
                                var _thead = _table.find('thead');

                                if (_thead.find('tr').length === 2) {
                                    
                                    // Reset Colgroup
                                    if (_table.find('colgroup').length) {
                                       _table.find('colgroup').remove(); 
                                    }
                                
                                    var _colgroup = '<colgroup>\n';
                                    var regex = /width:(.*?)\;/g;
                                    var _colspan = 0;

                                    _thead.find('tr:first-child').find('th, td').each(function(){
                                        var _td = $(this);                                       

                                        if (typeof _td.attr('colspan') !== 'undefined') {

                                            if (_td[0].style.cssText.match(regex) !== null) {
                                                var strWidth = _td[0].style.cssText.match(regex);
                                                var strToNum = strWidth[0].match(/\d/g), colsWidtSum = 0;
                                                strToNum = Number(strToNum.join(""));

                                                var _colspanStart = _colspan, currentColspan = Number(_td.attr('colspan'));
                                                _colspan += currentColspan;
                                                var secondtr = _thead.find('tr:last-child').find('th, td');

                                                for (var i = _colspanStart; i < _colspan; i++) {
                                                    if (typeof secondtr[i] !== 'undefined') {
                                                        var getWidth = secondtr[i].style.cssText.match(regex);
                                                        
                                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined') {
                                                            var strToChildNum = getWidth[0].match(/\d/g);
                                                            colsWidtSum += Number(strToChildNum.join(""));                                                            
                                                            currentColspan--;
                                                        }
                                                    }
                                                }
                                                
                                                var equalWidth = (strToNum - colsWidtSum) / currentColspan;

                                                for (var i = _colspanStart; i < _colspan; i++) {
                                                    if (typeof secondtr[i] !== 'undefined') {
                                                        var getWidth = secondtr[i].style.cssText.match(regex);
                                                        
                                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined')                                                        
                                                            _colgroup += '<col style="' + getWidth[0] + '">\n';
                                                        else
                                                            _colgroup += '<col style="width:' + equalWidth + 'px">\n';
                                                    }
                                                }

                                            } else {

                                                var _colspanStart = _colspan;
                                                _colspan += Number(_td.attr('colspan'));
                                                var secondtr = _thead.find('tr:last-child').find('th, td');

                                                for (var i = _colspanStart; i < _colspan; i++) {
                                                    if (typeof secondtr[i] !== 'undefined') {
                                                        var getWidth = secondtr[i].style.cssText.match(regex);
                                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                                                            _colgroup += '<col style="' + getWidth[0] + '">\n';
                                                    }
                                                }
                                            }

                                        } else {
                                            if (_td.hasAttr('style')) {
                                                var getWidth = _td.attr('style').match(regex);
                                                if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                                                    _colgroup += '<col style="' + getWidth[0] + '">\n';
                                            }
                                        }

                                    });
                                    _colgroup += '</colgroup>';
                                    _table.prepend(_colgroup);
                                }

                                /*if (_thead.find('tr').length === 3) {
                                    var _colgroup = '<colgroup>\n';
                                    var regex = /width:(.*?)\;/g;
                                    var _colspan = 0;                                   

                                    _thead.find('tr:first-child').find('th, td').each(function(){
                                        var _td = $(this);                                       

                                        if (typeof _td.attr('colspan') !== 'undefined') {
                                            if (typeof _td.attr('colspan') !== 'undefined') {
                                                var _colspanStart = _colspan;
                                                _colspan += Number(_td.attr('colspan'));
                                                var secondtr = _thead.find('tr:last-child').find('th, td');

                                                for (var i = _colspanStart; i < _colspan; i++) {
                                                    if (typeof secondtr[i] !== 'undefined') {
                                                        var getWidth = secondtr[i].style.cssText.match(regex);
                                                        if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                                                            _colgroup += '<col style="' + getWidth[0] + '">\n';
                                                    }
                                                }
                                            } else {
                                                var getWidth = _td.attr('style').match(regex);
                                                if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                                                    _colgroup += '<col style="' + getWidth[0] + '">\n';
                                            }
                                        } else {
                                            var getWidth = _td.attr('style').match(regex);
                                            if (getWidth !== null && typeof getWidth[0] !== 'undefined')
                                                _colgroup += '<col style="' + getWidth[0] + '">\n';
                                        }

                                   });
                                   _colgroup += '</colgroup>';
                                   _table.prepend(_colgroup);
                                }*/ 
                            });
                            
                            var reportValue = $html.find('.tinymce-page-border').html();
                            
                            if (reportValue == '&nbsp;') {
                                reportValue = '';
                            }
                            
                            if ($("form#editMetaSystemForm").find("textarea#" + textAreaName).length) {
                                $("form#editMetaSystemForm").find("textarea#" + textAreaName).val(reportValue);
                            } else {
                                $("form#editMetaSystemForm").append('<textarea name="' + textAreaName + '" id="' + textAreaName + '" class="display-none"></textarea>');
                                $("form#editMetaSystemForm").find("textarea#" + textAreaName).val(reportValue);
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
                        $dialog.find("div.report-tags").css("height", dialogHeight+'px');
                        $dialog.find("div.report-tags").css("max-height", dialogHeight+'px');
                    }, 
                    "restore" : function() { 
                        var dialogHeight = $dialog.height();
                        $dialog.find("div.report-tags").css("height", dialogHeight+'px');
                        $dialog.find("div.report-tags").css("max-height", dialogHeight+'px');
                    }
                });
                $dialog.dialog('open');
                $dialog.dialogExtend('maximize');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
            }
        });
    }
    function fromEditorHtml(htmlContent) {
        var newHtml = '<div id="editorContent">' + htmlContent + '</div>';
        newDocument = new DOMParser().parseFromString(newHtml, "text/html");
        $(newDocument).find(".tag-meta").each(function() {
            var metaData = $(this).find("span").text();
            $(this).html("#" + metaData + "#");
        });
        $(newDocument).find(".tag-const").each(function() {
            var constValue = $(this).find("span").text();
            $(this).html("#" + constValue + "#");
        });
        $(newDocument).find(".tag-configvalue").each(function() {
            var configvalue = $(this).find("span").text();
            $(this).html("#" + configvalue + "#");
        });
        return newDocument.getElementById("editorContent").innerHTML;
    }
    function toEditorHtml(htmlContent) {
        var newHtml = '<div id="editorContent">' + htmlContent + '</div>';
        newDocument = new DOMParser().parseFromString(newHtml, "text/html");
        $(newDocument).find(".tag-meta").each(function() {
            var metaData = $(this).text().slice(1, -1);
            $(this).html('<span>' + metaData + '</span>' + '<a href="#" title="Remove">x</a>');
        });
        $(newDocument).find(".tag-const").each(function() {
            var constValue = $(this).text().slice(1, -1);
            $(this).html('<span>' + constValue + '</span>' + '<a href="#" title="Remove">x</a>');
        });
        $(newDocument).find(".tag-configvalue").each(function() {
            var configvalue = $(this).text().slice(1, -1);
            $(this).html('<span>' + configvalue + '</span>' + '<a href="#" title="Remove">x</a>');
        });
        return newDocument.getElementById("editorContent").innerHTML;
    }
    function dataviewChoose(chooseType, elem, params) {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            $("#dataViewId").val(rows[0]['META_DATA_ID']);
            $("#dataview-name").text(rows['0']['META_DATA_CODE'] + ' | ' + rows['0']['META_DATA_NAME']);
        }
    }
    function removeDataview(elem) {
        var $row = $(elem).closest("tr");
        $row.find("input[name='dataViewId']").val('');
        $row.find("span#dataview-name").empty();
    }
    
    function metaModelChoose(chooseType, elem, params) {
        var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
        if (metaBasketNum > 0) {
            var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
            $("#metamodel-name").html(rows[0]['META_DATA_NAME']);
            $("#metaModelId").val(rows[0]['MODEL_ID']);
        }
    }
    function removeMetaModel(elem) {
        var _row = $(elem).closest("tr");
        _row.find("input[name='metaModelId']").val("");
        _row.find("span#metamodel-name").empty();
    }
    function setReportGrouping(elem) {
        var $dialogName = 'dialog-report-grouping';

        if ($("#" + $dialogName).children().length > 0) {
            $("#" + $dialogName).dialog({
                appendTo: "form#editMetaSystemForm",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Report Grouping',
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
                url: 'mdmeta/setStatementReportGrouping',
                data: {
                    metaDataId: '<?php echo $this->metaDataId; ?>',
                    dataViewId: $("input#dataViewId").val(),
                    editMode: true
                },
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
    function reportExpressionCode(elem) {

        var $dialogName = 'dialog-reportExpcriteria-<?php echo $this->metaDataId; ?>';
        
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('form#editMetaSystemForm');
        }
        var $dialog = $('#' + $dialogName);
        
        if ($dialog.children().length > 0) {
            $dialog.dialog({
                appendTo: "form#editMetaSystemForm",
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: 'Report Expression',
                width: 1200,
                minWidth: 1200,
                height: "auto",
                modal: false,
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm green bp-btn-subsave', click: function () {
                        reportlExpressionRowEditor.save();
                        reportlExpressionGlobalEditor.save(); 
                        reportlExpressionSuperGlobalEditor.save(); 
                        headerFooterExpressionUIEditor.save();
                        groupExpressionUIEditor.save();
                        detailExpressionUIEditor.save();
                        
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
            $dialog.dialogExtend("maximize");
            
        } else {
            
            $.cachedScript('assets/custom/addon/plugins/codemirror/lib/codemirror.min.js').done(function() {
                $.ajax({
                    type: 'post',
                    url: 'mdstatement/setReportExpressionCriteria',
                    data: {
                        statementId: '<?php echo $this->metaDataId; ?>',
                        reportType: $('#reportType').val()
                    },
                    dataType: "json",
                    beforeSend: function() {
                        Core.blockUI({
                            message: 'Loading...',
                            boxed: true
                        });
                        if (!$("link[href='assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css']").length){
                            $("head").append('<link rel="stylesheet" type="text/css" href="assets/custom/addon/plugins/codemirror/lib/codemirror.v1.css"/>');
                        }
                    },
                    success: function(data) {
                        $dialog.empty().append(data.Html);
                        $dialog.dialog({
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
                            buttons: [
                                {text: data.save_btn, class: 'btn btn-sm green bp-btn-subsave', click: function() {
                                    reportlExpressionRowEditor.save();
                                    reportlExpressionGlobalEditor.save();
                                    reportlExpressionSuperGlobalEditor.save();
                                    headerFooterExpressionUIEditor.save();
                                    groupExpressionUIEditor.save();
                                    detailExpressionUIEditor.save();

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
                                $dialog.find("div.table-scrollable").css({"height": (dialogHeight-40)+'px', "max-height": (dialogHeight-40)+'px'});
                                $dialog.find(".CodeMirror").css("height", (dialogHeight - 48)+'px');
                                headerFooterExpressionUIEditor.setSize(null, (dialogHeight - 85));
                                groupExpressionUIEditor.setSize(null, (dialogHeight - 85));
                                detailExpressionUIEditor.setSize(null, (dialogHeight - 85));
                            }, 
                            "restore": function() { 
                                var dialogHeight = $dialog.height();
                                $dialog.find("div.table-scrollable").css({"height": (dialogHeight-40)+'px', "max-height": (dialogHeight-40)+'px'});
                                $dialog.find(".CodeMirror").css("height", (dialogHeight - 47)+'px');
                                headerFooterExpressionUIEditor.setSize(null, (dialogHeight - 84));
                                groupExpressionUIEditor.setSize(null, (dialogHeight - 84));
                                detailExpressionUIEditor.setSize(null, (dialogHeight - 84));
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
    }    
    
    function iframeReportDesigner(elem, statementId, dvId) {
        $.ajax({
            type: 'post',
            url: 'mdstatement/iframeReportDesigner',
            data: {
                statementId: statementId, 
                dvId: dvId, 
                expandDvId: $(elem).closest('table').find('input[name="groupDataViewId"]').val(), 
                windowHeight: $(window).height()
            },
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({
                    message: 'Loading...',
                    boxed: true
                });
            },
            success: function(data) {
                
                if (data.status == 'success') {
                    
                    var $dialogName = 'dialog-reportiframe-<?php echo $this->metaDataId; ?>';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialog = $('#' + $dialogName);
        
                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 1200,
                        minWidth: 1200,
                        height: "auto",
                        modal: false,
                        buttons: [
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
                        }
                    });
                    $dialog.dialog('open');
                    $dialog.dialogExtend("maximize");
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });  
                }
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
                Core.unblockUI();
            }
        });
    }
    
    function iframeReportTemplateCopy(statementId, dvId) {
        $.ajax({
            type: 'post',
            url: 'mdstatement/iframeReportTemplateCopy', 
            data: {statementId: statementId, dvId: dvId}, 
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                var $dialogName = 'dialog-reportcopy-<?php echo $this->metaDataId; ?>';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $('#' + $dialogName);

                $dialog.empty().append(data.html);
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: data.title,
                    width: 500,
                    height: "auto",
                    modal: true,
                    buttons: [
                        {text: data.copy_btn, class: 'btn green-meadow btn-sm bp-btn-save', click: function () {  
                                
                            var $copyForm = $('form#report-template-copy', $dialog);
                            $copyForm.validate({errorPlacement: function () {}});
                            
                            if ($copyForm.valid()) {
                                $copyForm.ajaxSubmit({
                                    type: 'post',
                                    url: 'mdstatement/iframeReportTemplateCopySave', 
                                    dataType: 'json',
                                    beforeSubmit: function (formData, jqForm, options) {
                                        formData.push( 
                                            {name: 'srcStatementId', value: statementId}
                                        );
                                    },
                                    beforeSend: function () {
                                        Core.blockUI({message: 'Loading...', boxed: true});
                                    },
                                    success: function (data) {
                                        PNotify.removeAll();
                                        new PNotify({
                                            title: data.status,
                                            text: data.message,
                                            type: data.status,
                                            sticker: false
                                        });

                                        if (data.status === 'success') {
                                            $dialog.dialog('close');
                                        } 

                                        Core.unblockUI();
                                    }
                                });
                            }
                        }},
                        {text: data.close_btn, class: 'btn btn-sm blue-hoki', click: function() {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
                Core.unblockUI();
            }
        });
    }
    
    function iframeReportTemplateDesigner(statementId, expandDvId) {
        $.ajax({
            type: 'post',
            url: 'mdstatement/iframeReportDesigner',
            data: {statementId: statementId, expandDvId: expandDvId, windowHeight: $(window).height()},
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function(data) {
                
                if (data.status == 'success') {
                    
                    var $dialogName = 'dialog-reportiframe-<?php echo $this->metaDataId; ?>';
                    if (!$("#" + $dialogName).length) {
                        $('<div id="' + $dialogName + '"></div>').appendTo('body');
                    }
                    var $dialog = $('#' + $dialogName);
        
                    $dialog.empty().append(data.html);
                    $dialog.dialog({
                        cache: false,
                        resizable: true,
                        bgiframe: true,
                        autoOpen: false,
                        title: data.title,
                        width: 1200,
                        minWidth: 1200,
                        height: "auto",
                        modal: false,
                        buttons: [
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
                        }
                    });
                    $dialog.dialog('open');
                    $dialog.dialogExtend("maximize");
                    
                } else {
                    PNotify.removeAll();
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false
                    });  
                }
                Core.unblockUI();
            },
            error: function() {
                alert("Error");
                Core.unblockUI();
            }
        });
    }
</script>