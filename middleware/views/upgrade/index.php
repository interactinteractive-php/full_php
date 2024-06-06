<div class="card light">
  <div class="card-title tabbable-line tab-not-padding-top package-tab">
    <ul class="nav nav-tabs float-left">
      <li class="nav-item"><a href="#by_customer_<?php echo $this->uniqId ?>" class="nav-link active" data-toggle="tab">Харилцагчаар</a></li>
      <li class="nav-item"><a id="byDaylyTab" href="#by_day_<?php echo $this->uniqId ?>" data-toggle="tab" class="nav-link">Өдрөөр</a></li>
    </ul>
  </div>
  <div class="card-body xs-form">
    <div class="tab-content">
      <div class="tab-pane in active" id="by_customer_<?php echo $this->uniqId ?>">
        <div class="row mt10">
          <div class="jeasyuiTheme3" id="dataGridDiv">
            <table class="no-border mt0" id="customerdatagrid_<?php echo $this->uniqId ?>" style="width: 100%; "></table>
          </div>
        </div>
      </div>
      <div class="tab-pane in" id="by_day_<?php echo $this->uniqId ?>">
        <div class="row mt10">
          <div class="jeasyuiTheme3">
            <table id="upgrade_dayly_datagrid_<?php echo $this->uniqId ?>"></table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
    var dataGridEl=$("#customerdatagrid_" + <?php echo (isset($this->uniqId) && !is_null($this->uniqId)) ? $this->uniqId : ""; ?>);
    var dataGridElDayly=$("#upgrade_dayly_datagrid_" + <?php echo (isset($this->uniqId) && !is_null($this->uniqId)) ? $this->uniqId : ""; ?>);

    $(function(){
      if(typeof customerUpgrade === 'undefined'){
        $.getScript(URL_APP + 'middleware/assets/js/upgrade/customerUpgrade.js', function(){
          customerUpgrade.init();
        });
      } else {
        customerUpgrade.init();
      }
    });
</script>