<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'processDtlTransfer-form', 'method' => 'post')); ?>
<div class="tabbable-line">
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a href="#tab_15_1" class="nav-link active" data-toggle="tab">Main config</a>
        </li>
        <li class="nav-item">
            <a href="#tab_15_2" data-toggle="tab" class="nav-link">Basket config</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane active" id="tab_15_1">
            <div class="col-md-12 pl0 pr0">
                <div class="table-scrollable" style="margin-top: 0 !important">
                    <table class="table process-dtl-transfer-tbl">
                        <thead>
                            <tr>
                                <th style="width: 400px">Get Process</th>
                                <th>View field path</th>
                                <th>Input param path</th>
                                <th style="width: 70px;">Set param path</th>
                                <th style="width: 15px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($this->paramsConfigs) {
                            foreach ($this->paramsConfigs['groupProcessDtlTransferGetMetaId'] as $k => $row) {
                                foreach ($row as $n => $rowChild) {
                                    
                                    $getMetaId = $this->paramsConfigs['groupProcessDtlTransferGetMetaId'][$k][$n];
                                    $metaDataCode = $metaDataName = '';
                                    
                                    if ($getMetaId != '') {
                                        $metaRow = (new Mdmetadata())->getMetaData($getMetaId);
                                        $metaDataCode = $metaRow['META_DATA_CODE'];
                                        $metaDataName = $metaRow['META_DATA_NAME'];
                                        $array = array(
                                            'name' => 'groupProcessDtlTransferParamPath',
                                            'class' => 'form-control form-control-sm select2', 
                                            'data' => (new Mdmeta())->getMetaProcessParamMeta($getMetaId),
                                            'op_value' => 'PARAM_REAL_PATH', 
                                            'op_text' => 'PARAM_REAL_PATH| |-| |LABEL_NAME',
                                            'value' => $this->paramsConfigs['groupProcessDtlTransferParamPath'][$k][$n],
                                            'style' => 'width: 220px;'
                                        );
                                    } else {
                                        $array = array(
                                            'name' => 'groupProcessDtlTransferParamPath',
                                            'class' => 'form-control form-control-sm select2', 
                                            'data' => $this->defaultProcessInputParam,
                                            'op_value' => 'PARAM_REAL_PATH', 
                                            'op_text' => 'PARAM_REAL_PATH| |-| |LABEL_NAME',
                                            'value' => $this->paramsConfigs['groupProcessDtlTransferParamPath'][$k][$n], 
                                            'style' => 'width: 220px;'
                                        );
                                    }
                        ?>
                            <tr>
                                <td>
                                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                                        <div class="input-group double-between-input">
                                            <input id="groupProcessDtlTransferGetMetaId" name="groupProcessDtlTransferGetMetaId" type="hidden" value="<?php echo $getMetaId; ?>">
                                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" value="<?php echo $metaDataCode; ?>" title="<?php echo $metaDataCode; ?>" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">
                                            <span class="input-group-btn">
                                                <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                                            </span>     
                                            <span class="input-group-btn not-group-btn">
                                                <div class="btn-group pf-meta-manage-dropdown">
                                                    <button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>
                                                    <ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>
                                                </div>
                                            </span>
                                            <span class="input-group-btn flex-col-group-btn">
                                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo $metaDataName; ?>" title="<?php echo $metaDataName; ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">      
                                            </span>     
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <?php 
                                    echo Form::select(
                                        array(
                                            'name' => 'groupProcessDtlTransferViewPath',
                                            'class' => 'form-control form-control-sm select2', 
                                            'data' => $this->groupChildDatas,
                                            'op_value' => 'META_DATA_CODE',
                                            'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME',
                                            'value' => $this->paramsConfigs['groupProcessDtlTransferViewPath'][$k][$n], 
                                            'style' => 'width: 220px;'
                                        )
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?php echo Form::select($array); ?>
                                </td>
                                <td>
                                    <?php 
                                    echo Form::text(
                                        array(
                                            'name' => 'groupProcessDtlTransferDefaultValue',
                                            'class' => 'form-control form-control-sm', 
                                            'value' => $this->paramsConfigs['groupProcessDtlTransferDefaultValue'][$k][$n], 
                                            'style' => 'width: 80px;'
                                        )
                                    );
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" class="btn red btn-xs" onclick="groupProcessDtlTransferRemoveRow(this);"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php
                                }
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
        </div>
        <div class="tab-pane" id="tab_15_2">
            <div class="col-md-12 pl0 pr0">
                <div class="table-scrollable" style="margin-top: 0 !important">
                    <table class="table process-transfer-basket-configs-tbl">
                        <thead>
                            <tr>
                                <th style="width: 350px;">View field path</th>
                                <th style="width: 350px;">Input param path</th>
                                <th style="width: 70px;">Default value</th>
                                <th style="width: 15px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($this->paramsBasketConfigs) {
                            foreach ($this->paramsBasketConfigs['groupProcessDtlBasketTransferViewPath'] as $k => $row) {
                                foreach ($row as $n => $rowChild) {
                                    
                        ?>
                            <tr>
                                <td>
                                    <?php 
                                    echo Form::select(
                                        array(
                                            'name' => 'groupProcessDtlBasketTransferViewPath',
                                            'class' => 'form-control form-control-sm select2', 
                                            'data' => $this->groupChildDatas,
                                            'op_value' => 'META_DATA_CODE',
                                            'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME',
                                            'required' => 'required',
                                            'value' => $this->paramsBasketConfigs['groupProcessDtlBasketTransferViewPath'][$k][$n], 
                                            'style' => 'width: 300px;'
                                        )
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    echo Form::text(
                                        array(
                                            'name' => 'groupProcessDtlBasketTransferParamPath',
                                            'class' => 'form-control form-control-sm', 
                                            'value' => $this->paramsBasketConfigs['groupProcessDtlBasketTransferParamPath'][$k][$n], 
                                        )
                                    );
                                    ?>
                                </td>
                                <td>
                                    <?php 
                                    echo Form::text(
                                        array(
                                            'name' => 'groupProcessDtlBasketTransferDefaultValue',
                                            'class' => 'form-control form-control-sm', 
                                            'value' => $this->paramsBasketConfigs['groupProcessDtlBasketTransferDefaultValue'][$k][$n], 
                                            'style' => 'width: 80px;'
                                        )
                                    );
                                    ?>
                                </td>
                                <td class="text-center">
                                    <a href="javascript:;" class="btn red btn-xs" onclick="groupProcessDtlBasketTransferRemoveRow(this);"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php
                                }
                            }
                        }
                        ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5">
                                    <a href="javascript:;" class="btn green btn-xs" onclick="groupProcessDtlBasketTransferAddRow(this);">
                                        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                                    </a>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>       
            </div>
        </div>
    </div>
</div>
<?php echo Form::close(); ?>

<script type="text/javascript">
    $(function(){
        $('table.process-dtl-transfer-tbl').on('change', 'input#groupProcessDtlTransferGetMetaId', function(){
            var $this = $(this);
            var $val = $this.val();
            if ($val !== '') {
                getProcessInputParamDropdownFill($val, $this);
            } else {
                getProcessInputParamDropdownFill('', $this);
            }
        });
    });
    
    function groupProcessDtlTransferAddRow(elem) {
        var $this = $(elem);
        var $parentTbl = $this.closest('table');
        var $newRow = $('<tr>'+
                '<td><div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">'+
                    '<div class="input-group double-between-input">'+
                        '<input id="groupProcessDtlTransferGetMetaId" name="groupProcessDtlTransferGetMetaId" type="hidden">'+
                        '<input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text">'+
                        '<span class="input-group-btn">'+
                            '<button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid(\'single\', \'\', this);"><i class="fa fa-search"></i></button>'+
                        '</span>'+     
                        '<span class="input-group-btn not-group-btn">'+  
                            '<div class="btn-group pf-meta-manage-dropdown">'+  
                                '<button class="btn grey-cascade btn-bordered form-control-sm mr0 dropdown-toggle" type="button" data-toggle="dropdown"></button>'+  
                                '<ul class="dropdown-menu dropdown-menu-right" style="min-width: 126px;" role="menu"></ul>'+  
                            '</div>'+  
                        '</span>'+  
                        '<span class="input-group-btn flex-col-group-btn">'+
                            '<input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">'+      
                        '</span>'+     
                    '</div>'+
                '</div></td>'+
                '<td><?php echo Form::select(array('name'=>'groupProcessDtlTransferViewPath','class'=>'form-control form-control-sm select2','data'=>$this->groupChildDatas,'op_value'=>'META_DATA_CODE','op_text'=>'META_DATA_CODE| |-| |META_DATA_NAME','required'=>'required','style' => 'width: 220px;')); ?></td>'+
                '<td><?php echo Form::select(array('name'=>'groupProcessDtlTransferParamPath','class'=>'form-control form-control-sm select2','data'=>$this->defaultProcessInputParam,'op_value'=>'PARAM_REAL_PATH','op_text'=>'PARAM_REAL_PATH| |-| |LABEL_NAME','style' => 'width: 220px;')); ?></td>'+
                '<td><?php echo Form::text(array('name' => 'groupProcessDtlTransferDefaultValue','class' => 'form-control form-control-sm','style'=>'width: 80px;')); ?></td>'+
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
        var $select = $row.find("select[name*='groupProcessDtlTransferParamPath']");
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