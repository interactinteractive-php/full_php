<div class="clearfix w-100 mt10"></div>
<div  id="wfmStatusCfgUserAndRoleProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>">
    <div class="col-md-12 pl0 pr0" style="min-height: 550px; background-color: #FFF; border: 1px solid #EEE; border-radius: 3px;">
        <?php echo Form::create(array('class' => 'form-horizontal xs-form', 'id' => 'createWorkflowStatus-from', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>

        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                  <a href="#wfmStatusCfgUserProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>" id="tabid_wfmStatusCfgUserProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>" class="nav-link active" data-toggle="tab">Хэрэглэгч</a>
                </li>
                <li class="nav-item">
                    <a href="#wfmStatusCfgRoleProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>" id="tabid_wfmStatusCfgRoleProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>" data-toggle="tab" class="nav-link">Дүр</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="wfmStatusCfgUserProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group quick-item" style="width: 280px;">
                                    <div class="input-icon">
                                        <i class="fa fa-search"></i>
                                        <?php echo Form::text(array('name' => 'userQuickCode', 'id' => 'userQuickCode', 'class' => 'form-control', 'placeholder' => 'Хэрэглэгчийн нэр', 'style' => 'padding-left:33px;')); ?>
                                    </div>
                                    <span class="input-group-btn">
                                        <?php echo Form::button(array('class' => 'btn green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => "dataViewCustomSelectableGrid('sysUmUserList', 'single', 'userSelectabledGridForMain', '', this);")); ?>
                                    </span>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 jeasyuiTheme3">
                          <table class="mdWfmTransition_userlist_<?php echo $this->sourceId.'_'.$this->targetId ?>"></table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="wfmStatusCfgRoleProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group quick-item" style="width: 280px;">
                                    <div class="input-icon">
                                        <i class="fa fa-search"></i>
                                        <?php echo Form::text(array('name' => 'roleQuickCode', 'id' => 'roleQuickCode', 'class' => 'form-control', 'placeholder' => 'Дүрийн нэр', 'style' => 'padding-left:33px;')); ?>
                                    </div>
                                    <span class="input-group-btn">
                                        <?php echo Form::button(array('class' => 'btn green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => "dataViewCustomSelectableGrid('sysUmRoleList', 'single', 'roleSelectabledGridForMain', '', this);")); ?>
                                    </span>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 jeasyuiTheme3">
                          <table class="mdWfmTransition_rolelist_<?php echo $this->sourceId.'_'.$this->targetId ?>"></table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php echo Form::hidden(array('name' => 'wfmStatusId', 'value' => $this->wfmStatusId)); ?>
        <?php echo Form::close(); ?>  
    </div>
</div>
<script type="text/javascript">
    $(function() {
        
        $('.mdWfmTransition_userlist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid({
            url: 'mdprocessflow/getWfmTransitionUserList',
            queryParams: {transitionId: '<?php echo $this->transitionId; ?>' }, 
            resizeHandle: 'right',
            fitColumns: true,
            autoRowHeight: true,
            striped: false,
            method: 'post',
            nowrap: true,
            pagination: false,
            rownumbers: true,
            singleSelect: false,
            ctrlSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            pagePosition: 'bottom',
            pageNumber: 1,
            pageSize: 30,
            pageList: [10, 30,40,50,100,200],
            multiSort: false,
            remoteSort: true,
            showHeader: true,
            showFooter: false,
            scrollbarSize: 18,            
            remoteFilter: true,
            filterDelay: 10000000000,
            frozenColumns: [[
                {field: 'ck', checkbox: true}
            ]],
            columns: [[
                {field:'USERNAME',title:'Хэрэглэгчийн нэр',sortable:true,fixed:true,width: '80%', halign: 'center',align: 'left'},
            ]],
            onCheckAll: function() {
                $.uniform.update();
            },
            onUncheckAll: function() {
                $.uniform.update();
            },
            onClickRow: function(index, row) {
                $("#currentSelectedRowIndex", "#object-value-list").val(index);
                $.uniform.update();
            },
            onCollapseRow: function(index, row) {
                $('.save_btn_'+ row.SALES_INVOICE_ID +'').addClass('hidden');
            },
            onLoadSuccess: function (data) {
              if (data.status === 'error') {
                  PNotify.removeAll();
                  new PNotify({
                      title: 'Error',
                      text: data.message,
                      type: 'error',
                      sticker: false
                  });
              }
              var _thisGrid = $(this);
              showGridMessage(_thisGrid);
              if (_thisGrid.datagrid('getRows').length == 0) {
                  var tr = _thisGrid.datagrid('getPanel').children('div.datagrid-view')
                            .find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table")
                            .find("tbody tr");
                  $(tr).find('td').find('div').find('span').each(function () {
                      this.remove();
                  });
              }
              _thisGrid.datagrid("getPanel").children("div.datagrid-view")
                          .find(".datagrid-htable")
                          .find(".datagrid-filter-row")
                          .find("input[name=workstartdate]").addClass("dateMaskInit");                                                    
              Core.initInputType();
              $(this).datagrid('resize');
            }
      });
        $('.mdWfmTransition_rolelist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid({
            url: 'mdprocessflow/getWfmTransitionRoleList',
            queryParams: {transitionId: '<?php echo $this->transitionId; ?>' }, 
            resizeHandle: 'right',
            fitColumns: true,
            autoRowHeight: true,
            striped: false,
            method: 'post',
            nowrap: true,
            pagination: false,
            rownumbers: true,
            singleSelect: false,
            ctrlSelect: true,
            checkOnSelect: true,
            selectOnCheck: false,
            pagePosition: 'bottom',
            pageNumber: 1,
            pageSize: 30,
            pageList: [10, 30,40,50,100,200],
            multiSort: false,
            remoteSort: true,
            showHeader: true,
            showFooter: false,
            scrollbarSize: 18,            
            remoteFilter: true,
            filterDelay: 10000000000,
            frozenColumns: [[
                {field: 'ck', checkbox: true}
            ]],
            columns: [[
                {field:'ROLENAME',title:'Дүр',sortable:true,fixed:true, width: '78%', halign: 'center',align: 'left',},
            ]],
            onCheckAll: function() {
                $.uniform.update();
            },
            onUncheckAll: function() {
                $.uniform.update();
            },
            onClickRow: function(index, row) {
                $("#currentSelectedRowIndex", "#object-value-list").val(index);
                $.uniform.update();
            },
            onCollapseRow: function(index, row) {
                $('.save_btn_'+ row.SALES_INVOICE_ID +'').addClass('hidden');
            },
            onLoadSuccess: function (data) {
              if (data.status === 'error') {
                  PNotify.removeAll();
                  new PNotify({
                      title: 'Error',
                      text: data.message,
                      type: 'error',
                      sticker: false
                  });
              }
              var _thisGrid = $(this);
              showGridMessage(_thisGrid);
              if (_thisGrid.datagrid('getRows').length == 0) {
                  var tr = _thisGrid.datagrid('getPanel').children('div.datagrid-view')
                            .find(".datagrid-view2").find(".datagrid-footer").find(".datagrid-footer-inner table")
                            .find("tbody tr");
                  $(tr).find('td').find('div').find('span').each(function () {
                      this.remove();
                  });
              }
              _thisGrid.datagrid("getPanel").children("div.datagrid-view")
                          .find(".datagrid-htable")
                          .find(".datagrid-filter-row")
                          .find("input[name=workstartdate]").addClass("dateMaskInit");                                                    
              Core.initInputType();
              $(this).datagrid('resize');
            }
      });
        
        $('#wfmStatusCfgUserProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>').on("focus", 'input#userQuickCode', function(e) {
            var _this = $(this);
            _this.autocomplete({
                minLength: 1,
                maxShowItems: 7,
                delay: 0,
                highlightClass: "lookup-ac-highlight", 
                appendTo: "body",
                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                autoFocus: true,
                source: function(request, response) {
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/filterUserInfo',
                        dataType: "json",
                        data: {
                            q: request.term
                        },
                        success: function(data) {
                            if (data.items) {
                                response($.map(data.items, function(item) {
                                    return {
                                        label: item.USERFULLNAME,
                                        name: item.USERNAME,
                                        data: item
                                    };
                                }));    
                            }
                            
                        }
                    });
                },
                focus: function() {
                    return false;
                },
                close: function (event, ui){
                    $(this).autocomplete("option","appendTo","body"); 
                }, 
                select: function(event, ui) {
                    var origEvent = event;
                    var data = ui.item.data;
                    addUserDtlWithAccountValue(data);

                    while (origEvent.originalEvent !== undefined){
                        origEvent = origEvent.originalEvent;
                    }
                    _this.val("");
                    return false;                    
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            };    
        });
        $('#wfmStatusCfgRoleProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>').on("focus", 'input#roleQuickCode', function(e) {
            var _this = $(this);
            _this.autocomplete({
                minLength: 1,
                maxShowItems: 7,
                delay: 0,
                highlightClass: "lookup-ac-highlight", 
                appendTo: "body",
                position: {my : "left top", at: "left bottom", collision: "flip flip"}, 
                autoFocus: true,
                source: function(request, response) {
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/filterRoleInfo',
                        dataType: "json",
                        data: {
                            q: request.term
                        },
                        success: function(data) {
                            if (data.items) {
                                response($.map(data.items, function(item) {
                                    return {
                                        label: item.ROLECODE,
                                        name: item.ROLENAME,
                                        data: item
                                    };
                                }));    
                            }
                            
                        }
                    });
                },
                focus: function() {
                    return false;
                },
                close: function (event, ui){
                    $(this).autocomplete("option","appendTo","body"); 
                }, 
                select: function(event, ui) {
                    var origEvent = event;
                    var data = ui.item.data;
                    addRoleDtlWithAccountValue(data);

                    while (origEvent.originalEvent !== undefined){
                        origEvent = origEvent.originalEvent;
                    }
                    _this.val("");
                    return false;                    
                }
            }).autocomplete("instance")._renderItem = function(ul, item) {
                ul.addClass('lookup-ac-render');

                var re = new RegExp("(" + this.term + ")", "gi"),
                    cls = this.options.highlightClass,
                    template = "<span class='" + cls + "'>$1</span>",
                    label = item.label.replace(re, template);

                return $('<li>').append('<div class="lookup-ac-render-code">'+label+'</div><div class="lookup-ac-render-name">'+item.name+'</div>').appendTo(ul);
            };    
        });
        
        
        $('#tabid_wfmStatusCfgUserProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>').click(function () {
            $('.mdWfmTransition_userlist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid('reload');
            $('.mdWfmTransition_userlist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid('resize');
        });
        $('#tabid_wfmStatusCfgRoleProcess_<?php echo $this->sourceId.'_'.$this->targetId ?>').click(function () {
            $('.mdWfmTransition_rolelist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid('reload');
            $('.mdWfmTransition_rolelist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid('resize');
        });
    });
    
    function addUserDtlWithAccountValue (data) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addTransitionUserPermission',
            data: {userId: data.ID, wfmStatusId: '<?php echo $this->wfmStatusId ?>', sourceId: '<?php echo $this->sourceId ?>', targetId: '<?php echo $this->targetId ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    target: "#workFlowEditor",
                    animate: true
                });
            },
            success: function (data) {
                if (data.status === 'success') {
                    $('.mdWfmTransition_userlist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid('reload');
                }
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                Core.unblockUI('#workFlowEditor');
            },
            error: function () {
            }
        });   
    }
    
    function addRoleDtlWithAccountValue (data) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addTransitionRolePermission',
            data: {roleId: data.ID, wfmStatusId: '<?php echo $this->wfmStatusId ?>', sourceId: '<?php echo $this->sourceId ?>', targetId: '<?php echo $this->targetId ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    target: "#workFlowEditor",
                    animate: true
                });
                if (data.status === 'success') {
                    $('.mdWfmTransition_rolelist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid('reload');
                }
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            },
            success: function (data) {
                Core.unblockUI('#workFlowEditor');
            },
            error: function () {
            }
        });   
    }
    function userSelectabledGridForMain (metaDataCode, chooseType, elem, rows) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addTransitionUserPermission',
            data: {userId: rows[0].id, wfmStatusId: '<?php echo $this->wfmStatusId ?>', sourceId: '<?php echo $this->sourceId ?>', targetId: '<?php echo $this->targetId ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    target: "#workFlowEditor",
                    animate: true
                });
            },
            success: function (data) {
                if (data.status === 'success') {
                    $('.mdWfmTransition_userlist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid('reload');
                }
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                Core.unblockUI('#workFlowEditor');
            },
            error: function () {
            }
        });  
    }
    
    function roleSelectabledGridForMain (metaDataCode, chooseType, elem, rows) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addTransitionRolePermission',
            data: {roleId: rows[0].id, wfmStatusId: '<?php echo $this->wfmStatusId ?>', sourceId: '<?php echo $this->sourceId ?>', targetId: '<?php echo $this->targetId ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    target: "#workFlowEditor",
                    animate: true
                });
            },
            success: function (data) {
                Core.unblockUI('#workFlowEditor');
                if (data.status === 'success') {
                    $('.mdWfmTransition_rolelist_<?php echo $this->sourceId.'_'.$this->targetId ?>').datagrid('reload');
                }
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            },
            error: function () {
            }
        });  
    }
</script>