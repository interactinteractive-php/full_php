<div class="kpi-form-paper-portrait">
    <div class="kpi-form-paper-portrait-child">    
        <div class="d-flex justify-content-between">
            <img style="height: 24px" src="assets/custom/img/new_veritech_black_logo.png"/>
        </div>
        <div class="d-flex justify-content-center">
            <p class="mb-0 mt-1 ml-2" style="font-size: 20px;font-weight: bold;"><?php echo $this->title; ?></p>
        </div>
        <div class="row mv-checklist-render-parent mv-checklist3-render-parent" id="mv-checklist-render-parent-<?php echo $this->uniqId; ?>">

            <?php 
            echo Form::hidden(array('data-path' => 'endToEndLogHdrId', 'value' => issetParam($this->endToEndLogData['headerLogId']))); 
            echo Form::hidden(array('data-path' => 'listIndicatorId', 'value' => $this->listIndicatorId)); 
            echo Form::hidden(array('data-path' => 'headerRecordId', 'value' => $this->recordId)); 
            echo Form::hidden(array('data-path' => 'headerParams', 'value' => htmlentities(json_encode($this->selectedRow, JSON_UNESCAPED_UNICODE), ENT_QUOTES, 'UTF-8'))); 
            ?>

            <div class="bp-tabs tabbable-line mv-main-tabs w-100">
                <ul class="nav nav-tabs">            
                    <li class="nav-item">
                        <a href="#structabcustom_1703241175512581" class="nav-link active" data-toggle="tab" aria-expanded="false">
                            Хүсэлт                
                        </a>
                    </li>                        
                    <li class="nav-item">
                        <a href="#maintabcustom_1703241175512581" class="nav-link" data-toggle="tab" aria-expanded="false">
                            Үнэлгээ
                        </a>
                    </li>
                </ul>        
                <div class="tab-content"> 
                    <div class="tab-pane active" id="structabcustom_1703241175512581">
                        <div style="width: 100%; padding: 5px 15px 10px 15px;">
                            <form method="post" enctype="multipart/form-data">
                                <div class="meta-toolbar is-bp-open-">
                                    <div class="main-process-text">
                                        <div></div>                                        
                                    </div>
                                    <div class="ml-auto">
                                        <button type="button" class="btn btn-sm btn-circle btn-success bp-btn-save" onclick="saveKpiIndicatorHeaderForm(this);">
                                            <i class="icon-checkmark-circle2"></i> <?php echo $this->lang->line('save_btn'); ?>
                                        </button>
                                    </div>
                                </div>
                                <?php echo $this->headerProcess; ?>
                            </form>
                        </div>                
                    </div>
                    <div class="tab-pane p-1" id="maintabcustom_1703241175512581">
                        <div class="d-flex">
                            <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md pr-2" style="width:280px">
                                <div class="sidebar-content">

                                    <div class="card">
                                        <div class="card-body mv-checklist-menu">
                                            <ul class="nav nav-sidebar" data-nav-type="accordion">
                                                <?php
                                                $n = 0;
                                                $n2 = 0;
                                                $relationList = Arr::groupByArrayByNullKey($this->relationList, 'TAB_NAME');

                                                foreach ($relationList as $groupName => $groupRow) {

                                                    $item = '';
                                                    $rows = $groupRow['rows'];

                                                    foreach ($rows as $row) {

                                                        $kpiTypeId = $row['KPI_TYPE_ID'];
                                                        $class = '';

                                                        if ($kpiTypeId == 2008) {
                                                            $name = $row['STRUCTURE_NAME'];
                                                        } elseif ($row['META_DATA_ID']) {
                                                            $name = $row['META_DATA_NAME'];
                                                        } else {
                                                            $name = $row['NAME'];
                                                        }

                                                        if ($n == 0) {
                                                            $class = ' active';
                                                        }

                                                        if (!$this->selectedRow) {
                                                            $class = ' disabled';
                                                        }

                                                        $rowJson = json_encode(array(
                                                            'mapId'          => $row['MAP_ID'], 
                                                            'indicatorId'    => $row['ID'], 
                                                            'strIndicatorId' => $row['STRUCTURE_INDICATOR_ID'],
                                                            'kpiTypeId'      => $row['KPI_TYPE_ID'], 
                                                            'metaDataId'     => $row['META_DATA_ID'], 
                                                            'metaTypeId'     => $row['META_TYPE_ID'], 
                                                        ));
                                                        $rowJson = htmlentities($rowJson, ENT_QUOTES, 'UTF-8');

                                                        $hiddenParams = json_encode(array(
                                                            'srcMapId'       => $row['MAP_ID'],
                                                            'srcIndicatorId' => $this->strIndicatorId, 
                                                            'srcRecordId'    => $this->recordId, 
                                                            'trgIndicatorId' => $row['ID']
                                                        ));
                                                        $hiddenParams = htmlentities($hiddenParams, ENT_QUOTES, 'UTF-8');
                                                        
                                                        $iconName = 'far fa-square';
                                                        
                                                        if (isset($this->endToEndLogData['detailData'][$row['ID']]) && $this->endToEndLogData['detailData'][$row['ID']]['STATUS_CODE'] == 'done') {
                                                            $iconName = 'fas fa-check-square';
                                                        }

                                                        $item .= '<li class="nav-item '.($groupName == 'яяяrow' ? '' : '').'">
                                                            <a href="javascript:;" class="mv_checklist_02_sub nav-link'.$class.'" data-indicatorid="'.$this->indicatorId.'" data-uniqid="'.$this->uniqId.'" data-json="'.$rowJson.'" data-hidden-params="'.$hiddenParams.'">
                                                                <i class="'.$iconName.'"></i> <span class="pt1">'.$name.'</span>
                                                            </a>
                                                        </li>';

                                                        $n ++;
                                                    }

                                                    if ($groupName != 'яяяrow') {
                                                        echo '<li class="nav-item nav-item-submenu '.(!$n2 ? 'nav-group-sub-mv-opened' : '').'">';
                                                            echo '<a href="javascript:;" class="nav-link mv_checklist_02_groupname">'.$this->lang->line($groupName).'</a>';
                                                            echo '<ul class="nav nav-group-sub">';
                                                    }
                                                    $n2++;

                                                    echo $item;

                                                    if ($groupName != 'яяяrow') {
                                                            echo '</ul>';
                                                        echo '</li>';
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="content-wrapper pt-0 pl-3 pr-3 mv-checklist-render">        
                            </div>                
                        </div>                
                    </div>
                </div>
            </div>               
        </div>
    </div>
</div>
<?php 
    $bgImage = 'middleware/assets/img/process/background/paperclip.png';

    if (isset($this->bgImage) && file_exists($this->bgImage)) {
        $bgImage = $this->bgImage;
    }
?>

<style type="text/css">
.nav-link.mv_checklist_02_groupname {
    font-size: 13px;
    color: #333 !important;
    font-weight: bold !important;
    padding-top: 5px;
    padding-bottom: 5px;
    text-transform: none !important;
}    
.nav-link.mv_checklist_02_sub {
    padding-top: 2px;
    padding-bottom: 2px;
    font-size: 12px;
    /*font-weight: bold;*/
}    
.nav-link.mv_checklist_02_sub i {
    color: #1B84FF !important;
    margin-top: 2px;
    font-size: 18px;    
    margin-right: 13px;
}    
.mv-checklist3-render-parent form > .meta-toolbar {
    border-bottom: none;
    margin-top: 0;
    margin-bottom: 12px;
}
.mv-checklist3-render-parent .meta-toolbar .main-process-text {
    font-size: 12px;
}    
.mv-checklist3-render-parent .bp-add-one-row-num {
    display: none;
}    
.mv-checklist3-render-parent .mv-add-row-actions {
    margin-top: 0 !important;
    margin-bottom: 0 !important;
    display: block !important;
}    
.mv-checklist3-render-parent .kpi-ind-tmplt-section button.bp-add-one-row {
    display: block;
    margin-bottom: 8px;
}
.mv-checklist3-render-parent .nav-group-sub-mv-opened .nav-group-sub {
    display: block;
}
.mv-checklist3-render-parent .nav-sidebar .nav-item:not(.nav-item-header):last-child {
    padding-bottom: 0 !important;
}
.mv-checklist3-render-parent .nav-item-submenu.nav-group-sub-mv-opened>.nav-link:after {
    -webkit-transform: rotate(90deg);
    transform: rotate(90deg);
}
.mv-checklist3-render-parent .nav-group-sub .nav-link {
    padding-left: 20px;
}
.mv-checklist3-render-parent button.bp-add-one-row {
    background-color: #F9F9F9;
    color: #252F4A;
    font-size: 12px;
    padding: 0px 5px 0px 5px;
}    
.mv-checklist3-render-parent .main-process-text-description {
    color: #99A1B7;
    text-transform: none;
    font-weight: normal;
    font-size: 11px;    
}    
.mv-checklist3-render-parent button.bp-add-one-row:hover {
    background-color: #1B84FF;
}    
.nav-item-submenu>.nav-link.mv_checklist_02_groupname:after {
    margin-top: -6px;
}
.mv-checklist3-render-parent {
    margin: -20px -15px 0px -15px!important;
}
.mv-checklist3-render-parent button.bp-btn-save i {
    display: none;
}
.mv-checklist3-render-parent button.bp-btn-save {
    color: #fff!important;
    border-color: #1B84FF!important;
    padding-left: 18px!important;
    padding-right: 18px!important;
    background-color: #1B84FF!important;
    padding-bottom: 2px !important;
    font-size: 12px!important;
}
.mv-checklist3-render-parent button.bp-btn-save:hover {
    background-color: #1B84FF!important;
}
.mv-checklist3-render-parent .mv-rows-title {
    display: none;
}
.mv-checklist3-render-parent > .sidebar {
    width: 16.875rem;
    padding: 0;
    background-color: rgb(243, 244, 246);
}
.mv-checklist3-render-parent > .sidebar .sidebar-content {
    padding: 15px 10px;
}
.mv-checklist3-render-parent .sidebar-light .nav-sidebar .nav-item>.nav-link.active {
    background-color: #1b84ff54;
}
.mv-checklist3-render-parent .mv-checklist-title {
    color: #3C3C3C;
    text-transform: uppercase;
    font-size: 12px;
    font-weight: 700;
}
.mv-checklist3-render-parent .mv-checklist-description {
    color: #67748E;
    margin-top: 10px;
}
.mv-checklist3-render-parent > .sidebar > .sidebar-content > .card > .card-body .step {
    background: #A0A0A0;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist3-render-parent > .sidebar > .sidebar-content > .card > .card-body .step.active {
    background: #468CE2;
    height: 3px;
    border-radius: 5px;
    width: calc(100% / 5);
}
.mv-checklist3-render-parent .mv-checklist-menu {
    height: 70vh;
    padding: 0;
    margin-left: -10px;
    margin-right: -10px;
    overflow: auto;
}
.mv-checklist3-render-parent .mv-checklist-menu li {
    width: 100%;
}
.mv-checklist3-render-parent > .sidebar .card-body .nav-sidebar a.nav-link {
    display: flex;
    align-items: center;
    -webkit-box-orient: vertical;
    -webkit-line-clamp: 3;
    padding: 10px 22px 10px 10px;
    overflow: hidden;
    font-size: 12px;
    text-transform: none;
}
.mv-checklist3-render-parent > .sidebar .card-body .nav-sidebar a.nav-link:hover {
    background-color: #E8EBF0;
    color: #468CE2;
}
.mv-checklist3-render-parent > .sidebar .card-body .nav-sidebar a.nav-link i {
    font-size: 18px;
    margin-right: 10px;
}
.mv-checklist3-render-parent > .sidebar .card-body .nav-sidebar a.nav-link span {
    font-size: 12px;
    font-weight: 600;
}
.mv-checklist3-render-parent > .sidebar .card-body .nav-sidebar a.nav-link.active {
    background-color: #E8EBF0;
    color: #468CE2;
}
.kpi-form-paper-portrait {
    background-image: url("<?php echo $bgImage; ?>"); 
    background-repeat: no-repeat; 
    background-position: top center;
    background-attachment: fixed;
    background-color: #ededed;
    margin: -10px -15px;
    padding-top: 11px;
    padding-bottom: 20px;
    background-size: cover;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child {
    position: relative;
    width: 1040px;
    min-height: calc(100vh - 126px);
    margin-top: 10px;
    margin-left: auto;
    margin-right: auto;
    background: #FFF;
    padding: 20px;
    box-shadow: 0px 2px 6px 0 rgba(0,0,0,.5);
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-title {
    margin-bottom: -14px;
    /*margin-top: 10px;*/
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-logo {
    position: absolute;
    max-width: 150px;
    max-height: 70px;
    top: 20px;
    left: 20px;
}
.kpi-form-paper-portrait .kpi-form-paper-portrait-child .kpi-form-paper-header-text {
    position: absolute;
    max-width: 180px;
    max-height: 70px;
    top: 20px;
    right: 20px;
    text-align: right;
    line-height: 14px;
}
.kpi-form-paper-portrait h1 {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
    line-height: 28px;
    margin-bottom: 20px;
    margin-left: auto;
    margin-right: auto;
    max-width: 500px;
}
.kpi-form-paper-portrait .mv-main-tabs {
    margin-top: 1.25rem;
}
/*.kpi-form-paper-portrait .kpi-hdr-table .kpi-hdr-table-label {
    background-color: #fbd9a5 !important;
}*/
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::-webkit-input-placeholder {
    color: transparent !important;
}
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input:-moz-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::-moz-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input:-ms-input-placeholder {
    color: transparent !important;
} 
.kpi-form-paper-portrait table.kpi-dtl-table tbody td input::placeholder {
    color: transparent !important;
}
.kpi-form-paper-portrait .bp-overflow-xy-auto {
    border: 0;
}
.kpi-form-paper-portrait table.kpi-dtl-table td, 
.kpi-form-paper-portrait table.kpi-dtl-table th {
    border: 1px solid transparent;
}
.kpi-form-paper-portrait table.kpi-dtl-table td {
    border-bottom: 1px #eee solid;
    border-right: 1px #eee solid;
    background-color: #fff;
}
.kpi-form-paper-portrait table.kpi-dtl-table thead tr {
    height: 50px;
}
.kpi-form-paper-portrait table.kpi-dtl-table thead tr th {
    border-top: 1px #F9F9F9 solid !important;
    border-bottom: 1px #F9F9F9 solid !important;
    background: #fff !important;
    font-weight: bold;
    font-size: 12px!important;
    background-color: #F9F9F9 !important;
    color: #99A1B7;
}
.kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a.active {
    border-bottom: 3px solid #1B84FF !important;
    color: #1B84FF;
}
.mv-checklist3-render-parent .kpi-form-paper-portrait .tabbable-line>.nav-tabs>li.open, 
.mv-checklist3-render-parent .kpi-form-paper-portrait .tabbable-line>.nav-tabs>li a:hover {
    border-bottom: 3px solid #1B84FF;
    color: #1B84FF;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link {
    background-color: #f5f5f5;
    border-top: 1px #ddd solid;
    border-left: 1px #ddd solid;
    border-bottom: 1px #ddd solid;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li:last-child a.nav-link {
    border-right: 1px #ddd solid;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link.active {
    background-color: #fff;
    border-top: 1px transparent solid;
    border-bottom: 1px solid transparent!important;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link:before {
    height: 2px;
    top: -1px;
    left: -1px;
    right: -1px;
    content: '';
    position: absolute;
}
.kpi-form-paper-portrait .bp-tabs .tab-pane .tabbable-line>.nav-tabs>li a.nav-link.active:before {
    background-color: #e9a22f;
}
.kpi-notfocus-readonly-input {
    cursor: text!important;
    background-color: inherit!important;
}
.kpi-notfocus-readonly-input:focus, 
table.table td.stretchInput input.kpi-notfocus-readonly-input:not(.select2-input):not(.error):focus {
    border: 1px solid transparent !important;
}
input.kpi-notfocus-readonly-input::-webkit-input-placeholder {
    color: transparent !important;
}
input.kpi-notfocus-readonly-input:-moz-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input::-moz-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input:-ms-input-placeholder {
    color: transparent !important;
} 
input.kpi-notfocus-readonly-input::placeholder {
    color: transparent !important;
}
.select2-container.select2-container-disabled.kpi-notfocus-readonly-input .select2-choice {
    background-color: #fff;
    background-image: none;
    border: 1px solid #fff;
    cursor: text;
    padding-left: 3px;
}
.select2-container.select2-container-disabled.kpi-notfocus-readonly-input .select2-choice .select2-arrow {
    display: none;
}
.kpi-form-paper-portrait table.table td.stretchInput input[type="text"]:not(.select2-input):not(.error):focus, 
.kpi-form-paper-portrait table.table td.stretchInput textarea:not(.error):focus {
    border: 1px solid #e9a22f !important;
}
.mv-rows-title .label-colon {
    display: none;
}
.mv-inline-field {
    display: inline-block;
    padding-left: 10px;
}
<?php
if ($this->kpiTypeId == '2013' && Config::getFromCache('IS_MV_ACTIVE_INPUT_COLOR')) {
?>
.kpi-form-paper-portrait input[name][type="text"][data-path]:not(:read-only), 
.kpi-form-paper-portrait input.bigdecimalInit[type="text"][data-path]:not(:read-only), 
.kpi-form-paper-portrait input.lookup-code-autocomplete[name][type="text"]:not(:read-only), 
.kpi-form-paper-portrait input.lookup-name-autocomplete[name][type="text"]:not(:read-only), 
.kpi-form-paper-portrait textarea[name][data-path]:not(:read-only), 
.kpi-form-paper-portrait .mv-ind-combo:not(.select2-container-disabled) .select2-choice, 
.kpi-form-paper-portrait .mv-ind-combo:not(.select2-container-disabled) .select2-choices {
    border: 2px #179d81 solid!important;
}
.kpi-form-paper-portrait textarea.description_autoInit {
    min-height: 28px!important;
}
<?php
}
?>
</style>

<script type="text/javascript">
    $(document.body).on('shown.bs.tab', '.mv-main-tabs > ul.nav-tabs > li > a', function(e) {
        $('.mv_checklist_02_sub').first().trigger('click');
    })
</script>

<?php require getBasePath() . 'middleware/views/form/kpi/indicator/checklist/scripts.php'; ?>