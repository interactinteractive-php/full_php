<div class="col-md-12" id="whPriceKey">
  <div class="card light shadow">	
<!--    <div class="card-header card-header-no-padding header-elements-inline">
      <div class="caption font-weight-bold"><i class="fa fa-list"></i> <?php echo $this->title; ?></div>
      <div class="tools">
                        <a href="javascript:;" class="collapse"></a>
                        <a href="javascript:;" class="fullscreen"></a>
      </div>
    </div>-->
    <div class="card-body">
      <form class="form-horizontal form-middle" role="form" name="search-report-form" id="search-report-form">
          <div class="form-body">
            <div class="row">
              <div class="col-md-12">
                <div class="col-md-6">
                  <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Тайлангийн нэр', 'for' => 'reportName', 'class' => 'col-form-label col-md-4')); ?>
                    <div class="col-md-8">
                      <?php echo Form::text(array('name' => 'reportName', 'id' => 'reportName', 'class' => 'form-control form-control-inline m-wrap', 'data' => '')); ?>
                    </div>
                  </div>

                </div>
                <div class="col-md-6">
                  <div class="form-group row fom-row">
                    <?php echo Form::label(array('text' => 'Дата март нэр', 'for' => 'reportDataMartName', 'class' => 'col-form-label col-md-4')); ?>
                    <div class="col-md-8">
                      <?php echo Form::text(array('name' => 'reportDataMartName', 'id' => 'reportDataMartName', 'class' => 'form-control form-control-inline m-wrap', 'data' => '')); ?>
                    </div>
                  </div>

                </div>
                <div class="form-group row fom-row">
                  <div class="col-md-12 ">
                    <div class="float-right">
                      <div class="btn-group">
                        <button id="search-report-list" type="button" class="btn btn-xs blue"><i class="fa fa-search"></i> Хайх</button>
                        <a id="clear-report-list" type="button" href="javascript:;" class="btn btn-xs default"> Цэвэрлэх </a>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      <div class="row">
        <div class="col-md-12 jeasyuiTheme3" style="min-height: 591px;">
          <div class="table-toolbar">
            <div class="row">
              <div class="col-md-12">
                <div class="btn-group">
                    <?php
                    echo Html::anchor(
                            'javascript:;',
                            '<i class="fa fa-edit"></i> ' . $this->lang->line('edit_btn'),
                            array(
                        'class' => 'btn blue btn-sm',
                        'onclick' => 'rmReportModelEdit(this);'
                            ), $this->isEdit
                    );
                    echo Html::anchor(
                            'javascript:;',
                            '<i class="fa fa-trash"></i> ' . $this->lang->line('delete_btn'),
                            array(
                        'class' => 'btn red btn-sm',
                        'onclick' => 'rmReportModelDelete(this);'
                            ), $this->isDelete
                    );
                    ?>  
                </div>
              </div>
            </div>
          </div>
          <table id="reportDataGrid"></table>
        </div>
      </div>   
    </div>
  </div>   
</div>

<div class="hidden" >
  <div id="deleteConfirmDialog">
    <div class="col-md-12" id="confirmationText">
    </div>
  </div>
</div>

<style type="text/css">
  .jstree {
      overflow-x: auto; 
      overflow-y: hidden;
  }
  #priceKeySearchForm .form-group {
      margin-bottom: 5px !important;
  }
  #priceKeySearchForm label {
      font-size: 12px !important;
  }
  #priceKeySearchForm .form-actions {
      margin-top: 20px !important;
  }
</style>

