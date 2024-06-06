<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); 
$headerPathArray = $headerArray = $rowsArray = array();
$positionTimer = '';
$positionSetTimer = '';


$positionSetChecked = '';
$positionSetCorrect = '';
$positionSetConfirm = '';
$minusPx = '150';
$scrollType = '0';
$isRead = '0';
if (issetParamArray($this->relationComponentsConfigData['header'])) {
    $headerPathArray = $this->relationComponentsConfigData['header'];
    $headerArray = Arr::groupByArrayOnlyRow($headerPathArray, 'trg_indicator_path', false);
    $positionTimer = issetParam($headerArray['position-timer']) ? issetParam($this->rowData[$headerArray['position-timer']['src_indicator_path']]) : '';
    $positionSetTimer = issetParam($headerArray['position-settimer']) ? issetParam($this->rowData[$headerArray['position-settimer']['src_indicator_path']]) : '';
    $scrollType = issetParam($headerArray['position-scrolltype']) ? '1' : '0';
    $isRead = issetParam($headerArray['position-isread']) ? '1' : '0';
}

if (issetParamArray($this->relationComponentsConfigData['rows'])) {
    $rowsPathArray = $this->relationComponentsConfigData['rows'];
    $rowsArray = Arr::groupByArrayOnlyRow($rowsPathArray, 'trg_indicator_path', false);

    if (issetParam($rowsArray['position-checked']['src_indicator_path']) && issetParam($rowsArray['position-correct']['src_indicator_path'])) {
        $positionCheckedPathArr = explode('.', $rowsArray['position-checked']['src_indicator_path']);
        $positionCorrectPathArr = explode('.', $rowsArray['position-correct']['src_indicator_path']);
        $positionSetChecked = issetParam($positionCheckedPathArr['1']);
        $positionSetCorrect = issetParam($positionCorrectPathArr['1']);
    }

    if (issetParam($rowsArray['position-confirm']['src_indicator_path']) ) {
        $positionConfirmPathArr = explode('.', $rowsArray['position-confirm']['src_indicator_path']);
        $positionSetConfirm = issetParam($positionConfirmPathArr['1']);
    }
}

