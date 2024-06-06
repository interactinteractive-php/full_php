<div class="card light">
  <div class="card-header card-header-no-padding header-elements-inline">
    <b>Encrypt&Decrypt</b>
  </div>
  <div class="card-body">
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo Form::create(array('id' => 'decryptionForm', 'name' => 'decryptionForm', 'method' => 'post', 'action' => 'mdupgrade/decrypt', 'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data'));
        ?>
      <div class="col-md-12">
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Файл', 'for' => 'xmlFile', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
          <div class="col-md-10 mb0">
            <input type="file" name="xmlFile" class="col-md-12 form-control" onchange="hasImportFileExtension(this);" required="required">
          </div>
        </div>
        <button type="submit" class="btn btn-sm btn-success fr">Decrypt хийх</button>
      </div>
      <?php
      echo Form::close();
      ?>
    </div>
    <div class="col-md-6 col-sm-12 col-xs-12">
        <?php
        echo Form::create(array('id' => 'encryptionForm', 'name' => 'encryptionForm', 'method' => 'post', 'action' => 'mdupgrade/encrypt', 'class' => 'form-horizontal',
            'enctype' => 'multipart/form-data'));
        ?>
      <div class="col-md-12">
        <div class="form-group row fom-row">
            <?php echo Form::label(array('text' => 'Файл', 'for' => 'xmlFile', 'class' => 'col-form-label col-md-2', 'required' => 'required')); ?>
          <div class="col-md-10 mb0">
            <input type="file" name="xmlFile" class="col-md-12 form-control" onchange="hasImportFileExtension(this);" required="required">
          </div>
        </div>
      </div>
      <button type="submit" class="btn btn-sm btn-info fr">Encrypt хийх</button>
      <?php
      echo Form::close();
      ?>
    </div>
    <div class="clearfix w-100"></div>
  </div>
</div>