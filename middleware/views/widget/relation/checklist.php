<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 
$headerPathArray = $headerArray = $rowsArray = array();
$positionTimer = '';
$positionSetTimer = '';
$positionSetCorrectPath = 'C8.IS_CORRECT';
$minusPx = '150';

if (issetParamArray($this->relationComponentsConfigData['header'])) {
    $headerPathArray = $this->relationComponentsConfigData['header'];
    $headerArray = Arr::groupByArrayOnlyRow($headerPathArray, 'trg_indicator_path', false);
    $positionTimer = issetParam($headerArray['position-timer']) ? issetParam($this->rowData[$headerArray['position-timer']['src_indicator_path']]) : '';
    $positionSetTimer = issetParam($headerArray['position-settimer']) ? issetParam($this->rowData[$headerArray['position-settimer']['src_indicator_path']]) : '';
}

if (issetParamArray($this->relationComponentsConfigData['rows'])) {
    $rowsPathArray = $this->relationComponentsConfigData['rows'];
    $rowsArray = Arr::groupByArrayOnlyRow($rowsPathArray, 'trg_indicator_path', false);
}
?>
<div class="wg-form-paper <?php echo $this->uniqId ?> " id="mv-checklist-render<?php echo $this->uniqId ?>">
    <div class="wg-form d-flex">
        <div class="card card-side no-border px-0 w-100">
            <div class="card-body">
                <div class="d-flex justify-content-center px-2">
                    <?php 
                        $logoImage = 'assets/custom/img/new_veritech_black_logo.png';

                        if (isset($this->logoImage) && file_exists($this->logoImage)) {
                            $logoImage = $this->logoImage;
                        }
                    ?>
                    <img style="height: 30px;float: left;" class="mr-auto" src="<?php echo $logoImage ?>">
                    <p class="mb-0 mt-1 ml-2 headerTitle mr-auto"><?php echo $this->title ?></p>
                    <?php if (issetParam($rowsArray)) { ?>
                    <button type="button" class="btn btn-sm btn-circle mr-1 btn-success bp-btn-save bp-btn-finish<?php echo $this->uniqId ?> pull-right" onclick="saveQuestion_<?php echo $this->uniqId ?>(this)" style="display: none;">
                        <i class="icon-checkmark-circle2"></i> <?php echo Lang::line('finish_btn') ?>
                    </button>
                    <?php } ?>
                </div>
                <ul class="nav nav-tabs nav-tabs-bottom mt-3">
                    <li class="nav-item"><a href="#tab-info-section-<?php echo $this->uniqId ?>" class="nav-link active" data-toggle="tab"><?php echo Lang::line('LMS_'. $this->mainIndicatorId .'TITLE_001') ?> </a></li>
                    <li class="nav-item"><a href="#tab-question-section<?php echo $this->uniqId ?>" class="nav-link disabled" data-toggle="tab"><?php echo Lang::line('LMS_'. $this->mainIndicatorId .'TITLE_002') ?></a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade active show" id="tab-info-section-<?php echo $this->uniqId ?>">
                        <div class="kpi-ind-tmplt-section padding-content">
                            <div class="row m-0">
                                <?php 
                                if ($headerArray) {
                                    for ($i = 1; $i <= sizeOf($headerArray)/2; $i++) {
                                        if (issetParam($headerArray['position-'. $i .'-label'])) {
                                            ?>
                                            <div class="col-md-6 mb-2">
                                                <div class="form-group">
                                                    <label class="form-label"><?php echo issetParam($headerArray['position-'. $i .'-label']['default_value']) ?>:</label>
                                                    <input type="text" readonly class="form-control" value="<?php echo is_array(issetParam($this->rowData[$headerArray['position-'. $i]['src_indicator_path']])) ? '' : issetParam($this->rowData[$headerArray['position-'. $i]['src_indicator_path']]); ?>">
                                                </div>
                                            </div>
                                        <?php }
                                    }
                                }
                                ?>
                            </div>
                            <?php if (issetParam($rowsArray)) { ?>
                                <div class="row m-0 mt-4">
                                    <div class="col-md-12">
                                        <button type="button" class="btn btn-sm btn-circle mr-1 btn-success bp-btn-next pull-left" onclick="closeQuestion_<?php echo $this->uniqId ?>(this)">
                                            <?php echo Lang::line('close_btn') ?> 
                                        </button>
                                        <button type="button" class="btn btn-sm btn-circle btn-success bp-btn-start pull-right" onclick="startExam_<?php echo $this->uniqId ?>(this)">
                                            <?php echo Lang::line('LMS_'. $this->mainIndicatorId .'_START') ?>
                                        </button>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="tab-question-section<?php echo $this->uniqId ?>">
                        <form method="post" enctype="multipart/form-data" class="saveForm">
                            <div class="d-flex">
                                <div class="d-none">
                                    <?php 
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiMainIndicatorId'.Mdform::$mvPathSuffix, 'value' => $this->indicatorId));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiStructureIndicatorId'.Mdform::$mvPathSuffix, 'value' => $this->structureIndicatorId));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'uxFlowActionIndicatorId'.Mdform::$mvPathSuffix, 'value' => $this->uxFlowActionIndicatorId));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'uxFlowIndicatorId'.Mdform::$mvPathSuffix, 'value' => $this->uxFlowIndicatorId));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiTblId'.Mdform::$mvPathSuffix, 'value' => Mdform::$firstTplId));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiTblIdField'.Mdform::$mvPathSuffix, 'value' => Mdform::$inputId));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiDataTblName'.Mdform::$mvPathSuffix, 'value' => $this->dataTableName));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiNamePattern'.Mdform::$mvPathSuffix, 'value' => $this->namePattern));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiCrudIndicatorId'.Mdform::$mvPathSuffix, 'value' => $this->crudIndicatorId));
                                        echo Form::hidden(array('name' => Mdform::$mvPathPrefix.'kpiActionType'.Mdform::$mvPathSuffix, 'value' => $this->actionType));
                                        echo $this->standardHiddenFields;
                                        echo implode('', Mdform::$headerHiddenControl);
                                    ?>
                                    <?php foreach ($this->rowData as $key => $row) {
                                        if (!is_array($row)) { ?>
                                            <input type="text" name="mvParam[<?php echo $key; ?>]" value="<?php echo $row ?>" >
                                            <?php }
                                    } ?>
                                </div>
                                <div class="sidebar sidebar-light sidebar-secondary sidebar-expand-md pr-2" style="width:280px">
                                    <div class="sidebar-content">
                                        <div class="card">
                                            <div class="card-body mv-checklist-menu" >
                                                <ul class="nav nav-sidebar" data-nav-type="accordion">
                                                    <?php 
                                                    if (issetParam($rowsArray['position-1'])) {
                                                        $position1PathArr = explode('.', $rowsArray['position-1']['src_indicator_path']);
                                                        if (issetParamArray($this->rowData[$position1PathArr['0']])) {
                                                            $groupIndex = 1;
                                                            $position1GroupArray = Arr::groupByArrayOnlyRows($this->rowData[$position1PathArr['0']], $position1PathArr['1']);
                                                            $number = 1;
                                                            foreach ($position1GroupArray as $position1Key => $position1Group) {
                                                                ?>
                                                                <li class="nav-item nav-item-submenu nav-group-sub-mv-opened">
                                                                    <a href="javascript:;" class="nav-link mv_checklist_02_groupname"><?php echo $position1Key; ?></a>
                                                                    <ul class="nav nav-group-sub">
                                                                    <?php 
                                                                    
                                                                    $position2PathArr = explode('.', $rowsArray['position-2']['src_indicator_path']);
                                                                    $position2GroupArray = Arr::groupByArrayOnlyRows($position1Group, $position2PathArr['1']);
                                                                    $index= $counter= 0;
                                                                    foreach ($position2GroupArray as $position2Key =>  $position2Grp) { 
                                                                        $position2Group = $position2Grp['0'];
                                                                        $tmparr = $hideTmparr = array();

                                                                        foreach ($position2Grp as $rKey => $rVal) {
                                                                            for ($c=3; $c<=10; $c++) {
                                                                                if (issetParam($rowsArray['position-' . $c]['src_indicator_path'])) {
                                                                                    $position2PathArr = explode('.', $rowsArray['position-' . $c]['src_indicator_path']);
                                                                                    if (issetParam($position2PathArr['1']))
                                                                                        $tmp['position' . $c] = issetParam($rVal[$position2PathArr['1']]);
                                                                                }
                                                                            }
                                                                            array_push($tmparr, $tmp);
                                                                            array_push($hideTmparr, $rVal);
                                                                        }
                                                                        
                                                                        $rowJson = htmlentities(json_encode($tmparr), ENT_QUOTES, 'UTF-8');
                                                                        $hideRowJson = htmlentities(json_encode($hideTmparr), ENT_QUOTES, 'UTF-8');
                                                                        ?>
                                                                        <li class="nav-item checklistmenu-item">
                                                                            <a href="javascript:;" class="mv_checklist_02_sub nav-link" onclick="checkMenuFnc<?php echo $this->uniqId ?>(this)" data-uniqid="<?php echo $this->uniqId; ?>" data-json="<?php echo $rowJson; ?>" data-paramhidden="<?php echo $hideRowJson; ?>" data-iscomment="1" data-stepid="<?php echo $this->uniqId . '_' . $groupIndex . '_' . $index++; ?>">
                                                                                <i class="far icon-checkbox-unchecked2"></i>
                                                                                <span class="number"><?php echo $number++ . '. '; ?></span>
                                                                                <span><?php echo $position2Key; ?></span>
                                                                            </a>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    </ul>
                                                                </li>
                                                                <?php 
                                                                $groupIndex++;
                                                            }
                                                        }
                                                    }
                                                    ?>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="w-100 main-content" style="background-color: #F9F9F9; width: 100vh !important;">
                                    <div class="mv-checklist-render-comment p-3">
                                        <div class="w-100 text-center">
                                            <img src="middleware/assets/img/process/background/watch.png" />
                                        </div>
                                    </div>
                                    <?php 
                                        if (issetParam($rowsArray['position-1'])) {
                                            $position1PathArr = explode('.', $rowsArray['position-1']['src_indicator_path']);
                                            if (issetParamArray($this->rowData[$position1PathArr['0']])) {
                                                $groupIndex = 1;
                                                $position1GroupArray = Arr::groupByArrayOnlyRows($this->rowData[$position1PathArr['0']], $position1PathArr['1']);
                                                $questionIndex = 1;
                                                foreach ($position1GroupArray as $position1Key => $position1Group) {

                                                    $position2PathArr = explode('.', $rowsArray['position-2']['src_indicator_path']);
                                                    $position2GroupArray = Arr::groupByArrayOnlyRows($position1Group, $position2PathArr['1']);

                                                    $index= $counter = 0;
                                                    foreach ($position2GroupArray as $position2Key =>  $position2Grp) { 
                                                        $position2Group = $position2Grp['0'];
                                                        $tmparr = $hideTmparr = array();

                                                        foreach ($position2Grp as $rKey => $rVal) {
                                                            $tmp['files'] = array();
                                                            $tmp['filePath'] = issetParam($rVal['ATTACHMENT_FILE']);

                                                            for ($c=3; $c<=10; $c++) {
                                                                if (issetParam($rowsArray['position-' . $c]['src_indicator_path'])) {
                                                                    $position2PathArr = explode('.', $rowsArray['position-' . $c]['src_indicator_path']);
                                                                    if (issetParam($position2PathArr['1']))
                                                                        $tmp['position' . $c] = issetParam($rVal[$position2PathArr['1']]);
                                                                }
                                                            }

                                                            if (issetParamArray($rowsArray['position-checktype'])) { 
                                                                $positionFilesPathArr = explode('.', $rowsArray['position-checktype']['src_indicator_path']);
                                                                $tmp['checkType'] = checkDefaultVal($rVal[$positionFilesPathArr['1']], '0');
                                                            }
                                                            else {
                                                                $tmp['checkType'] = '0';
                                                            }

                                                            if (issetParamArray($rowsArray['position-usedesc'])) { 
                                                                $positionDescPathArr = explode('.', $rowsArray['position-usedesc']['src_indicator_path']);
                                                                $tmp['usedesc'] = checkDefaultVal($rVal[$positionDescPathArr['1']], '0');
                                                            }
                                                            else {
                                                                $tmp['usedesc'] = '0';
                                                            }

                                                            if (issetParamArray($rowsArray['position-check'])) { 
                                                                $positionSetCorrectPath = $rowsArray['position-check']['src_indicator_path'];
                                                            }

                                                            if (issetParamArray($rowsArray['position-files'])) {
                                                                $positionFilesPathArr = explode('.', $rowsArray['position-files']['src_indicator_path']);

                                                                $tmp['files'] = issetParamArray($rVal[$positionFilesPathArr['1']]);
                                                                
                                                                if (issetParamArray($rowsArray['position-showtype'])) {
                                                                    $tmp['fileShowType'] = issetParamArray($rowsArray['position-showtype']) ? issetParam($rowsArray['position-showtype']['src_indicator_path']) : '1';
                                                                }
                                                            }
                                                            
                                                            if (issetParam($rowsArray['position-showtype']) && $rowsArray['position-showtype']['default_value'] === 'star') {
                                                                $tmp['showtype'] = 'star';
                                                            }
                                                            
                                                            array_push($tmparr, $tmp);
                                                            array_push($hideTmparr, $rVal);
                                                        }
                                                        
                                                        $rowJson = htmlentities(json_encode($tmparr), ENT_QUOTES, 'UTF-8');
                                                        $hideRowJson = htmlentities(json_encode($hideTmparr), ENT_QUOTES, 'UTF-8');
                                                        ?>
                                                        <div class="mv-checklist-render-comment p-3" data-stepkey="<?php echo $this->uniqId . '_' . $groupIndex . '_' . $index; ?>" style="display: none">
                                                            <div class="d-flex">
                                                                <span class="question-num"><?php echo $questionIndex++ . '. ' ?></span>
                                                                <div class="d-grid">
                                                                    <p class="question-txt"><?php echo checkDefaultVal($tmparr[0]['position3'], '...') ?> :</p>
                                                                    <p class="question-desc"><?php echo checkDefaultVal($tmparr[0]['position5'], 'Тайлбаргүй') ?> :</p>
                                                                </div>
                                                            </div>
                                                            
                                                            <?php if (issetParamArray($tmparr[0]['files'])) {
                                                                if (issetParam($tmparr[0]['fileShowType']) === '1') {
                                                                    ?>
                                                                    <div class="slick-carousel3<?php echo $this->uniqId ?>">
                                                                    <?php foreach ($tmparr[0]['files'] as $files) { ?>
                                                                        <a class="question-files text-center fancybox-img main" href="<?php echo $files['PHYSICAL_PATH']; ?>" data-fancybox="images" data-rel="fancybox-img">
                                                                            <img src="<?php echo $files['PHYSICAL_PATH']; ?>" class="file-rounded" />
                                                                        </a>
                                                                    <?php 
                                                                    } ?>
                                                                    </div>
                                                                <?php
                                                                } else { ?>
                                                                    <div class="row mb-2 bg-white p-2 rounded border">
                                                                        <?php 
                                                                        $className = sizeOf($tmparr[0]['files']) > 1 ? 'col-md-6' : 'col-md-12';
                                                                        foreach ($tmparr[0]['files'] as $files) { ?>
                                                                            <a class="<?php echo $className; ?> question-files text-center fancybox-img main" href="<?php echo $files['PHYSICAL_PATH']; ?>" data-fancybox="image" data-rel="fancybox-img">
                                                                                <img src="<?php echo $files['PHYSICAL_PATH']; ?>" class="file-rounded w-100 mw-100" />
                                                                            </a>
                                                                        <?php 
                                                                        } ?>
                                                                    </div>
                                                                <?php } ?>
                                                                
                                                                <script type="text/javascript">
                                                                    $(function () {
                                                                        Core.initFancybox($('#mv-checklist-render<?php echo $this->uniqId ?> div[data-stepkey="<?php echo $this->uniqId . '_' . $groupIndex . '_' . $index; ?>"]'));
                                                                    });
                                                                </script>
                                                            <?php } ?>
                                                            <div class="row">
                                                                <div class="col-md-12 column-parent <?php echo (issetParam($tmparr['0']['showtype']) === 'star'  || (issetParam($tmparr['0']['filePath']) && sizeOf($tmparr) % 2 === 1 )) ? '' : 'column-grap' ?>">
                                                                    <?php 
                                                                    
                                                                    if (issetParam($tmparr['0']['showtype']) === 'star') {
                                                                        ?>
                                                                        <div class="hide-param d-none">
                                                                            <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.rowState' ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.rowState' ?>" data-col-path="rowState" value="add" >
                                                                            <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.rowCount' ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.rowCount' ?>" data-col-path="rowCount" value="0" >
                                                                            <?php foreach ($hideTmparr['0'] as $key => $value) { 
                                                                                if (!is_array($value) && $position2PathArr['0'] . '.' .$key !== $positionSetCorrectPath) {
                                                                                    ?>
                                                                                    <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.' .$key ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.' .$key ?>" data-col-path="<?php echo $key ?>" value="<?php echo $value ?>" >
                                                                                <?php 
                                                                                }
                                                                            } ?>
                                                                        </div>
                                                                        <ul class="nav navbar-nav star-rating" style="display: -webkit-inline-box;">
                                                                            <input type="hidden" name="mvParam[<?php echo $positionSetCorrectPath; ?>]['0']" class="form-control form-control-sm" data-path="<?php echo $positionSetCorrectPath; ?>" data-field-name="<?php echo $positionSetCorrectPath; ?>" data-isclear="0" value="0">
                                                                            <li data-id="1" title="1">
                                                                                <i class="icon-star-empty3" style="color: #ccc; cursor: pointer;"></i>
                                                                            </li>
                                                                            <li data-id="2" title="2">
                                                                                <i class="icon-star-empty3" style="color: #ccc; cursor: pointer;"></i>
                                                                            </li>
                                                                            <li data-id="3" title="3">
                                                                                <i class="icon-star-empty3" style="color: #ccc; cursor: pointer;"></i>
                                                                            </li>
                                                                            <li data-id="4" title="4">
                                                                                <i class="icon-star-empty3" style="color: #ccc; cursor: pointer;"></i>
                                                                            </li>
                                                                            <li data-id="5" title="5">
                                                                                <i class="icon-star-empty3" style="color: #ccc; cursor: pointer;"></i>
                                                                            </li>
                                                                        </ul>
                                                                        <?php
                                                                    } else {
                                                                        foreach ($tmparr as $i => $row) {
                                                                            if (issetParam($row['usedesc']) === '1') { ?>
                                                                                <div class="hide-param d-none">
                                                                                    <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.rowState' ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.rowState' ?>" data-col-path="rowState" value="add" >
                                                                                    <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.rowCount' ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.rowCount' ?>" data-col-path="rowCount" value="0" >
                                                                                    <?php foreach ($hideTmparr[$i] as $key => $value) { 
                                                                                        if (!is_array($value) && (Str::lower($key) !== 'description')) {
                                                                                            ?>
                                                                                            <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.' .$key ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.' .$key ?>" data-col-path="<?php echo $key ?>" value="<?php echo $value ?>" >
                                                                                        <?php 
                                                                                        }
                                                                                    } ?>
                                                                                </div>
                                                                                <textarea class="form-control form-control-sm description_autoInit" name="mvParam[<?php echo $position2PathArr['0'] . '.DESCRIPTION' ?>][<?php echo $counter; ?>]"></textarea>
                                                                            <?php
                                                                            } else {
                                                                                if (issetParam($row['filePath'])) { 
                                                                                    $checkBoxType = ($row['checkType'] === '1') ? "icon-checkbox-unchecked2" : "icon-radio-unchecked"; ?>
                                                                                    <div class="w-100 file-parent">
                                                                                        <button type="button" data-checktype="<?php echo $row['checkType'] ?>" class="col-md-12 btn text-left answer-txt answer-txt-nopadding <?php echo issetParam($row['filePath']) ? 'bg-none' : ''; ?>" onclick="activeAnswer_<?php echo $this->uniqId ?>(this)">
                                                                                            <div class="hide-param d-none">
                                                                                                <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.rowState' ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.rowState' ?>" data-col-path="rowState" value="add" >
                                                                                                <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.rowCount' ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.rowCount' ?>" data-col-path="rowCount" value="0" >
                                                                                                <?php foreach ($hideTmparr[$i] as $key => $value) { 
                                                                                                    if (!is_array($value)) {
                                                                                                        ?>
                                                                                                        <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.' .$key ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.' .$key ?>" data-col-path="<?php echo $key ?>" value="<?php echo $value ?>" >
                                                                                                    <?php 
                                                                                                    }
                                                                                                } ?>
                                                                                            </div>
                                                                                            <i class="far <?php echo $checkBoxType ?>"></i>
                                                                                            <!-- <input type="<?php echo $checkBoxType ?>" class="booleanInit" id="answer_<?php echo $this->uniqId. '_' . $index . '_' . $i ?>" name="answer_<?php echo $this->uniqId. '_' . $index ?>" /> -->
                                                                                            <label class="mb-2" for="answer_<?php echo $this->uniqId. '_' . $index . '_' . $i ?>"><?php echo $row['position4'] ?></label>
                                                                                        </button>
                                                                                        <a class="col-md-12 question-files text-center fancybox-img main answer_<?php echo $this->uniqId. '_' . $index . '_' . $i ?>" href="<?php echo $row['filePath']; ?>" data-fancybox="image" data-rel="fancybox-img">
                                                                                            <img src="<?php echo $row['filePath']; ?>" class="file-rounded w-100 mw-100" />
                                                                                        </a>
                                                                                        <script type="text/javascript">
                                                                                            $(function () {
                                                                                                Core.initFancybox($('#mv-checklist-render<?php echo $this->uniqId ?> a.answer_<?php echo $this->uniqId. '_' . $index . '_' . $i ?>'));
                                                                                            });
                                                                                        </script>
                                                                                    </div>
                                                                                <?php } else { ?>
                                                                                <button type="button" data-checktype="<?php echo $row['checkType'] ?>" class="btn text-left answer-txt <?php echo issetParam($row['filePath']) ? 'bg-none' : ''; ?>" onclick="activeAnswer_<?php echo $this->uniqId ?>(this)">
                                                                                    <div class="hide-param d-none">
                                                                                        <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.rowState' ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.rowState' ?>" data-col-path="rowState" value="add" >
                                                                                        <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.rowCount' ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.rowCount' ?>" data-col-path="rowCount" value="0" >
                                                                                        <?php foreach ($hideTmparr[$i] as $key => $value) { 
                                                                                            if (!is_array($value)) {
                                                                                                ?>
                                                                                                <input type="hidden" name="mvParam[<?php echo $position2PathArr['0'] . '.' .$key ?>][<?php echo $counter; ?>]" data-path="<?php echo $position2PathArr['0'] . '.' .$key ?>" data-col-path="<?php echo $key ?>" value="<?php echo $value ?>" >
                                                                                            <?php 
                                                                                            }
                                                                                        } ?>
                                                                                    </div>
                                                                                    <?php echo $row['position4'] ?>
                                                                                </button>
                                                                                <?php } ?>
                                                                            <?php 
                                                                            }
                                                                            $counter++;
                                                                        }
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <script type="text/javascript">
                                                            $(function () {
                                                                Core.initInputType($('#mv-checklist-render<?php echo $this->uniqId ?> div[data-stepkey="<?php echo $this->uniqId . '_' . $groupIndex . '_' . $index; ?>"]'));
                                                            });
                                                        </script>
                                                        <?php
                                                        $index++;
                                                    }
                                                    $groupIndex++;
                                                }
                                                
                                            }
                                        }
                                    ?>
                                </div>
                            </div>           
                        </form>
                        <?php if (issetParam($rowsArray)) { ?>
                            <div class="w-100 pull-left p-2 actions"> 
                                <button type="button" class="btn btn-sm btn-circle mr-1 btn-success bp-btn-next pull-left" onclick="closeQuestion_<?php echo $this->uniqId ?>(this)">
                                    <?php echo Lang::line('close_btn') ?> 
                                </button>
                                <button type="button" class="btn btn-sm btn-circle mr-1 btn-success bp-btn-next pull-right next-question" onclick="nextQuestion_<?php echo $this->uniqId ?>(this)">
                                    <?php echo Lang::line('next_btn') ?> <i class="icon-arrow-right5"></i> 
                                </button>
                                <button type="button" class="btn btn-sm btn-circle mr-1 btn-success bp-btn-next pull-right prev-question" style="display: none" onclick="prevQuestion_<?php echo $this->uniqId ?>(this)">
                                    <i class="icon-arrow-left5"></i>  <?php echo Lang::line('prev_btn') ?>
                                </button>
                            </div> 
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php if (checkDefaultVal($positionTimer, '0') !== '0') { ?>
        <div class="timer-group<?php echo $this->uniqId ?> ">
            <div class="timer-group ml-3">
                <div class="timer hour d-none">
                    <div class="hand<?php echo $this->uniqId ?>"><span></span></div>
                    <div class="hand<?php echo $this->uniqId ?>"><span></span></div>
                </div>
                <div class="timer minute">
                    <div class="hand<?php echo $this->uniqId ?> not-start"><span></span></div>
                    <div class="hand<?php echo $this->uniqId ?> not-start"><span></span></div>
                </div>
                <!-- <div class="timer second">
                    <div class="hand"><span></span></div>
                    <div class="hand"><span></span></div>
                </div> -->
                <div class="face">
                    <h2>Хугацаа</h2>
                    <p id="lazy<?php echo $this->uniqId ?>">00:<?php echo checkDefaultVal($positionTimer, '0') < '10' ? '0' . checkDefaultVal($positionTimer, '0') : checkDefaultVal($positionTimer, '00')  ?>:00</p>  
                </div>
            </div>
        </div>
    <?php } ?>
    </div>
</div>

<style type="text/css">

    div[aria-describedby="dialog-widgetrender-<?php echo $this->mainIndicatorId ?>"] {
        .wg-form-paper {
            background-image: url('<?php echo checkDefaultVal($this->bgImage, 'middleware/assets/img/process/background/back.png') ?>');
            background-repeat: no-repeat;
            background-position: top center;
            background-attachment: fixed;
            background-color: #ededed;
            padding-top: 11px;
            background-size: cover;
            height: 100%;
            .number {
                width: 25px;
                min-width: 25px;
            }
            /* @media (max-width: 576px) {
                .wg-form {
                    max-width: 540px !important; 
                }
            }

            @media (max-width: 768px) {
                .wg-form {
                    max-width: 720px !important; 
                }
            }

            @media (max-width: 992px) {
                .wg-form {
                    max-width: 960px !important; 
                }
            }

            @media (max-width: 1200px) {
                .wg-form {
                    max-width: 1140px !important; 
                } 
            }

            @media (max-width: 1440px) {
                .wg-form {
                    max-width: 1340px !important; 
                } 
            } */

            .wg-form {
                position: relative;
                width: 1200px;
                min-height: calc(100vh - 126px);
                margin-left: auto;
                margin-right: auto;

                .card-side  {

                    /* max-width: 80% !important; */
                    margin-top: 40px;
                    padding: 20px;
                    box-shadow: 0px 2px 6px 0 rgba(0,0,0,.5);
                    background: #FFF;
                    border-radius: 20px;
                    width: 1200px;

                    .nav-link.active {
                        color: #699BF7 !important;
                        font-size: 18px;
                    }

                    .done > .nav-link.active,
                    .done > .nav-link.active i {
                        color: #4CAF50 !important;
                    }
                    
                    .nav-link {
                        font-size: 18px;
                        font-weight: 500;
                        line-height: 21px;
                        letter-spacing: 0px;
                        text-align: left;
                        padding: 20px;
                    }
    
                    .nav-tabs-bottom .nav-link.active:before {
                        background-color: #699BF7 !important;
                    }
    
                    .headerTitle {
                        font-size: 24px;
                        font-weight: 700;
                        line-height: 28px;
                        letter-spacing: 0px;
                        text-align: left;
                    }
    
                    .nav-tabs-bottom {
                        box-shadow: 0px 5px 10px 0px #0000001A;
                    }
                    
                    .bp-btn-start,
                    .bp-btn-next,
                    .bp-btn-save {
                        color: #699BF7;
                        border-color: #699BF7;
                        background-color: #FFF !important;
                    }
                    
                    .bp-btn-start:hover,
                    .bp-btn-next:hover,
                    .bp-btn-save:hover {
                        color: #FFF !important;
                        border-color: #699BF7;
                        background-color: #699BF7 !important;
                    }

                    .bp-btn-start,
                    .bp-btn-next,
                    .bp-btn-save {
                        padding: 12px 15px 10px 15px;
                        border-radius: 20px;
                        gap: 5px;
                        background: #468CE2;
                        font-size: 14px;
                        line-height: 16px;
                        letter-spacing: 0em;
                        text-align: center;
                    }
                    
                    .form-control {
                        border-radius: 20px;
                        border: 0.5px;
                        padding: 11px 20px;
                        font-size: 16px;
                        font-weight: 600;
                        letter-spacing: 0px;
                        min-height: 46px !important;
                        text-align: left;
                        color: #585858;
                    }
                    .form-control:not(.description_autoInit) {
                        line-height: 24px;
                        height: 46px !important;
                    }
    
                    .form-label {
                        font-size: 16px;
                        font-weight: 400;
                        line-height: 24px;
                        letter-spacing: 0px;
                        text-align: left;
                    }
    
                    .padding-content {
                        padding: 24px 11px 0 11px;
                    }
    
                    .mv-checklist-menu {
                        overflow-y: auto;
                        overflow-x: hidden;

                        .nav-group-sub-mv-opened .nav-group-sub {
                            display: block;
                        }
                        .nav-sidebar .nav-item:not(.nav-item-header):last-child {
                            padding-bottom: 0 !important;
                        }
                        .nav-item-submenu.nav-group-sub-mv-opened>.nav-link:after {
                            -webkit-transform: rotate(90deg);
                            transform: rotate(90deg);
                        }
                        .nav-group-sub .nav-link {
                            padding-left: 20px;
                        }
                        .nav-item-submenu>.nav-link.mv_checklist_02_groupname:after {
                            margin-top: -6px;
                        }
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
                        }    

                        .nav-link.mv_checklist_02_sub i {
                            color: #1B84FF;
                            margin-top: 2px;
                            font-size: 18px;    
                            margin-right: 13px;
                        }    
                    }
                    
                    .main-content {
                        background-color: #F9F9F9;
                        overflow-y: auto;
                        overflow-x: hidden;
                    }
                    
                    .mv-checklist-render-comment {

                        .question-num {
                            width: 40px;
                            min-width: 40px;
                            font-size: 18px;
                            font-weight: 500;
                            line-height: 21px;
                            letter-spacing: 0px;
                            text-align: left;
                            color: #585858;
                            margin-bottom: 5px;
                        }

                        .question-txt {
                            font-size: 18px;
                            font-weight: 500;
                            line-height: 21px;
                            letter-spacing: 0px;
                            text-align: left;
                            color: #585858;
                            margin-bottom: 5px;
                        }

                        .question-desc {
                            font-size: 12px;
                            font-weight: 500;
                            line-height: 14px;
                            letter-spacing: 0px;
                            text-align: left;
                            color: #9E9E9E;
                            margin-bottom: 20px;
                        }

                        .file-rounded {
                            border-radius: 10px;
                            border: 4px solid transparent;
                            box-shadow: 0px 4px 4px 0px transparent;
                            max-width: max-content !important;
                            max-width: 365px !important;
                            max-height: 175px;
                            height: 175px;
                        }

                        .column-grap {
                            display: inline-block;
                            column-gap: 10px;
                            column-count: 2;
                        }

                        .answer-txt:not(.answer-txt-nopadding) {
                            font-size: 12px;
                            font-weight: 400;
                            line-height: 14px;
                            letter-spacing: 0px;
                            text-align: left !important;
                            padding: 15px 25px 15px 25px !important;
                            border-radius: 50px !important;
                            gap: 10px !important;
                            margin-bottom: 15px;
                            background-color: #FFF !important;
                            border-color: #FFF;
                            color: #585858 !important;
                            width: 100%;
                            
                            .far {
                                color: #333 ;
                                font-size: 18px;
                            }
                            
                        }
                        .answer-txt-nopadding {
                            font-size: 12px;
                            font-weight: 400;
                            line-height: 14px;
                            letter-spacing: 0px;
                            text-align: left !important;
                            padding: 0.5rem 0 0 0.5rem !important;
                            border-radius: 50px !important;
                            gap: 10px !important;
                            margin-bottom: 15px;
                            background-color: #FFF !important;
                            border-color: #FFF;
                            color: #585858 !important;
                            width: 100%;
                            
                            .far {
                                color: #333 ;
                                font-size: 18px;
                            }
                        }

                        .answer-txt.active {
                            .far {
                                color: #699BF7 !important;
                            }

                        }

                        .file-rounded.active {
                            border: 4px solid #699BF7 !important;
                            box-shadow: 0px 4px 4px 0px #699BF766 !important;
                        }

                        .answer-txt.active,
                        .answer-txt:hover {
                            .far {
                                color: #333;
                            }

                            color: #FFF !important;
                            background: linear-gradient(90deg, #468CE2 0%, rgba(70, 140, 226, 0.52) 100%);
                        }
                        
                        .question-files.fancy-button {
                            max-height: max-content !important;
                            height: max-content !important;
                        }

                        .bg-none,
                        .bg-none:hover,
                        .bg-none.active{
                            background: none !important;
                            box-shadow: none !important;
                            background-color: none !important;
                            border: none !important;
                        }

                        .slick-carousel3<?php echo $this->uniqId; ?> .slick-dots{
                            position: absolute;
                            display: block;
                            width: 100%;
                            padding: 0;
                            margin: 0;
                            margin-top: 0px;
                            list-style: none;
                            list-style-type: none;
                            text-align: center;
                            bottom: 16px;
                        }    
                        .slick-carousel3<?php echo $this->uniqId; ?> .slick-dots{
                            margin: auto;
                            display: flex;
                            grid-gap: 5px;
                            text-align: center;
                            justify-content: center;
                        }
                        
                    }
                }

                .position-timer {
                    margin-left: 10px;
                    margin-top: 10%;

                    .card-body {
                        padding: 10px;
                        border-radius: 20px;
                        border: none;
                        box-shadow: 0px 5px 10px 0px #0000001A;
                        
                        .timer {

                            font-size: 38px;
                            font-weight: 400;
                            line-height: 38px;
                            letter-spacing: 0px;
                            text-align: center;
                            color: #585858;

                            .num {
                                font-size: 38px;
                                font-weight: 600;
                                line-height: 38px;
                                letter-spacing: 0px;
                                text-align: center;
                                color: #699BF7;
                            }
                            .txt {
                                font-size: 16px;
                                font-weight: 700;
                                line-height: 14px;
                                letter-spacing: 0px;
                                text-align: center;
                                color: #699BF7;
                            }
                            
                            .all {
                                padding: 55px 30px;
                                border: 15px solid #2196F3;
                                border-radius: 50%;
                            }
                        }
                    }
                }
            }
        }

        #dialog-widgetrender-<?php echo $this->mainIndicatorId ?> {
            padding-left: 0;
            padding-right: 0;
        }

        .ui-dialog-titlebar {
            border: none;
            display: none !important;
        }

        .ui-dialog-buttonpane {
            display: none !important;
        }
    }

    <?php if (checkDefaultVal($positionTimer, '0') !== '0') { ?>
        
    /* @media (max-width: 768px) {
        .timer-group<?php echo $this->uniqId ?> {
            right: 5% !important;
        }
    }

    @media (max-width: 992px) {
        .timer-group<?php echo $this->uniqId ?> {
            right: 5% !important;
        }
    }

    @media (max-width: 1200px) {
        .timer-group<?php echo $this->uniqId ?> {
            right: 5% !important;
        } 
    }

    @media (max-width: 1440px) {
        .timer-group<?php echo $this->uniqId ?> {
            right: 10% !important;
        } 
    }
    @media (max-width: 1600px) {
        .timer-group<?php echo $this->uniqId ?> {
            right: 0% !important;
        } 
    } */
    .timer-group<?php echo $this->uniqId ?> {
        /* position: fixed;
        right: 15%; */

        .timer-group {
            height: <?php echo 400-$minusPx  ?>px;
            width: <?php echo 400-$minusPx  ?>px;
            margin: 0 auto;
            position: relative;
        }
    
        .timer {
            border-radius: 50%;
            height: 100px;
            width: 100px;
            overflow: hidden;
            position: absolute;
        }
    
        .timer:after {
            background: #FFF;
            border-radius: 50%;
            content: "";
            display: block;
            position: absolute;

            height: 80px;
            width: 80px;
            left: 10px;
            top: 10px;
        }
    
        .timer .hand<?php echo $this->uniqId ?> {
            height: 100%;
            width: 50%;
            float: left;
            overflow: hidden;
            position: relative;
        }
    
        .timer .hand<?php echo $this->uniqId ?> span {
            border: 50px solid rgba(0, 255, 255, .4);
            border-bottom-color: transparent;
            border-left-color: transparent;
            border-radius: 50%;
            display: block;
            position: absolute;
            transform: rotate(225deg);

            height: 0;
            width: 0;
            right: 0;
            top: 0;
        }
    
        .timer .hand<?php echo $this->uniqId ?>:first-child {
            transform: rotate(180deg);
        }
    
        .timer .hand<?php echo $this->uniqId ?> span {
            animation-duration: 4s;
            animation-iteration-count: infinite;
            animation-timing-function: linear;
        }
    
        .timer .done<?php echo $this->uniqId ?>:first-child span,
        .timer .done<?php echo $this->uniqId ?>:last-child span {
            background: rgba(105, 155, 247, 1);
        }

        .timer .alert<?php echo $this->uniqId ?>:first-child span,
        .timer .alert<?php echo $this->uniqId ?>:last-child span {
            background: #FFE2DF !important;
            border-top-color: #F67162 !important;
            border-right-color: #F67162 !important;
        }

        .face.alert<?php echo $this->uniqId ?> p ,
        .face.alert<?php echo $this->uniqId ?> h2 {
            color: #F67162 !important; 
        }

        .timer .hand<?php echo $this->uniqId ?>:not(.done<?php echo $this->uniqId ?>, .not-start):first-child span {
            animation-name: spin1_<?php echo $this->uniqId ?>;
        }
    
        .timer .hand<?php echo $this->uniqId ?>:not(.done<?php echo $this->uniqId ?>, .not-start):last-child span {
            animation-name: spin2_<?php echo $this->uniqId ?>; 
        }
    
        .timer.hour {
            background: rgba(0, 0, 0, .3);
            left: 0;
            top: 0;
            height: <?php echo 400-$minusPx  ?>px;
            width: <?php echo 400-$minusPx  ?>px;
        }
    
        .timer.hour .hand<?php echo $this->uniqId ?> span {
            animation-duration: 3600s;
            border-top-color: rgba(105, 155, 247, 0.9);
            border-right-color: rgba(105, 155, 247, 0.9);
            border-width: <?php echo 200-$minusPx/2  ?>px;
        }
    
        .timer.hour:after {
            left: 20px;
            top: 20px;
            height: <?php echo 360-$minusPx  ?>px;
            width: <?php echo 360-$minusPx  ?>px;
        }
    
        .timer.minute {
            background: rgba(0, 0, 0, .2);
            left: 25px;
            top: 25px;
            height: <?php echo 350-$minusPx  ?>px;
            width: <?php echo 350-$minusPx  ?>px;
        }
    
        .timer.minute .hand<?php echo $this->uniqId ?> span {
            animation-duration: <?php echo checkDefaultVal($positionTimer, '1')*60 ?>s;
            border-top-color: rgba(105, 155, 247, 1);
            border-right-color: rgba(105, 155, 247, 1);
            border-width: <?php echo 175-$minusPx/2  ?>px;
        }
    
        .timer.minute:after {
            left: 20px;
            top: 20px;
            height: <?php echo 310-$minusPx  ?>px;
            width: <?php echo 310-$minusPx  ?>px;
        }
    
        .timer.second {
            background: rgba(0, 0, 0, .2);
            left: 50px;
            top: 50px;
            height: <?php echo 300-$minusPx  ?>px;
            width: <?php echo 300-$minusPx  ?>px;
        }
    
        .timer.second .hand<?php echo $this->uniqId ?> span {
            animation-duration: 1s;
            border-top-color: rgba(255, 255, 255, .15);
            border-right-color: rgba(255, 255, 255, .15);
            border-width: 150px;
        }
    
        .timer.second:after {
            left: 2px;
            top: 2px;
            height: <?php echo 296-$minusPx  ?>px;
            width: <?php echo 296-$minusPx  ?>px;
        }
    
        .face {
            /* background: rgba(0, 0, 0, .1); */
            background: rgb(255 254 254 / 10%);
            border-radius: 50%;
            padding: <?php echo 165-$minusPx  ?>px 40px 0;
            text-align: center;
            position: absolute;
            left: 52px;
            top: 52px;
            height: <?php echo 296-$minusPx  ?>px;
            width: <?php echo 296-$minusPx  ?>px;
        }
    
        .face h2 {
            font-weight: 300; 
            color: rgba(105, 155, 247, 1);
            position: absolute;
            width: 110px;
            left: 18px;
            top: 30px;
        }
    
        .face p {
            border-radius: 20px;
            font-weight: 400;
            position: absolute;
            color: rgba(105, 155, 247, 1);
            font-size: 24px;
            top: 60px;
            left: 20px;
            width: <?php echo 260-$minusPx  ?>px;
        }
    }
    
    @keyframes spin1_<?php echo $this->uniqId ?> {
        0% {
            transform: rotate(225deg);
        }
        50% {
            transform: rotate(225deg);
        }
        100% {
            transform: rotate(405deg);
        }
    }
    
    @keyframes spin2_<?php echo $this->uniqId ?> {
        0% {
            transform: rotate(225deg);
        }
        50% {
            transform: rotate(405deg);
        }
        100% {
            transform: rotate(405deg);
        }
    }
    <?php } ?>
</style>

<script type="text/javascript">
    
    var $checkList_<?php echo $this->uniqId; ?> = $('#mv-checklist-render<?php echo $this->uniqId ?>');
    var $kpiTmp_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>;
    var bp_window_<?php echo $this->uniqId; ?> = $kpiTmp_<?php echo $this->uniqId; ?>;

    var $checkListMenu_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.mv-checklist-menu');
    var $checkListContent_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.main-content');

    <?php if (checkDefaultVal($positionTimer, '0') !== '0') { ?>
        var defaults = {}, 
        one_second = 1000, 
        one_minute = one_second * 60, 
        one_hour = one_minute * 60, 
        one_day = one_hour * 24, 
        date = new Date (),
        minutes = '<?php echo checkDefaultVal($positionTimer, '0') ?>',
        finishDate = new Date(date.getTime() + minutes*60000);
        
        var requestAnimationFrame<?php echo $this->uniqId ?> = (function() {
            return window.requestAnimationFrame     || 
                window.webkitRequestAnimationFrame  || 
                window.mozRequestAnimationFrame     || 
                window.oRequestAnimationFrame       || 
                window.msRequestAnimationFrame      || 
                function(callback) {
                    window.setTimeout(callback, 1000 / 60);
                };
        }());
    
        function tick<?php echo $this->uniqId ?>() {
            try {
                var face = document.getElementById('lazy<?php echo $this->uniqId ?>');
                var now = new Date(), 
                    elapsed = finishDate - now, 
                    parts = [];
                    
                if (elapsed <= 30000) {
                    $('.hand<?php echo $this->uniqId ?>').addClass('alert<?php echo $this->uniqId ?>');
                    $('.face').addClass('alert<?php echo $this->uniqId ?>');
                }
                if (elapsed <= 0) {
                    $('.hand<?php echo $this->uniqId ?>').addClass('done<?php echo $this->uniqId ?>');
                    
                    $('.hand<?php echo $this->uniqId ?>').removeClass('alert<?php echo $this->uniqId ?>');
                    $('.face').removeClass('alert<?php echo $this->uniqId ?>');
    
                    $('.timer-group<?php echo $this->uniqId ?>').find('.face > h2').empty().append('');
                    $('.bp-btn-save<?php echo $this->uniqId ?>').trigger('click');
                    return false;
                }
                
                parts[0] = '' + Math.floor( elapsed / one_hour );
                parts[1] = '' + Math.floor( (elapsed % one_hour) / one_minute );
                parts[2] = '' + Math.floor( ( (elapsed % one_hour) % one_minute ) / one_second );
    
                parts[0] = (parts[0].length == 1) ? '0' + parts[0] : parts[0];
                parts[1] = (parts[1].length == 1) ? '0' + parts[1] : parts[1];
                parts[2] = (parts[2].length == 1) ? '0' + parts[2] : parts[2];
                face.innerText = parts.join(':');
    
                requestAnimationFrame<?php echo $this->uniqId ?>(tick<?php echo $this->uniqId ?>);
            } catch (error) {
                console.log(error);                
            }
        }
    <?php } ?>

    <?php echo $this->fullExp['varFnc']; ?>   

    $(function() { 

        Core.initInputType($checkList_<?php echo $this->uniqId; ?>);

        $checkListMenu_<?php echo $this->uniqId; ?>.height($(window).height() - $checkListMenu_<?php echo $this->uniqId; ?>.offset().top - 390);
        $checkListContent_<?php echo $this->uniqId; ?>.height($(window).height() - $checkListMenu_<?php echo $this->uniqId; ?>.offset().top - 390);
        
        $checkListMenu_<?php echo $this->uniqId; ?>.on('click', 'a.nav-link:not(.disabled)', function() {
            var $this = $(this);
        
            $checkListMenu_<?php echo $this->uniqId; ?>.find('a.nav-link.active').removeClass('active');
            $this.addClass('active');
            
            var rowJson = $this.attr('data-json'), uniqId = $this.attr('data-uniqid'), indicatorId = $this.attr('data-indicatorid'), 
                isComment = $this.hasAttr('data-iscomment') ? $this.attr('data-iscomment') : 0;
            
            if (typeof rowJson === 'undefined') {
                if ($this.parent().hasClass('nav-group-sub-mv-opened')) {
                    $this.parent().removeClass('nav-group-sub-mv-opened');
                } else {
                    $this.parent().addClass('nav-group-sub-mv-opened');
                }

                return;
            }
            
            /* if (typeof rowJson !== 'object') {
                var jsonObj = JSON.parse(html_entity_decode(rowJson, 'ENT_QUOTES'));
            } else {
                var jsonObj = rowJson;
            } */

        });

        $('.slick-carousel3<?php echo $this->uniqId; ?>').slick({
            infinite: true,
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            // variableWidth: true,
            autoplay: true,
            dots: true,
            prevArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-left" style="font-size:22px;margin: 9px;"></i></div>',
            nextArrow:'<div style="flex-shrink: 0;width: 40px;height: 40px;background: #fff;border-radius: 40px;text-align: center;box-shadow: 0px 2px 10px rgba(0, 0, 0, 0.12); cursor:pointer" class=""><i class="far fa-angle-right" style="font-size:22px;margin: 9px;"></i></div>'       
        }); 

    });

    function startExam_<?php echo $this->uniqId ?>(element) {
        var $this = $(element),
            $parent = $this.closest('.<?php echo $this->uniqId ?>');
        $this.remove();
        $parent.find('a[href="#tab-question-section<?php echo $this->uniqId ?>"]').removeClass('disabled');
        $parent.find('a[href="#tab-question-section<?php echo $this->uniqId ?>"]').trigger('click');
        
        <?php if (checkDefaultVal($positionTimer, '0') !== '0') { ?>
            date = new Date (),
            minutes = '<?php echo checkDefaultVal($positionTimer, '0') ?>',
            finishDate = new Date(date.getTime() + minutes*60000);
            
            tick<?php echo $this->uniqId ?>();
            $('.hand<?php echo $this->uniqId ?>').removeClass('not-start');
            /* $('.timer-group<?php echo $this->uniqId ?>').show(); */
        <?php } ?>
    } 

    function nextQuestion_<?php echo $this->uniqId ?>(element) {
        var $this = $(element),
            $parentAction = $this.closest('.actions'),
            $parent = $this.closest('.<?php echo $this->uniqId ?>');
        
        $parentAction.find('.prev-question').show();
        $parentAction.find('.next-question').hide();

        var $parentMenu = $parent.find('.checklistmenu-item.selected').closest('.nav-item-submenu');
        
        $('#tab-question-section1711419823453061').find('.nav-item-submenu:eq(0)').next()

        if ($parent.find('.checklistmenu-item.selected').next().length > 0) {
            if ($parent.find('.checklistmenu-item.selected').next().next().length > 0) {
                $parentAction.find('.next-question').show();
            } else {
                if ($parentMenu.next().length > 0) {
                    $parentAction.find('.next-question').show();
                } else {
                    $parent.find('.bp-btn-finish<?php echo $this->uniqId ?>').show();
                }
            }

            $parent.find('.checklistmenu-item.selected').next().find('a.mv_checklist_02_sub').trigger('click');
        } else {
            if ($parentMenu.next().length > 0) {
                $parentAction.find('.next-question').show();
                $parentMenu.next().find('.checklistmenu-item:eq(0)').find('a.mv_checklist_02_sub').trigger('click');
            }
        }
    }

    function prevQuestion_<?php echo $this->uniqId ?>(element) {
        var $this = $(element),
            $parentAction = $this.closest('.actions'),
            $parent = $this.closest('.<?php echo $this->uniqId ?>');
        
        $parentAction.find('.next-question').show();
        $parentAction.find('.prev-question').hide();

        var $parentMenu = $parent.find('.checklistmenu-item.selected').closest('.nav-item-submenu');
        if ($parent.find('.checklistmenu-item.selected').prev().length > 0) {
            if ($parent.find('.checklistmenu-item.selected').prev().prev().length > 0) {
                $parentAction.find('.prev-question').show();
            } else {
                if ($parentMenu.prev().length > 0) {
                    $parentAction.find('.prev-question').show();
                } else {
                    $parent.find('.bp-btn-finish<?php echo $this->uniqId ?>').hide();
                }
            }

            $parent.find('.checklistmenu-item.selected').prev().find('a.mv_checklist_02_sub').trigger('click');
        } else {
            if ($parentMenu.prev().length > 0) {
                $parentAction.find('.prev-question').show();
                $parentMenu.prev().find('.checklistmenu-item').last().find('a.mv_checklist_02_sub').trigger('click');
            }
        }
    } 

    function closeQuestion_<?php echo $this->uniqId ?>(element) { 
        $('.bp-btn-close<?php echo $this->uniqId ?>').trigger('click');
    }

    function saveQuestion_<?php echo $this->uniqId ?>(element) {
        
        var confirmDialog = '#dialog-ntrservice-confirm';
        if (!$(confirmDialog).length) {
            $('<div id="' + confirmDialog.replace('#', '') + '"></div>').appendTo('body');
        }

        $(confirmDialog).empty().append('<p style="text-align: center; font-size: 17px;"><?php echo Lang::line('msg_save_confirm') ?></p>');
        $(confirmDialog).dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Санамж',
            width: 450,
            height: "auto",
            modal: true,
            close: function() {
                $(confirmDialog).empty().dialog('destroy').remove();
            },
            buttons: [{
                    text: plang.get('yes_btn'),
                    class: 'btn green-meadow btn-sm',
                    click: function() {
                        $('.bp-btn-save<?php echo $this->uniqId ?>').trigger('click');
                        $(confirmDialog).empty().dialog('destroy').remove();
                    }
                },
                {
                    text: plang.get('no_btn'),
                    class: 'btn blue-madison btn-sm',
                    click: function() {
                        $(confirmDialog).empty().dialog('destroy').remove();
                    }
                }
            ]
        });

        $(confirmDialog).dialog('open');
    }

    $('a[href="#tab-question-section<?php echo $this->uniqId ?>"]').on('shown.bs.tab', function(e) {
        $checkList_<?php echo $this->uniqId; ?>.find('.mv_checklist_02_sub').first().trigger('click');
        /* $checkList_<?php echo $this->uniqId; ?>.find('.bp-btn-start').hide(); */
    });

    $('a[href="#tab-info-section-<?php echo $this->uniqId ?>"]').on('shown.bs.tab', function(e) {
        /* $('.timer-group<?php echo $this->uniqId ?>').hide(); */
    });

    $('body').on('keydown', '#tab-question-section<?php echo $this->uniqId ?> .description_autoInit', function () {
        $('#tab-question-section<?php echo $this->uniqId ?> .checklistmenu-item.selected').find('i.icon-checkbox-unchecked2').addClass('icon-checkbox-checked2').removeClass('icon-checkbox-unchecked2');
        $('#tab-question-section<?php echo $this->uniqId ?> .checklistmenu-item.selected').addClass('done');
    });

    function activeAnswer_<?php echo $this->uniqId ?>(element) {
        var _this = $(element),
            _checkType = _this.attr('data-checktype'),
            _selector = _this.find('.hide-param');
            
        if (_checkType === '1') {
            if (_this.hasClass('active')) {
                _this.removeClass('active');
                _this.find('input[data-path="<?php echo $positionSetCorrectPath; ?>"]').val('0');
                _this.closest('.file-parent').find('.file-rounded').removeClass('active');
                _this.find('.far').removeClass('icon-checkbox-checked2').addClass('icon-checkbox-unchecked2');
            } else {
                _this.addClass('active');
                _this.closest('.file-parent').find('.file-rounded').addClass('active');
                _this.find('input[data-path="<?php echo $positionSetCorrectPath; ?>"]').val('1');
                _this.find('.far').removeClass('icon-checkbox-unchecked2').addClass('icon-checkbox-checked2');
            }
        } else {
            _this.closest('.column-parent').find('input[data-path="<?php echo $positionSetCorrectPath; ?>"]').val('0');
            _this.closest('.column-parent').find('.far').removeClass('icon-radio-checked').addClass('icon-radio-unchecked');
            _this.closest('.column-parent').find('.active').removeClass('active');
            _this.closest('.column-parent').find('.file-rounded').removeClass('active');

            _this.find('input[data-path="<?php echo $positionSetCorrectPath; ?>"]').val('1');
            _this.find('.far').removeClass('icon-radio-unchecked').addClass('icon-radio-checked');
            _this.find('.far').removeClass('icon-radio-unchecked').addClass('icon-radio-checked');
            _this.closest('.file-parent').find('.file-rounded').addClass('active');
            _this.addClass('active');

        }

        
        $('#tab-question-section<?php echo $this->uniqId ?> .checklistmenu-item.selected').find('i.icon-checkbox-unchecked2').addClass('icon-checkbox-checked2').removeClass('icon-checkbox-unchecked2');
        $('#tab-question-section<?php echo $this->uniqId ?> .checklistmenu-item.selected').addClass('done');
    };

    function checkMenuFnc<?php echo $this->uniqId ?>(element) {
        var _this = $(element),
            stepKey = _this.attr('data-stepid'),
            rowJson = JSON.parse(_this.attr('data-json')),
            hideParams = JSON.parse(_this.attr('data-paramhidden')),
            $parentSelector = _this.closest('#mv-checklist-render<?php echo $this->uniqId ?>'),
            $parent = _this.closest('.<?php echo $this->uniqId ?>');
        
        $parentSelector.find('.checklistmenu-item.selected').removeClass('selected');
        _this.parent().addClass('selected');
        /* _this.find('i.icon-checkbox-unchecked2').addClass('icon-checkbox-checked2').removeClass('icon-checkbox-unchecked2'); */

        var $parentMenu = $parent.find('.checklistmenu-item.selected').closest('.nav-item-submenu');
        $parentSelector.find('.main-content > .mv-checklist-render-comment').hide();
        $parentSelector.find('.main-content > .mv-checklist-render-comment[data-stepkey="'+ stepKey +'"]').show();

        if ($parent.find('.checklistmenu-item.selected').next().length == 0 && $parentMenu.next().length == 0) {
            $parent.find('.bp-btn-finish<?php echo $this->uniqId ?>').show();
            $parent.find('.next-question').hide();
            $parent.find('.prev-question').show();
        }
    };

    function kpiIndicatorBeforeSave_<?php echo $this->uniqId; ?>(thisButton) {
        PNotify.removeAll();

        <?php echo issetParam($this->fullExp['beforeSave']); ?> 

        return true;
    }

    function kpiIndicatorAfterSave_<?php echo $this->uniqId; ?>(thisButton, responseStatus, responseData) {
            
        <?php echo issetParam($this->fullExp['afterSave']); ?> 

        return true;
    }
    
    function bpFullScriptsWithoutEvent_<?php echo $this->uniqId; ?>(elem, groupPath, isAddMulti, isLastRow, multiMode) {
        var element = typeof elem === 'undefined' ? 'open' : elem; 
        var groupPath = typeof groupPath === 'undefined' ? '' : groupPath; 
        var isAddMulti = typeof isAddMulti === 'undefined' ? false : isAddMulti; 
        var isLastRow = typeof isLastRow === 'undefined' ? false : isLastRow; 
        var multiMode = typeof multiMode === 'undefined' ? '' : multiMode; 
        
        <?php echo issetParam($this->fullExp['withoutEvent']); ?> 
    }
    
</script>