<?php if ($this->dataViewHeaderData) { 
    $colCount = Arr::get($this->getAddonSettings, 'criteriaSplitColumnCount');
?>
    <div class="col-md-12 xs-form changeDashboardFilterOperator-<?php echo $this->metaDataId ?>" style="max-width: <?php echo $colCount ? '100%' : '880px'; ?>">
        <div class="row">
            <?php
            $colCount = $colCount ? $colCount : 1;
            $colMd = floor(12 / $colCount);
            foreach ($this->dataViewHeaderData as $k => $param) {
            ?>
                <div class="mt10 col-md-<?php echo $colMd; ?>">
                    <div class="form-group row fom-row">
                        <?php echo Form::label(array('text' => Lang::line($param['META_DATA_NAME']), 'for' => 'param[' . $param['META_DATA_CODE'] . ']', 'class' => 'col-form-label col-md-4', 'style' => 'text-align: right;')); ?>
                        <div class="col-md-1 pl0 pr0 mb5 d-none"> 
                            <?php 
                            echo Form::select(
                                array(
                                    'name' => 'criteriaOperator['.$param['META_DATA_CODE'].']',
                                    'id' => 'criteriaOperator['.$param['META_DATA_CODE'].']',
                                    'class' => 'form-control form-control-sm',
                                    'data' => Info::criteriaCondition(), 
                                    'op_value' => 'value',
                                    'op_text' => 'code',
                                    'text' => '',
                                    'value' => '=',
                                    'onchange' => 'changeDashboardFilterOperator(this, \''.$param['META_DATA_CODE'].'\', \''.$param['META_TYPE_CODE'].'\', '.$k.')'
                                )
                            );
                            ?>
                        </div>
                        <div class="col-md-6 no-padding">   
                            <div id="dashboard-filter-default-input-<?php echo $param['ID']; ?>">
                                <?php
                                echo Mdwebservice::renderParamControl($this->metaDataId, $param, 'param[' . $param['META_DATA_CODE'] . ']', $param['META_DATA_CODE'], (isset($this->fillPath) ? $this->fillPath : false));
                                ?>
                            </div>
                            <div id="dashboard-filter-custom-input-<?php echo $param['ID']; ?>" class="hidden"></div>
                        </div>
                        <?php
                        if (isset($this->isFilterButton) && $this->isFilterButton && $param === end($this->dataViewHeaderData)) {
                        ?>
                        <div class="col-md-1 no-padding">
                            <button type="button" class="btn btn-sm btn-circle blue-madison chart-filter-btn"><?php echo $this->lang->line('do_filter'); ?></button>
                        </div>
                        <?php
                        }
                        ?>
                    </div>    
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="clearfix w-100"></div>

  <script type="text/javascript">
      $(function () {
         Core.init($('.dashboardFilterOperator-<?php echo $this->metaDataId ?>'));
      });
      function changeDashboardFilterOperator(currenctObject, metaDataCode, metaType, index) {
          var currentVal = $(currenctObject).val();
          if(currentVal === 'BETWEEN') {
              var metaType = '';
              if(metaType.toLowerCase() === 'datetime') {
                  metaType = 'datetimeInit';
              }else if(metaType.toLowerCase() === 'number'){
                  metaType = 'numberInit';
              }else{
                  metaType = 'stringInit';
              }
              var html = '<input type="text" name="param['+metaDataCode+'][]" class="form-control form-control-sm '+metaType+'" /> - <input type="text" name="param['+metaDataCode+'][]" class="form-control form-control-sm stringInit" />';
              $('#dashboard-filter-default-input-<?php echo $param['ID']; ?>').addClass('hidden');
              $('#dashboard-filter-custom-input-<?php echo $param['ID']; ?>').html(html).removeClass('hidden');
          }else{
              $('#dashboard-filter-custom-input-<?php echo $param['ID']; ?>').addClass('hidden');
              $('#dashboard-filter-default-input-<?php echo $param['ID']; ?>').removeClass('hidden');            
          }
      }
  </script>
<?php } ?>