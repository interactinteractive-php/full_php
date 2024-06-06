<div id="dialog-data-permission">
  <div class="col-md-12 mt10">
    <div class="row">  
        <div class="col-md-3 col-sm-12">
          <div class="btn-group btn-group-devided">
            <a href="javascript:;" title="Нэмэх" id="dataPermissionToUserBn" onclick="dataViewSelectableGrid('dataPermissionToUser', '0', '1457199411282002', 'multi', 'dataPermissionToUser', this, 'saveUserDataPermissionToUserFromBasket');" class="btn green btn-circle btn-sm">
                <i class="icon-plus3 font-size-12"></i> Хэрэглэгч нэмэх
            </a>        
            <a href="javascript:;" title="Нэмэх" id="dataPermissionToRoleBn" onclick="dataViewSelectableGrid('dataPermissionToRole', '0', '1457174283509032', 'multi', 'dataPermissionToRole', this, 'saveUserDataPermissionToRoleFromBasket');" class="btn btn-circle btn-sm blue">
                <i class="icon-plus3 font-size-12"></i> Дүр нэмэх
            </a>        
            <a href="javascript:;" title="Хасах" id="removeUserDataPermissionBnFinance" class="btn btn-danger btn-circle btn-sm"><i class="fa fa-minus"></i> Хасах</a>              
          </div>
        </div>
        <div class="col-md-9 col-sm-12" id="actionCheckBoxesAllDiv">
          <div class="form-group row fom-row">
            <label class="col-md-1 col-form-label">Үйлдэл:</label>
            <div class="col-md-11">
              <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000001"><i class="state-icon fa fa-unchecked"></i> Нэмэх (Бүгд)</button> 
              <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000002"><i class="state-icon fa fa-unchecked"></i> Засах (Бүгд)</button> 
              <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000003"><i class="state-icon fa fa-unchecked"></i> Устгах (Бүгд)</button> 
              <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000004"><i class="state-icon fa fa-unchecked"></i> Харах (Бүгд)</button>
              <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000005"><i class="state-icon fa fa-unchecked"></i> Ажиллуулах (Бүгд)</button>
              <button class="btn btn-sm actionCheckBoxesAll" childActionid="300101010000006"><i class="state-icon fa fa-unchecked"></i> Жагсаалт (Бүгд)</button>
            </div>
          </div>
          <div class="clearfix w-100"></div>
        </div>    
    </div>  
    <div class="row mt10">
      <div class="jeasyuiTheme3" id="div-user-permission-map-datagrid">

      </div>
      <button type="button" class="btn btn-sm btn-success fr mt10 mr10 savePermissionToUserBtn"><i class="fa fa-save"></i> Хадгалах</button>
    </div>
  </div>
</div>

<script type="text/javascript">
    var dbStructureId = '<?php echo (isset($this->dbStructureId) && !is_null($this->dbStructureId)) ? $this->dbStructureId : ''; ?>',
        recordId = '<?php echo (isset($this->recordId) && !is_null($this->recordId)) ? $this->recordId : ''; ?>',
        uniqId = <?php echo (isset($this->uniqId) && !is_null($this->uniqId)) ? $this->uniqId : ''; ?>;

    $(document).ready(function(){
        $.getScript("middleware/assets/js/um/md_um_data_permission_to_user.js", function(){
            if (dbStructureId !== '' || recordId !== '') {
                MdUmDataPermissionToUser.init(dbStructureId, recordId);
            }
        });
    });
</script>