<?php echo Form::create(array('id'=>'metaImportForm', 'name'=>'metaImportForm', 'method'=>'post', 'action'=>'javascript:;', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data'));?>
<div class="col-md-12">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00149'), 'for' => 'xmlFile', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
        <div class="col-md-10 mb0">
            <div class="table-scrollable-borderless overflowYauto">
                <table class="table table-hover table-light meta_import_files mb0">
                    <tbody>
                        <tr>
                            <td><input type="file" name="meta_import_file[]" class="col-md-12 form-control" onchange="hasImportFileExtension(this);" required="required"></td>
                        </tr>
                    </tbody>
                </table>
            </div>    
        </div>
        <div class="clearfix w-100"></div>
    </div>
</div>
<div class="col-md-12">
    <div class="form-group row fom-row">
        <table class="table table-hover table-light meta_import_files mb0" id="importResultTable">
            
        </table>
    </div>
</div>
<?php 
echo Form::hidden(array('name' => 'rowId', 'value' => $this->rowId));
echo Form::hidden(array('name' => 'importType', 'value' => $this->importType));
echo Form::close(); 
?>

