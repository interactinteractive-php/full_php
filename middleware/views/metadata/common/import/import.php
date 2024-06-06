<?php echo Form::create(array('id'=>'metaImportForm', 'name'=>'metaImportForm', 'method'=>'post', 'action'=>'javascript:;', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data'));?>
<div class="col-md-12">
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => 'Мета', 'for' => 'isOverride', 'class' => 'col-form-label col-md-2')); ?>
        <div class="col-md-10 text-align-left">
            <label class="checkbox-list"><input type="checkbox" name="isOverride" id="isOverride" class="isOverride" value="0"> Дагалдсан метаг засах уу</label>
        </div>
    </div>
    <div class="form-group row fom-row">
        <?php echo Form::label(array('text' => $this->lang->line('META_00149'), 'for' => 'xmlFile', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
        <div class="col-md-10 mb0">
            <div class="table-scrollable-borderless overflowYauto" style="max-height: 900px">
                <table class="table table-hover table-light meta_import_files mb0">
                    <tbody>
                        <tr>
                            <td><input type="file" name="meta_import_file[]" class="col-md-12 form-control" onchange="hasImportFileExtension(this);" required="required"></td>
                            <td>
                                <a href="javascript:;" class="btn btn-xs btn-success addMetaImportFile">
                                    <i class="icon-plus3 font-size-12"></i>
                                </a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>    
        </div>
        <div class="clearfix w-100"></div>
    </div>
</div>
<?php echo Form::hidden(array('name'=>'rowId','value'=>$this->rowId)); ?>
<?php echo Form::hidden(array('name'=>'importType','value'=>$this->importType)); ?>
<?php echo Form::close(); ?>

<script type="text/javascript">
$(function(){
    $('#isOverride').on("click", function(){
        var _this = $(this);
        if (_this.attr('checked')) {
            _this.val(1);
        } else {
            _this.val(0);
        }
    });
    
    $('a.addMetaImportFile').on("click", function(){    
        var rowCount = $("table.meta_import_files").find("tr").length;
        
        if (rowCount <= 19) {
            $('.meta_import_files').append(
            '<tr>'+
                '<td><input type="file" name="meta_import_file[]" class="col-md-12 form-control" onchange="hasImportFileExtension(this);"></td>'+
                '<td>'+
                    '<a href="javascript:;" class="btn btn-xs btn-danger" onclick="removeMetaFile(this);"><i class="icon-cross2 font-size-12"></i></a>' + 
                '</td>'+
            '</tr>');
        }
    });
});
</script>

