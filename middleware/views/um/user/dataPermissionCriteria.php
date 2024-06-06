<div class="um-meta-permission">
  <div class="col-md-12 ">
    <form action="#" name="dataPermissionCriteriaForm" id="dataPermissionCriteriaForm">
      <input type="hidden" name="tableDvId" value="<?php echo $this->selectedRow['tabledvid']; ?>" />
      <input type="hidden" name="userId" value="<?php echo isset($this->selectedRow['userid']) ? $this->selectedRow['userid'] : ''; ?>" />
      <input type="hidden" name="roleId" value="<?php echo isset($this->selectedRow['roleid']) ? $this->selectedRow['roleid'] : ''; ?>" />
      <div class="col-md-12 no-padding mb10">
        <a class="btn btn-success btn-circle btn-sm" title="" href="javascript:;" id="addCriteriaBatchBtn">
          <i class="icon-plus3 font-size-12"></i> 
          Нэмэх
        </a>
      </div>

      <div id="batchDiv">
        <?php
        if (empty($this->umMetaPermissionList)) {
            require_once BASEPATH . 'middleware/views/um/user/dataPermissionCriteriaSingle.php';
        } else {
            foreach ($this->umMetaPermissionList['recordCriteriaResult'] as $key => $umMetaPermission) {
                require BASEPATH . 'middleware/views/um/user/dataPermissionCriteriaUpdate.php';
            }
        }
        ?>
      </div>
    </form>
  </div>
</div>


<script type="text/javascript">
    $(function(){
      if(typeof mdUmDataPermissionCriteria === "undefined"){
        $.getStylesheet(URL_APP + 'middleware/assets/css/um/metaPermission.css');
        $.getScript("middleware/assets/js/um/md_um_data_permission_criteria.js", function(){
          mdUmDataPermissionCriteria.init(<?php
        echo (isset($this->selectedRow) && !is_null($this->selectedRow)) ? json_encode($this->selectedRow) : "";
        ?>);
        });
      } else {
        mdUmDataPermissionCriteria.init(<?php
        echo (isset($this->selectedRow) && !is_null($this->selectedRow)) ? json_encode($this->selectedRow) : "";
        ?>);
      }
    });
</script>