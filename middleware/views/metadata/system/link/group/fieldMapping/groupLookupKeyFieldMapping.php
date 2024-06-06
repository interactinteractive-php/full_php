<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'groupLookupFieldMapping-form', 'method' => 'post')); ?>
<div class="form-group row fom-row mb0">
    <?php echo Form::label(array('text' => 'Param name', 'class' => 'col-form-label col-md-3')); ?>
    <div class="col-md-9">
        <p class="form-control-plaintext font-weight-bold"><?php echo $this->paramName . ' /' . $this->paramPath . '/'; ?></p>
    </div>
</div>
<div class="table-scrollable overflowYauto" style="max-height: 500px">
    <table class="table group-lookup-field-map-tbl">
        <thead>
            <tr>
                <th>Group Param</th>
                <th>Lookup Param</th>
                <th style="width: 15px;"></th>
            </tr>
        </thead>
        <tbody>
        <?php
        if ($this->tempField) {
            if (count($this->fieldsMapping['fieldMappingParamMetaIdKey'][$this->paramPath]) == 0) {
                $childMetaDatas = $this->childMetas['paramTrgAttr'];
                $groupLookupFieldMapGroupParam = Form::comboOneGroupChildMetas(
                    array(
                        'name' => 'groupLookupFieldMapGroupParam[]',
                        'class' => 'form-control form-control-sm select2 group-param', 
                        'data' => $this->childMetas, 
                        'required' => 'required', 
                        'style' => 'width:330px'
                    )
                ); 
                $groupLookupFieldMapLookupParam = Form::select(
                    array(
                        'name' => 'groupLookupFieldMapLookupParam[]',
                        'class' => 'form-control form-control-sm select2 lookup-param',
                        'data' => $this->lookupParams, 
                        'required' => 'required', 
                        'op_value' => 'FIELD_NAME|-|META_DATA_ID', 
                        'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME', 
                        'style' => 'width:330px'
                    )
                ); 
                foreach ($childMetaDatas as $row) {
        ?>
            <tr>
                <td>
                    <?php echo $groupLookupFieldMapGroupParam; ?>
                </td>
                <td>
                    <?php echo $groupLookupFieldMapLookupParam; ?>
                </td>
                <td class="text-center middle">
                    <a href="javascript:;" class="btn red btn-xs" onclick="groupLookupFieldMappingRemoveRow(this);"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        <?php    
                }
            } else {
                foreach ($this->fieldsMapping['fieldMappingParamMetaIdKey'][$this->paramPath] as $k => $fieldMappingParamMetaIdKey) {
            ?>    
                <tr>
                    <td>
                        <?php 
                        echo Form::comboOneGroupChildMetas(
                            array(
                                'name' => 'groupLookupFieldMapGroupParam[]',
                                'class' => 'form-control form-control-sm select2', 
                                'data' => $this->childMetas, 
                                'required' => 'required', 
                                'style' => 'width:330px', 
                                'value' => $this->fieldsMapping['fieldMappingParamFieldPathKey'][$this->paramPath][$k]."-".$fieldMappingParamMetaIdKey
                            )
                        ); 
                        ?>
                    </td>
                    <td>
                        <?php 
                        echo Form::select(
                            array(
                                'name' => 'groupLookupFieldMapLookupParam[]',
                                'class' => 'form-control form-control-sm select2',
                                'data' => $this->lookupParams, 
                                'required' => 'required', 
                                'op_value' => 'FIELD_NAME|-|META_DATA_ID', 
                                'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME', 
                                'style' => 'width:330px', 
                                'value' => $this->fieldsMapping['fieldMappingLookupFieldPathKey'][$this->paramPath][$k]."-".$this->fieldsMapping['fieldMappingLookupMetaIdKey'][$this->paramPath][$k]
                            )
                        ); 
                        ?>
                    </td>
                    <td class="text-center middle">
                        <a href="javascript:;" class="btn red btn-xs" onclick="groupLookupFieldMappingRemoveRow(this);"><i class="fa fa-trash"></i></a>
                    </td>
                </tr>
            <?php
                }
            }
        } else {            
            if (count($this->fieldsMapping) == 0) {
                $childMetaDatas = $this->childMetas['paramTrgAttr'];
                $groupLookupFieldMapGroupParam = Form::comboOneGroupChildMetas(
                    array(
                        'name' => 'groupLookupFieldMapGroupParam[]',
                        'class' => 'form-control form-control-sm select2 group-param', 
                        'data' => $this->childMetas, 
                        'required' => 'required', 
                        'style' => 'width:330px'
                    )
                ); 
                $groupLookupFieldMapLookupParam = Form::select(
                    array(
                        'name' => 'groupLookupFieldMapLookupParam[]',
                        'class' => 'form-control form-control-sm select2 lookup-param',
                        'data' => $this->lookupParams, 
                        'required' => 'required', 
                        'op_value' => 'FIELD_NAME|-|META_DATA_ID', 
                        'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME', 
                        'style' => 'width:330px'
                    )
                ); 
                foreach ($childMetaDatas as $row) {
        ?>
            <tr>
                <td>
                    <?php echo $groupLookupFieldMapGroupParam; ?>
                </td>
                <td>
                    <?php echo $groupLookupFieldMapLookupParam; ?>
                </td>
                <td class="text-center middle">
                    <a href="javascript:;" class="btn red btn-xs" onclick="groupLookupFieldMappingRemoveRow(this);"><i class="fa fa-trash"></i></a>
                </td>
            </tr>
        <?php    
                }
            } else {
                foreach ($this->fieldsMapping as $row) {
        ?>
            <tr>
                <td>
                    <?php 
                    echo Form::comboOneGroupChildMetas(
                        array(
                            'name' => 'groupLookupFieldMapGroupParam[]',
                            'class' => 'form-control form-control-sm select2', 
                            'data' => $this->childMetas, 
                            'required' => 'required', 
                            'style' => 'width:330px', 
                            'value' => $row['PARAM_FIELD_PATH']."-".$row['PARAM_META_DATA_ID']
                        )
                    ); 
                    ?>
                </td>
                <td>
                    <?php 
                    echo Form::select(
                        array(
                            'name' => 'groupLookupFieldMapLookupParam[]',
                            'class' => 'form-control form-control-sm select2',
                            'data' => $this->lookupParams, 
                            'required' => 'required', 
                            'op_value' => 'FIELD_NAME|-|META_DATA_ID', 
                            'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME', 
                            'style' => 'width:330px', 
                            'value' => Str::lower($row['LOOKUP_FIELD_PATH'])."-".$row['LOOKUP_META_ID']
                        )
                    ); 
                    ?>
                </td>
                <td class="text-center middle">
                    <a href="javascript:;" class="btn red btn-xs" onclick="groupLookupFieldMappingRemoveRow(this);"><i class="fa fa-trash"></i></a>
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
                <td colspan="3">
                    <a href="javascript:;" class="btn green btn-xs" onclick="groupLookupFieldMappingAddRow(this);">
                        <i class="icon-plus3 font-size-12"></i> <?php echo $this->lang->line('META_00103'); ?> 
                    </a>
                </td>
            </tr>
        </tfoot>
    </table>
</div>       
<?php echo Form::close(); ?>

<script type="text/javascript">
function groupLookupFieldMappingAddRow(elem){
    var _this = $(elem);
    var parentTbl = _this.closest("table");
    parentTbl.find("tbody").append('<tr>'+
            '<td><?php echo Form::comboOneGroupChildMetas(array('name'=>'groupLookupFieldMapGroupParam[]','class'=>'form-control form-control-sm select2 group-param','data'=>$this->childMetas,'required'=>'required', 'style' => 'width:330px')); ?></td>'+
            '<td><?php echo Form::select(array('name'=>'groupLookupFieldMapLookupParam[]','class'=>'form-control form-control-sm select2 lookup-param','data' => $this->lookupParams, 'required'=>'required', 'op_value'=>'FIELD_NAME|-|META_DATA_ID', 'op_text'=>'META_DATA_CODE| |-| |META_DATA_NAME', 'style' => 'width:330px')); ?></td>'+
            '<td class="text-center middle"><a href="javascript:;" class="btn red btn-xs" onclick="groupLookupFieldMappingRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
            '</tr>');
    Core.initSelect2();
} 
function groupLookupFieldMappingRemoveRow(elem){
    var parentRow = $(elem).closest("tr");
    parentRow.remove();
}
$(function(){
    $(document).on("change", "select.group-param", function(){
        var _this = $(this);
        var _row = _this.closest("tr");
        var _codeArr = _this.val().split("-");
        var _code = _codeArr[0].toLowerCase();
        var _selectedValue = _row.find("select.lookup-param option[value^='"+_code+"-']").attr("value");
        
        if (typeof _selectedValue !== 'undefined') {
            _row.find("select.lookup-param").select2('val', _selectedValue);
        }
    });
});
</script>