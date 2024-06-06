<div class="row">
  <div class="col-md-3"></div>
  <div class="col-md-3">
    <div class="form-group row fom-row">
      <label class="col-md-4 col-form-label"><span class="required">*</span>Үйлдэл:</label>
      <div class="col-md-8">
        <div class="">
            <?php
            echo Form::select(
                    array(
                        'name' => 'actionId',
                        'id' => 'actionId',
                        'class' => 'form-control select2 form-control-sm input-xxlarge',
                        'data' => $this->umAction,
                        'op_id' => 'ACTION_ID',
                        'op_value' => 'ACTION_ID',
                        'op_text' => 'ACTION_NAME'
                    )
            );
            ?>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3">
    <div class="form-group row fom-row">
      <label class="col-md-4 col-form-label"><span class="required">*</span>hierarchy:</label>
      <div class="col-md-8">
        <div class="input-group">
          <input type="checkbox" name="isHierarchy" id="isHierarchy" class="form-control">
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-3"></div>
  <div class="col-md-offset-2 col-md-10" id="actionCheckBoxesAllDiv">
    <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000001"><i class="state-icon fa fa-unchecked"></i> Нэмэх (Бүгд)</button> 
    <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000002"><i class="state-icon fa fa-unchecked"></i> Засах (Бүгд)</button> 
    <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000003"><i class="state-icon fa fa-unchecked"></i> Устгах (Бүгд)</button> 
    <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000004"><i class="state-icon fa fa-unchecked"></i> Харах (Бүгд)</button>
    <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000005"><i class="state-icon fa fa-unchecked"></i> Ажиллуулах (Бүгд)</button>
    <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000006"><i class="state-icon fa fa-unchecked"></i> Жагсаалт (Бүгд)</button>
  </div>
</div>
<div class="row">
  <div class="col-md-12">
    <div id="div-user-permission-selection-datagrid"></div>
  </div>
</div>