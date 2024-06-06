<?php 
echo Form::create(array('id'=>'newCopyImportForm', 'method'=>'post', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data')); 

echo Form::hidden(array('name' => 'isMetaImportCopy', 'value' => '1'));
?>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Файл /*.txt/', 'class' => 'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-10 mb0">
        <input type="file" accept=".txt" name="import_file[]" class="form-control h-auto" onchange="hasImportFileExtension(this);" required="required">  
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Шинэ код', 'class' => 'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-6 mb0">
        <input type="text" name="newMetaCode" class="form-control form-control-sm" required="required">  
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Шинэ нэр', 'class' => 'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-10 mb0">
        <input type="text" name="newMetaName" class="form-control form-control-sm" required="required">  
    </div>
</div>
<div class="form-group row">
    <?php echo Form::label(array('text'=>$this->lang->line('META_00024'), 'class'=>'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-10">
        <div class="meta-autocomplete-wrap">
            <div class="input-group double-between-input">
                <?php echo Form::hidden(array('name' => 'folderId', 'value' => Arr::get($this->folderRow, 'FOLDER_ID'))); ?>
                <span class="input-group-btn">
                    <input id="_displayField" value="<?php echo Arr::get($this->folderRow, 'FOLDER_CODE'); ?>" class="form-control form-control-sm md-folder-code-autocomplete" placeholder="<?php echo $this->lang->line('META_00068'); ?>" type="text" required="required">
                </span>   
                <span class="input-group-btn" style="max-width: 40px">
                    <?php echo Form::button(array('class' => 'btn purple-plum', 'value' => '<i class="far fa-search"></i>', 'onclick' => 'commonFolderDataGrid(\'single\', \'\', \'chooseMetaFolderByCopy\', this);')); ?>
                </span>
                <span class="input-group-btn flex-col-group-btn">
                    <input id="_nameField" value="<?php echo Arr::get($this->folderRow, 'FOLDER_NAME'); ?>" class="form-control form-control-sm md-folder-name-autocomplete" placeholder="<?php echo $this->lang->line('META_00099'); ?>" type="text" required="required">      
                </span>     
            </div>
        </div>  
    </div>
</div>

<div class="knowmetasinfile-copy-tbl mt20" style="overflow: auto;">
    <table class="table table-hover">
        <thead>
            <tr>
                <th class="font-weight-bold">Файлын нэр</th>
                <th class="font-weight-bold">Мета ID</th>
                <th class="font-weight-bold">Мета код</th>
                <th class="font-weight-bold">Төрөл</th>
                <th class="font-weight-bold">Огноо</th>
                <th class="font-weight-bold">Хэрэглэгч</th>
            </tr>
        </thead>
        <tbody>    
        </tbody>
    </table>
</div>
<?php echo Form::close(); ?>

<style type="text/css">   
.knowmetasinfile-copy-tbl {
    overflow: auto;
    max-height: 500px;
}
.knowmetasinfile-copy-tbl thead th {
    position: sticky; 
    top: 0;
    background-color: #f8f8f8;
}
</style>

<script type="text/javascript">
function chooseMetaFolderByCopy(chooseType, elem, params) {
    var folderBasketNum = $('#commonBasketFolderGrid').datagrid('getData').total;
    if (folderBasketNum > 0) {
        var rows = $('#commonBasketFolderGrid').datagrid('getRows');
        var row = rows[0];

        $("input[name='folderId']", "#newCopyImportForm").val(row.FOLDER_ID);
        $("input#_displayField", "#newCopyImportForm").val(row.FOLDER_CODE);
        $("input#_nameField", "#newCopyImportForm").val(row.FOLDER_NAME);
    }        
} 
    
$(function() {
    $('#newCopyImportForm').on('change', 'input[name="import_file[]"]', function() {
        
        var $this = $(this);
        PNotify.removeAll();

        $('#newCopyImportForm').ajaxSubmit({
            type: 'post',
            url: 'mdupgrade/knowMetasInFile',
            dataType: 'json',
            beforeSend: function() {
                Core.blockUI({message: 'Түр хүлээнэ үү...', boxed: true});
            },
            success: function (data) {

                var $knowMetasInFile = $('.knowmetasinfile-copy-tbl tbody'), tbl = [];

                if (data.status == 'success') {

                    if (Object.keys(data.metaList).length) {

                        var metaList = data.metaList;

                        for (var i in metaList) {

                            tbl.push('<tr>');
                                tbl.push('<td>'+metaList[i]['fileName']+'</td>');
                                tbl.push('<td>'+metaList[i]['metaId']+'</td>');
                                tbl.push('<td>'+metaList[i]['metaCode']+'</td>');
                                tbl.push('<td>'+metaList[i]['metaType']+'</td>');
                                tbl.push('<td>'+metaList[i]['modifiedDate']+'</td>');
                                tbl.push('<td>'+metaList[i]['userName']+'</td>');
                            tbl.push('</tr>');
                            
                            $('#newCopyImportForm').find('input[name="newMetaCode"]').val(metaList[i]['metaCode'] + '_copy');
                            $('#newCopyImportForm').find('input[name="newMetaName"]').val(metaList[i]['metaName'] + '_copy');
                            
                            if (metaList[i]['folderId'] != '' && $('#newCopyImportForm').find('input[name="folderId"]').val() == '') {
                                $('#newCopyImportForm').find('input[name="folderId"]').val(metaList[i]['folderId']);
                                $('#newCopyImportForm').find('input.md-folder-code-autocomplete').val(metaList[i]['folderCode']);
                                $('#newCopyImportForm').find('input.md-folder-name-autocomplete').val(metaList[i]['folderName']);
                            }
                        }
                    }

                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        hide: true,  
                        addclass: pnotifyPosition,
                        delay: 1000000000
                    });
                    
                    $this.val('');
                }

                $knowMetasInFile.empty().append(tbl.join(''));

                Core.unblockUI();
            }
        });
    });
});  
</script>
