<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'processDtlRowTransfer-form', 'method' => 'post')); ?>
    <div class="col-md-12 pl0 pr0">
        <div class="table-scrollable" style="margin-top: 0 !important">
            <table class="table process-dtl-row-transfer-tbl">
                <thead>
                    <tr>
                        <th>Dataview path</th>
                        <th>Process path</th>
                        <th style="width: 15px;"></th>
                    </tr>
                </thead>
                <tbody>
                <?php
                if ($this->paramsConfigs) {
                    $defaultProcessInputParamLower = array();
                    foreach ($this->defaultProcessInputParam as $lower) {
                        array_push($defaultProcessInputParamLower, array_map('strtolower', $lower));
                    }

                    $defaultProcessInputParamLower2 = array();
                    foreach ($this->groupChildDatas as $lower) {
                        array_push($defaultProcessInputParamLower2, array_map('strtolower', $lower));
                    }

                    foreach ($this->paramsConfigs['groupProcessDtlTransferDataViewPath'][$this->params['processMetaDataId']] as $k => $row) {
                        $array = array(
                            'name' => 'groupProcessDtlTransferProcessParamPath',
                            'class' => 'form-control form-control-sm select2', 
                            'data' => $defaultProcessInputParamLower,
                            'op_value' => 'PARAM_REAL_PATH', 
                            'op_text' => 'PARAM_REAL_PATH| |-| |LABEL_NAME',
                            'value' => $this->paramsConfigs['groupProcessDtlTransferProcessParamPath'][$this->params['processMetaDataId']][$k], 
                            'style' => 'width: 300px;'
                        );
                        ?>
                            <tr>
                                <td>
                                    <?php 
                                    echo Form::select(
                                        array(
                                            'name' => 'groupProcessDtlTransferDataViewPath',
                                            'class' => 'form-control form-control-sm select2', 
                                            'data' => $defaultProcessInputParamLower2,
                                            'op_value' => 'META_DATA_CODE',
                                            'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME',
                                            'required' => 'required',
                                            'value' => $this->paramsConfigs['groupProcessDtlTransferDataViewPath'][$this->params['processMetaDataId']][$k], 
                                            'style' => 'width: 300px;'
                                        )
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?php echo Form::select($array); ?>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" class="btn red btn-xs" onclick="groupProcessDtlTransferRemoveRow(this);"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php
                    }
                }
                ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5">
                            <a href="javascript:;" class="btn green btn-xs" onclick="groupProcessDtlTransferAddRow(this);">
                                <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                            </a>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>       
    </div>
<?php echo Form::close(); ?>

<script type="text/javascript">
    $(function(){
    });
    
    function groupProcessDtlTransferAddRow(elem) {
        var $this = $(elem);
        var $parentTbl = $this.closest('table');
        var $newRow = $('<tr>'+
                '<td><?php echo Form::select(array('name'=>'groupProcessDtlTransferDataViewPath','class'=>'form-control form-control-sm select2','data'=>$this->groupChildDatas,'op_value'=>'META_DATA_CODE','op_text'=>'META_DATA_CODE| |-| |META_DATA_NAME','required'=>'required','style' => 'width: 300px;')); ?></td>'+
                '<td><?php echo Form::select(array('name'=>'groupProcessDtlTransferProcessParamPath','class'=>'form-control form-control-sm select2','data'=>$this->defaultProcessInputParam,'op_value'=>'PARAM_REAL_PATH','op_text'=>'PARAM_REAL_PATH| |-| |LABEL_NAME','style' => 'width: 300px;')); ?></td>'+
                '<td class="text-center"><a href="javascript:;" class="btn red btn-xs" onclick="groupProcessDtlTransferRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
                '</tr>');

        $parentTbl.find('tbody').append($newRow);
        Core.initSelect2($parentTbl.find('tbody > tr:last'));
    }
    
    function groupProcessDtlBasketTransferAddRow(elem) {
        var $this = $(elem);
        var $parentTbl = $this.closest('table');
        var $newRow = $('<tr>'+
            '<td><?php echo Form::select(array('name'=>'groupProcessDtlBasketTransferViewPath','class'=>'form-control form-control-sm select2','data'=>$this->groupChildDatas,'op_value'=>'META_DATA_CODE','op_text'=>'META_DATA_CODE| |-| |META_DATA_NAME','required'=>'required','style' => 'width: 300px;')); ?></td>'+
            '<td><?php echo Form::text(array('name' => 'groupProcessDtlBasketTransferParamPath','class' => 'form-control form-control-sm')); ?></td>'+
            '<td><?php echo Form::text(array('name' => 'groupProcessDtlBasketTransferDefaultValue','class' => 'form-control form-control-sm','style'=>'width: 80px;')); ?></td>'+
            '<td class="text-center"><a href="javascript:;" class="btn red btn-xs" onclick="groupProcessDtlTransferRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
        '</tr>');
        $parentTbl.find('tbody').append($newRow);
        Core.initSelect2($parentTbl.find('tbody > tr:last'));
    }
    
    function groupProcessDtlTransferRemoveRow(elem) {
        var $parentRow = $(elem).closest('tr');
        $parentRow.remove();
    }
    
    function getProcessInputParamDropdownFill(getMetaDataId, elem) {
        var $row = $(elem).closest('tr');
        var $select = $row.find("select[name*='groupProcessDtlTransferProcessParamPath']");
        if (getMetaDataId !== '') {
            $.ajax({
                type: 'post',
                url: 'mdmeta/getMetaProcessParamMetaByPostJson',
                data: {processMetaDataId: getMetaDataId},
                dataType: "json",
                success: function(data) {
                    $("option:gt(0)", $select).remove();
                    $.each(data, function(){
                        $select.append($("<option />").val(this.PARAM_REAL_PATH).text(this.PARAM_REAL_PATH+' - '+this.META_DATA_NAME));
                    }); 
                    Core.initSelect2($row);
                    $select.trigger('change');
                },
                error: function(){alert("Error");}
            });
        } else {
            $("option:gt(0)", $select).remove();
            $select.trigger('change');
        }
    }
    
    function groupProcessDtlBasketTransferRemoveRow(elem) {
        var $parentRow = $(elem).closest('tr');
        $parentRow.remove();
    }
</script>