<form method="post" class="dv-paneltype-filter-form" autocomplete="off">
    <?php
    if ($this->filterParams) {
        foreach ($this->filterParams as $param) {
    ?>
    <div class="form-group">
        <label><?php echo $this->lang->line($param['META_DATA_NAME']); ?></label>
        <?php
        echo Mdwebservice::renderParamControl($this->metaDataId, $param, 'param['.$param['META_DATA_CODE'].']', $param['META_DATA_CODE'], null , '', true);
        echo Form::select(
            array(
                'name' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                'id' => 'criteriaCondition['. $param['META_DATA_CODE'] .']',
                'class' => 'hidden',
                'op_value' => 'value',
                'op_text' => 'code',
                'data' => Info::defaultCriteriaCondition($param['META_TYPE_CODE']),
                'text' => 'notext', 
                'value' => ($param['DEFAULT_OPERATOR'] ? $param['DEFAULT_OPERATOR'] : ($param['META_TYPE_CODE'] == 'string' ? 'like' : ''))
            )
        ); 
        ?>
    </div>
    <?php
        }
    ?>
    <button type="button" class="btn bg-blue btn-block mt-3 font-weight-bold text-uppercase dv-paneltype-filter-btn">Шүүх</button>
    <?php
    }
    ?>
</form>