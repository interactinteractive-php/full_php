<?php echo Form::create(array('id'=>'newImportForm', 'method'=>'post', 'class'=>'form-horizontal', 'enctype'=>'multipart/form-data'));?>
<div class="form-group row">
    <?php echo Form::label(array('text' => 'Файл /*.txt/', 'class' => 'col-form-label col-md-2 pt8', 'required' => 'required')); ?>
    <div class="col-md-10 mb0">
        <input type="file" multiple="multiple" accept=".txt" name="import_file[]" class="form-control h-auto" onchange="hasImportFileExtension(this);" required="required">  
    </div>
    <div class="col-md-12">
        <div class="alert alert-info mt20 mb0">Та *.txt өргөтгөлтэй олон файл нэг зэрэг сонгож болно.</div>
    </div>
</div>
<div id="knowmetasinfile"></div>
<?php echo Form::close(); ?>

<style type="text/css">   
.knowmetasinfile-tbl {
    overflow: auto;
    max-height: 500px;
}
.knowmetasinfile-tbl thead th {
    position: sticky; 
    top: 0;
    background-color: #f8f8f8;
}
</style>

