<?php if (!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class'=>'form-horizontal', 'id'=>'addFolder-form', 'method'=>'post')); ?>
<div class="row">
    <div class="col-md-7">
        <div class="form-body">
            <div class="form-group form-group-feedback form-group-feedback-left" style="width: 50%">
                <?php 
                echo Form::text(
                    array(
                        'name' => 'folderCode', 
                        'id' => 'folderCode', 
                        'class' => 'form-control form-control-sm border-0 focus-border-grey', 
                        'required' => 'required', 
                        'placeholder' => $this->lang->line('META_00170')
                    )
                ); 
                ?>
                <div class="form-control-feedback form-control-feedback-sm">
                    <i class="fa fa-tag"></i>
                </div>
            </div>
            <div class="form-group row fom-row">
                <div class="col-md-12">
                    <?php 
                    echo Form::textArea(
                        array(
                            'name' => 'folderName', 
                            'id' => 'folderName', 
                            'class' => 'form-control input-text-lg border-0', 
                            'required' => 'required', 
                            'style' => 'height: 65px', 
                            'placeholder' => $this->lang->line('META_00194')
                        )
                    ); 
                    ?>
                </div>
            </div>
            <hr />
        </div>
    </div>
    <div class="col-md-5">
        <?php echo $this->sidebar; ?>
    </div> 
</div>
<div class="row form-actions mt20">
    <div class="col-lg-8 ml-lg-auto">
        <?php 
        echo Form::button(
            array(
                'class' => 'btn grey-cascade meta-btn-back mr5', 
                'value' => $this->lang->line('back_btn'), 
                'onclick' => 'backFormMeta();'
            )
        ); 
        echo Form::button(
            array(
                'class' => 'btn green-meadow bp-btn-save', 
                'value' => '<i class="icon-checkmark-circle2"></i> ' . $this->lang->line('save_btn'), 
                'onclick' => 'createFolderForm(this);'
            )
        ); 
        ?>
    </div>
</div>
<?php echo Form::close(); ?>  

<script type="text/javascript">
$(function(){
    
    setTimeout(function () {
        $('#folderCode').focus();
    }, 10);
    
    $(".user-perm-check-all").on("click", function(){
        var $outputParamTable = $(this).closest("table");
        var outputParamCol = $(this).closest("tr").children().index($(this).closest("th"));
        var outputParamIndex = outputParamCol + 1;
        $outputParamTable.find("td:nth-child("+outputParamIndex+") input:checkbox").attr("checked", $(this).is(":checked"));
        $.uniform.update();
    });
});
 
function chooseMdUser(chooseMode, objectId, appendId, selectedElementId){
    var mdUserBasketNum = $('#mdUserBasketDataGrid').datagrid('getData').total;
    var mdUserGetPermission = $("#mdObj-"+appendId+" thead").find("th.objPermission");
    if (mdUserBasketNum > 0) {
        var rows = $('#mdUserBasketDataGrid').datagrid('getRows');
        for (var i=0; i<rows.length; i++) {
            var mdUserPermissionCheckbox = '';
            var row = rows[i];
            var isAddRow = true;
            $('#mdObj-'+appendId+' tbody').find("tr").each(function(){
                if ($(this).find("input.mdUserInput").val() === row.USER_ID) {
                    isAddRow = false;
                }
            });
            if (isAddRow) {
                mdUserGetPermission.each(function(){
                    mdUserPermissionCheckbox += '<td class="text-center"><input type="checkbox" name="mdUserPermissionId_'+objectId+'_'+row.USER_ID+'[]" value="'+($(this).find("input").val())+'"/></td>';
                });
                $('#mdObj-'+appendId+' tbody').append('<tr>'+ 
                            '<td>'+row.FIRST_NAME+' (Хэрэглэгч)<input type="hidden" name="mdUserId_'+objectId+'[]" class="mdUserInput" value="'+row.USER_ID+'"></td>'+
                            mdUserPermissionCheckbox +
                            '<td class="text-center"><a href="javascript:;" onclick="deleteMdUserList(this);" class="btn red btn-xs"><i class="fa fa-trash"></i></a></td>'+
                        '</tr>');
                Core.initUniform();
            }
        }
    } else {
        var rows = $('#mdUserDataGrid').datagrid('getSelections');
        for (var i=0; i<rows.length; i++) {
            var mdUserPermissionCheckbox = '';
            var row = rows[i];
            var isAddRow = true;
            $('#mdObj-'+appendId+' tbody').find("tr").each(function(){
                if ($(this).find("input.mdUserInput").val() === row.USER_ID) {
                    isAddRow = false;
                }
            });
            if (isAddRow) {
                mdUserGetPermission.each(function(){
                    mdUserPermissionCheckbox += '<td class="text-center"><input type="checkbox" name="mdUserPermissionId_'+objectId+'_'+row.USER_ID+'[]" value="'+($(this).find("input").val())+'"/></td>';
                });
                $('#mdObj-'+appendId+' tbody').append('<tr>'+ 
                            '<td>'+row.FIRST_NAME+' (Хэрэглэгч)<input type="hidden" name="mdUserId_'+objectId+'[]" class="mdUserInput" value="'+row.USER_ID+'"></td>'+
                            mdUserPermissionCheckbox +
                            '<td class="text-center"><a href="javascript:;" onclick="deleteMdUserList(this);" class="btn red btn-xs"><i class="fa fa-trash"></i></a></td>'+
                        '</tr>');
                Core.initUniform();
            }
        }
    }
}
function deleteMdUserList(elem){
    $(elem).parents("tr").remove();
}    
function chooseParentFolder(chooseType, elem, params) {
    var folderBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
    if (folderBasketNum > 0) {
        var rows = $('#commonBasketFolderGrid').datagrid('getRows');
        var row = rows[0];
        $("input[name='parentFolderId']").val(row.FOLDER_ID); 
        $("span.parent-folder-name").text(row.FOLDER_NAME);  
    }        
}
</script>