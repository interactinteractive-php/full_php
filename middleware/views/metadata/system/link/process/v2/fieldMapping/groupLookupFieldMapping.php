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
        if ($this->tempField && isset($this->fieldsMapping[$this->fieldMappingParamFieldPath][$this->paramPath])) {
            
            foreach ($this->fieldsMapping[$this->fieldMappingParamFieldPath][$this->paramPath] as $k => $fieldMappingParamFieldPath) {
        ?>    
            <tr>
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'groupLookupFieldMapGroupParam[]',
                            'class' => 'form-control form-control-sm', 
                            'required' => 'required', 
                            'style' => 'width: 330px', 
                            'value' => $this->fieldsMapping[$this->fieldMappingParamFieldPath][$this->paramPath][$k]
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
                            'op_value' => 'FIELD_NAME', 
                            'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME', 
                            'style' => 'width:330px', 
                            'value' => $this->fieldsMapping[$this->fieldMappingLookupFieldPath][$this->paramPath][$k]
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
        } elseif ($this->fieldsMapping) {  
            
            foreach ($this->fieldsMapping as $row) {
        ?>
            <tr>
                <td>
                    <?php 
                    echo Form::text(
                        array(
                            'name' => 'groupLookupFieldMapGroupParam[]',
                            'class' => 'form-control form-control-sm', 
                            'required' => 'required', 
                            'style' => 'width: 330px', 
                            'value' => $row['PARAM_FIELD_PATH']
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
                            'op_value' => 'FIELD_NAME', 
                            'op_text' => 'META_DATA_CODE| |-| |META_DATA_NAME', 
                            'style' => 'width:330px', 
                            'value' => strtolower($row['LOOKUP_FIELD_PATH'])
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
    var $this = $(elem);
    var $parentTbl = $this.closest("table");
    $parentTbl.find("tbody").append('<tr>'+
        '<td><?php echo Form::text(array('name'=>'groupLookupFieldMapGroupParam[]','class'=>'form-control form-control-sm group-param','required'=>'required', 'style' => 'width:330px')); ?></td>'+
        '<td><?php echo Form::select(array('name'=>'groupLookupFieldMapLookupParam[]','class'=>'form-control form-control-sm select2 lookup-param','data' => $this->lookupParams, 'required'=>'required', 'op_value'=>'FIELD_NAME', 'op_text'=>'META_DATA_CODE| |-| |META_DATA_NAME', 'style' => 'width:330px')); ?></td>'+
        '<td class="text-center middle"><a href="javascript:;" class="btn red btn-xs" onclick="groupLookupFieldMappingRemoveRow(this);"><i class="fa fa-trash"></i></a></td>'+
    '</tr>');
    Core.initSelect2($parentTbl.find('tbody > tr:last'));
} 
function groupLookupFieldMappingRemoveRow(elem){
    var $parentRow = $(elem).closest('tr');
    $parentRow.remove();
}

$(function(){
    $(document).on('keydown', 'input.group-param', function(e){
        var code = (e.keyCode ? e.keyCode : e.which);
        
        if (code === 13) {
            var $this = $(this);
            var $row = $this.closest('tr');
            var $code = $this.val().toLowerCase();
            var $selectedValue = $row.find("select.lookup-param option[value='"+$code+"']").attr('value');

            if (typeof $selectedValue !== 'undefined') {
                $row.find('select.lookup-param').select2('val', $selectedValue);
            }
        }
    });
});
</script>