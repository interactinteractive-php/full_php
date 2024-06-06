<?php
if (issetParam($this->dataGridOptionData['HIDELAYOUTVIEWER']) != 'true') { 
    if (isset($this->row['dataViewLayoutTypes']['calendar'])) {
?>
    <?php if (issetParam($this->viewType) == 'ganttchart') { ?>
        <button type="button" class="btn btn-primary btn-icon card-switch" onclick="dataViewer_<?php echo $this->metaDataId ?>(this, 'detail', '<?php echo $this->metaDataId ?>');" data-view-type="detail" data-old-type="ecommerce" title="<?php echo Lang::line('viewtype_list'); ?>"><i class="icon-list"></i></button>
    <?php } ?>
    <button type="button" class="btn btn-primary btn-icon card-switch" onclick="dataViewer_<?php echo $this->metaDataId ?>(this, 'calendar', '<?php echo $this->metaDataId ?>');" data-view-type="calendar" data-old-type="ecommerce" title="<?php echo Lang::line('viewtype_calendar'); ?>"><i class="icon-calendar"></i></button>
    <?php
    } else {
    ?>
    <button type="button" class="btn btn-primary btn-icon card-switch dv-layout-type-<?php echo $this->metaDataId ?> ml-1" onclick="renderCardView_<?php echo $this->metaDataId ?>(this);" data-view-type="<?php echo $this->layoutType; ?>" data-old-type="ecommerce"><i class="icon-list"></i></button>
<?php 
    }
} 
?>
<button type="button" class="btn btn-grey btn-icon dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
    <i class="icon-link"></i>
