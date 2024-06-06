<?php
if ($this->dataViewMandatoryHeaderData && $this->row['LAYOUT_TYPE'] != 'ecommerce' && issetParam($this->dataViewCriteriaType) !== 'left web civil') {
    $sizeOfArray = count($this->dataViewMandatoryHeaderData);
    $colCount = (isset($this->row['M_CRITERIA_COL_COUNT']) && $this->row['M_CRITERIA_COL_COUNT']) ? (int) $this->row['M_CRITERIA_COL_COUNT'] : 2;
    $colMd = isset($this->row['LAYOUT_TYPE']) && $this->row['LAYOUT_TYPE'] == 'ecommerce' ? '3' : floor(12 / $colCount);
    $reminder = $sizeOfArray % $colCount;
    $rowCount = floor($sizeOfArray / $colCount) + ($reminder > 0 ? 1 : 0);
?>
<div class="xs-form top-sidebar-content w-100" id="dv-search-<?php echo $this->metaDataId; ?>">
    <form class="form-horizontal xs-form row align-items-center mandatory-criteria-form-<?php echo $this->metaDataId; ?>" method="post" id="default-mandatory-criteria-form">
        <?php
        for ($j = 0; $j < $rowCount; $j++) {
            
            $index = $j;
            $columnCount = (($reminder != 0 && $j + 1 == $rowCount) ? $reminder : $colCount);
            
            for ($i = 0; $i < $columnCount; $i++) {
                
                if ($this->dataViewMandatoryHeaderData[$index]['IS_MANDATORY_CRITERIA'] === '1') {
                    
                    if (isset($this->isBasketGrid) && $this->isBasketGrid && $this->dataViewMandatoryHeaderData[$index]['IS_CRITERIA_SHOW_BASKET'] != '1') {
                        $this->dataViewMandatoryHeaderData[$index]['IS_SHOW'] = 0;
                        echo Mdwebservice::renderParamControl($this->metaDataId, $this->dataViewMandatoryHeaderData[$index], 'param['.$this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'].']', $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)); 
                        continue;
                    }
        ?>
        <div class="col-md-<?php echo $colMd ?>">
            <?php 
            if ($sizeOfArray > 1) {
            ?>
            <div class="panel-group accordion" id="accordion3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a class="accordion-toggle accordion-toggle-styled expanded" data-toggle="collapse" data-parent="" href="#" aria-expanded="true" tabindex="-1">
                                <?php 
                                if ($this->dataViewMandatoryHeaderData[$index]['IS_REQUIRED'] == '1') {
                                    echo '<span class="required">*</span>';
                                }
                                echo $this->lang->line($this->dataViewMandatoryHeaderData[$index]['META_DATA_NAME']) 
                                ?>
                            </a>
                        </h4>
                    </div>
                    <div id="collapse_3_<?php echo $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] ?>" class="panel-collapse collapse in" aria-expanded="true">
                        <div class="panel-body p-0">
                            <div class="width-60 col-md-2 pl0 pr0 dropdown-filter-<?php echo $this->dataViewMandatoryHeaderData[$index]['ID'] ?>">
                                <?php 
                                $defaultOperator = $this->dataViewMandatoryHeaderData[$index]['DEFAULT_OPERATOR'];
                                echo Form::hidden(array('name' => 'criteriaCondition['. $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] .']', 'value' => ($defaultOperator ? $defaultOperator : '='))); 
                                echo Form::hidden(array('name' => 'mandatoryCriteria['. $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] .']', 'value' => '1')); 
                                ?>
                            </div>
                            <div class="col-md-12 pl0 pr0">
                                <?php echo Mdwebservice::renderParamControl($this->metaDataId, $this->dataViewMandatoryHeaderData[$index], "param[".$this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE']."]", $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false));  ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php 
            } else { 
                $lookupType = $this->dataViewMandatoryHeaderData[$index]['LOOKUP_TYPE']; 
            ?>
            <table class="table table-sm table-no-bordered bp-header-param mb5">
                <tbody>
                    <tr data-cell-path="<?php echo $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] ?>">                                        
                        <td class="text-right middle" style="<?php echo ($lookupType === 'button') ? 'width: 20%' : 'width: 30%' ?>">
                            <?php echo Form::label(array('text'=> $this->lang->line($this->dataViewMandatoryHeaderData[$index]['META_DATA_NAME']) ,'for'=>'param['.$this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'].']', 'data-label-path' => $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'])); ?>
                        </td>
                        <td class="middle" style="<?php echo ($lookupType === 'button') ? 'width: 80%' : 'width: 70%' ?>">
                            <div data-section-path="<?php echo $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] ?>">
                                <?php 
                                $defaultOperator = $this->dataViewMandatoryHeaderData[$index]['DEFAULT_OPERATOR'];
                                echo Form::hidden(array('name' => 'criteriaCondition['. $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] .']', 'value' => ($defaultOperator ? $defaultOperator : '='))); 
                                echo Form::hidden(array('name' => 'mandatoryCriteria['. $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] .']', 'value' => '1')); 
                                echo Mdwebservice::renderParamControl($this->metaDataId, $this->dataViewMandatoryHeaderData[$index], "param[".$this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE']."]", $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)); 
                                ?>
                            </div>
                        </td>                                    
                    </tr>
                </tbody>
            </table>
            <?php } ?>
        </div>    
        <?php
              $index = $index + ($rowCount - ($reminder != 0 && $i >= $reminder ? 1 : 0));
            }
            }
        } 
        if (!isset($this->row['IS_ALL_NOT_SEARCH']) || $this->row['IS_ALL_NOT_SEARCH'] != '1') {
        ?>
        <div class="col-md-6">
            <label class="form-check-label">
                <?php echo Form::checkbox(array('name'=>'mandatoryNoSearch', 'class'=>'notuniform mr5')).' '.$this->lang->line('all'); ?> 
            </label>
        </div>
        <?php
        }
        ?>
        <div class="clearfix w-100"></div> 
        <?php echo Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)); ?>
    </form>        
</div>
<div class="clearfix w-100"></div>

<style type="text/css">
    .width-60 {
        width: 60px !important;
    }
    .right-radius-0 .form-control-sm, .right-radius-0 .input-group .form-control, .right-radius-0 .form-control-sm,
    .right-radius-0 .select2-container .select2-choice {
        border-top-left-radius: 0 !important;
        border-bottom-left-radius: 0 !important;
    }
    .right-radius-0 .right-radius-zero {
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
        border-top-left-radius: 3px !important;
        border-bottom-left-radius: 3px !important;
        text-align: center;
        width: 60px;
        padding-left:0px !important;
    }
    .right-radius-0 .left-radius-3 {
        border-top-left-radius: 3px !important;
        border-bottom-left-radius: 3px !important;
        border-top-right-radius: 0 !important;
        border-bottom-right-radius: 0 !important;
    }
</style>
<?php
}
?>