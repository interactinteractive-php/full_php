<form id="kpiIndicatorBpRunForm">
    <div class="col-md-12">
        <div class="form-group row">
            <?php 
            echo Form::label(
                array(
                    'text' => $this->lang->line('REP_FISCAL_PERIOD'),  
                    'class' => 'col-form-label col-md-3 text-right pr0', 
                    'required' => 'required'
                )
            ); 
            ?>
            <div class="col-md-9">
                <?php 
                $controlConfig = array(
                    'GROUP_PARAM_CONFIG_TOTAL' => '0', 
                    'GROUP_CONFIG_PARAM_PATH' => NULL, 
                    'GROUP_CONFIG_LOOKUP_PATH' => NULL, 
                    'GROUP_CONFIG_PARAM_PATH_GROUP' => NULL, 
                    'GROUP_CONFIG_FIELD_PATH_GROUP' => NULL, 
                    'GROUP_CONFIG_FIELD_PATH' => NULL, 
                    'GROUP_CONFIG_GROUP_PATH' => NULL, 
                    'META_DATA_CODE' => 'fiscalPeriodId', 
                    'LOWER_PARAM_NAME' => 'fiscalperiodid', 
                    'PARAM_REAL_PATH' => 'fiscalPeriodId', 
                    'NODOT_PARAM_REAL_PATH' => 'fiscalPeriodId', 
                    'META_DATA_NAME' => $this->lang->line('REP_FISCAL_PERIOD'), 
                    'LABEL_NAME' => $this->lang->line('REP_FISCAL_PERIOD'), 
                    'LOOKUP_META_DATA_ID' => $this->fiscalPeriodDvId, 
                    'LOOKUP_TYPE' => 'popup', 
                    'CHOOSE_TYPE' => 'single', 
                    'ATTRIBUTE_ID_COLUMN' => NULL, 
                    'ATTRIBUTE_CODE_COLUMN' => NULL, 
                    'ATTRIBUTE_NAME_COLUMN' => NULL, 
                    'IS_SHOW' => 1, 
                    'IS_REQUIRED' => 1, 
                    'DEFAULT_VALUE' => NULL, 
                    'DISPLAY_FIELD' => NULL, 
                    'VALUE_FIELD' => NULL, 
                    'ID' => '1485492847774873', 
                    'PARENT_ID' => NULL, 
                    'META_TYPE_CODE' => 'long', 
                    'TAB_NAME' => null, 
                    'SIDEBAR_NAME' => null, 
                    'FEATURE_NUM' => null, 
                    'IS_SAVE' => null, 
                    'FILE_EXTENSION' => null, 
                    'PATTERN_TEXT' => null, 
                    'PATTERN_NAME' => null, 
                    'GLOBE_MESSAGE' => null, 
                    'IS_MASK' => null, 
                    'COLUMN_WIDTH' => null, 
                    'MAX_VALUE' => null, 
                    'MIN_VALUE' => null, 
                    'IS_REFRESH' => '0', 
                    'FRACTION_RANGE' => null, 
                    'PLACEHOLDER_NAME' => null
                );
                
                echo $metaControllers = Mdwebservice::renderParamControl(Mdgl::$glBookDtlGroupProcessId, $controlConfig, 'param['.$controlConfig['PARAM_REAL_PATH'].']', '', false); 
                ?> 
            </div>
        </div>
    </div>      
</form> 