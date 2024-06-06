<p>Param name: <strong><?php echo $this->paramName . ' /' . $this->paramPath . '/'; ?></strong></p>

<?php echo $this->button; ?>
<div class="table-scrollable overflowYauto" style="max-height: 400px;">
    <table class="table table-bordered table-advance table-hover" id="param-value-list">
        <thead>
            <tr>
                <th style="width: 150px;"><?php echo $this->lang->line('META_00075'); ?></th>
                <th style="width: 60%"><?php echo $this->lang->line('META_00125'); ?></th>
                <th style="width: 50px;"></th>
            </tr>    
        </thead> 
        <tbody>
            <?php
            if ($this->paramValues) {
                
                foreach ($this->paramValues as $k => $val) {
                    $valueRow = (new Mddatamodel())->getIdCodeName($this->lookupMetaDataId, $val);
                    $code = $valueRow['code'];
                    $name = $valueRow['name'];
            ?>
            <tr>
                <td>
                    <?php echo Form::hidden(array('name'=>'valueId[]','value'=>$val)); ?>
                    <?php echo $code; ?>
                </td>
                <td><?php echo $name; ?></td>
                <td class="text-center">
                    <a href="javascript:;" class="btn red btn-xs" onclick="deleteParamDefaultValue(this);"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
            <?php
                }
            }
            ?>
        </tbody>
    </table>  
</div>    

<script type="text/javascript">
function deleteParamDefaultValue(elem) {
    $(elem).closest("tr").remove();
}    
function addBasketParamValues(metaDataCode, processMetaDataId, chooseType, elem, rows, paramRealPath, lookupMetaDataId, isMetaGroup) {
    $.ajax({
        type: 'post',
        url: 'mdmetadata/selectableGroupInputFills',
        data: {
            processMetaDataId: processMetaDataId,
            paramRealPath: paramRealPath,
            lookupMetaDataId: lookupMetaDataId
        },
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({
                animate: true
            });
        },
        success: function(data) {
            var displayField = data.displayField;
            var valueField = data.valueField;
            var codeField = data.codeField;
            
            for (var i = 0; i < rows.length; i++) {
                var row = rows[i];
                var isAddRow = true;
                $('table#param-value-list tbody').find("tr").each(function() {
                    if ($(this).find("input[name='valueId[]']").val() === row[valueField]) {
                        isAddRow = false;
                    }
                });
                if (isAddRow) {
                    $('table#param-value-list tbody').append('<tr>'+
                        '<td>'+row[codeField]+'<input type="hidden" name="valueId[]" value="'+row[valueField]+'"></td>'+
                        '<td>'+row[displayField]+'</td>'+
                        '<td class="text-center"><a href="javascript:;" class="btn red btn-xs" onclick="deleteParamDefaultValue(this);"><i class="fa fa-trash"></i></a></td>'+
                    '</tr>');
                }
            }
            
            Core.unblockUI();
        }
    });
}
</script>