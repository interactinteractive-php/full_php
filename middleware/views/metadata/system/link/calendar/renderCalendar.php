<style type="text/css">
  .calendar-goto {
      margin-bottom: 11px;
      display: flex;
      border-bottom: 1px solid #eee;
      padding-bottom: 5px;
  }
  #calendar_div_<?php echo $this->calUniqId; ?> div.actions {
      padding: 1px 0 2px;
  }
  #calendar_<?php echo $this->calUniqId; ?> .fc-event .fc-title {
      font-size:  <?php echo isset($this->calendarMetaRow['TEXT_FONT_SIZE']) ? $this->calendarMetaRow['TEXT_FONT_SIZE'] : '13px'; ?> !important;
  }  
  .fc-content .fc-time {
      display: none !important;
  }
  .fc-content .fc-title {
      padding: 0 2px;
  }
  #calendar_div_<?php echo $this->calUniqId; ?> .fc-button {
      height: 30px;
  }
  #calendar_div_<?php echo $this->calUniqId; ?> .font-green-sharp {
      color: #4DB3A2 !important;
      font-size: 14px !important;
  }
  #calendar_div_<?php echo $this->calUniqId; ?> label {
      text-align: right !important;
      padding-right: 0;
  }
</style>

<div id="calendar_div_<?php echo $this->calUniqId; ?>" class="card light shadow calendar" <?php
echo isset($this->calendarMetaRow['WIDTH']) ? 'style="width: ' . $this->calendarMetaRow['WIDTH'] . ';"' : '';
?>>
  <div class="card-header card-header-no-padding header-elements-inline">
    <div class="card-title">
      <i class="fa fa-calendar font-green-sharp"></i>
      <span class="caption-subject font-green-sharp font-weight-bold uppercase" id="calendar-title-<?php echo $this->calUniqId; ?>">
          <?php echo isset($this->calendarMetaRow['TITLE']) ? $this->calendarMetaRow['TITLE'] : '';
          ?>
      </span>
      <span class="caption-helper"></span>
    </div>
    <div class="actions">
        <?php if (isset($this->defaultCriteria)) { ?>
          <button type="button" class="btn btn-circle btn-icon-only btn-secondary" title="<?php echo $this->lang->line('META_00193'); ?>" id="search-btn-<?php echo $this->calUniqId; ?>"><i class="fa fa-search"></i></button>
      <?php } ?>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-md-12 col-sm-12">
        <div class="row">
          <div id="calendar-goto-<?php echo $this->calUniqId; ?>" class="calendar-goto w-100">
            <div class="col-md-3">
              <div class="form-group row fom-row">
                <label for="year-<?php echo $this->calUniqId; ?>" class="col-md-3">Он:</label>
                <div class="col-md-9">
                  <select name="year-<?php echo $this->calUniqId; ?>" id="year-<?php echo $this->calUniqId; ?>" class="form-control form-control-sm select2">
                    <option value="">-Сонгох-</option>
                    <?php
                    foreach (range(2000, 2021) as $x) {
                        echo '<option value="' . $x . '"' . ($x == date('Y') ? ' selected="selected"' : '') . '>' . $x . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-3">
              <div class="form-group row fom-row">
                <label for="month-<?php echo $this->calUniqId; ?>" class="col-md-3">Сар:</label>
                <div class="col-md-9">
                  <select name="month-<?php echo $this->calUniqId; ?>" id="month-<?php echo $this->calUniqId; ?>" class="form-control form-control-sm select2">
                    <option value="">-Сонгох-</option>
                    <?php
                    foreach (range(1, 12) as $x) {
                        echo '<option value="' . (strlen($x) == 1 ? '0' . $x : $x) . '">' . $x . '</option>';
                    }
                    ?>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-md-6">
              <div class="form-group row fom-row">
                  <?php if (!is_null($this->calendarMetaRow['FILTER_GROUP_PARAM_PATH'])) {
                      ?>
                    <label for="filterGroup-<?php echo $this->calUniqId; ?>" class="col-md-3"><?php echo $this->calendarMetaRow['FILTER_GROUP_PARAM_NAME']; ?>:</label>
                <?php }
                ?>

                <div class="col-md-8" id="htmlOption">

                </div>
              </div>
            </div>
          </div>
        </div>

        <div id="calendar_<?php echo $this->calUniqId; ?>" class="has-toolbar" style="margin-right: 5px;">
        </div>
      </div>
    </div>
  </div>
</div>

<div id="calendar-filter-div-<?php echo $this->calUniqId; ?>" class="hidden">
  <form class="calendar-filter-form-<?php echo $this->calUniqId; ?>">
    <?php echo isset($this->defaultCriteria) ? $this->defaultCriteria : ''; ?>
  </form>
</div>



<script type="text/javascript">
    var $calendarFilterForm=$("#calendar-filter-div-<?php echo $this->calUniqId; ?>").find('.calendar-filter-form-' + <?php echo $this->calUniqId; ?>);
    $(document).ready(function(){
      if(typeof mdCalendar !== "undefined"){
        mdCalendar.init(<?php echo $this->calUniqId; ?>, <?php echo json_encode($this->calendarMetaRow); ?>);
      }
    });

    $("#search-btn-<?php echo $this->calUniqId; ?>").on("click", function(){

      var dialogName='dialog-search-calendar-<?php echo $this->calUniqId; ?>-divid';

      if($("#" + dialogName).length === 0){
        $('<div id="' + dialogName + '"></div>').appendTo('body');
      }

      var $filterDialog=$("#" + dialogName);

      $filterDialog.empty().html($('#calendar-filter-div-<?php echo $this->calUniqId; ?>').html());

      $filterDialog.dialog({
        cache: false,
        resizable: true,
        bgiframe: true,
        autoOpen: false,
        title: '<?php echo $this->calendarMetaRow['TITLE']; ?> хайлт',
        width: 500,
        height: 'auto',
        clickOutside: true,
        modal: true,
        close: function(){
          $filterDialog.empty().dialog('destroy').remove();
        },
        buttons: [
          {html: '<i class="fa fa-search"></i> <?php echo $this->lang->line('do_filter') ?>',
            class: 'btn btn-sm blue-madison', click: function(){
              $calendarFilterForm=$filterDialog.find('.calendar-filter-form-<?php echo $this->calUniqId; ?>');
              mdCalendar.init(<?php echo $this->calUniqId; ?>, <?php echo json_encode($this->calendarMetaRow); ?>);
              $filterDialog.empty().dialog('destroy').remove();
            }
          },
          {html: '<?php echo $this->lang->line('clear_btn') ?>', class: 'btn btn-sm grey-cascade', click: function(){
              mdCalendar.init(<?php echo $this->calUniqId; ?>, <?php echo json_encode($this->calendarMetaRow); ?>);
            }
          }
        ]
      }).dialogExtend({
        "closable": true,
        "maximizable": true,
        "minimizable": true,
        "collapsable": true,
        "dblclick": "maximize",
        "minimizeLocation": "left",
        "icons": {
          "close": "ui-icon-circle-close",
          "maximize": "ui-icon-extlink",
          "minimize": "ui-icon-minus",
          "collapse": "ui-icon-triangle-1-s",
          "restore": "ui-icon-newwin"
        }
      });

      $filterDialog.dialog('open');

    });
</script>