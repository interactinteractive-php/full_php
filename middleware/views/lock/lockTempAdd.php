<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'temp-lock-form', 'method' => 'post')); ?>
<div class="col-md-12 xs-form">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Категори', 'for' => 'categoryId', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-6">
            <?php 
            echo Form::select(
                array(
                    'name' => 'categoryId', 
                    'id' => 'categoryId', 
                    'class' => 'form-control form-control-sm select2', 
                    'data' => $this->categoryList, 
                    'op_value' => 'ID', 
                    'op_text' => 'CAT_NAME', 
                    'value' => $this->categoryId 
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <input type="password" name="password" value="" style="display:none">
        <input type="text" name="username" value="" style="display:none">
        <?php echo Form::label(array('text' => 'Lock name', 'for' => 'lockName', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
        <div class="col-md-6">
            <?php 
            echo Form::text(
                array(
                    'name' => 'lockName', 
                    'id' => 'lockName', 
                    'class' => 'form-control form-control-sm readonly-white-bg', 
                    'required' => 'required', 
                    'readonly' => 'readonly', 
                    'onfocus' => 'this.removeAttribute(\'readonly\');', 
                    'autocomplete' => 'false'
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Lock pass', 'for' => 'lockPass', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
        <div class="col-md-6">
            <?php 
            echo Form::password(
                array(
                    'name' => 'lockPass', 
                    'id' => 'lockPass', 
                    'class' => 'form-control form-control-sm readonly-white-bg', 
                    'required' => 'required', 
                    'readonly' => 'readonly', 
                    'onfocus' => 'this.removeAttribute(\'readonly\');', 
                    'autocomplete' => 'false'
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Түгжих эсэх', 'for' => 'isLocked', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-6">
            <?php 
            echo Form::checkbox(
                array(
                    'name' => 'isLocked', 
                    'id' => 'isLocked', 
                    'value' => '1', 
                    'saved_val' => '1'
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Тайлбар', 'for' => 'description', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-10">
            <?php 
            echo Form::textArea(
                array(
                    'name' => 'description', 
                    'id' => 'description', 
                    'class' => 'form-control form-control-sm', 
                    'rows' => 3
                )
            ); 
            ?>
        </div>
    </div>
    <?php echo Form::button(array('class'=>'btn btn-xs green-meadow mb10','value'=>'<i class="icon-plus3 font-size-12"></i> Нэмэх','onclick'=>'commonMetaDataGrid(\'multi\', \'metaGroup\', \'autoSearch=1&ignorePermission=1\');')); ?>
    <div id="excel-temp-fz-parent" class="freeze-overflow-xy-auto">
        <table class="table table-sm table-bordered table-hover bprocess-table-dtl bprocess-theme1" id="excel-temp-param-dtl">
            <thead>
                <tr>
                    <th class="bp-head-sort" style="width: 200px; min-width: 200px; max-width: 200px">Код</th>
                    <th class="bp-head-sort">Нэр</th>
                    <th class="text-center bp-head-sort" style="width: 130px;">Төрөл</th>
                    <th style="width: 35px; min-width: 35px; max-width: 35px"></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<?php echo Form::close(); ?>

<style type="text/css">
#excel-temp-fz-parent {
    min-height: 200px;
    max-height: 200px;
}
</style>

<script type="text/javascript">
function excelTemplateDtlFreeze() {
    $('table#excel-temp-param-dtl').tableHeadFixer({'head': true, 'z-index': 9}); 
    $('div#excel-temp-fz-parent').trigger('scroll');
}    
function selectableCommonMetaDataGrid(chooseType, elem, params) {
    
    var metaBasketNum = $('#commonBasketMetaDataGrid').datagrid('getData').total;
    
    if (metaBasketNum > 0) {
        var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
        var $lockBasket = $('#excel-temp-param-dtl');
        var html = [], row = [], rowHtml = '', isAddRow = true;
        
        for (var i = 0; i < rows.length; i++) {
            row = rows[i];
            isAddRow = true;
            
            if ($lockBasket.find('tr[data-meta-id="'+row.META_DATA_ID+'"]').length) {
                isAddRow = false;
            }

            if (isAddRow) {
                
                rowHtml = '<tr data-meta-id="'+row.META_DATA_ID+'">'+
                    '<td class="middle" style="padding-left:5px !important">'+row.META_DATA_CODE+'</td>'+
                    '<td class="middle" style="padding-left:5px !important">'+row.META_DATA_NAME+
                    '<input name="metaDataId[]" type="hidden" value="'+row.META_DATA_ID+'">'+
                    '</td>'+
                    '<td class="middle text-center">'+row.META_TYPE_NAME+'</td>'+
                    '<td class="text-center"><a href="javascript:;" class="btn btn-xs red" onclick="excelInputParamRemove(this);"><i class="fa fa-trash"></a></a></td>'+
                '</tr>';
        
                html.push(rowHtml);
            }
        }
        
        $('table#excel-temp-param-dtl > tbody').append(html.join('')).promise().done(function(){
            excelTemplateDtlFreeze();
        });
    }
}  
function excelInputParamRemove(elem) {
    $(elem).closest('tr').remove();
}
</script>    