<script type="text/javascript">
  var itemFolderTree=$('#itemFolderTreeView');
  $(function(){
//        itemFolderTree.jstree({
//            "core": {
//                "themes": {
//                    "responsive": true
//                }
//            },
//            "types": {
//                "default": {
//                    "icon": "icon-folder2 text-orange-300"
//                }
//            },
//            "plugins": ["types", "cookies"]
//        }).on('loaded.jstree', function () {
//            itemFolderTree.jstree('close_all');
//        });

    $('#reportDataGrid').datagrid({
      url: 'rmreport/reportModelDataGrid',
      rownumbers: true,
      singleSelect: true,
      pagination: true,
      pageSize: 20,
      remoteFilter: true,
      filterDelay: 10000000000,
      fit: true,
      fitColumn: true,
      striped: true,
      nowrap: false,
      pagePosition: 'both',
      frozenColumns: [[
          {field: 'REPORT_MODEL_NAME', title: 'Тайлангийн нэр', halign: 'center', sortable: true}
        ]],
      columns: [[
          {field: 'DATA_MART_NAME', title: 'Ашигласан дата март', halign: 'center', sortable: true}
        ]],
      onRowContextMenu: function(e, index, row){
        e.preventDefault();
        $(this).datagrid('selectRow', index);
//                $.contextMenu({
//                    selector: ".datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
//                    callback: function (key, opt) {
//                        else if (key === 'edit') {
//                            mdPriceSaleEdit(this);
//                        } else if (key === 'delete') {
//                            mdPriceSaleDelete(this);
//                        }
//                    },
//                    items: {
//                        "edit": {name: "Засах", icon: "edit"},
//                        "delete": {name: "Устгах", icon: "trash"}
//                    }
//                });
      },
      onLoadSuccess: function(){
//           showGridMessage($('#reportDataGrid'));
//           $('#reportDataGrid').datagrid('getPanel').children('div.datagrid-view')
//            .find(".num-field").autoNumeric('init', {aPad: false, mDec: 2, vMin: '-99999999999.99'});
//            $('#reportDataGrid').datagrid('getPanel').children('div.datagrid-view')
//            .find(".datagrid-htable")
//            .find(".datagrid-filter-row")
//            .find("input[name=SALE_PRICE], input[name=PRICE]").addClass('numberInit');
        Core.initInputType();
      }
    });
//    $('#reportDataGrid').datagrid('enableFilter', [
//        {
//            field: 'METAS',
//            type: 'label'
//        }
//    ]);
//    $(window).bind('resize', function(){
//        $('#reportDataGrid').datagrid('resize'); 
//    });
//    $('#whPriceKey').on('click', '.card-title .fullscreen', function(e){
//        $('#reportDataGrid').datagrid('resize'); 
//    });
//    
//    $("#priceKeySearchForm").on("keydown", 'input', function(e){
//        if (e.which === 13) {
//            whPriceKeyDataGridSearch();
//        }
//    });
//    
//    $("#priceKeySearchForm").find("input, select").removeClass("input-medium");
//    $("#priceKeySearchForm").find("input, select").addClass("form-control-sm");
  });
  
  $('#search-report-list').click(function() {
      $('#reportDataGrid').datagrid('load', {
        REPORT_NAME  : $('#reportName').val(),
        REPORT_DATA_MART_NAME: $('#reportDataMartName').val()
      });
    });

    $('#clear-report-list').click(function() {

      $('#reportName').val('');
      $('#reportDataMartName').val('');

      $('#reportDataGrid').datagrid('load', {
          REPORT_NAME : '',
          REPORT_DATA_MART_NAME : ''
      });

    });


  function rmReportModelEdit(elem){
    var row=$('#reportDataGrid').datagrid('getSelected');
    if(row){
      if(row.REPORT_MODEL_ID){
        console.log(row.REPORT_MODEL_ID);

        location.href="rmreport/index/?id=" + row.REPORT_MODEL_ID;
      }
    } else {
      alert("Та жагсаалтаас сонгоно уу!");
    }
  }

  function rmReportModelDelete(elem){
    var row=$('#reportDataGrid').datagrid('getSelected');
    if(row){
      if(row.REPORT_MODEL_ID){
        $(deleteConfirmDialog).dialog({
          autoOpen: false,
          title: 'Сануулга',
          width: 'auto',
          resizable: false,
          height: 'auto',
          modal: true,
          open: function(){
            $('#confirmationText').text('Та "' + row.REPORT_MODEL_NAME +
                    '" нэртэй тайланг устгах гэж байна.');
          },
          close: function(){
            $(deleteConfirmDialog).dialog('close');
          },
          buttons: [
            {text: 'Устгах', class: 'btn btn-sm red float-right', click: function(){
                $.ajax({
                  data: {REPORT_MODEL_ID: row.REPORT_MODEL_ID},
                  url: "rmreport/deleteReport",
                  dataType: "json",
                  type: "POST",
                  success: function(result){
                    new PNotify({
                      title: result.title,
                      text: result.message,
                      type: result.status,
                      sticker: false
                    });
                    if(result.status === 'success'){
                      $('#reportDataGrid').datagrid('reload');
                    }
                  },
                  error: function(jqXHR, exception){

                  }
                });
                $(deleteConfirmDialog).dialog('close');
              }
            },
            {text: 'Болих', class: 'btn btn-sm float-right', click: function(){
                $(deleteConfirmDialog).dialog('close');
              }}
          ]
        });
        $(deleteConfirmDialog).dialog('open');
      }
    } else {
      alert("Та жагсаалтаас сонгоно уу!");

    }
  }


</script>