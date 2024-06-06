<?php
if ($this->dataViewHeaderData) {
    $dataViewCriteriaType = $this->dataViewCriteriaType;
    $varDataViewHeaderData = $this->dataViewHeaderData;
    $dataViewHeaderData = !empty($this->dataViewHeaderData['data']) ? $this->dataViewHeaderData['data'] : $this->dataViewHeaderData;
    $advancedCriteriaArr = array();
    $saveCriteriaTemplate = issetParam($this->saveCriteriaTemplate) === '1' ? '' : ' hidden';
    $isHidden = (isset($this->callerType) && $this->callerType == 'package' && isset($this->packageRenderType) && $this->packageRenderType == 'leftside') ? true : false;
    
    if ($isHidden) {
        
        echo '<div class="xs-form" id="dv-search-'.$this->metaDataId.'">
            <form class="form-horizontal xs-form" method="post" id="default-criteria-form">
                '.Form::button(array('class' => 'dataview-default-filter-reset-btn')).'
                '.Form::button(array('class' => 'dataview-default-filter-btn')).'
                '.Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)).'
            </form>
        </div>';
        
    } else {
    
    if (isset($this->layoutType) && 'treeview' === $this->layoutType) {
        if (!empty($dataViewHeaderData) && !isset($dataViewHeaderData['data'])) {
            $tabCriteriaArr = array();                        
            foreach ($dataViewHeaderData as $gKey => $param) {
                if (empty($param['IS_MANDATORY_CRITERIA'])) {
            ?>
                    <div class="form-group">
                        <div class="panel-group accordion" id="accordion4-<?php echo $this->metaDataId; ?>">
                            <div class="panel panel-default">
                                <div class="panel-heading">
                                    <h4 class="panel-title"><?php echo $param['LOOKUP_TYPE'] !== 'icon' ? $this->lang->line($param['META_DATA_NAME']) : '' ?></h4>
                                </div>
                                <div id="collapse_3_<?php echo $param['META_DATA_CODE'].'_'.$this->metaDataId; ?>" aria-expanded="true">
                                    <?php
                                    if (isset($this->permissionCriteriaData['metaValues'])) {
                                        $fillPath = $this->permissionCriteriaData['metaValues'];
                                    } else {
                                        $fillPath = (isset($this->fillPath) ? $this->fillPath : false);
                                    } 

                                    echo Mdcommon::dvRenderCriteria($param, Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $fillPath, '', true));
                                    ?>
                                </div>
                            </div>
                        </div>
                    </div>  
            <?php
                }
            }
        }    
    } elseif (isset($this->layoutType) && $this->layoutType === 'ecommerce') { 
        
        if ($this->dataViewMandatoryHeaderData) {
    ?>
            <div class="xs-form p-0" id="dv-search-<?php echo $this->metaDataId; ?>" style="<?php echo $dataViewCriteriaType === 'left web civil' ? "padding-bottom: 0 !important;" : '' ?> ">
                <form class="form-horizontal mandatory-criteria-form-<?php echo $this->metaDataId; ?>" method="post" id="default-mandatory-criteria-form">
                    <?php
                    foreach ($this->dataViewMandatoryHeaderData as $gKey => $param) {
                        
                        if (isset($this->isBasketGrid) && $this->isBasketGrid && $param['IS_CRITERIA_SHOW_BASKET'] != '1') {
                            $param['IS_SHOW'] = 0;
                            echo Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)); 
                            continue;
                        }
                    ?>
                    <div class="mb-2 dv-criteria-row <?php echo ($param['META_DATA_NAME']) ? '' : 'd-none'; ?>" id="accordion4-<?php echo $this->metaDataId; ?>">
                            <h4 class="panel-title"><?php echo $param['LOOKUP_TYPE'] !== 'icon' ? $this->lang->line($param['META_DATA_NAME']) : '' ?></h4>
                            <div id="collapse_3_<?php echo $param['META_DATA_CODE'].'_'.$this->metaDataId; ?>" aria-expanded="true">
                                <?php
                                if (isset($this->permissionCriteriaData['metaValues'])) {
                                    $fillPath = $this->permissionCriteriaData['metaValues'];
                                } else {
                                    $fillPath = (isset($this->fillPath) ? $this->fillPath : false);
                                }
                                
                                if ($dataViewCriteriaType === 'left web civil') {
                                    
                                    echo '<div class="input-group input-group-criteria">';
                                        echo Mdcommon::dataviewRenderCriteriaCondition(
                                            $param,
                                            Mdwebservice::renderParamControl($this->metaDataId,
                                                $param, 
                                                'param['.$param['META_DATA_CODE'].']', 
                                                $param['META_DATA_CODE'], 
                                                $fillPath, '', true)
                                        );
                                    echo '</div>';
                                        
                                } else {
                                    
                                    echo Mdwebservice::renderParamControl($this->metaDataId, $param, 
                                            'param['.$param['META_DATA_CODE'].']', 
                                            $param['META_DATA_CODE'], 
                                            $fillPath, '', true);
                                    
                                    $metaDataCode = $param['META_TYPE_CODE'];
                                    $operand = '=';

                                    if ($metaDataCode != 'long' && $metaDataCode != 'integer' && $metaDataCode != 'date' && $metaDataCode != 'datetime' && $metaDataCode != 'boolean') {
                                        $operand = 'like';
                                    }              

                                    echo Form::hidden(array('name' => 'criteriaCondition['.$param['META_DATA_CODE'].']','value' => $operand));
                                }
                                ?>
                            </div>
                        </div>
                    <?php
                    }
                    if (!isset($this->row['IS_ALL_NOT_SEARCH']) || $this->row['IS_ALL_NOT_SEARCH'] != '1') {
                        ?>
                        <div class="col-md-6 p-0">
                            <label>
                                <span>Бүгд</span> <?php echo Form::checkbox(array('name' => 'mandatoryNoSearch')); ?>
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="clearfix w-100"></div> 
                    <?php echo Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)); ?>
                </form>        
            </div>

        <?php }
