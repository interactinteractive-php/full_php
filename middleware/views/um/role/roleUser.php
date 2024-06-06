<div class="card light mb0 pb0">
  <div class="card-body xs-form">
    <div class="row" id="role_user_list_tab_<?php echo $this->uniqId ?>">
      <div id="permissionWindow" class="col-md-12">
        <div class="row mt5">
          <div class="jeasyuiTheme3 col-12" id="dataGridDiv">
            <div class="table-toolbar mb5">
              <div class="row">
                <div class="col-md-5">
                  <div class="input-group quick-item">
                    <div class="input-icon">
                      <i class="fa fa-search"></i>
                      <?php
                      echo Form::hidden(array('id' => 'selectedUserId'));
                      echo Form::text(array('name' => 'username', 'id' => 'usernameAc', 'class' => 'form-control usernameMask', 'placeholder' => 'Нэр', 'style' => 'padding-left:33px;', 'tabindex' => 4));
                      ?>
                    </div>
                    <span class="input-group-btn">
                        <?php echo Form::button(array('class' => 'btn green-meadow', 'id' => 'addRoleToUser', 'value' => '<i class="icon-plus3 font-size-12"></i>')); ?>
                    </span>
                  </div>
                </div>
              </div>
            </div>
            <table class="no-border mt0" id="usersdatagrid_<?php echo $this->uniqId ?>" style="width: 100%; ">
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
$(function(){
    $.getScript('middleware/assets/js/um/md_um_role_user.js', function(){
        var roleId = <?php echo (isset($this->roleId) && !is_null($this->roleId)) ? $this->roleId : ''; ?>;
        if (roleId !== '') {
            umRoleUserObj = new MdUmRoleUser(roleId, null, <?php echo (isset($this->uniqId) && !is_null($this->uniqId)) ? $this->uniqId : ''; ?>);
            umRoleUserObj.initEventListener();
            umRoleUserObj.loadDataGrid();
            umRoleUserObj.initUserAutocomplete();
        }
    });
});
</script>