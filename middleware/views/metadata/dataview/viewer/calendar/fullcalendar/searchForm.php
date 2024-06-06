<div class="sidebar-sticky sidebar sidebar-light sidebar-secondary sidebar-component sidebar-expand-md sidebar-calendar <?php echo 'dv-searchcolor-'.issetParam($this->row['COLOR_SCHEMA']).(($this->callerType == 'package') ? ' d-none' : ''); ?>">
    <form method="post" id="calendar-searchform-<?php echo $this->metaDataId; ?>">
        <div class="dvecommerce pr15">
            <?php
            if ($this->dataViewMandatoryHeaderData) {
                $this->dataViewHeaderData['data'] = array_merge($this->dataViewMandatoryHeaderData, $this->dataViewHeaderData['data']);
            }
            
            $data = $this->dataViewHeaderData['data'];
            
            foreach ($data as $param) {

                if ($param['LOOKUP_META_DATA_ID'] != '' && $param['LOOKUP_TYPE'] == 'combo' && $param['CHOOSE_TYPE'] != 'singlealways') {
                    $param['CHOOSE_TYPE'] = 'multi';
                }
                
                if (isset($this->uriParams) && $this->uriParams) {
                    $param['DEFAULT_VALUE'] = '';
                }
            ?>
            <div class="form-group mandatory-criteria-param-<?php echo $param['IS_MANDATORY_CRITERIA']; ?>">
                <label><?php echo $this->lang->line($param['META_DATA_NAME']); ?>:</label>
                <?php
                echo Mdcommon::dataviewRenderCriteriaCondition(
                    $param,     
                    Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], false, '', true),
                    '=',
                    'top'
                );
                ?>
            </div>
            <?php
            }
            ?>

        </div>
        <div class="row mt-4">
            <div class="col-6">
                <button type="button" class="btn btn-danger btn-block dataview-default-filter-reset-btn"><i class="icon-reset mr-2"></i>Цэвэрлэх</button>
            </div>
            <div class="col-6">
                <button type="button" class="btn btn-primary btn-block dataview-default-filter-btn"><i class="icon-search4 mr-2"></i>Шүүх</button>
            </div>
        </div>
    </form>
</div>   