?>
        <div class="<?php echo (isset($this->useBasket) && $this->useBasket &&  Config::getFromCache('tmsCustomerCode') == 'gov') ? 'p-2 search-forms' : ''; ?>" id="dv-search-<?php echo $this->metaDataId; ?>">
            <form class="form-horizontal" method="post" id="default-criteria-form">
                <?php
                
                if (isset($varDataViewHeaderData['dataGroup']) && !empty($varDataViewHeaderData['dataGroup']) && $dataViewCriteriaType !== 'left web civil') {
                    $firstRow = true;
                    foreach ($varDataViewHeaderData['dataGroup']['header'] as $hkey => $headerParam) {
                        $criteriaHeaderData = Arr::groupByArrayOnlyRows($varDataViewHeaderData['dataGroup']['content'][$hkey], 'SEARCH_GROUPING_NAME');

                        foreach ($criteriaHeaderData as $key => $headerData) {

                            $criteriaHtml = $accordion_toggle = '';
                            foreach ($headerData as $param) { 
                                if (empty($param['IS_MANDATORY_CRITERIA'])) {
                                    $accordion_toggle = $param['META_DATA_CODE'] . '_' . $this->metaDataId;
                                    $criteriaHtml .= '<h4 class="panel-title">'. $param['LOOKUP_TYPE'] !== 'icon' ? $this->lang->line($param['META_DATA_NAME']) : '' .'</h4>';
                                    $criteriaHtml .= '<div id="collapse_3_'. $accordion_toggle .'" aria-expanded="true">';
                                        $criteriaHtml .= '<div class="form-group input-group">';
                                        if (isset($this->permissionCriteriaData['metaValues'])) {
                                            $criteriaHtml .= Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], $this->permissionCriteriaData['metaValues'], '', true); 
                                        } else {
                                            $criteriaHtml .= Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false), '', true); 
                                        }
                                        $criteriaHtml .= '</div>';
                                    $criteriaHtml .= '</div>';
                                }
                            }  
                            if ($criteriaHtml) { ?>
                                <div class="form-group">
                                    <div class="panel-group accordion" id="accordion4-<?php echo $this->metaDataId; ?>">
                                        <div class="panel panel-default">
                                            <?php if ($firstRow) { ?>
                                                <div class="collapse-search-header" data-toggle="collapse" href="#collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" role="button" aria-expanded="false">
                                                    <span><?php echo $this->lang->line($key) ?></span>
                                                    <span><i class="icon-plus3"></i><span>
                                                </div>
                                                <div class="collapse multi-collapse" id="collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" aria-expanded="true">
                                                    <?php echo $criteriaHtml; ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="collapse-search-header" data-toggle="collapse" href="#collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" role="button" aria-expanded="false">
                                                    <span><?php echo $this->lang->line($key) ?></span>
                                                    <span><i class="icon-plus3"></i></span>
                                                </div>
                                                <div class="collapse multi-collapse"  id="collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" aria-expanded="true">
                                                    <?php echo $criteriaHtml; ?>
                                                </div>
                                            <?php } $firstRow = false; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        }
                    }
                }
                
                if (!empty($dataViewHeaderData) && !isset($dataViewHeaderData['data'])) {
                    
                    $tabCriteriaArr = array();
                    
                    foreach ($dataViewHeaderData as $gKey => $param) {
                        
                        if (empty($param['IS_MANDATORY_CRITERIA'])) {

                            if ($param['LOOKUP_TYPE'] === 'tab') {
                                
                                array_push($tabCriteriaArr, array(
                                    'TAB_NAME' => $this->lang->line($param['META_DATA_NAME']),
                                    'META_DATA_CODE' => $param['META_DATA_CODE'],
                                    'META_DATA_ID' => $this->metaDataId,
                                    'PARAM' => $param,
                                ));

                                echo '<div class="leftsidebar_button">'.
                                    '<div class="topbutton" id="tab-lookupdata-'.$this->metaDataId.'">'.
                                        '<div class="leftbutton">'.
                                            '<i class="fa fa-left-arrow"></i>'.
                                        '</div>'.
                                    '</div>'.
                                '</div>';
                                continue;

                            } elseif ($param['IS_ADVANCED'] === '1') {
                                
                                array_push($advancedCriteriaArr, $param);
                                continue;

                            } elseif (isset($param['IS_COUNTCARD']) && $param['IS_COUNTCARD'] === '1') {
                                
                                echo '<h4 class="panel-title">'.$this->lang->line($param['META_DATA_NAME']).'</h4>';
                                echo '<div class="leftsidebar_button mt4">'.    
                                    '<div class="topbutton" id="tab-lookupdata-'.$this->metaDataId.'">'.
                                        '<div class="leftbutton">'.
                                            '<i class="fa fa-left-arrow"></i>'.
                                        '</div>'.
                                    '</div>'.
                                '</div>';
                                
                                continue;
                            }
                        ?>
                        <div class="mb-2 dv-criteria-row <?php echo ($param['META_DATA_NAME']) ? '' : 'd-none'; ?>" id="accordion4-<?php echo $this->metaDataId; ?>">
                            <h4 class="panel-title"><?php echo $param['LOOKUP_TYPE'] !== 'icon' ? $this->lang->line($param['META_DATA_NAME']) : ''; ?></h4>
                            <div id="collapse_3_<?php echo $param['META_DATA_CODE'].'_'.$this->metaDataId; ?>" aria-expanded="true">
                                <?php
                                if (isset($this->permissionCriteriaData['metaValues'])) {
                                    
                                    if ($dataViewCriteriaType === 'left web civil'  && $param['META_TYPE_CODE'] !== 'finger_search') {
                                        
                                        echo '<div class="input-group input-group-criteria">';
                                            echo Mdcommon::dataviewRenderCriteriaCondition(
                                                $param,
                                                Mdwebservice::renderParamControl($this->metaDataId,
                                                    $param, 
                                                    'param['.$param['META_DATA_CODE'].']', 
                                                    $param['META_DATA_CODE'], 
                                                    (isset($this->fillPath) ? $this->fillPath : false) , '', true)
                                            );
                                        echo '</div>';
                                        
                                    } else {
                                        echo Mdwebservice::renderParamControl($this->metaDataId, $param, 
                                            'param['.$param['META_DATA_CODE'].']', 
                                            $param['META_DATA_CODE'], 
                                            $this->permissionCriteriaData['metaValues'] , '', true);
                                    }
                                    
                                } else {
                                    
                                    if ($dataViewCriteriaType === 'left web civil' && $param['META_TYPE_CODE'] !== 'finger_search') {
                                        
                                        echo '<div class="input-group input-group-criteria">';
                                            echo Mdcommon::dataviewRenderCriteriaCondition(
                                                $param,
                                                Mdwebservice::renderParamControl($this->metaDataId,
                                                    $param, 
                                                    'param['.$param['META_DATA_CODE'].']', 
                                                    $param['META_DATA_CODE'], 
                                                    (isset($this->fillPath) ? $this->fillPath : false) , '', true));
                                        echo '</div>';
                                        
                                    } else {
                                        echo Mdwebservice::renderParamControl($this->metaDataId,
                                            $param, 
                                            'param['.$param['META_DATA_CODE'].']', 
                                            $param['META_DATA_CODE'], 
                                            (isset($this->fillPath) ? $this->fillPath : false) , '', true);
                                    }
                                } 
                                
                                if ($dataViewCriteriaType !== 'left web civil' || $param['META_TYPE_CODE'] === 'finger_search') {
                                    $metaDataCode = $param['META_TYPE_CODE'];
                                    $operand = '=';

                                    if ($metaDataCode !== 'finger_search' && $metaDataCode != 'long' && $metaDataCode != 'integer' && $metaDataCode != 'date' && $metaDataCode != 'datetime' && $metaDataCode != 'boolean') {
                                        $operand = 'like';
                                    }           
                                    
                                    if ($defaultOperator = issetParam($param['DEFAULT_OPERATOR'])) {
                                        $operand = $defaultOperator;
                                    }
                                
                                    echo Form::hidden(array('name' => 'criteriaCondition['.$param['META_DATA_CODE'].']','value' => $operand));
                                }
                                ?>
                            </div>
                        </div>
                        <?php
                            }
                        }
                    $GLOBALS['tabCriteriaArr'] = $tabCriteriaArr;
                    $GLOBALS['ecommerceAdvancedCriteria'] = false;
                    
                    /**
                     * Advanced Criteria
                     */
                    if ($advancedCriteriaArr) {
                        $GLOBALS['ecommerceAdvancedCriteria'] = true;
                        $colCount = (isset($this->row['M_CRITERIA_COL_COUNT']) && $this->row['M_CRITERIA_COL_COUNT']) ? (int) $this->row['M_CRITERIA_COL_COUNT'] : 3;
                        $colMd = 12; //floor(12 / $colCount);

                        $detect_md_12 = 1;
                        $fieldCount = count($advancedCriteriaArr);

                        if ($fieldCount > 9) {
                            $array = array_chunk($advancedCriteriaArr, ceil($fieldCount / $colCount));
                        } else {
                            $array = array_chunk($advancedCriteriaArr, 3);
                        }
                        
                        if (issetParam($this->layoutType) !== 'ecommerce') {
                            echo '<div id="dvecommerce-advanced-criteria-' . $this->metaDataId . '" class="hidden">';
                                foreach ($array as $dataViewSearchData) {

                                    $detect_md_12++;

                                    echo '<div class="col-md-'.$colMd.' pr0">';

                                    foreach ($dataViewSearchData as $param) { ?>
                                        <div class="form-group row dv-criteria-row">
                                            <label class="col-form-label col-lg-3 text-right"><?php echo $this->lang->line($param['META_DATA_NAME']) ?></label>
                                            <div class="col-lg-9">
                                                <div class="input-group input-group-criteria">
                                                    <?php
                                                    if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
                                                        $param['CHOOSE_TYPE'] = 'multi';
                                                    }

                                                    if ($param['LOOKUP_META_DATA_ID'] == '' && $param['LOOKUP_TYPE'] == '' && ($param['META_TYPE_CODE'] === 'bigdecimal' || $param['META_TYPE_CODE'] === 'integer')) {
                                                        echo Mdcommon::dataviewRenderCriteriaCondition(
                                                            $param,     
                                                            Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."][]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)),
                                                            '=',
                                                            'top'
                                                        );
                                                    } 
                                                    else {
                                                        echo Mdcommon::dataviewRenderCriteriaCondition(
                                                            $param,     
                                                            Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)),
                                                            '=',
                                                            'top'
                                                        );
                                                    }
                                                    ?> 
                                                </div>
                                            </div>
                                        </div>
                                <?php        
                                    }

                                    echo '</div>';

                                }                        
                            echo '</div>';
                        }

                    }
                }
                
                if (isset($varDataViewHeaderData['dataGroup']) && !empty($varDataViewHeaderData['dataGroup']) && $dataViewCriteriaType === 'left web civil') {
                    $firstRow = true;
                    foreach ($varDataViewHeaderData['dataGroup']['header'] as $hkey => $headerParam) {
                        $criteriaHeaderData = Arr::groupByArrayOnlyRows($varDataViewHeaderData['dataGroup']['content'][$hkey], 'SEARCH_GROUPING_NAME');

                        foreach ($criteriaHeaderData as $key => $headerData) {

                            $criteriaHtml = $accordion_toggle = '';
                            foreach ($headerData as $param) { 
                                if (empty($param['IS_MANDATORY_CRITERIA'])) {
                                    $accordion_toggle = $param['META_DATA_CODE'] . '_' . $this->metaDataId;
                                    $criteriaHtml .= '<h4 class="panel-title">'. $param['LOOKUP_TYPE'] !== 'icon' ? $this->lang->line($param['META_DATA_NAME']) : '' .'</h4>';
                                    $criteriaHtml .= '<div id="collapse_3_'. $accordion_toggle .'" aria-expanded="true">';
                                        $criteriaHtml .= '<div class="form-group input-group">';
                                        if (isset($this->permissionCriteriaData['metaValues'])) {
                                            $criteriaHtml .= Mdcommon::dataviewRenderCriteriaCondition($param,     
                                                                Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], $this->permissionCriteriaData['metaValues'], '', true)
                                                            );
                                        } else {
                                            $criteriaHtml .= Mdcommon::dataviewRenderCriteriaCondition($param,     
                                                                Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false), '', true)
                                                            );
                                        }
                                        $criteriaHtml .= '</div>';
                                    $criteriaHtml .= '</div>';
                                }
                            }  
                            if ($criteriaHtml) { ?>
                                <div class="form-group">
                                    <div class="panel-group accordion" id="accordion4-<?php echo $this->metaDataId; ?>">
                                        <div class="panel panel-default">
                                            <?php if ($firstRow) { ?>
                                                <div class="collapse-search-header" data-toggle="collapse" href="#collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" role="button" aria-expanded="false">
                                                    <span><?php echo $this->lang->line($key) ?></span>
                                                    <span><i class="icon-plus3"></i><span>
                                                </div>
                                                <div class="collapse multi-collapse" id="collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" aria-expanded="true">
                                                    <?php echo $criteriaHtml; ?>
                                                </div>
                                            <?php } else { ?>
                                                <div class="collapse-search-header" data-toggle="collapse" href="#collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" role="button" aria-expanded="false">
                                                    <span><?php echo $this->lang->line($key) ?></span>
                                                    <span><i class="icon-plus3"></i></span>
                                                </div>
                                                <div class="collapse multi-collapse"  id="collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" aria-expanded="true">
                                                    <?php echo $criteriaHtml; ?>
                                                </div>
                                            <?php } $firstRow = false; ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                            }
                        }
                    }
                }
                ?>
                <div class="clearfix w-100"></div>
                
                <?php 
                if (!empty($dataViewHeaderData) 
                    && (
                        (!isset($dataViewHeaderData['data']) && !isset($dataViewHeaderData['dataGroup'])) 
                        || 
                        (isset($dataViewHeaderData['data']) && !empty($dataViewHeaderData['data'])) 
                        || 
                        (isset($dataViewHeaderData['dataGroup']) && !empty($dataViewHeaderData['dataGroup'])) 
                       )   
                    ) { 
                    
                    if (issetParam($this->row['IS_SHOW_FILTER_TEMPLATE']) == '1') {
                ?>
                    <hr class="mt10 mb10 <?php echo $saveCriteriaTemplate ?>" />
                    <div class="form-group<?php echo $saveCriteriaTemplate ?>">
                        <label for="criteriaTemplatesEcommerce" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplateList') ?></label>
                        <div>
                            <?php
                            echo Form::select(
                                array(
                                    'name' => 'criteriaTemplates',
                                    'id' => 'criteriaTemplatesEcommerce',
                                    'text' => $this->lang->line('choose'),
                                    'class' => 'form-control form-control-sm dropdownInput select2 select2-criteria-template-' . $this->metaDataId,
                                    'data' => array(),
                                    'op_value' => 'ID',
                                    'op_text' => 'NAME'
                                )
                            );
                            ?>                                        
                        </div>
                    </div>                                
                    <div class="form-group<?php echo $saveCriteriaTemplate ?>">
                        <label for="isSaveCriteriaTemplate" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplate') ?></label>
                        <div>
                            <input type="checkbox" value="1" name="isSaveCriteriaTemplate" id="isSaveCriteriaTemplate" class="notuniform form-check-input-switchery-<?php echo $this->metaDataId; ?>" data-fouc="" data-switchery="true">
                        </div>
                    </div>
                    <div class="form-group hidden criteria-template-hidden-<?php echo $this->metaDataId ?>">
                        <label for="criteriaTemplateName" class="col-form-label panel-title"><span class="text-danger">*</span> <?php echo $this->lang->line('criteriaTemplateName') ?></label>
                        <div>
                            <input type="text" name="criteriaTemplateName" id="criteriaTemplateName" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('criteriaTemplateName') ?>">
                        </div>
                    </div>               
                    <?php 
                    }
                    if (Config::getFromCache('searchBtnLeft') == '1') { 
                    ?>
                        <div class="row mt-3 mb-2 filter-button">
                            <div class="col-6">
                                <?php 
                                echo Form::button(
                                    array(
                                        'class' => 'btn btn-info btn-block dataview-default-filter-btn', 
                                        'value' => $this->lang->line('do_filter')
                                    )
                                ); ?>
                            </div>
                            <div class="col-6">
                                <?php echo Form::button(
                                    array(
                                        'class' => 'btn btn-danger btn-block dataview-default-filter-reset-btn', 
                                        'value' => $this->lang->line('clear_btn')
                                    )
                                ); ?>
                            </div>
                        </div> 
                    <?php } else { ?>
                        <div class="row mt-3 mb-2 filter-button">
                            <div class="col-6">
                                <?php echo Form::button(
                                    array(
                                        'class' => 'btn btn-danger btn-block dataview-default-filter-reset-btn', 
                                        'value' => $this->lang->line('clear_btn')
                                    )
                                ); ?>
                            </div>
                            <div class="col-6">
                                <?php 
                                echo Form::button(
                                    array(
                                        'class' => 'btn btn-info btn-block dataview-default-filter-btn', 
                                        'value' => $this->lang->line('do_filter')
                                    )
                                ); ?>
                            </div>
                        </div> 
                <?php 
                    } 
                }
                echo Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)); 
                ?>
            </form>        
        </div>
    <?php 
    } else {   
        if ($dataViewCriteriaType == 'top') { 
?>
            <div class="xs-form" id="dv-search-<?php echo $this->metaDataId; ?>">
                <form class="form-horizontal xs-form" method="post" id="default-criteria-form">
                    <div class="w-100" style="max-height: 300px;overflow-y: auto;overflow-x: hidden;">
                        <div class="filter-form-body">
                        <?php 
                        if (!isset($dataViewHeaderData['data'])) {
                            
                            $colCount = (isset($this->row['M_CRITERIA_COL_COUNT']) && $this->row['M_CRITERIA_COL_COUNT']) ? (int) $this->row['M_CRITERIA_COL_COUNT'] : 3;
                            $array = array_chunk($dataViewHeaderData, $colCount);

                            foreach ($array as $dataViewSearchData) {

                                $countCol = 0;
                                echo '<div class="row">';

                                foreach ($dataViewSearchData as $param) { 
                                    $countCol++; 
                                    ?>
                                    <div class="col-lg col-md-3 col-sm-4">
                                        <a class="div-accordionToggler" href="javascript:;" data-toggler-status="open" tabindex="-1">
                                            <?php echo $this->lang->line($param['META_DATA_NAME']); ?>
                                        </a>
                                        <div class="mb5 pb5">
                                            <div class="input-group input-group-criteria">
                                                <?php
                                                if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
                                                    $param['CHOOSE_TYPE'] = 'multi';
                                                }
                                                if ($param['LOOKUP_META_DATA_ID'] == '' && $param['LOOKUP_TYPE'] == '' && ($param['META_TYPE_CODE'] === 'bigdecimal' || $param['META_TYPE_CODE'] === 'integer')) {
                                                    echo Mdcommon::dataviewRenderCriteriaCondition(
                                                        $param,     
                                                        Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].'][]', $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)),
                                                        '=',
                                                        'top'
                                                    );
                                                } else {
                                                    if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] === 'popup' && $param['CHOOSE_TYPE'] === 'multi') {
                                                        if (isset($this->fillPath) && isset($this->fillPath[$param['LOWER_PARAM_NAME']]) && is_array($this->fillPath[$param['LOWER_PARAM_NAME']]) && $this->fillPath[$param['LOWER_PARAM_NAME']][0]) {
                                                            $this->fillPath[$param['LOWER_PARAM_NAME']] = $this->fillPath[$param['LOWER_PARAM_NAME']][0];
                                                        }
                                                    }
                                                    echo Mdcommon::dataviewRenderCriteriaCondition(
                                                        $param,     
                                                        Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false), '', true),
                                                        '=',
                                                        'top'
                                                    );
                                                }    
                                                if (issetParam($param['IS_KPI_CRITERIA']) && $param['REF_STRUCTURE_ID']) {
                                                    echo (new Mdform)->getKpiTemplatesByRefStrIdByInline($param['REF_STRUCTURE_ID']);
                                                }
                                                ?> 
                                            </div>
                                        </div>
                                    </div>
                                <?php    
                                }
                                if ($countCol < $colCount) {
                                    for ($i = 0; $i < $colCount - $countCol; $i++) {
                                        echo "<div class='col-lg col-md-3 col-sm-4'></div>";
                                    }
                                }
                                echo '</div>';
                            }
                        }
                        echo '<div class="clearfix w-100"></div>';
                        if (isset($varDataViewHeaderData['dataGroup']) && !empty($varDataViewHeaderData['dataGroup'])) {
                            $appendClear = 0;                            
                            echo '<div class="row w-100">';
                                foreach ($varDataViewHeaderData['dataGroup']['header'] as $hkey => $headerParam) {
                                    $appendClear++;
                                    ?>
                                    <div class="col-md-4 pb5">
                                        <div class="panel-group accordion">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title">
                                                        <a class="accordion-toggle accordion-toggle-styled collapsed" data-toggle="collapse" data-parent="" style="color: #0080ff; font-weight: bold; font-weight: 600; text-transform: uppercase;" href="<?php echo "#collapse_search_top_group_".$this->metaDataId.'_'.$hkey; ?>" aria-expanded="true" tabindex="-1"><?php echo $this->lang->line($headerParam) ?></a>
                                                    </h4>
                                                </div>
                                                <div id="collapse_search_top_group_<?php echo $this->metaDataId.'_'.$hkey ?>" class="panel-collapse collapse" aria-expanded="true">
                                                    <div class="panel-body p-0 row">
                                                        <div class="right-radius-0 row col-md-12">
                                                        <?php
                                                        $colCount = (isset($this->row['M_GROUP_CRITERIA_COL_COUNT']) && $this->row['M_GROUP_CRITERIA_COL_COUNT']) ? (int) $this->row['M_GROUP_CRITERIA_COL_COUNT'] : 2;
                                                        $colMd = floor(12 / $colCount);

                                                        $detect_md_12 = 1;
                                                        $dgCriterGroups = $varDataViewHeaderData['dataGroup']['content'][$hkey];
                                                        $fieldCount = count($dgCriterGroups);

                                                        if ($fieldCount > 6) {
                                                            $array = array_chunk($dgCriterGroups, ceil($fieldCount / $colCount));
                                                        } else {
                                                            $array = array_chunk($dgCriterGroups, 2);
                                                        }

                                                        foreach ($array as $dataViewSearchData) {

                                                            $detect_md_12++;
                                                            echo '<div class="col-md-'.$colMd.' pr0">';        

                                                            foreach ($dataViewSearchData as $param) { ?>
                                                                <a class="div-accordionToggler" href="javascript:;" data-toggler-status="open" tabindex="-1">
                                                                    <?php echo $this->lang->line($param['META_DATA_NAME']); ?>
                                                                </a>                                    
                                                                <?php
                                                                echo '<div class="mb5 pb5">';
                                                                echo '<div class="input-group input-group-criteria">';
                                                                if (empty($param['IS_MANDATORY_CRITERIA'])) {
                                                                    if (!empty($param['LOOKUP_META_DATA_ID']) && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
                                                                        $param['CHOOSE_TYPE'] = 'multi';
                                                                    }

                                                                    if ($param['LOOKUP_META_DATA_ID'] == '' && $param['LOOKUP_TYPE'] == '' && ($param['META_TYPE_CODE'] === 'bigdecimal' || $param['META_TYPE_CODE'] === 'integer')) {
                                                                        echo Mdcommon::dataviewRenderCriteriaCondition(
                                                                            $param, 
                                                                            Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."][]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)),
                                                                            '=',
                                                                            'top'                                                                    
                                                                        );
                                                                    } else {
                                                                        echo Mdcommon::dataviewRenderCriteriaCondition(
                                                                            $param, 
                                                                            Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)),
                                                                            '=',
                                                                            'top'                                                                    
                                                                        );                                                                        
                                                                    }
                                                                }
                                                                echo "</div>";
                                                                echo "</div>";
                                                            }
                                                            echo '</div>';
                                                        }
                                                        ?> 
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>  
                                    <?php
                                    if ($appendClear === 3) {
                                        echo '<div class="clearfix w-100"></div>';
                                        $appendClear = 0;
                                    }
                                }
                            echo '</div>';
                        }
                        ?>
                        <div class="clearfix w-100"></div>
                        </div>
                        <div class="col-md-12 text-right pr0 filter-right-btn">
                            <?php
                            if (issetParam($this->row['IS_SHOW_FILTER_TEMPLATE']) == '1') {
                            ?>
                            
                            <div class="form-group d-inline-block mr10 <?php echo $saveCriteriaTemplate ?>">
                                <label for="criteriaTemplates" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplateList'); ?>:</label>
                                <div class="d-inline-block" style="width: 160px">
                                    <?php
                                    echo Form::select(
                                        array(
                                            'name' => 'criteriaTemplates',
                                            'id' => 'criteriaTemplates',
                                            'text' => $this->lang->line('choose'),
                                            'data-field-name' => 'top', 
                                            'class' => 'form-control form-control-sm dropdownInput select2 select2-criteria-template-' . $this->metaDataId,
                                            'data' => array(),
                                            'op_value' => 'ID',
                                            'op_text' => 'NAME'
                                        )
                                    );
                                    ?>                                        
                                </div>
                            </div>                                
                            <div class="form-check form-check-switchery form-check-inline form-check-right mr10 <?php echo $saveCriteriaTemplate ?>">
                                <label for="isSaveCriteriaTemplate" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplate'); ?>:</label>
                                <div class="d-inline-block">
                                    <input type="checkbox" value="1" name="isSaveCriteriaTemplate" id="isSaveCriteriaTemplate" class="notuniform form-check-input-switchery-<?php echo $this->metaDataId; ?>" data-fouc="" data-switchery="true">
                                </div>
                            </div>
                            <div class="form-group d-inline-block mr10 hidden criteria-template-hidden-<?php echo $this->metaDataId ?>">
                                <label for="criteriaTemplateName" class="col-form-label panel-title"><span class="text-danger">*</span> <?php echo $this->lang->line('criteriaTemplateName'); ?>:</label>
                                <div class="d-inline-block">
                                    <input type="text" name="criteriaTemplateName" id="criteriaTemplateName" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('criteriaTemplateName') ?>">
                                </div>
                            </div>
                            
                            <?php 
                            }
                            echo Form::button(
                                array(
                                    'class' => 'btn btn-sm btn-circle blue-madison dataview-default-filter-btn', 
                                    'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('do_filter')
                                )
                            ); 
                            echo Form::button(
                                array(
                                    'class' => 'btn btn-sm btn-circle default dataview-default-filter-reset-btn', 
                                    'value' => $this->lang->line('clear_btn')
                                )
                            ); 
                            ?>
                        </div>    
                    </div>    
                    <?php echo Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)); ?>
                </form>        
            </div>
        <?php 
        } else {
            
            if ($this->dataViewMandatoryHeaderData && $dataViewCriteriaType === 'left web civil') {
                
                $sizeOfArray = count($this->dataViewMandatoryHeaderData);
                $colCount = (isset($this->row['M_CRITERIA_COL_COUNT']) && $this->row['M_CRITERIA_COL_COUNT']) ? (int) $this->row['M_CRITERIA_COL_COUNT'] : 2;
                $colMd = 12;
                $reminder = $sizeOfArray % $colCount;
                $rowCount = 1;
                ?>
                <div class="xs-form p-2" id="dv-search-<?php echo $this->metaDataId; ?>" style="<?php echo $dataViewCriteriaType === 'left web civil' ? "padding-bottom: 0 !important;" : '' ?> ">
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
                                    } ?>
                                    <div class="w-100 pull-left col-md-12">
                                        <div class="panel-group accordion" id="accordion3">
                                            <div class="panel panel-default pb-1">
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
                                                <div id="collapse_3_<?php echo $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] ?>" class="p-0 panel-collapse collapse in" aria-expanded="true">
                                                    <div class="panel-body p-0">
                                                        <div class="width-60 col-md-2 pl0 pr0 dropdown-filter-<?php echo $this->dataViewMandatoryHeaderData[$index]['ID'] ?>">
                                                            <?php 
                                                            echo Form::hidden(array('name' => 'criteriaCondition['. $this->dataViewMandatoryHeaderData[$index]['META_DATA_CODE'] .']', 'value' => '=')); 
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
                                    </div>  
                                    <?php
                                    $index = $index + ($rowCount - ($reminder != 0 && $i >= $reminder ? 1 : 0));
                                }
                            }
                        } 
                        if (!isset($this->row['IS_ALL_NOT_SEARCH']) || $this->row['IS_ALL_NOT_SEARCH'] != '1') {
                            ?>
                            <div class="col-md-6">
                                <label>
                                    <span>Бүгд</span> <?php echo Form::checkbox(array('name' => 'mandatoryNoSearch')); ?>
                                </label>
                            </div>
                            <?php
                        }
                        ?>
                        <div class="clearfix w-100"></div> 
                        <?php echo Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)); ?>
                    </form>        
                </div>

            <?php }
            
            if ($dataViewCriteriaType === 'left web' || $dataViewCriteriaType === 'left web civil') { 
        ?>
                <div class="xs-form p-2" id="dv-search-<?php echo $this->metaDataId; ?>" style="<?php echo $dataViewCriteriaType === 'left web civil' ? "padding-top: 0 !important;" : '' ?> ">
                    <form class="form-horizontal xs-form" method="post" id="default-criteria-form">
                        <div class="leftweb-accordion leftweb-criteria-seemore">
                            <?php
                            if (isset($varDataViewHeaderData['dataGroup']) && !empty($varDataViewHeaderData['dataGroup']) && $dataViewCriteriaType !== 'left web civil') {
                                foreach ($varDataViewHeaderData['dataGroup']['header'] as $hkey => $headerParam) {
                                    $criteriaHeaderData = Arr::groupByArrayOnlyRows($varDataViewHeaderData['dataGroup']['content'][$hkey], 'SEARCH_GROUPING_NAME');

                                    foreach ($criteriaHeaderData as $key => $headerData) {

                                        $criteriaHtml = $accordion_toggle = '';
                                        foreach ($headerData as $param) { 
                                            if (empty($param['IS_MANDATORY_CRITERIA'])) {

                                                $accordion_toggle = $param['META_DATA_CODE'] . '_' . $this->metaDataId;

                                                $criteriaHtml .= '<div id="collapse_3_'. $accordion_toggle .'" aria-expanded="true">';
                                                    if (isset($this->permissionCriteriaData['metaValues'])) {
                                                        $criteriaHtml .= Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], $this->permissionCriteriaData['metaValues'], '', true); 
                                                    } else {
                                                        $criteriaHtml .= Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false), '', true); 
                                                    }
                                                $criteriaHtml .= '</div>';
                                            }
                                        }  
                                        if ($criteriaHtml) { ?>
                                            <div class="panel-group accordion" id="accordion4-<?php echo $this->metaDataId; ?>">
                                                <div class="panel panel-default">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle accordion-toggle-styled expanded" tabindex="-1" data-toggle="collapse" data-parent="<?php echo '#collapse_3_'.$accordion_toggle; ?>" aria-expanded="true"><?php echo $this->lang->line($key) ?></a>
                                                        </h4>
                                                    </div>
                                                    <div id="accordion-item-group<?php echo $key ?>" aria-expanded="true">
                                                        <?php echo $criteriaHtml; ?>
                                                    </div>
                                                </div>
                                            </div>
                            <?php
                                        }
                                    }
                                }
                            }
                            if (!empty($dataViewHeaderData) && !isset($dataViewHeaderData['data'])) {
                                
                                foreach ($dataViewHeaderData as $gKey => $param) {
                                    
                                    if (empty($param['IS_MANDATORY_CRITERIA'])) {
                                        
                                        if ($param['LOOKUP_META_DATA_ID'] && $param['LOOKUP_TYPE'] == 'icon') {
                                            $title = '<a href="javascript:;" class="accordion-toggle accordion-toggle-styled text-primary expanded" onclick="dvFilterIconControl(this, \''.$this->metaDataId.'\', \''.$param['LOOKUP_META_DATA_ID'].'\');" tabindex="-1" data-toggle="collapse" data-parent="#collapse_3_'.$param['META_DATA_CODE'].'_'.$this->metaDataId.'" aria-expanded="true">
                                                '.$this->lang->line($param['META_DATA_NAME']).' <i class="icon-filter4"></i>
                                            </a>';
                                        } else {
                                            $title = '<a class="accordion-toggle accordion-toggle-styled expanded" tabindex="-1" data-toggle="collapse" data-parent="#collapse_3_'.$param['META_DATA_CODE'].'_'.$this->metaDataId.'" aria-expanded="true">
                                                '.$this->lang->line($param['META_DATA_NAME']).'
                                            </a>';
                                        }
                            ?>
                                <div class="panel-group accordion dv-criteria-row" id="accordion4-<?php echo $this->metaDataId; ?>">
                                    <div class="panel">
                                        <div class="panel-heading">
                                            <h4 class="panel-title"><?php echo $title; ?></h4>
                                        </div>
                                        <div id="collapse_3_<?php echo $param['META_DATA_CODE'].'_'.$this->metaDataId; ?>" aria-expanded="true" class="clearfix">
                                            <?php 
                                            if (isset($this->permissionCriteriaData['metaValues'])) {
                                                $fillPath = $this->permissionCriteriaData['metaValues'];
                                            } else {
                                                $fillPath = (isset($this->fillPath) ? $this->fillPath : false);
                                            } 

                                            echo Mdcommon::dvRenderCriteria($param, Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $fillPath, '', true));
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                                    }
                                }
                            }
                            if (isset($varDataViewHeaderData['dataGroup']) && !empty($varDataViewHeaderData['dataGroup']) && $dataViewCriteriaType === 'left web civil') {
                                foreach ($varDataViewHeaderData['dataGroup']['header'] as $hkey => $headerParam) {
                                    $criteriaHeaderData = Arr::groupByArrayOnlyRows($varDataViewHeaderData['dataGroup']['content'][$hkey], 'SEARCH_GROUPING_NAME');

                                    foreach ($criteriaHeaderData as $key => $headerData) {

                                        $criteriaHtml = $accordion_toggle = '';
                                        foreach ($headerData as $param) { 
                                            if (empty($param['IS_MANDATORY_CRITERIA'])) {

                                                $accordion_toggle = $param['META_DATA_CODE'] . '_' . $this->metaDataId;

                                                $criteriaHtml .= '<div class="panel-heading">';
                                                    $criteriaHtml .= '<h4 class="panel-title" style="font-weight: 400;"><a class="accordion-toggle accordion-toggle-styled expanded" tabindex="-1" data-toggle="collapse" data-parent="'. '#collapse_3_'.$param['META_DATA_CODE'].'_'.$this->metaDataId .'" aria-expanded="true">'. $this->lang->line($param['META_DATA_NAME']) .'</a></h4>';
                                                $criteriaHtml .= '</div>';
                                                $criteriaHtml .= '<div id="collapse_3_'. $accordion_toggle .'" aria-expanded="true">';
                                                    if (isset($this->permissionCriteriaData['metaValues'])) {
                                                        $criteriaHtml .= Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], $this->permissionCriteriaData['metaValues'], '', true); 
                                                    } else {
                                                        $criteriaHtml .= Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false), '', true); 
                                                    }
                                                $criteriaHtml .= '</div>';
                                            }
                                        }  
                                        if ($criteriaHtml) { ?>
                                            <div class="panel-group accordion" id="accordion4-<?php echo $this->metaDataId; ?>">
                                                <div class="panel panel-default" style="margin-top: 10px; padding-bottom: 5px;">
                                                    <div class="panel-heading">
                                                        <h4 class="panel-title">
                                                            <a class="accordion-toggle accordion-toggle-styled collapsed" href="#accordion-item-group<?php echo $key ?>" tabindex="-1" data-toggle="collapse" data-parent="<?php echo '#collapse_3_'.$accordion_toggle; ?>" aria-expanded="true">
                                                                <i class="fa fa-plus"></i>
                                                                <?php echo $this->lang->line($key) ?>
                                                            </a>
                                                        </h4>
                                                    </div>
                                                    <div id="accordion-item-group<?php echo $key ?>" aria-expanded="true" class="collapse">
                                                        <?php echo $criteriaHtml; ?>
                                                    </div>
                                                </div>
                                            </div>
                            <?php
                                        }
                                    }
                                }
                            }
                            ?>
                            
                            <div class="clearfix w-100"></div>   
                            <?php
                            if (issetParam($this->row['IS_SHOW_FILTER_TEMPLATE']) == '1') {
                            ?>
                            <div class="form-group panel-group<?php echo $saveCriteriaTemplate ?>">
                                <label for="criteriaTemplatesLeftWeb" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplateList') ?></label>
                                <div>
                                    <?php
                                    echo Form::select(
                                        array(
                                            'name' => 'criteriaTemplates',
                                            'id' => 'criteriaTemplatesLeftWeb',
                                            'text' => $this->lang->line('choose'),
                                            'class' => 'form-control form-control-sm dropdownInput select2 select2-criteria-template-' . $this->metaDataId,
                                            'data' => array(),
                                            'op_value' => 'ID',
                                            'op_text' => 'NAME'
                                        )
                                    );
                                    ?>                                        
                                </div>
                            </div>                                
                            <div class="d-flex justify-content-between form-group panel-group<?php echo $saveCriteriaTemplate ?>">
                                <div>
                                    <label for="isSaveCriteriaTemplate" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplate') ?></label>
                                    <div>
                                        <input type="checkbox" value="1" name="isSaveCriteriaTemplate" id="isSaveCriteriaTemplate" class="notuniform form-check-input-switchery-<?php echo $this->metaDataId; ?>" data-fouc="" data-switchery="true">
                                    </div>
                                </div>
                                <div>
                                    <a href="javascrip:;" class="criteria-template-delete-list-<?php echo $this->metaDataId ?>">Загвар устгах</a>
                                </div>
                            </div>
                            <div class="form-group panel-group hidden criteria-template-hidden-<?php echo $this->metaDataId ?>">
                                <label for="criteriaTemplateName" class="col-form-label panel-title"><span class="text-danger">*</span> <?php echo $this->lang->line('criteriaTemplateName') ?></label>
                                <div>
                                    <input type="text" name="criteriaTemplateName" id="criteriaTemplateName" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('criteriaTemplateName') ?>">
                                </div>
                            </div>   
                            <?php
                            }
                            ?>
                        </div>    
                        <div class="filter-right-btn leftweb-accordion-btn d-flex justify-content-between">
                            <?php
                                echo Form::button(
                                    array(
                                        'class' => 'btn btn-circle float-left default dataview-default-filter-reset-btn', 
                                        'value' => $this->lang->line('clear_btn')
                                    )
                                );
                                echo Form::button(
                                    array(
                                        'class' => 'btn btn-circle blue-madison dataview-default-filter-btn', 
                                        'value' => $this->lang->line('do_filter')
                                    )
                                );
                            ?>
                        </div> 
                        <?php echo Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)); ?>
                    </form>        
                </div>
            <?php 
            } elseif ($dataViewCriteriaType === 'popup') { 
        ?>
                <div class="xs-form" id="dv-search-<?php echo $this->metaDataId; ?>">
                    <form class="form-horizontal xs-form" method="post" id="default-criteria-form">
                        <?php
                        if (isset($varDataViewHeaderData['dataGroup']) && !empty($varDataViewHeaderData['dataGroup']) && false) {
                            foreach ($varDataViewHeaderData['dataGroup']['header'] as $hkey => $headerParam) {
                                $criteriaHeaderData = Arr::groupByArrayOnlyRows($varDataViewHeaderData['dataGroup']['content'][$hkey], 'SEARCH_GROUPING_NAME');

                                foreach ($criteriaHeaderData as $key => $headerData) {

                                    $criteriaHtml = $accordion_toggle = '';
                                    foreach ($headerData as $param) { 
                                        if (empty($param['IS_MANDATORY_CRITERIA'])) {

                                            $accordion_toggle = $param['META_DATA_CODE'] . '_' . $this->metaDataId;

                                            $criteriaHtml .= '<div id="collapse_3_'. $accordion_toggle .'" aria-expanded="true">';
                                            if (isset($this->permissionCriteriaData['metaValues'])) {
                                                $criteriaHtml .= Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], $this->permissionCriteriaData['metaValues'], '', true); 
                                            } else {
                                                $criteriaHtml .= Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false), '', true); 
                                            }                  
                                            $criteriaHtml .= '</div>';
                                        }
                                    }  
                                    if ($criteriaHtml) { ?>
                                        <div class="panel-group accordion" id="accordion4-<?php echo $this->metaDataId; ?>">
                                            <div class="panel panel-default">
                                                <div class="panel-heading">
                                                    <h4 class="panel-title"><a class="accordion-toggle accordion-toggle-styled expanded" tabindex="-1" data-toggle="collapse" data-parent="<?php echo '#collapse_3_'.$accordion_toggle; ?>" aria-expanded="true"><?php echo $this->lang->line($key) ?></a></h4>
                                                </div>
                                                <?php echo $criteriaHtml; ?>
                                            </div>
                                        </div>
                        <?php
                                    }
                                }
                            }
                        }
                        if (!empty($dataViewHeaderData) && !isset($dataViewHeaderData['data'])) {
                            foreach ($dataViewHeaderData as $gKey => $param) {
                                if (empty($param['IS_MANDATORY_CRITERIA'])) {
                                ?>
                                    <div class="form-group row dv-criteria-row">
                                        <label class="col-form-label col-lg-3"><?php echo $this->lang->line($param['META_DATA_NAME']) ?></label>
                                        <div class="col-lg-9">
                                            <?php 
                                                $operandVal = '=';
                                                if (isset($this->permissionCriteriaData['metaOperand'])) {
                                                    $operandVal = html_entity_decode($this->permissionCriteriaData['metaOperand'][strtolower($param['META_DATA_CODE'])], ENT_QUOTES);
                                                }

                                                if (isset($this->permissionCriteriaData['metaValues'])) {
                                                    $fillPath = $this->permissionCriteriaData['metaValues'];
                                                } else {
                                                    $fillPath = (isset($this->fillPath) ? $this->fillPath : false);
                                                } 
                                                
                                                echo Mdcommon::dvRenderCriteria($param, Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], $fillPath, '', true));

                                                echo Form::select(
                                                    array(
                                                        'name' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                                        'id' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                                        'class' => 'form-control form-control-sm right-radius-zero float-right hidden',
                                                        'op_value' => 'value',
                                                        'op_text' => 'code',
                                                        'data' => Info::defaultCriteriaCondition($param['META_TYPE_CODE']),
                                                        'text' => 'notext',
                                                        'value' => $operandVal
                                                    )
                                                );                                                    
                                            ?>
                                        </div>                                    
                                    </div>
                            <?php
                                }
                            } 
                            if (issetParam($this->row['IS_SHOW_FILTER_TEMPLATE']) == '1') {
                            ?>
                            <hr class="mt10 mb10 <?php echo $saveCriteriaTemplate ?>"/>
                            <div class="form-group row<?php echo $saveCriteriaTemplate ?>">
                                <label for="criteriaTemplates" class="col-form-label col-lg-3"><?php echo $this->lang->line('criteriaTemplateList') ?></label>
                                <div class="col-lg-9">
                                    <?php
                                    echo Form::select(
                                        array(
                                            'name' => 'criteriaTemplates',
                                            'id' => 'criteriaTemplates',
                                            'text' => $this->lang->line('choose'),
                                            'class' => 'form-control form-control-sm dropdownInput select2 select2-criteria-template-' . $this->metaDataId,
                                            'data' => array(),
                                            'op_value' => 'ID',
                                            'op_text' => 'NAME'
                                        )
                                    );
                                    ?>                                        
                                </div>
                            </div>                                
                            <div class="form-group row<?php echo $saveCriteriaTemplate ?>">
                                <label for="isSaveCriteriaTemplate" class="col-form-label col-lg-3"><?php echo $this->lang->line('criteriaTemplate') ?></label>
                                <div class="col-lg-9">
                                    <input type="checkbox" value="1" name="isSaveCriteriaTemplate" id="isSaveCriteriaTemplate" class="notuniform form-check-input-switchery-<?php echo $this->metaDataId; ?>" data-fouc="" data-switchery="true">
                                </div>
                            </div>
                            <div class="form-group row hidden criteria-template-hidden-<?php echo $this->metaDataId ?>">
                                <label for="criteriaTemplateName" class="col-form-label col-lg-3"><span class="text-danger">*</span> <?php echo $this->lang->line('criteriaTemplateName') ?></label>
                                <div class="col-lg-9">
                                    <input type="text" name="criteriaTemplateName" id="criteriaTemplateName" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('criteriaTemplateName') ?>">
                                </div>
                            </div>
                        <?php
                            }
                        }
                        ?>
                        <div class="clearfix w-100"></div> 
                        <?php 
                        echo Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)); 
                        echo Form::button(
                            array(
                                'class' => 'btn btn-circle blue-madison dataview-default-filter-btn hidden', 
                                'value' => $this->lang->line('do_filter')
                            )
                        );             
                        echo Form::button(
                            array(
                                'class' => 'btn btn-sm btn-circle default dataview-default-filter-reset-btn hidden', 
                                'value' => $this->lang->line('clear_btn')
                            )
                        );    
                        ?>
                    </form>        
                </div>
            <?php              
            } else {
            ?>
            <div class="xs-form" id="dv-search-<?php echo $this->metaDataId; ?>">
                <form class="form-horizontal xs-form p-0" method="post" id="default-criteria-form">
                    <div class="height-dynamic row filter-form-body">
                        <?php
                        if (isset($varDataViewHeaderData['dataGroup']) && !empty($varDataViewHeaderData['dataGroup'])) {
                            foreach ($varDataViewHeaderData['dataGroup']['header'] as $hkey => $headerParam) {
                                ?>
                                <div class="<?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? 'col-md-12' : 'col-md-6' ?> pl0 pr0">
                                    <div class="panel-group accordion" id="accordion3-<?php echo $this->metaDataId; ?>">
                                        <div class="panel panel-default">
                                            <div class="panel-heading">
                                                <h4 class="panel-title"><a class="accordion-toggle accordion-toggle-styled <?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? 'collapsed' : 'expanded' ?>" tabindex="-1" data-toggle="collapse" href="<?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? '#collapse_search_group_'.$hkey : '#collapse_search_group_'.$hkey ?>" aria-expanded="<?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? 'false' : 'true' ?>" style="color: #0080ff; font-weight: bold; font-weight: 600; text-transform: uppercase;"><?php echo $this->lang->line($headerParam) ?></a></h4>
                                            </div>
                                            <div id="collapse_search_group_<?php echo $hkey ?>" class="panel-collapse collapse" aria-expanded="<?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? 'false' : 'true' ?>">
                                                <div class="panel-body">   
                                                    <?php                                
                                                    foreach ($varDataViewHeaderData['dataGroup']['content'][$hkey] as $param) {
                                                        if (empty($param['IS_MANDATORY_CRITERIA'])) {
                                                        ?>
                                                            <div class="<?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? 'col-md-12' : 'col-md-12' ?> pl0 pr0">
                                                                <div class="panel-group accordion" id="accordion4-<?php echo $this->metaDataId; ?>">
                                                                    <div class="panel panel-default">
                                                                        <div class="panel-heading">
                                                                            <h4 class="panel-title"><a class="accordion-toggle accordion-toggle-styled expanded" tabindex="-1" data-toggle="collapse" data-parent="<?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? '#accordion'.$param['META_DATA_CODE'] : '' ?>" href="<?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? '#collapse_3_'.$param['META_DATA_CODE'].'_'.$this->metaDataId : '#collapse_3_'.$param['META_DATA_CODE'].'_'.$this->metaDataId; ?>" aria-expanded="true"><?php echo $this->lang->line($param['META_DATA_NAME']) ?></a></h4>
                                                                        </div>
                                                                        <div id="collapse_3_<?php echo $param['META_DATA_CODE'].'_'.$this->metaDataId; ?>" aria-expanded="true">
                                                                            <div class="<?php echo ($dataViewCriteriaType == 'left') ? 'col-md-4' : 'col-md-2'; ?>  pl0 pr0 dropdown-filter-<?php echo $param['ID'] ?> dv-filter-criteria-condition">
                                                                                <?php 
                                                                                $operandVal = '';
                                                                                if (isset($this->permissionCriteriaData['metaOperand'])) {
                                                                                    $operandVal = html_entity_decode($this->permissionCriteriaData['metaOperand'][strtolower($param['META_DATA_CODE'])], ENT_QUOTES);
                                                                                }
                                                                                echo Form::select(
                                                                                    array(
                                                                                        'name' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                                                                        'id' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                                                                                        'class' => 'form-control form-control-sm right-radius-zero float-right',
                                                                                        'op_value' => 'value',
                                                                                        'op_text' => 'code',
                                                                                        'data' => Info::defaultCriteriaCondition($param['META_TYPE_CODE']),
                                                                                        'text' => 'notext',
                                                                                        'value' => $operandVal
                                                                                    )
                                                                                ); ?>
                                                                            </div>
                                                                            <div class="<?php echo ($dataViewCriteriaType == 'left') ? 'col-md-8 pr0' : 'col'; ?> pl0 <?php echo ($dataViewCriteriaType == 'left') ? '' : 'pr0' ?>">
                                                                                <?php 
                                                                                if (isset($this->permissionCriteriaData['metaValues'])) {
                                                                                    echo Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], $this->permissionCriteriaData['metaValues']); 
                                                                                } else {
                                                                                    echo Mdwebservice::renderParamControl($this->metaDataId, $param, "param[".$param['META_DATA_CODE']."]", $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false)); 
                                                                                }
                                                                                ?>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>    
                                                        <?php
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>                                                        
                            <?php
                            }
                        }
                        if (!empty($dataViewHeaderData) && !isset($dataViewHeaderData['data'])) {
                            foreach ($dataViewHeaderData as $gKey => $param) {
                                if (empty($param['IS_MANDATORY_CRITERIA'])) {
                            ?>
                            <div class="<?php echo ($dataViewCriteriaType == 'left' || $dataViewCriteriaType == 'left static') ? 'col-md-12' : 'col-md-6' ?> mb5">
                                <a class="div-accordionToggler" data-toggler-class="accordion-toggler-<?php echo $this->metaDataId.'_'.$gKey ?>" href="javascript:;" data-toggler-status="open" tabindex="-1"><?php echo $this->lang->line($param['META_DATA_NAME']) ?> <i class="fa fa-angle-up"></i></a>
                                <div class="accordion-toggler-<?php echo $this->metaDataId.'_'.$gKey ?> mb5">
                                    <div class="input-group text-left">
                                    <?php 
                                        $operandVal = '=';
                                        if (isset($this->permissionCriteriaData['metaOperand'])) {
                                            $operandVal = html_entity_decode($this->permissionCriteriaData['metaOperand'][strtolower($param['META_DATA_CODE'])], ENT_QUOTES);
                                        }

                                        if (isset($this->permissionCriteriaData['metaValues'])) {
                                            echo Mdcommon::dataviewRenderCriteriaCondition(
                                                $param,     
                                                Mdwebservice::renderParamControl($this->metaDataId, $param, 
                                                        "param[".$param['META_DATA_CODE']."]", 
                                                        $param['META_DATA_CODE'], 
                                                        $this->permissionCriteriaData['metaValues']),
                                                $operandVal
                                            );
                                        } else {

                                            if (Config::getFromCache('CONFIG_ACCOUNT_SEGMENT')) {
                                                $lowerPath = strtolower($param['META_DATA_CODE']);

                                                if (in_array($lowerPath, Mdgl::$segmentAccountPath)) {
                                            ?>
                                            <div class="input-group input-group-criteria">
                                                <?php
                                                echo Mdcommon::criteriaCondidion(
                                                    $param, 
                                                    Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false))
                                                );
                                                ?>
                                                <span class="input-group-btn">
                                                    <button type="button" class="btn default btn-bordered" tabindex="-1" title="Dimension сонгох" onclick="accountSegmentCriteria(this);"><i class="fa fa-navicon"></i></button>
                                                </span>
                                            </div>
                                            <?php
                                                } else {
                                                    echo Mdcommon::dataviewRenderCriteriaCondition(
                                                        $param,     
                                                        Mdwebservice::renderParamControl($this->metaDataId,
                                                                $param, 
                                                                "param[".$param['META_DATA_CODE']."]", 
                                                                $param['META_DATA_CODE'], 
                                                                (isset($this->fillPath) ? $this->fillPath : false)),
                                                        $operandVal
                                                    );
                                                }
                                            } else {

                                                echo Mdcommon::dataviewRenderCriteriaCondition(
                                                    $param,     
                                                    Mdwebservice::renderParamControl($this->metaDataId,
                                                            $param, 
                                                            "param[".$param['META_DATA_CODE']."]", 
                                                            $param['META_DATA_CODE'], 
                                                            (isset($this->fillPath) ? $this->fillPath : false)),
                                                    $operandVal
                                                );
                                            }
                                        } 
                                        if (issetParam($param['IS_KPI_CRITERIA']) && $param['REF_STRUCTURE_ID']) {
                                            echo (new Mdform)->getKpiTemplatesByRefStrIdByInline($param['REF_STRUCTURE_ID']);
                                        }                                        
                                    ?>
                                    </div>
                                </div>   
                            </div>    
                            <?php
                                }
                            }
                        }
                        ?>
                        <div class="clearfix w-100"></div>   
                    </div>    
                    <div class="col-md-12 text-right filter-right-btn">
                        <?php
                        if (issetParam($this->row['IS_SHOW_FILTER_TEMPLATE']) == '1') {
                            
                            if ($dataViewCriteriaType == 'button') {
                        ?>
                            <div class="form-group d-inline-block mr10 <?php echo $saveCriteriaTemplate ?>">
                                <label for="criteriaTemplates" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplateList'); ?>:</label>
                                <div class="d-inline-block" style="width: 160px">
                                    <?php
                                    echo Form::select(
                                        array(
                                            'name' => 'criteriaTemplates',
                                            'id' => 'criteriaTemplates',
                                            'text' => $this->lang->line('choose'),
                                            'data-field-name' => 'button', 
                                            'class' => 'form-control form-control-sm dropdownInput select2 select2-criteria-template-' . $this->metaDataId,
                                            'data' => array(),
                                            'op_value' => 'ID',
                                            'op_text' => 'NAME'
                                        )
                                    );
                                    ?>                                        
                                </div>
                            </div>                                
                            <div class="form-check form-check-switchery form-check-inline form-check-right mr10 <?php echo $saveCriteriaTemplate ?>">
                                <label for="isSaveCriteriaTemplate" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplate'); ?>:</label>
                                <div class="d-inline-block">
                                    <input type="checkbox" value="1" name="isSaveCriteriaTemplate" id="isSaveCriteriaTemplate" class="notuniform form-check-input-switchery-<?php echo $this->metaDataId; ?>" data-fouc="" data-switchery="true">
                                </div>
                            </div>
                            <div class="form-group d-inline-block mr10 hidden criteria-template-hidden-<?php echo $this->metaDataId ?>">
                                <label for="criteriaTemplateName" class="col-form-label panel-title"><span class="text-danger">*</span> <?php echo $this->lang->line('criteriaTemplateName'); ?>:</label>
                                <div class="d-inline-block">
                                    <input type="text" name="criteriaTemplateName" id="criteriaTemplateName" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('criteriaTemplateName') ?>">
                                </div>
                            </div>
                            <?php
                            } else {
                            ?>
                            <div class="form-group text-left <?php echo $saveCriteriaTemplate ?>">
                                <label for="criteriaTemplates" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplateList'); ?>:</label>
                                <div>
                                    <?php
                                    echo Form::select(
                                        array(
                                            'name' => 'criteriaTemplates',
                                            'id' => 'criteriaTemplates',
                                            'text' => $this->lang->line('choose'),
                                            'data-field-name' => 'left', 
                                            'class' => 'form-control form-control-sm dropdownInput select2 select2-criteria-template-' . $this->metaDataId,
                                            'data' => array(),
                                            'op_value' => 'ID',
                                            'op_text' => 'NAME'
                                        )
                                    );
                                    ?>                                        
                                </div>
                            </div>                                
                            <div class="form-group text-left <?php echo $saveCriteriaTemplate ?>">
                                <label for="isSaveCriteriaTemplate" class="col-form-label panel-title"><?php echo $this->lang->line('criteriaTemplate'); ?>:</label>
                                <div>
                                    <input type="checkbox" value="1" name="isSaveCriteriaTemplate" id="isSaveCriteriaTemplate" class="notuniform form-check-input-switchery-<?php echo $this->metaDataId; ?>" data-fouc="" data-switchery="true">
                                </div>
                            </div>
                            <div class="form-group text-left hidden criteria-template-hidden-<?php echo $this->metaDataId ?>">
                                <label for="criteriaTemplateName" class="col-form-label panel-title"><span class="text-danger">*</span> <?php echo $this->lang->line('criteriaTemplateName'); ?>:</label>
                                <div>
                                    <input type="text" name="criteriaTemplateName" id="criteriaTemplateName" class="form-control form-control-sm stringInit" placeholder="<?php echo $this->lang->line('criteriaTemplateName') ?>">
                                </div>
                            </div>
                            <div class="w-100 mb20"></div>
                        <?php 
                            }
                        }
                        echo Form::button(
                            array(
                                'class' => 'btn btn-sm btn-circle blue-madison dataview-default-filter-btn', 
                                'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('do_filter')
                            )
                        ); 
                        echo Form::button(
                            array(
                                'class' => 'btn btn-sm btn-circle default dataview-default-filter-reset-btn', 
                                'value' => $this->lang->line('clear_btn')
                            )
                        ); 
                        ?>
                    </div> 
                    <?php echo Form::hidden(array('name' => 'inputMetaDataId', 'value' => $this->metaDataId)); ?>
                </form>        
            </div>
        <?php  
            } 
        }
    }
    ?>
    <div class="clearfix w-100"></div>

    <style type="text/css">
        .accordionToggler {
            font-weight: 600; 
            font-size: 12px; 
            text-decoration: none; 
            color: #000;
        }
        .accordionToggler:hover {
            color:#30a2dd;
            text-decoration: none; 
        }
        #dv-search-<?php echo $this->metaDataId; ?> .col-md-1 {
            width: 12.33333333%;
        }            
        #dv-search-<?php echo $this->metaDataId; ?> .dv-criteria-row .radio-list label {
            display: block;
        }            
    </style>
<?php
    }
} 
?>