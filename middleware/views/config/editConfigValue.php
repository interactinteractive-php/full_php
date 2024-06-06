<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'editConfigValue-form', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
<div class="col-md-12">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Түлхүүр', 'for'=>'configId', 'class'=>'col-form-label col-md-2', 'required'=>'required')); ?>
        <div class="col-md-10">
            <?php 
            echo Form::select(
                array(
                    'name' => 'configId', 
                    'id' => 'configId', 
                    'class' => 'form-control select2', 
                    'required' => 'required',
                    'data' => $this->configKeyList,
                    'op_value' => 'ID', 
                    'op_text' => 'CODE| |-| |DESCRIPTION', 
                    'value' => $this->row['CONFIG_ID']
                )
            ); 
            ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Утга', 'for'=>'configValue', 'class'=>'col-form-label col-md-2', 'required'=>'required')); ?>
        <div class="col-md-7" id="config-value-type">
            <?php echo (new Mdconfig())->renderValueType($this->row['CONFIG_ID'], $this->row['CONFIG_VALUE']); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Тайлбар', 'for'=>'configDescr', 'class'=>'col-form-label col-md-2')); ?>
        <div class="col-md-10">
            <?php echo Form::textArea(array('name'=>'configDescr', 'value' => $this->row['DESCRIPTION'], 'id'=>'configDescr', 'class'=>'form-control', 'rows' => '2')); ?>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text'=>'Нөхцөл', 'class'=>'col-form-label col-md-2')); ?>
        <div class="col-md-7" style="max-height: 200px; overflow: auto">
            <table class="table table-light config_criterias">
                <tbody>
                    <?php
                    if ($this->criteriaList) {
                    ?>
                            <tr>
                                <td style="width: 100%" class="no-padding pb5">
                                    <?php echo Form::text(array('name'=>'criteria[]', 'class'=>'form-control', 'value'=>$this->criteriaList)); ?>
                                </td>
                                <td style="width: 5%" class="middle no-padding text-right pb5">
                                    <a href="javascript:;" class="btn btn-xs btn-success d-none addConfigCriteria">
                                        <i class="icon-plus3 font-size-12"></i>
                                    </a>
                                </td>
                            </tr>                    
                    <?php
                    } else {
                    ?>
                    <tr>
                        <td style="width: 95%" class="no-padding pb5">
                            <?php echo Form::text(array('name'=>'criteria[]', 'class'=>'form-control')); ?>
                        </td>
                        <td style="width: 5%" class="middle no-padding text-right pb5">
                            <a href="javascript:;" class="btn btn-xs btn-success d-none addConfigCriteria">
                                <i class="icon-plus3 font-size-12"></i>
                            </a>
                        </td>
                    </tr>        
                    <?php        
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="form-group row">
        <?php echo Form::label(array('text'=>'Company department', 'for'=>'companyDepartmentId', 'class'=>'col-form-label col-md-2')); ?>
        <div class="col-md-7">
            <?php 
            echo Form::select(array(
                'name' => 'companyDepartmentId', 
                'id' => 'companyDepartmentId', 
                'class' => 'form-control select2', 
                'data' => $this->companyDepList,
                'op_value' => 'id', 
                'op_text' => 'name', 
                'value' => $this->row['COMPANY_DEPARTMENT_ID']
            )); 
            ?>
        </div>
    </div>
</div>
<?php 
echo Form::hidden(array('name'=>'id','value'=>$this->row['ID'])); 
echo Form::close(); 
?>

<script type="text/javascript">
$(function(){
    $.ui.dialog.prototype._allowInteraction = function(e) {
         return !!$(e.target).closest('.ui-dialog, .ui-datepicker, .select2-dropdown').length;
    };
    
    $(".addConfigCriteria").on("click", function(){    
        $('.config_criterias').append(
            '<tr>'+
                '<td class="no-padding pb5">'+
                    '<?php echo Form::text(array('name'=>'criteria[]', 'class'=>'form-control')); ?>'+
                '</td>'+
                '<td class="middle no-padding text-right pb5">'+
                    '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="removeConfigCriteria(this);"><i class="fa fa-trash"></i></a>' + 
                '</td>'+
            '</tr>');
    }); 
    $("#configId").on("change", function(){
        var configId = $(this).val();
        $.ajax({
            type: 'post',
            url: 'mdconfig/printValueType',
            data: {configId: configId},
            beforeSend: function(){
                Core.blockUI({
                    animate: true
                });
            },
            success: function(data){
                $("#config-value-type").empty().append(data);
                Core.unblockUI();
            },
            error: function(){
                alert("Error");
            }
        }).done(function(){
            Core.initAjax($("#config-value-type"));
        });
    });
});

function removeConfigCriteria(element){
    $(element).closest("tr").remove();
}
</script>    