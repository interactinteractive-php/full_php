<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'processDtlTransferAutoMap-form', 'method' => 'post')); ?>
<div class="table-scrollable">
    <table class="table process-dtl-transfer-automap-tbl">
        <thead>
            <tr>
                <th style="width: 60px;">Auto map</th>
                <th style="width: 60px;">Auto map src</th>
                <th style=""><?php echo $this->lang->line('meta_auto_map_src_path'); ?></th>
                <th style=""><?php echo $this->lang->line('meta_auto_map_table_name'); ?></th>
                <th>On delete</th>
                <th>On update</th>
                <th style="width: 180px">Delete process</th>
                <th style="min-width: 300px">List meta</th>
                <th>Src pattern</th>
                <th>Trg pattern</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if (empty($this->paramsConfigs)) {
                $this->paramsConfigs['groupProcessDtlTransferAutoMapValue'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferAutoMapSrcValue'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferAutoMapOnDeleteValue'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferAutoMapSrcPath'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferAutoMapTableName'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferAutoMapOnUpdateValue'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferDeleteMetaId'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferListMetaId'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferPattern'][$this->params['processMetaDataId']][0] = '';
                $this->paramsConfigs['groupProcessDtlTransferTrgPattern'][$this->params['processMetaDataId']][0] = '';
            }

            $staticDatas = array(
                array(
                    'TEXT' => 'No Action',
                    'VALUE' => 'noaction'
                ),
                array(
                    'TEXT' => 'Cascade',
                    'VALUE' => 'cascade'
                ),
                array(
                    'TEXT' => 'Restrict',
                    'VALUE' => 'restrict'
                )
            ); 
            ?>
            <tr>
                <td class="text-center">
                    <?php 
                    echo Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlTransferAutoMapValue',
                            'class' => 'form-control form-control-sm groupProcessDtlTransferAutoMapValue', 
                            'value' => '1',
                            'saved_val' => empty($this->paramsConfigs['groupProcessDtlTransferAutoMapValue'][$this->params['processMetaDataId']][0]) ? '0' : '1'
                        )
                    );
                    ?>
                </td>
                <td class="text-center">
                    <?php 
                    echo Form::checkbox(
                        array(
                            'name' => 'groupProcessDtlTransferAutoMapSrcValue',
                            'class' => 'form-control form-control-sm groupProcessDtlTransferAutoMapSrcValue', 
                            'value' => '1',
                            'saved_val' => empty($this->paramsConfigs['groupProcessDtlTransferAutoMapSrcValue'][$this->params['processMetaDataId']][0]) ? '0' : '1'
                        )
                    );
                    ?>
                </td>
                <td>
                    <?php 
                    echo Form::select(
                        array(
                            'name' => 'groupProcessDtlTransferAutoMapSrcPath',
                            'class' => 'form-control form-control-sm select2 groupProcessDtlTransferAutoMapSrcPath', 
                            'data' => $this->groupChildDatas,
                            'op_value' => 'META_DATA_CODE',
                            'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME',
                            'value' => $this->paramsConfigs['groupProcessDtlTransferAutoMapSrcPath'][$this->params['processMetaDataId']][0],
                            'style' => 'width: 200px;'
                        )
                    );
                    ?>
                </td>                    
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'groupProcessDtlTransferAutoMapTableName',
                            'class' => 'form-control form-control-sm groupProcessDtlTransferAutoMapTableName',
                            'value' => $this->paramsConfigs['groupProcessDtlTransferAutoMapTableName'][$this->params['processMetaDataId']][0],
                            'style' => 'width: 150px;'
                        )
                    );
                    ?>
                </td>                    
                <td>
                    <?php 
                    $onDelete = array(
                        'name' => 'groupProcessDtlTransferAutoMapOnDeleteValue',
                        'class' => 'form-control form-control-sm select2 groupProcessDtlTransferAutoMapOnDeleteValue', 
                        'data' => $staticDatas,
                        'op_value' => 'VALUE',
                        'op_text' => 'TEXT',
                        'text' => 'notext',
                        'value' => $this->paramsConfigs['groupProcessDtlTransferAutoMapOnDeleteValue'][$this->params['processMetaDataId']][0], 
                        'style' => 'width: 150px;'
                    );
                    $onUpdate = array(
                        'name' => 'groupProcessDtlTransferAutoMapOnUpdateValue',
                        'class' => 'form-control form-control-sm select2 groupProcessDtlTransferAutoMapOnUpdateValue', 
                        'data' => $staticDatas,
                        'op_value' => 'VALUE',
                        'op_text' => 'TEXT',
                        'text' => 'notext',
                        'value' => $this->paramsConfigs['groupProcessDtlTransferAutoMapOnUpdateValue'][$this->params['processMetaDataId']][0], 
                        'style' => 'width: 150px;'
                    );

                    echo Form::select($onDelete);
                    ?>
                </td>
                <td>
                    <?php echo Form::select($onUpdate); ?>
                </td> 
                <td>
                    <?php
                    $metaDataCode = $metaDataName = $listMetaDataCode = $listMetaDataName = '';
                    $deleteGetMetaId = $this->paramsConfigs['groupProcessDtlTransferDeleteMetaId'][$this->params['processMetaDataId']][0];

                    if (!empty($deleteGetMetaId)) {
                        $metaRow = (new Mdmetadata())->getMetaData($deleteGetMetaId);
                        $metaDataCode = $metaRow['META_DATA_CODE'];
                        $metaDataName = $metaRow['META_DATA_NAME'];                        
                    }
                    
                    $listMetaId = $this->paramsConfigs['groupProcessDtlTransferListMetaId'][$this->params['processMetaDataId']][0];
                    
                    if (!empty($listMetaId)) {
                        $metaRow = (new Mdmetadata())->getMetaData($listMetaId);
                        $listMetaDataCode = $metaRow['META_DATA_CODE'];
                        $listMetaDataName = $metaRow['META_DATA_NAME'];                        
                    }
                    ?>
                    <div class="input-group">
                        <input type="hidden" id="deleteMetaDataId_valueField" name="groupProcessDtlTransferDeleteMetaId" value="<?php echo $deleteGetMetaId; ?>">
                        <input type="text" value="<?php echo $metaDataCode; ?>" id="deleteMetaDataId_displayField" class="form-control form-control-sm delete-metadata-transfer">
                        <span class="input-group-btn">
                            <button type="button" class="btn blue form-control-sm mr0" onclick="commonMetaDataGrid('single', 'metaObject', 'autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>', 'deleteMetaSelectProcessDtlTransfer', this);"><i class="fa fa-search"></i></button>
                        </span>
                    </div><div id="deleteMetaDataId_nameField"><?php echo $metaDataName; ?></div>
                </td>
                <td>
                    <div class="meta-autocomplete-wrap" data-params="autoSearch=1&grouptype=tablestructure&metaTypeId=<?php echo Mdmetadata::$metaGroupMetaTypeId; ?>">
                        <div class="input-group double-between-input">
                            <?php echo Form::hidden(array('name' => 'groupProcessDtlTransferListMetaId', 'value' => $listMetaId)); ?>
                            <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" value="<?php echo $listMetaDataCode; ?>" type="text">
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
                                <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" value="<?php echo $listMetaDataCode; ?>" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text">
                            </span>
                        </div>
                    </div>
                </td> 
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'groupProcessDtlTransferPattern',
                            'class' => 'form-control form-control-sm groupProcessDtlTransferPattern',
                            'value' => $this->paramsConfigs['groupProcessDtlTransferPattern'][$this->params['processMetaDataId']][0],
                            'style' => 'width: 200px;'
                        )
                    );
                    ?>
                </td> 
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'groupProcessDtlTransferTrgPattern',
                            'class' => 'form-control form-control-sm groupProcessDtlTransferTrgPattern',
                            'value' => $this->paramsConfigs['groupProcessDtlTransferTrgPattern'][$this->params['processMetaDataId']][0],
                            'style' => 'width: 200px;'
                        )
                    );
                    ?>
                </td> 
            </tr>
        </tbody>
    </table>
