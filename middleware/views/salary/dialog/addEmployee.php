<div class="row selectableGrid">
  <div class="col-md-4 left-content-selectableGrid">
    <div class="tabbable-line">
      <ul class="nav nav-tabs">
        <li class="nav-item">
          <a href="javascript;" data-status="open" class="nav-link active" data-toggle="tab">Шүүлтүүр</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active in selectableGrid-tab-144491113094152" id="commonSelectableTabFilter">
          <form role="form" id="employeeListSearchForm" method="post">
            <div class="form-body">
              <div class="col-md-12">
                <label class="customLabel col-md-12" for="departmentId" style="text-align: left !important;">Алба хэлтэс:</label>
                <?php
                echo Form::select(
                        array(
                            'name' => 'departmentId',
                            'id' => 'departmentId_'.$this->uniqId,
                            'class' => 'form-control select2 form-control-sm input-xxlarge',
                            'data' => $this->departmentList,
                            'op_value' => 'DEPARTMENTID',
                            'op_text' => 'DEPARTMENTNAME',
                            'required' => 'required'
                        )
                );
                ?>
              </div>
              <div class="col-md-12">
                <label class="customLabel col-md-12" for="employeeCode" style="text-align: left !important;">Ажилтны код:</label>
                <input type="text" class="addEmployeeInput form-control form-control-sm" name="employeeCode" id="employeeCode_<?php echo $this->uniqId;?>">
              </div>
              <div class="col-md-12">
                <label class="customLabel col-md-12" for="employeeLastName" style="text-align: left !important;">Овог:</label>
                <input type="text" class="addEmployeeInput form-control form-control-sm" name="employeeLastName" id="employeeLastName_<?php echo $this->uniqId;?>">
              </div>
              <div class="col-md-12">
                <label class="customLabel col-md-12" for="employeeFirstName" style="text-align: left !important;">Нэр:</label>
                <input type="text" class="addEmployeeInput form-control form-control-sm" name="employeeFirstName" id="employeeFirstName_<?php echo $this->uniqId;?>">
              </div>
                <div class="col-md-12">
                    <label class="radio-inline">
                        <input type="radio" name="checkEmployee" class="radioEmployee" checked="checked" value="1"> Идэвхтэй
                    </label>
                    <label class="radio-inline">
                      <input type="radio" name="checkEmployee" class="radioEmployee" value="0"> Идэвхгүй
                    </label>
                    <input type="hidden" id="isActive_<?php echo $this->uniqId;?>" value="1">
              </div>
            </div>
            <div class="form-actions">
              <button type="button" class="btn blue btn-sm" id="search-unselected_<?php echo $this->uniqId;?>"><i class="fa fa-search"></i> Хайх</button>
              <button type="button" class="btn grey-cascade btn-sm" id="search-unselected-clear_<?php echo $this->uniqId;?>">Цэвэрлэх</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="col-md-8 right-content-selectableGrid">
    <div class="tabbable-line">
      <ul class="nav nav-tabs">
        <li class="nav-item commonSelectableTabOrder">
          <a href="#unselectedListTab_<?php echo $this->uniqId;?>" data-toggle="tab" id="unselectedTabLink_<?php echo $this->uniqId;?>" onclick="unselectedTabLink_<?php echo $this->uniqId; ?>()" class="nav-link active">Жагсаалт</a>
        </li>
        <li class="nav-item">
          <a href="#selectedListTab_<?php echo $this->uniqId;?>" data-toggle="tab" id="selectedTabLink_<?php echo $this->uniqId;?>" onclick="selectedTabLink_<?php echo $this->uniqId; ?>()">Сагс (<span id="selectedEmployeeCount_<?php echo $this->uniqId;?>" class="nav-link">0</span>)</a>
        </li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active jeasyuiTheme3" id="unselectedListTab_<?php echo $this->uniqId; ?>" class="unselectedListTab">
          <div><table id="employee-choose-list-unselected_<?php echo $this->uniqId; ?>"></table></div>
        </div>
        <div class="tab-pane jeasyuiTheme3" id="selectedListTab_<?php echo $this->uniqId;?>" class="selectedListTab">
          <div><table id="employee-choose-list-selected_<?php echo $this->uniqId; ?>"></table></div>
        </div>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
  jQuery(document).ready(function(){
    salary_<?php echo $this->uniqId; ?>.setEmployeeListForm_<?php echo $this->uniqId; ?>();
    $(".radioEmployee").click(function(){
        var checkedRadio = $(this).val();
        $("#isActive_<?php echo $this->uniqId;?>").val(checkedRadio);
    });
  });
  var selectedEmployee_<?php echo $this->uniqId; ?>=function(EMPLOYEE_KEY_ID){
    if(typeof EMPLOYEE_KEY_ID === "undefined")
      return;
    else {
      salary_<?php echo $this->uniqId; ?>.selectedEmployee_<?php echo $this->uniqId; ?>(EMPLOYEE_KEY_ID);
    }
  };
  var unselectedEmployee_<?php echo $this->uniqId; ?>=function(EMPLOYEE_KEY_ID){
    if(typeof EMPLOYEE_KEY_ID === "undefined")
      return;
    else {
      salary_<?php echo $this->uniqId; ?>.unselectedEmployee_<?php echo $this->uniqId; ?>(EMPLOYEE_KEY_ID);
    }
  };
  var selectedTabLink_<?php echo $this->uniqId; ?>=function(){
    salary_<?php echo $this->uniqId; ?>.getselectedTabLink_<?php echo $this->uniqId; ?>();
  };
  var unselectedTabLink_<?php echo $this->uniqId; ?>=function(){
    salary_<?php echo $this->uniqId; ?>.getunselectedTabLink_<?php echo $this->uniqId; ?>();
  };
</script>
<style type="text/css">
  #employeeListSearchForm .form-body {
      overflow: auto;
      max-height: 331px !important;
  }
  #employeeListSearchForm label {
      font-size: 13px !important;
  }
  #employeeListSearchForm .form-group {
      margin-bottom: 5px !important;
  }
  #employeeListSearchForm .form-actions {
      margin-top: 20px !important;
  }
</style>