<div class="col-md-12">
  <div class="card light mb0 p-0">
    <div class="card-body xs-form">
      <div class="row">
        <div class="table-toolbar mb0">
          <div class="row">
            <div class="col-md-3">
              <div class="btn-group btn-group-devided">
                <a href="javascript:;" title="Нэмэх" id="addUserDataPermissionBn" onclick="dataViewSelectableGrid('addUserDataPermission', '0', '<?php echo $this->selectedRow['tabledvid']; ?>', 'multi', 'addUserDataPermission', this, 'saveUserDataPermissionFromBasket');" class="btn green btn-circle btn-sm">
                  <i class="icon-plus3 font-size-12"></i> Нэмэх
                </a>
                <!-- <a href="javascript:;" title="Хасах" id="enableUserDataPermissionBn" class="btn green btn-circle btn-sm"><i class="fa fa-check"></i> Идэвхтэй болгох</a> -->
                <a href="javascript:;" title="Хасах" id="removeUserDataPermissionBn" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-minus"></i> Хасах</a>
              </div>
            </div>
            <div class="col-md-9" id="actionCheckBoxesAllDiv">
              <div class="form-group row fom-row">
                <label class="col-md-1 col-form-label">Үйлдэл:</label>
                <div class="col-md-11">
                  <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000001"><i class="state-icon fa fa-square-o"></i> Нэмэх (Бүгд)</button> 
                  <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000002"><i class="state-icon fa fa-square-o"></i> Засах (Бүгд)</button> 
                  <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000003"><i class="state-icon fa fa-square-o"></i> Устгах (Бүгд)</button> 
                  <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000004"><i class="state-icon fa fa-square-o"></i> Харах (Бүгд)</button>
                  <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000005"><i class="state-icon fa fa-square-o"></i> Ажиллуулах (Бүгд)</button>
                  <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000006"><i class="state-icon fa fa-square-o"></i> Жагсаалт (Бүгд)</button>
                  <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000007"><i class="state-icon fa fa-square-o"></i> Дүр тохируулах (Бүгд)</button>
                </div>
              </div>
              <div class="clearfix w-100"></div>
              <div class="form-group row fom-row">
                <label class="col-md-1 col-form-label">Hierarchy:</label>
                <div class="col-md-11">
                  <div class="input-group">
                    <input type="checkbox" name="isHierarchy" id="isHierarchy" class="form-control">
                    (Check хийсэн тохиолдолд <i class="fa fa-sitemap ml5 mr5"></i> icon-тай харагдана)
                  </div>
                </div>
              </div>
              <!-- <div class="clearfix w-100"></div>
              <div class="form-group row fom-row">
                <label class="col-md-1 col-form-label">Is active:</label>
                <div class="col-md-11">
                  <div class="input-group">
                    <input type="checkbox" name="isActivePermission" id="isActivePermission" checked class="form-control">
                  </div>
                </div>
              </div> -->
              <div class="clearfix w-100"></div>
            </div>
            <div class="jeasyuiTheme3 col-md-12" id="div-user-permission-map-datagrid">

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>


<script type="text/javascript">
var userId='<?php echo (isset($this->userId) && !is_null($this->userId)) ? $this->userId : ''; ?>',
    roleId='<?php echo (isset($this->roleId) && !is_null($this->roleId)) ? $this->roleId : ''; ?>',
    selectedTableRow=<?php echo (isset($this->selectedRow) && !is_null($this->selectedRow)) ? json_encode($this->selectedRow) : ''; ?>,
    uniqId=<?php echo (isset($this->uniqId) && !is_null($this->uniqId)) ? $this->uniqId : ''; ?>;

$(document).ready(function(){
    $.getScript("middleware/assets/js/um/md_um_data_permission.js", function(){
        if (userId !== '' || roleId !== '') {
            MdUmDataPermission.init(roleId, userId);
        }
    });
});
</script>