</div>       
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function(){
    Core.initSelect2($("table.process-dtl-transfer-automap-tbl"));
    
    $("table.process-dtl-transfer-automap-tbl").on("change", "input.groupProcessDtlTransferAutoMapValue", function(e){
        var $this = $(this), $thisTR = $this.closest('tr');
        
        if ($this.is(':checked')) {
            $thisTR.find('.groupProcessDtlTransferAutoMapSrcValue, .groupProcessDtlTransferAutoMapTableName, .groupProcessDtlTransferPattern, .groupProcessDtlTransferTrgPattern, .md-code-autocomplete, .md-name-autocomplete').prop("readonly", false);
            $thisTR.find('.groupProcessDtlTransferAutoMapSrcValue').parent().parent().removeClass("disabled");            
            $thisTR.find('.groupProcessDtlTransferAutoMapOnDeleteValue, .groupProcessDtlTransferAutoMapOnUpdateValue, .groupProcessDtlTransferAutoMapSrcPath').select2("readonly", false);
            $thisTR.find('#deleteMetaDataId_displayField').prop("readonly", false).closest('td').find('button').prop('disabled', false);
            $thisTR.find('.btn[onclick]').prop('disabled', false);
        } else {
            $thisTR.find('.groupProcessDtlTransferAutoMapSrcValue, .groupProcessDtlTransferAutoMapTableName, .groupProcessDtlTransferPattern, .groupProcessDtlTransferTrgPattern, .md-code-autocomplete, .md-name-autocomplete').prop("readonly", true);
            $thisTR.find('.groupProcessDtlTransferAutoMapSrcValue').parent().parent().addClass("disabled");
            $thisTR.find('.groupProcessDtlTransferAutoMapOnDeleteValue, .groupProcessDtlTransferAutoMapOnUpdateValue, .groupProcessDtlTransferAutoMapSrcPath').select2("readonly", true);
            $thisTR.find('#deleteMetaDataId_displayField').prop("readonly", true).closest('td').find('button').prop('disabled', true);
            $thisTR.find('.btn[onclick]').prop('disabled', true);
        }
    });
        
    $("input.groupProcessDtlTransferAutoMapValue", "table.process-dtl-transfer-automap-tbl").trigger('change');
});
function deleteMetaSelectProcessDtlTransfer(chooseType, elem, params, _this){
    var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
    if (metaBasketNum > 0) {
        var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
        var row = rows[0];
        var parentCell = $(_this).closest("td");
        parentCell.find("#deleteMetaDataId_valueField").val(row.META_DATA_ID);
        parentCell.find("#deleteMetaDataId_displayField").val(row.META_DATA_CODE);
        parentCell.find("#deleteMetaDataId_nameField").text(row.META_DATA_NAME);
    }
}
</script>