</button>
<div class="dropdown-menu dropdown-menu-right" x-placement="bottom-end">
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-file"></i> '.$this->lang->line('META_VIEW_REPORT_TEMPLATE'), array(
            'class' => 'dropdown-item',
            'onclick' => 'objectReportTemplateView_'.$this->metaDataId.'()'
        ), $this->isReportTemplate  
    ); 
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-dashboard"></i> '.$this->lang->line('META_VIEW_DASHBOARD'), array(
            'class' => 'dropdown-item',
            'onclick' => 'objectDashboardView_'.$this->metaDataId.'()'
        ), $this->isDashboard  
    ); 
    echo Html::anchor(
            'javascript:;', '<i class="icon-cube"></i> Pivot view', array(
        'title' => 'Pivot view',
        'class' => 'dropdown-item',
        'onclick' => 'dataViewPivotView(\'' . $this->metaDataId . '\', this);'
            ), (defined('CONFIG_PIVOT_SERVICE_ADDRESS') && CONFIG_PIVOT_SERVICE_ADDRESS)
    );
    ?>
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-qrcode"></i> Qrcode', array(
        'onclick' => 'dataViewStatementPreview_' . $this->metaDataId . '(\'' . $this->metaDataId . '\', true, \'toolbar\', this);',
        'class' => 'dropdown-item',
            ), $this->isStatementBtnSee
    );
    ?>
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-folder"></i> Detail view', array(
                'title' => 'Detail view',
                'class' => 'dropdown-item',
                'onclick' => 'dataViewer_' . $this->metaDataId . '(this, \'explorer\', \'' . $this->metaDataId . '\');'
            ), isset($this->row['dataViewLayoutTypes']['explorer']) && $this->row['dataViewLayoutTypes']['explorer']['LAYOUT_THEME'] ? true : false
    );
    ?>
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-bar-chart-o"></i> Layout', array(
        'class' => 'dropdown-item callLayoutDataView_' . $this->metaDataId,
        'title' => 'Layout',
        'onclick' => 'callLayoutDataView_' . $this->metaDataId . '(' . $this->metaLayoutLinkId . ', this);'
            ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false
    );
    ?>
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-table"></i> Table', array(
        'class' => 'dropdown-item callDataView_' . $this->metaDataId,
        'title' => 'Table',
        'onclick' => 'callDataView_' . $this->metaDataId . '(' . $this->metaDataId . ', this);'
            ), isset($this->metaLayoutBtn) ? $this->metaLayoutBtn : false
    );
    ?>
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-map-marker"></i> Map', array(
        'class' => 'dropdown-item googleMapBtnByDataView_' . $this->metaDataId,
        'title' => 'Map',
        'onclick' => 'googleMapBtnByDataView_' . $this->metaDataId . '(this);'
            ), isset($this->isGoogleMap) ? $this->isGoogleMap : false
    );
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-calendar"></i> Calendar view', array(
        'title' => 'Calendar view',
        'class' => 'dropdown-item',
        'onclick' => 'callCalendarByMeta(' . $this->calendarMetaDataId . ');'
            ), isset($this->isCalendarSee) ? $this->isCalendarSee : false
    );
    ?>
    <?php
    if (issetParam($this->row['IS_EXCEL_EXPORT_BTN']) != '') {

        if (strpos($commandBtn, '<!--excelexportbutton-->') !== false) {
            echo Html::anchor(
                    'javascript:;', '<i class="icon-file-excel"></i> ' . $this->lang->line('excel_btn'), array(
                'title' => $this->lang->line('excel_btn'),
                'class' => 'dropdown-item',
                'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                    ), true
            );
        }
    } else {
        echo Html::anchor(
                'javascript:;', '<i class="icon-file-excel"></i> ' . $this->lang->line('excel_btn'), array(
            'title' => $this->lang->line('excel_btn'),
            'class' => 'dropdown-item',
            'onclick' => 'dataViewExportToExcel_' . $this->metaDataId . '();'
                ), (!isset($this->row['IS_IGNORE_EXCEL_EXPORT']) || (isset($this->row['IS_IGNORE_EXCEL_EXPORT']) && $this->row['IS_IGNORE_EXCEL_EXPORT'] != '1'))
        );
    }
    ?>
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-file-text-o"></i> Text file', array(
            'title' => 'Text file',
            'class' => 'dropdown-item',
            'onclick' => 'dataViewExportToText_' . $this->metaDataId . '();'
        ), isset($this->isExportText) ? $this->isExportText : false
    );
    echo Html::anchor(
            'javascript:;', '<i class="far fa-print"></i> Print', array(
            'title' => 'Print',
            'class' => 'dropdown-item',
            'onclick' => 'dataViewExportToPrint_' . $this->metaDataId . '();'
        ), (issetParam($this->row['IS_DIRECT_PRINT']) == '1')
    );
    ?>
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="icon-table2"></i> Merge cell', array(
        'class' => 'dropdown-item value-grid-merge-cell',
        'title' => 'Merge cell'
            ), ($this->dataGridOptionData['MERGECELLS'] == 'true' ? true : false)
    );
    ?>
    <?php
    echo Html::anchor(
            'javascript:;', '<i class="icon-cog"></i> '.$this->lang->line('user_configuration'), array(
        'title' => $this->lang->line('user_configuration'),
        'class' => 'dropdown-item',
        'onclick' => 'dataViewAdvancedConfig_' . $this->metaDataId . '(this);'
            ), true
    );
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-question-circle"></i> Тусламж', array(
            'onclick' => 'pfHelpDataView(\''. $this->metaDataId .'\');',
            'title' => 'Тусламж',
            'class' => 'dropdown-item'
        ), (issetParam($this->row['IS_KNOWLEDGE']) == '1')
    );
    echo Html::anchor(
            'javascript:;', (new Mduser())->iconQuickMenu($this->metaDataId) . ' QuickMenu', array(
        'onclick' => 'toQuickMenu(\'' . $this->metaDataId . '\', \'dataview\', this);',
        'class' => 'dropdown-item',
        'title' => 'Quick menu',
            ), true
    );
    echo Html::anchor(
            'javascript:;', '<i class="fa fa-file"></i> '.$this->lang->line('META_VIEW_REPORT_TEMPLATE'), array(
        'onclick' => 'objectReportTemplateView_'.$this->metaDataId.'();',
        'class' => 'dropdown-item',
        'title' => '',
            ), $this->isReportTemplate
    );    
    ?>
</div>    