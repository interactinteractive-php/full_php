<div class="col-md-12" id="assignation_<?php echo $this->uniqId; ?>">
  <div class="row mt10">
    <div class="col-md-12">
      <div class="card light shadow">      
        <div class="card-header card-header-no-padding header-elements-inline">
          <div class="card-title">
            <i class="fa fa-cogs"></i>Хэрэглэгчийн эрх шилжүүлэх
          </div>
          <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body xs-form">
          <div class="row mt10">
            <div class="col-md-12">
              <form action="javascript:;" class="form-horizontal" id="assignation_form_<?php echo $this->uniqId; ?>">
                <div class="form-body">
                  <div class="col-md-12 jeasyuiTheme3" id="container_<?php echo $this->uniqId; ?>">
                    <div class="table-toolbar mb5">
                      <div class="row">
                        <div class="col-md-4">
                          <div class="input-group quick-item">
                            <div class="input-icon">
                              <i class="fa fa-search"></i>
                              <?php
                              echo Form::hidden(array('id' => 'assignationFromUserId'));
                              echo Form::text(array('name'        => 'assignationFromUser',
                                  'id'          => 'assignationFromUser', 'class'       => 'form-control usernameMask',
                                  'placeholder' => 'Эрх шилжүүлэх хэрэглэгч', 'style'       => 'padding-left:33px;',
                                  'tabindex'    => 4));
                              ?>
                            </div>
                          </div>
                        </div>
                        <div class="col-md-4"></div>
                        <div class="col-md-4">
                          <div class="input-group quick-item">
                            <div class="input-icon">
                              <i class="fa fa-search"></i>
                              <?php
                              echo Form::hidden(array('id' => 'assignationToUserId'));
                              echo Form::text(array('name'        => 'assignationToUser',
                                  'id'          => 'assignationToUser', 'class'       => 'form-control usernameMask',
                                  'placeholder' => 'Эрх хүлээн авах хэрэглэгч', 'style'       => 'padding-left:33px;',
                                  'tabindex'    => 4));
                              ?>
                            </div>
                          </div>
                        </div>
                      </div>
                      <br>
                      <div class="row">
                        <div class="col-md-4">
                          <div id="assignationFromUserTree"></div>
                        </div>
                        <div class="col-md-4">
                          <div id="assignationToUserSelectedTree"></div>
                        </div>
                        <div class="col-md-4">
                          <div id="assignationToUserTree"></div>
                        </div>
                      </div>
                    </div>
                    <table class="no-border mt0" id="usersdatagrid_<?php echo $this->uniqId ?>" style="width: 100%; "></table>
                  </div>
                </div>    
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-12">
      <a id="save_assignation_permission" href="javascript:;" class="btn green-meadow btn-sm float-left mt5"><i class="icon-plus3 font-size-12"></i> Хадгалах</a>
    </div>
  </div>

  <script type="text/javascript">
    var uniqId=<?php echo (isset($this->uniqId) && !is_null($this->uniqId)) ? $this->uniqId : ""; ?>,
            metaDataId=<?php
                              echo (isset($this->metaDataId) && !is_null($this->metaDataId)) ? $this->metaDataId
                                        : "";
                              ?>;
    jQuery(document).ready(function(){
      $.getScript("middleware/assets/js/um/md_um_role_assignation.js", function(){
        umRoleAssignationObj=new MdUmRoleAssignation(metaDataId, uniqId);
        umRoleAssignationObj.initEventListener();
        umRoleAssignationObj.initUserAutocomplete();
      });
    });

  </script>