?>
<div class="wg-form-paper <?php echo $this->uniqId ?> " id="mv-checklist-render<?php echo $this->uniqId ?>">
    <div class="wg-form d-flex">
        <div class="card card-side no-border px-0">
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
                    <?php if (issetParam($rowsArray) && $isRead == '0') { ?>
                        <button type="button" class="btn btn-sm btn-circle mr-1 btn-success bp-btn-save bp-btn-finish<?php echo $this->uniqId ?> pull-right" onclick="saveQuestion_<?php echo $this->uniqId ?>(this)" >
                            <i class="icon-checkmark-circle2"></i> <?php echo Lang::line('save_btn') ?>
                        </button>
                    <?php } ?>
                </div>
                <ul class="nav nav-tabs nav-tabs-bottom mt-3">
                    <?php if ($isRead !== '0') { ?>
                        <li class="nav-item"><a href="#tab-info-section-<?php echo $this->uniqId ?>" class="nav-link active" data-toggle="tab"><?php echo Lang::line('LMS_'. $this->mainIndicatorId .'TITLE_001') ?> </a></li>
                        <li class="nav-item"><a href="#tab-question-section<?php echo $this->uniqId ?>" class="nav-link" data-toggle="tab"><?php echo Lang::line('LMS_'. $this->mainIndicatorId .'TITLE_002') ?></a></li>
                    <?php } else { ?>
                        <li class="nav-item"><a href="#tab-question-section<?php echo $this->uniqId ?>" class="nav-link active" data-toggle="tab"><?php echo Lang::line('LMS_'. $this->mainIndicatorId .'TITLE_002') ?></a></li>
                    <?php } ?>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane fade <?php echo ($isRead !== '0') ? 'active show' : '' ?>" id="tab-info-section-<?php echo $this->uniqId ?>">
                        <div class="kpi-ind-tmplt-section padding-content">
                            <div class="row m-0">
                                
                                <div class="mx-auto col-md-12 mb-3">
                                    <div class="form-group position-1-label">
                                        <?php echo checkDefaultVal($this->rowData[$headerArray['position-1']['src_indicator_path']], issetParam($headerArray['position-1']['default_value'])) ?>
                                    </div>
                                </div>
                                <div class="mx-auto col-md-12 mb-4">
                                    <div class="form-group  position-2-label">
                                        <?php echo checkDefaultVal($this->rowData[$headerArray['position-2']['src_indicator_path']], issetParam($headerArray['position-2']['default_value'])) ?>
                                    </div>
                                </div>
                                <div class="mx-auto col-md-12">
                                    <div class="form-group  position-3-label">
                                        <button type="button" class="btn btn-sm btn-circle btn-success bp-btn-next mx-autp" onclick="prevResult_<?php echo $this->uniqId ?>(this)">
                                            <?php echo Lang::line('preview_result') ?> 
                                        </button>
                                    </div>
                                </div>
                                <div class="p-3 mx-auto col-md-12">
                                    <div class="w-100 text-center">
                                        <?php if (checkDefaultVal($this->rowData[$headerArray['position-3']['src_indicator_path']], '0') === '1') { ?>
                                            <img src="middleware/assets/img/process/background/passed.png" style="max-height: 350px;" />
                                        <?php } else { ?>
                                            <img src="middleware/assets/img/process/background/failed.png" style="max-height: 350px;" />
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade <?php echo ($isRead === '0') ? 'active show' : '' ?>" id="tab-question-section<?php echo $this->uniqId ?>">
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
                                            <div class="card-body mv-checklist-menu">
                                                <ul class="nav nav-sidebar" data-nav-type="accordion">
                                                    <?php 
                                                    if (issetParam($rowsArray['position-1'])) {
                                                        $position1PathArr = explode('.', $rowsArray['position-1']['src_indicator_path']);
                                                        if (issetParamArray($this->rowData[$position1PathArr['0']])) {
                                                            
                                                            $number = 1;
                                                            $position1GroupArray = Arr::groupByArrayOnlyRows($this->rowData[$position1PathArr['0']], $position1PathArr['1']);
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
                                                                        $addinClass = $iClass = '';
                                                                        foreach ($position2Grp as $rKey => $rVal) {
                                                                            for ($c=3; $c<=10; $c++) {
                                                                                if (issetParam($rowsArray['position-' . $c]['src_indicator_path'])) {
                                                                                    $position2PathArr = explode('.', $rowsArray['position-' . $c]['src_indicator_path']);
                                                                                    if (issetParam($position2PathArr['1']))
                                                                                        $tmp['position' . $c] = issetParam($rVal[$position2PathArr['1']]);
                                                                                }
                                                                            }
                                                                            
                                                                            if (issetParam($rVal[$positionSetConfirm]) === '1') {
                                                                                $addinClass = 'confirm-type';
                                                                                $iClass = 'icon-checkbox-checked2';
                                                                            } else {
                                                                                $addinClass = 'unconfirm-type';
                                                                                $iClass = 'icon-cancel-square2';
                                                                            }

                                                                            array_push($tmparr, $tmp);
                                                                            array_push($hideTmparr, $rVal);
                                                                        }
                                                                        
                                                                        
                                                                        $rowJson = htmlentities(json_encode($tmparr), ENT_QUOTES, 'UTF-8');
                                                                        $hideRowJson = htmlentities(json_encode($hideTmparr), ENT_QUOTES, 'UTF-8');
                                                                        ?>
                                                                        <li class="nav-item checklistmenu-item <?php echo $addinClass; ?>">
                                                                            <a href="javascript:;" class="mv_checklist_02_sub nav-link" onclick="checkMenuFnc<?php echo $this->uniqId ?>(this)" data-uniqid="<?php echo $this->uniqId; ?>" data-json="<?php echo $rowJson; ?>" data-paramhidden="<?php echo $hideRowJson; ?>" data-iscomment="1" data-stepid="<?php echo $this->uniqId . '_' . $index++; ?>">
                                                                                <i class="far <?php echo $iClass ?>"></i>
                                                                                <span class="number"><?php echo $number++ . '. '; ?></span>
                                                                                <span class="pt1 q-text"><?php echo $position2Key; ?></span>
                                                                            </a>
                                                                        </li>
                                                                    <?php
                                                                    }
                                                                    ?>
                                                                    </ul>
                                                                </li>
                                                                <?php 
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
                                    <?php 
                                        if (issetParam($rowsArray['position-1'])) {
                                            $position1PathArr = explode('.', $rowsArray['position-1']['src_indicator_path']);
                                            if (issetParamArray($this->rowData[$position1PathArr['0']])) {
                                                
                                                $position1GroupArray = Arr::groupByArrayOnlyRows($this->rowData[$position1PathArr['0']], $position1PathArr['1']);
                                                foreach ($position1GroupArray as $position1Key => $position1Group) {

                                                    $position2PathArr = explode('.', $rowsArray['position-2']['src_indicator_path']);
                                                    $position2GroupArray = Arr::groupByArrayOnlyRows($position1Group, $position2PathArr['1']);
                                                    $index= $counter = 0;
                                                    $questionIndex = 1;
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
                                                            if ($positionSetChecked !== '' && $positionSetCorrect !== '') {
                                                                $tmp[$positionSetChecked] = issetParam($rVal[$positionSetChecked]);
                                                                $tmp[$positionSetCorrect] = issetParam($rVal[$positionSetCorrect]);
                                                            }
                                                            
                                                            if (issetParamArray($rowsArray['position-files'])) {
                                                                $positionFilesPathArr = explode('.', $rowsArray['position-files']['src_indicator_path']);
                                                                $tmp['files'] = issetParam($rVal[$positionFilesPathArr['1']]);
                                                                
                                                                if (issetParamArray($rowsArray['position-showtype'])) {
                                                                    $tmp['fileShowType'] = issetParam($rowsArray['position-showtype']) ? issetParam($rowsArray['position-showtype']['src_indicator_path']) : '1';
                                                                }
                                                            }

                                                            if (issetParamArray($rowsArray['position-maxpoint'])) {
                                                                $positionPointPathArr = explode('.', $rowsArray['position-maxpoint']['src_indicator_path']);
                                                                $tmp['maxpoint'] = issetParam($rVal[$positionPointPathArr['1']]);
                                                            }

                                                            if (issetParamArray($rowsArray['position-setpoint'])) {
                                                                $tmp['setpoint'] = $rowsArray['position-setpoint']['default_value'];
                                                            }

                                                            array_push($tmparr, $tmp);
                                                            array_push($hideTmparr, $rVal);
                                                        }
                                                        
                                                        $rowJson = htmlentities(json_encode($tmparr), ENT_QUOTES, 'UTF-8');
                                                        $hideRowJson = htmlentities(json_encode($hideTmparr), ENT_QUOTES, 'UTF-8');
                                                        ?>
                                                        <div class="mv-checklist-render-comment p-3 <?php echo ($scrollType == '1' ? 'd-block' : '') ?>" data-stepkey="<?php echo $this->uniqId . '_' . $index; ?>" style="display: none">
                                                            <div class="question-txt d-flex">
                                                                <span class="w-100"><?php echo $questionIndex++ . '. ' . checkDefaultVal($tmparr[0]['position3'], '...') ?> :</span>
                                                                <div class="casePointElement input-group">
                                                                    <span class="input-group-btn">
                                                                        <button tabindex="-1" onclick="return false;" class="btn">
                                                                            <i class="icon-file-check"></i> <?php echo checKDefaultVal($tmparr[0]['maxpoint'], '0') ?> /
                                                                        </button>
                                                                    </span>
                                                                    <input type="text" class="form-control form-control-sm case_point" <?php echo $isRead == '0' ? '' : 'readonly'; ?> data-path="<?php echo checKDefaultVal($tmparr[0]['setpoint'], '') ?>" data-maxpoint="<?php echo checKDefaultVal($tmparr[0]['maxpoint'], '0') ?>" value="<?php echo checKDefaultVal($tmparr[0]['position5'], '0') ?>" />
                                                                </div>
                                                            </div>
                                                            <?php if (issetParamArray($tmparr[0]['files'])) {
                                                                if (issetParam($tmparr[0]['fileShowType']) === '1') { ?>
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
                                                                <?php }
                                                            } ?>
                                                            <script type="text/javascript">
                                                                $(function () {
                                                                    Core.initFancybox($('#mv-checklist-render<?php echo $this->uniqId ?> div[data-stepkey="<?php echo $this->uniqId . '_' .  $index; ?>"]'));
                                                                    var pointScore = $('#mv-checklist-render<?php echo $this->uniqId ?> div[data-stepkey="<?php echo $this->uniqId . '_' .  $index; ?>"]').find('.case_point');
                                                                    pointScore.autoNumeric('init', { aSep: '', vMin: 0, vMax: pointScore.data('maxpoint'), mDec: 0 });
                                                                });
                                                            </script>
                                                            <div class="row">
                                                                <div class="col-md-12 column-grap">
                                                                    <?php foreach ($tmparr as $i => $row) {
                                                                        $addinClass = '';
                                                                        if ($positionSetChecked !== '' && $positionSetCorrect !== '') {
                                                                            if (issetParam($row[$positionSetChecked]) === '1' && issetParam($row[$positionSetCorrect]) !== '1') {
                                                                                $addinClass= 'uncorrect-type';
                                                                            }

                                                                            if (issetParam($row[$positionSetChecked]) === '1' && issetParam($row[$positionSetCorrect]) === '1') {
                                                                                $addinClass= 'correct-type';
                                                                            }
                                                                        }

                                                                        if ($addinClass === '' && issetParam($row[$positionSetChecked]) === '0' && issetParam($row[$positionSetCorrect]) === '1') {
                                                                            $addinClass= 'correct-type';
                                                                        }
                                                                        
                                                                        if (issetParam($row[$positionSetChecked]) === '1') {
                                                                            $addinClass .= ' selected';
                                                                        }

                                                                        ?>
                                                                        <button type="button" class="btn text-left answer-txt <?php echo $addinClass ?>">
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
                                                                            <?php if (issetParam($row['filePath'])) {  ?>
                                                                                <label class="mb-2" for="answer_<?php echo $this->uniqId. '_' . $index . '_' . $i ?>"><?php echo $row['position4'] ?></label>
                                                                                <label class="w-100 mw-100" for="answer_<?php echo $this->uniqId. '_' . $index . '_' . $i ?>">
                                                                                    <img src="<?php echo $row['filePath']; ?>" class="file-rounded w-100 mw-100" />
                                                                                </labe>
                                                                            <?php } else { ?>
                                                                                <?php echo $row['position4'] ?>
                                                                            <?php } ?>
                                                                        </button>
                                                                    <?php 
                                                                        $counter++;
                                                                    }
                                                                    ?>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <?php
                                                        $index++;
                                                    }

                                                }

                                            }
                                        }
                                    ?>
                                </div>
                            </div>           
                        </form>
                    </div>
                </div>
            </div>
        </div>
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
            .wg-form {

                .position-1-label {
                    font-size: 18px;
                    font-weight: 500;
                    line-height: 21px;
                    letter-spacing: 0em;
                    text-align: center;

                }

                .position-2-label {
                    font-size: 16px;
                    font-weight: 500;
                    line-height: 19px;
                    letter-spacing: 0em;
                    text-align: center;

                }

                .position-3-label {
                    text-align: center;
                    .btn {
                        border: 1px solid #585858 !important;
                        color: #585858 !important;

                    }
                }

                .correct-type {
                    background: linear-gradient(90deg, #39E0CF 0%, rgba(57, 224, 207, 0.52) 100%);
                    
                }

                .correct-type.selected {
                    border-color: #25D4C3 !important;
                }

                .uncorrect-type.selected {
                    border-color: #FF7E79 !important;
                }
                
                .uncorrect-type {
                    background: linear-gradient(90deg, #FF7E79 0%, rgba(255, 126, 121, 0.52) 100%);
                }

                .confirm-type {
                    .far,
                    .number,
                    .q-text {
                        color: #3EE1D0 !important;
                    }
                }

                .unconfirm-type {
                    .far,
                    .number,
                    .q-text {
                        color: #FF7F7A !important;
                    }
                }
                
                position: relative;
                width: 1200px;
                min-height: calc(100vh - 126px);
                margin-left: auto;
                margin-right: auto;
                .card-side  {
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
                    
                    .form-control:not(.case_point) {
                        border-radius: 20px;
                        border: 0.5px;
                        padding: 11px 20px;
                        font-size: 16px;
                        font-weight: 600;
                        line-height: 24px;
                        letter-spacing: 0px;
                        text-align: left;
                        height: 46px !important;
                        color: #585858;
                    }

                    .form-control.case_point {
                        float: right;
                        height: 28px !important;
                        text-align: left;
                        font-size: 12px;
                        border-radius: 0;
                        font-weight: 700;
                        letter-spacing: normal;
                        color: #468CE2;
                        padding: 6px 15px 6px 1px;
                        line-height: 15px;
                        max-width: 80px;
                        border: 1px solid #468CE2;
                        border-radius: 15px;
                        border-left: 0;
                        border-top-left-radius: 0;
                        border-bottom-left-radius: 0;
                        background: #FFF;
                    }
                    .form-control.case_point:focus {
                        border: 1px solid #468CE2 !important;
                        border-left: 0 !important;
                    }
                    .casePointElement {
                        width: 105px;

                        .input-group-btn {
                            button {
                                border: 1px solid #468CE2;
                                border-radius: 15px;
                                border-top-right-radius: 0;
                                border-bottom-right-radius: 0;
                                background: #FFF;
                                border-right: 0;
                                font-size: 12px;
                                font-weight: 700;
                                color: #333;
                                display: flex;
                                align-items: center;
                                padding: 6px 6px 3px 10px !important;

                                i {
                                    margin-right: 3px;
                                    margin-top: -3px;
                                }
                            }
                        }
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
                            margin-right: 5px;
                        }   
                        .nav-link.mv_checklist_02_sub .number {
                            color: #1B84FF;
                            padding-top: 2px;
                            padding-bottom: 2px;
                            font-size: 12px;
                            margin-right: 5px;
                        }    
                    }
                    
                    .main-content {
                        background-color: #F9F9F9;
                        overflow-y: auto;
                        overflow-x: hidden;
                    }

                    .mv-checklist-render-comment {

                        .file-rounded {
                            border-radius: 10px;
                            border: 1px solid #e5e5e5;
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
                        
                        .question-txt {
                            font-size: 18px;
                            font-weight: 500;
                            line-height: 21px;
                            letter-spacing: 0px;
                            text-align: left;
                            color: #585858;
                            margin-bottom: 20px;
                        }
                        
                        .column-grap {
                            display: inline-block;
                            column-gap: 10px;
                            column-count: 2;
                        }

                        .answer-txt {
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
                            border: 4px solid #FFF;
                        }

                        .answer-txt.active,
                        .answer-txt:hover {
                            color: #FFF !important;
                            background: linear-gradient(90deg, #468CE2 0%, rgba(70, 140, 226, 0.52) 100%);
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
            /* display: none !important; */
        }
    }

</style>

<script type="text/javascript">
    var $checkList_<?php echo $this->uniqId; ?> = $('#mv-checklist-render<?php echo $this->uniqId ?>');
    var $checkListMenu_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.mv-checklist-menu');
    var $checkListContent_<?php echo $this->uniqId; ?> = $checkList_<?php echo $this->uniqId; ?>.find('.main-content');

    $(function() { 

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
        });

    });

    function checkMenuFnc<?php echo $this->uniqId ?>(element) {
        <?php if ($scrollType === '1') { ?>
            return false;
        <?php } ?>
        var _this = $(element),
            stepKey = _this.attr('data-stepid'),
            rowJson = JSON.parse(_this.attr('data-json')),
            hideParams = JSON.parse(_this.attr('data-paramhidden')),
            $parentSelector = _this.closest('#mv-checklist-render<?php echo $this->uniqId ?>');
        
        $parentSelector.find('.checklistmenu-item.selected').removeClass('selected');
        _this.parent().addClass('selected');
        /* _this.find('i.fa-square').addClass('fa-check-square').removeClass('fa-square'); */
        
        $parentSelector.find('.main-content > .mv-checklist-render-comment').hide();
        $parentSelector.find('.main-content > .mv-checklist-render-comment[data-stepkey="'+ stepKey +'"]').show();
    };

    function prevResult_<?php echo $this->uniqId ?>(element) {
        var $this = $(element),
            $parent = $this.closest('.<?php echo $this->uniqId ?>');
        $this.remove();
        $parent.find('a[href="#tab-question-section<?php echo $this->uniqId ?>"]').trigger('click');
        
    }
    <?php if ($isRead == '0') { ?>

        function saveQuestion_<?php echo $this->uniqId ?>(element) {
            
            var confirmDialog = '#dialog-ntrservice-confirm';
            if (!$(confirmDialog).length) {
                $('<div id="' + confirmDialog.replace('#', '') + '"></div>').appendTo('body');
            }

            $(confirmDialog).empty().append('<p style="text-align: center; font-size: 17px;"><?php echo Lang::line('msg_save_confirm_case') ?></p>');
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
        $('body').on('change', '#mv-checklist-render<?php echo $this->uniqId ?> input.case_point', function () {
            var _this = $(this),
                _parent = _this.closest('div[data-stepkey]');
            _parent.find('.hide-param input[data-path="'+ _this.data('path') +'"]').val(_this.val());
        });
    
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
    <?php } ?>
    
</script>