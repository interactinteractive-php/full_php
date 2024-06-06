<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'excelTempImportAdd-form', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00075'), 'for' => 'templateCode', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-5">
            <?php 
            echo Form::text(
                array(
                    'name' => 'templateCode', 
                    'id' => 'templateCode', 
                    'class' => 'form-control form-control-sm', 
                    'required' => 'required', 
                    'value' => Arr::get($this->row, 'CODE')
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00125'), 'for' => 'templateName', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-8">
            <?php 
            echo Form::text(
                array(
                    'name' => 'templateName', 
                    'id' => 'templateName', 
                    'class' => 'form-control form-control-sm', 
                    'required' => 'required', 
                    'value' => Arr::get($this->row, 'NAME') 
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Sheet ийн нэр', 'for' => 'sheetName', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-5">
            <?php 
            echo Form::text(
                array(
                    'name' => 'sheetName', 
                    'id' => 'sheetName', 
                    'class' => 'form-control form-control-sm', 
                    'required' => 'required', 
                    'value' => Arr::get($this->row, 'SHEET_NAME') 
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Эхлэх мөрийн дугаар', 'for' => 'rowIndex', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-1">
            <?php 
            echo Form::text(
                array(
                    'name' => 'rowIndex', 
                    'id' => 'rowIndex', 
                    'class' => 'form-control form-control-sm longInit', 
                    'required' => 'required', 
                    'value' => Arr::get($this->row, 'ROW_INDEX') 
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Эксель загвар сонгох', 'for' => 'excelFile', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-8">
            <?php echo Form::file(array('name' => 'excelFile', 'id' => 'excelFile', 'class' => 'form-control form-control-sm fileInit', 'data-valid-extension' => 'xls, xlsx')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Процесс сонгох', 'for' => 'processId', 'class' => 'col-form-label col-md-3', 'required' => 'required')); ?>
        <div class="col-md-8">
            <div class="meta-autocomplete-wrap" data-params="autoSearch=1&metaTypeId=<?php echo Mdmetadata::$businessProcessMetaTypeId; ?>">
                <div class="input-group double-between-input">
                    <input id="excelTemplateProcessId" name="excelTemplateProcessId" type="hidden" value="<?php echo Arr::get($this->row, 'PROCESS_META_DATA_ID'); ?>">
                    <input id="_displayField" class="form-control form-control-sm md-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" required="required" value="<?php echo Arr::get($this->row, 'PROCESS_META_DATA_CODE'); ?>">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="commonMetaDataSelectableGrid('single', '', this);"><i class="fa fa-search"></i></button>
                    </span>     
                    <span class="input-group-btn">
                        <input id="_nameField" class="form-control form-control-sm md-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" required="required" value="<?php echo Arr::get($this->row, 'PROCESS_META_DATA_NAME'); ?>">      
                    </span>     
                </div>
            </div>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Is Hierarchy', 'for' => 'isHierarchy', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-8">
            <?php echo Form::checkbox(array('name' => 'isHierarchy', 'id' => 'isHierarchy', 'value' => '1', 'saved_val' => Arr::get($this->row, 'IS_HIERARCHY'))); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Систем сонгох', 'for' => 'systemId', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-8">
            <div class="meta-autocomplete-wrap" data-params="">
                <div class="input-group double-between-input">
                    <input type="hidden" name="systemId" id="systemId_valueField" data-path="systemId" class="popupInit" value="<?php echo Arr::get($this->row, 'SYSTEM_ID'); ?>">
                    <input type="text" name="systemId_displayField" value="<?php echo Arr::get($this->row, 'SYSTEM_CODE'); ?>" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" id="systemId_displayField" data-processid="1552466535284" data-lookupid="1552528134433282" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('systemId', '1552466535284', '1552528134433282', 'single', 'systemId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                    </span>  
                    <span class="input-group-btn">
                        <input type="text" name="systemId_nameField" value="<?php echo Arr::get($this->row, 'SYSTEM_NAME'); ?>" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" id="systemId_nameField" data-processid="1552466535284" data-lookupid="1552528134433282" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                    </span>   
                </div>                
            </div>
        </div>
    </div>    
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Mодуль сонгох', 'for' => 'moduleId', 'class' => 'col-form-label col-md-3')); ?>
        <div class="col-md-8">
            <div class="meta-autocomplete-wrap" data-params="">
                <div class="input-group double-between-input">
                    <input type="hidden" name="moduleId" id="moduleId_valueField" data-path="moduleId" value="<?php echo Arr::get($this->row, 'MODULE_ID'); ?>" class="popupInit">
                    <input type="text" name="moduleId_displayField" value="<?php echo Arr::get($this->row, 'MODULE_CODE'); ?>" class="form-control form-control-sm meta-autocomplete lookup-code-autocomplete" id="moduleId_displayField" data-processid="1552466535284" data-lookupid="1552528190670515" placeholder="<?php echo $this->lang->line('code_search'); ?>" autocomplete="off">
                    <span class="input-group-btn">
                        <button type="button" class="btn default btn-bordered form-control-sm mr0" onclick="dataViewSelectableGrid('moduleId', '1552466535284', '1552528190670515', 'single', 'moduleId', this);" tabindex="-1"><i class="fa fa-search"></i></button>
                    </span>  
                    <span class="input-group-btn">
                        <input type="text" name="moduleId_nameField" value="<?php echo Arr::get($this->row, 'MODULE_NAME'); ?>" class="form-control form-control-sm meta-name-autocomplete lookup-name-autocomplete" id="moduleId_nameField" data-processid="1552466535284" data-lookupid="1552528190670515" placeholder="<?php echo $this->lang->line('name_search'); ?>" tabindex="-1" autocomplete="off">
                    </span>   
                </div>                
            </div>
        </div>
    </div>    
    
    <div class="table-toolbar mt10">
        <div class="row">
            <div class="col-md-6">
                <button type="button" class="btn btn-xs green-meadow" onclick="dataViewCustomSelectableGrid('META_PROCESS_PARAM_ATTR_LINKDV', 'multi', 'processInputParamsImport', 'criteriaCondition[processMetaDataId]==&param[processMetaDataId]='+$('#excelTemplateProcessId').val(), this);">
                    <i class="icon-plus3 font-size-12"></i> Мөр нэмэх
                </button>
            </div>
        </div>    
    </div>
    
    <div id="excel-temp-fz-parent" class="freeze-overflow-xy-auto">
        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" id="excel-temp-param-dtl">
            <thead>
                <tr>
                    <th class="bp-head-sort" style="width: 200px; min-width: 200px; max-width: 200px"><?php echo $this->lang->line('META_00125'); ?></th>
                    <th class="bp-head-sort">Path</th>
                    <th class="text-center bp-head-sort" style="width: 70px;">Баганын индекс /үсгээр/</th>
                    <th class="text-center bp-head-sort" style="width: 110px; min-width: 110px; max-width: 110px">Тогтмол утга</th>
                    <th class="text-center bp-head-sort" style="width: 230px; min-width: 230px; max-width: 230px">Томъёо</th>
                    <th style="width: 35px; min-width: 35px; max-width: 35px"></th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($this->params) {
                    foreach ($this->params as $param) {
                ?>
                <tr>
                    <td class="middle" style="padding-left:5px !important"><?php echo $this->lang->line($param['LABEL_NAME']); ?></td>
                    <td class="middle" style="padding-left:5px !important" data-path="true">
                        <?php echo $param['PARAM_PATH']; ?>
                        <input name="id[]" type="hidden" value="<?php echo $param['ID']; ?>">
                        <input name="isNew[]" type="hidden" value="0">
                        <input name="paramPath[]" type="hidden" value="<?php echo $param['PARAM_PATH']; ?>">
                    </td>
                    <td class="stretchInput middle"><input name="columnName[]" class="form-control form-control-sm alphaonly" type="text" value="<?php echo $param['COLUMN_NAME']; ?>"></td>
                    <td class="stretchInput middle"><input name="defaultValue[]" class="form-control form-control-sm" value="<?php echo $param['DEFAULT_VALUE']; ?>" type="text"></td>
                    <td class="stretchInput middle"><input name="expression[]" class="form-control form-control-sm" value="<?php echo $param['EXPRESSION']; ?>" type="text"></td>
                    <td class="text-center"><a href="javascript:;" class="btn btn-xs red" onclick="excelInputParamRemove(this);"><i class="fa fa-trash"></a></a></td>
                </tr>
                <?php
                    }
                }
                ?>
            </tbody>
        </table>
    </div>
</div>
<?php 
echo Form::hidden(array('name' => 'templateId', 'value' => Arr::get($this->row, 'ID')));
echo Form::close(); 
?>

<style type="text/css">
#excel-temp-fz-parent {
    min-height: 350px;
    max-height: 350px;
}
input.alphaonly {
    text-transform: uppercase;
}
</style>

<script type="text/javascript">
$(function(){
    
    $(document).on('keydown', '.alphaonly', function(e){
        if (e.ctrlKey || e.altKey) {
            e.preventDefault();
        } else {
            var key = e.keyCode;
            if (!((key == 8) || (key == 32) || (key == 46) || (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
                 e.preventDefault();
            }
        }
    });
    
    $(document).on('keyup', ".alphaonly", function () {
        $(this).val(function (_, val) {
            return val.toUpperCase();
        });
    });

    <?php
    if ($this->isEdit) {
    ?>
    excelTemplateDtlFreeze();        
    <?php
    }
    ?>        
            
    $('body').off('click', 'table.bprocess-table-dtl > thead > tr > th.bp-head-sort');
    
    $('body').on('click', 'table#excel-temp-param-dtl > thead > tr > th.bp-head-sort', function(){
        var _this = $(this);
        var _table = _this.closest('table');
        var table = _table.find('tbody:eq(0)');
        
        if (table.find('tr').length > 1) {
            var colIndex = _this.index();
            var fieldTypeElem = table.find('tr:eq(0) > td:eq('+colIndex+')');
            var fieldType = 'label';
            
            if (fieldTypeElem.find("input[type=text]:first").length > 0) {
                fieldType = 'text';
            }
            
            _table.find('thead:first > tr > th').removeClass('bp-head-sort-asc bp-head-sort-desc');
            
            var rows = table.children('tr').toArray().sort(bpComparer(colIndex, fieldType));
            this.asc = !this.asc;
            
            if (!this.asc) { 
                _this.removeClass('bp-head-sort-desc').addClass('bp-head-sort-asc');
                rows = rows.reverse(); 
            } else {
                _this.removeClass('bp-head-sort-asc').addClass('bp-head-sort-desc');
            }
            for (var i = 0; i < rows.length; i++) {
                table.append(rows[i]);
            }
        }
    });
});  
function processInputParamsImport(metaDataCode, chooseType, elem, rows) {
    
    var html = [], row = [], rowHtml = '', isAddRow = true, rowPathText = '', rowParamRealPath = '';
    
    for (var i = 0; i < rows.length; i++) {
        
        row = rows[i];
        isAddRow = true;
        
        $('#excel-temp-param-dtl > tbody > tr').each(function() {
            rowPathText = $(this).find("td[data-path='true']").text();
            rowParamRealPath = row.paramrealpath;
            
            if (rowPathText.trim().toLowerCase() === rowParamRealPath.trim().toLowerCase()) {
                isAddRow = false;
            }
        });
        
        if (isAddRow) {
            rowHtml = '<tr>'+
                '<td class="middle" style="padding-left:5px !important">'+row.langline+'</td>'+
                '<td class="middle" style="padding-left:5px !important" data-path="true">'+row.paramrealpath+
                '<input name="id[]" type="hidden">'+
                '<input name="isNew[]" type="hidden" value="1">'+
                '<input name="paramPath[]" type="hidden" value="'+row.paramrealpath+'">'+
                '</td>'+
                '<td class="stretchInput middle"><input name="columnName[]" class="form-control form-control-sm alphaonly" type="text"></td>'+
                '<td class="stretchInput middle"><input name="defaultValue[]" class="form-control form-control-sm" value="'+(row.defaultvalue != null ? row.defaultvalue : '')+'" type="text"></td>'+
                '<td class="stretchInput middle"><input name="expression[]" class="form-control form-control-sm" type="text"></td>'+
                '<td class="text-center"><a href="javascript:;" class="btn btn-xs red" onclick="excelInputParamRemove(this);"><i class="fa fa-trash"></a></a></td>'+
            '</tr>';
            html.push(rowHtml);
        }
    }
    
    $('table#excel-temp-param-dtl > tbody').append(html.join('')).promise().done(function(){
        excelTemplateDtlFreeze();
    });
    
    return;
}
function excelInputParamRemove(elem) {
    var $r = $(elem).closest('tr');
    var isNew = $r.find("input[name='isNew[]']").val();
    
    if (isNew == '1') {
        $r.remove();
    } else if (isNew == '0') {
        $r.addClass('removed-tr');
        $r.find("input[name='isNew[]']").val('2');
    } else if (isNew == '2') {
        $r.removeClass('removed-tr');
        $r.find("input[name='isNew[]']").val('0');
    }
}
function excelTemplateDtlFreeze() {
    $('table#excel-temp-param-dtl').tableHeadFixer({'head': true, 'left': 2, 'z-index': 9}); 
    $('div#excel-temp-fz-parent').trigger('scroll');
}
</script>