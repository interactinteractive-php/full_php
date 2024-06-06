<style type="text/css">
  .fileinput-button .big {
      font-size: 70px;
      line-height: 112px;
      text-align: center;
      color: #ddd;
  }
</style>
<?php echo Form::create(array('class' => 'form-horizontal', 'id' => 'excelTemplateForm', 'method' => 'post', 'enctype' => 'multipart/form-data',
    'autocomplete' => 'off'));
?>
<div class="row">
  <div class="col-md-12">
      <?php
      if (isset($this->excelTemplateFile['FILE_NAME'])) {
          echo '<strong>Одоогийн загвар</strong>: <a href="mdobject/downloadFile?fDownload=1&file='.$this->excelTemplateFile['PHYSICAL_PATH'].'">' . $this->excelTemplateFile['FILE_NAME'] . '</a>';
      }
      ?>
    <h4></h4>
    <ul class="grid cs-style-2 list-view0 list-view-excel-template">
      <li class="" data-attach-id="0">
        <a href="javascript:;" class="btn fileinput-button btn-block btn-xs" title="Файл нэмэх">
          <i class="icon-plus3 big"></i>
          <input type="file" name="excel_template_file[]" class="" onchange="processExcelTemplate.onChangeAttachFile(this)" />
        </a>
      </li>
      <li class="" id="excelTemplateFile">

      </li>
    </ul>

    <div id="hiddenExcelTemplateDivParent">
      <div id="hiddenExcelTemplateDiv" class="hidden"></div>
    </div>
  </div>
</div>
<?php
echo Form::hidden(array('name' => 'processMetaDataId', 'value' => $this->processMetaDataId));
echo Form::close();
?> 