<div class="col-md-12 pl0 pr0" style="background-color: #FFF;" id="wfmStatusCfgUserAndRoleProcess_<?php echo $this->wfmStatusId ?>">
    <?php echo Form::create(array('class' => 'form-horizontal xs-form', 'id' => 'createWorkflowStatus-from', 'method' => 'post', 'enctype' => 'multipart/form-data')); ?>
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#wfmStatusCfgRoleProcess_<?php echo $this->wfmStatusId ?>" id="tabid_wfmStatusCfgRoleProcess_<?php echo $this->wfmStatusId ?>" class="nav-link active" data-toggle="tab">Дүр</a>
                </li>
                <li class="nav-item">
                  <a href="#wfmStatusCfgUserProcess_<?php echo $this->wfmStatusId ?>" id="tabid_wfmStatusCfgUserProcess_<?php echo $this->wfmStatusId ?>" data-toggle="tab" class="nav-link">Хэрэглэгч</a>
                </li>
                <li class="nav-item">
                    <a href="#wfmStatusCfgAssignmentProcess_<?php echo $this->wfmStatusId ?>" id="tabid_wfmStatusCfgAssignmentProcess_<?php echo $this->wfmStatusId ?>" data-toggle="tab" class="nav-link">Assignment</a>
                </li>
                <li class="nav-item">
                    <a href="#wfmStatusLink_<?php echo $this->wfmStatusId ?>" id="tabid_wfmStatusLink_<?php echo $this->wfmStatusId ?>" data-toggle="tab" class="nav-link">Тохиргоо</a>
                </li>
                <li class="nav-item">
                    <a href="#wfmStatusCfgStatuses_<?php echo $this->wfmStatusId ?>" id="tabid_wfmStatusCfgStatusProcess_<?php echo $this->wfmStatusId ?>" class="nav-link" data-toggle="tab">Төлөв өөрчлөх</a>
                </li>                
            </ul>
            <div class="tab-content">
                <div class="tab-pane active" id="wfmStatusCfgRoleProcess_<?php echo $this->wfmStatusId ?>">
                    <div class="table-toolbar wfm-status-add-toolbar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group quick-item" style="width: 280px;">
                                    <div class="input-icon">
                                        <i class="fa fa-search"></i>
                                        <?php echo Form::text(array('name' => 'roleQuickCode', 'id' => 'roleQuickCode', 'class' => 'form-control', 'placeholder' => 'Дүрийн нэр', 'style' => 'padding-left:33px;')); ?>
                                    </div>
                                    <span class="input-group-btn">
                                        <?php echo Form::button(array('class' => 'btn green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => "dataViewCustomSelectableGrid('sysUmRoleList', 'multi', 'roleSelectabledGridForMain', '', this);")); ?>
                                    </span>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 jeasyuiTheme3" id="table_mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>">
                          <table id="mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>"></table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="wfmStatusCfgUserProcess_<?php echo $this->wfmStatusId ?>">
                    <div class="table-toolbar wfm-status-add-toolbar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group quick-item" style="width: 280px;">
                                    <div class="input-icon">
                                        <i class="fa fa-search"></i>
                                        <?php echo Form::text(array('name' => 'userQuickCode', 'id' => 'userQuickCode', 'class' => 'form-control', 'placeholder' => 'Хэрэглэгчийн нэр', 'style' => 'padding-left:33px;')); ?>
                                    </div>
                                    <span class="input-group-btn">
                                        <?php echo Form::button(array('class' => 'btn green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => "dataViewCustomSelectableGrid('sysUmUserList', 'multi', 'userSelectabledGridForMain', '', this);")); ?>
                                    </span>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 jeasyuiTheme3" id="table_mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>">
                          <table id="mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>"></table>
                        </div>
                    </div>
                </div>
                <div class="tab-pane" id="wfmStatusCfgAssignmentProcess_<?php echo $this->wfmStatusId ?>">
                    <div class="table-toolbar wfm-status-add-toolbar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group quick-item" style="width: 280px;">
                                    <div class="input-icon">
                                        <i class="fa fa-search"></i>
                                        <?php echo Form::text(array('name' => 'userQuickCode', 'id' => 'userQuickCode', 'class' => 'form-control', 'placeholder' => 'Хэрэглэгчийн нэр', 'style' => 'padding-left:33px;')); ?>
                                    </div>
                                    <span class="input-group-btn">
                                        <?php echo Form::button(array('class' => 'btn green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => "dataViewCustomSelectableGrid('sysUmUserList', 'multiple', 'userAssignmentSelectabledGridForMain', '', this);")); ?>
                                    </span>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 jeasyuiTheme3" id="table_mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?>">
                          <table class="mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?>"></table>
                        </div>
                    </div>
                </div>                
                <div class="tab-pane" id="wfmStatusLink_<?php echo $this->wfmStatusId ?>">
                    
                    <div class="table-toolbar wfm-status-add-toolbar">
                        <div class="row">
                            <div class="col-md-6">
                                <?php echo Form::button(array('class' => 'btn btn-success btn-circle btn-sm addWfmStatusLink_'.$this->wfmStatusId, 'value' => '<i class="icon-plus3 font-size-12"></i> Нэмэх')); ?>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 jeasyuiTheme3" id="table_mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>">
                          <table class="mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>"></table>
                        </div>
                    </div>
                </div>                
                <div class="tab-pane" id="wfmStatusCfgStatuses_<?php echo $this->wfmStatusId ?>">
                    <div class="table-toolbar wfm-status-add-toolbar">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="input-group quick-item" style="width: 280px;">
                                    <div class="input-icon">
                                        <i class="fa fa-search"></i>
                                        <?php echo Form::text(array('name' => 'statusQuickCode', 'id' => 'statusQuickCode', 'class' => 'form-control', 'placeholder' => 'Төлөвийн нэр', 'style' => 'padding-left:33px;')); ?>
                                    </div>
                                    <span class="input-group-btn">
                                        <?php echo Form::button(array('class' => 'btn green-meadow', 'value' => '<i class="icon-plus3 font-size-12"></i>', 'onclick' => "dataViewCustomSelectableGrid('META_WFM_INHERITANCE_LOOKUP', 'multi', 'statusSelectabledGridForMain', '', this);")); ?>
                                    </span>
                                </div>
                            </div>    
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 jeasyuiTheme3" id="table_mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>">
                          <table id="mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>"></table>
                        </div>
                    </div>
                </div>                
            </div>
        </div>
    <?php 
    echo Form::hidden(array('name' => 'wfmStatusId', 'value' => $this->wfmStatusId)); 
    echo Form::close(); 
    ?>  
</div>
<script type="text/javascript">
    var _lastIndex<?php echo $this->wfmStatusId ?> = '0';
    $(function() {
        
        $('#mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>').datagrid({
            url: 'mdprocessflow/getWfmStatusUserList',
            queryParams: {wfmStatusId: '<?php echo $this->wfmStatusId; ?>' }, 
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
            selectOnCheck: true,
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
                {field:'USERNAME',title:'Хэрэглэгчийн нэр',sortable:true,fixed:true,width: '70%', halign: 'center',align: 'left'},
                {field:'IS_EDIT',title:'<i class="fa fa-check-square-o"></i>',width: '10%', halign: 'center',align: 'center',
                    formatter: function (value, row) { 
                        
                        if (!isWfmShowOnly && !isWfmLock) {
                            var fncName = 'clickFunction';
                        } else {
                            var fncName = 'noAction';
                        }
                        
                        switch(value) {
                            case '1':
                                return '<i class="fa fa-eye" onclick="'+fncName+'_<?php echo $this->wfmStatusId ?>(this, '+ row.ID +', 2);"></i>'; 
                                break;
                            case '2':
                                return '<i class="fa fa-pencil" onclick="'+fncName+'_<?php echo $this->wfmStatusId ?>(this, '+ row.ID +', 0);"></i>'; 
                                break;
                            default:
                                return '<i class="fa fa-eye-slash" onclick="'+fncName+'_<?php echo $this->wfmStatusId ?>(this, '+ row.ID +', 1);"></i>'; 
                        }
                    }
                } 
            ]],
            onClickRow: function(index, row) {
                $("#currentSelectedRowIndex", "#object-value-list").val(index);
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
                
                var $this = $(this);
                
                if ($this.datagrid('getSelections').length == 1) {
                    $this.datagrid('unselectAll');
                }
                
                $this.datagrid('selectRow', index);
                
                $.contextMenu({
                    selector: "div#table_mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#table_mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    events: {
                        show: function(opt) {
                            if (!isWfmShowOnly && !isWfmLock) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    items: {
                        "04": {name: "Устгах", icon: "trash", callback: function(key, options) {
                            removeWfmStatusUserPermission_<?php echo $this->wfmStatusId ?>(key);
                        }}
                    }
                });
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
                showGridMessage(_thisGrid, '');
                
                var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
                var $panelFilterRow = $panelView.find('.datagrid-filter-row');
                
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
                
                initDVClearColumnFilterBtn($panelView, $panelFilterRow);      
                _thisGrid.datagrid('resize');
            }
        });
        $('#mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>').datagrid('enableFilter', [{field: 'IS_EDIT', type: 'label'}]);
        
        $('.mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?>').datagrid({
            url: 'mdprocessflow/getWfmStatusAssignmentList',
            queryParams: {wfmStatusId: '<?php echo $this->wfmStatusId; ?>' }, 
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
                {field:'username',title:'Хэрэглэгчийн нэр',sortable:true,fixed:true,width: '50%', halign: 'center',align: 'left'},
                {field:'dueperiod',title:'Хугацаа',sortable:true,fixed:true,width: '25%', halign: 'center',align: 'left'},
                {field:'isneedsign',title:'Гарын үсэгтэй эсэх',sortable:true,fixed:true, halign: 'center',align: 'center',formatter(value, row, index){
                    if(typeof value === 'undefined' || value === null || value === '0')
                        return '';        
                    return '<i class="fa fa-check"></i>';
                }}
            ]],
            onClickRow: function(index, row) {
                $("#currentSelectedRowIndex", "#object-value-list").val(index);
            },
            /*onRowContextMenu: function (e, index, row) {
                e.preventDefault();
                $.contextMenu({
                    selector: "div#table_mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#table_mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    items: {
                        "04": {name: "Устгах", icon: "trash", callback: function(key, options) {
                                removeWfmStatusAssignmentPermission_<?php echo $this->wfmStatusId ?>(options.$trigger, key);
                            }}
                    }
                });
            },*/
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
                _thisGrid.datagrid('resize');
            }
        });
        $('#mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>').datagrid({
            url: 'mdprocessflow/getWfmStatusRoleList',
            queryParams: {wfmStatusId: '<?php echo $this->wfmStatusId; ?>' }, 
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
            selectOnCheck: true,
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
                {field:'ROLENAME',title:'Дүр',sortable:true,fixed:true, width: '68%', halign: 'center',align: 'left', formatter: function (value, row) {
                    return '<a href="javascript:;" onclick="userRoleDataSelectableGrid(\''+row.ROLE_ID+'\')">'+value+'</a>';
                }},
                {field:'IS_EDIT',title:'<i class="fa fa-check-square-o"></i>',width: '10%', halign: 'center',align: 'center',
                    formatter: function (value, row) {
                        
                        if (!isWfmShowOnly && !isWfmLock) {
                            var fncName = 'clickFunction';
                        } else {
                            var fncName = 'noAction';
                        }
                        
                        switch(value) {
                            case '1':
                                return '<i class="fa fa-eye" onclick="'+fncName+'_<?php echo $this->wfmStatusId; ?>(this, '+ row.ID +', 2);"></i>'; 
                                break;
                            case '2':
                                return '<i class="fa fa-pencil" onclick="'+fncName+'_<?php echo $this->wfmStatusId; ?>(this, '+ row.ID +', 0);"></i>'; 
                                break;
                            default:
                                return '<i class="fa fa-eye-slash" onclick="'+fncName+'_<?php echo $this->wfmStatusId; ?>(this, '+ row.ID +', 1);"></i>'; 
                        }
                    }
                } 
            ]],
            onClickRow: function(index, row) {
                $("#currentSelectedRowIndex", "#object-value-list").val(index);
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
                
                var $this = $(this);
                
                if ($this.datagrid('getSelections').length == 1) {
                    $this.datagrid('unselectAll');
                }
                
                $this.datagrid('selectRow', index);
                
                $.contextMenu({
                    selector: "div#table_mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#table_mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    events: {
                        show: function(opt) {
                            if (!isWfmShowOnly && !isWfmLock) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    items: {
                        "04": {name: plang.get('delete_btn'), icon: "trash", callback: function(key, options) {
                            removeWfmStatusRolePermission_<?php echo $this->wfmStatusId ?>(key);
                        }}
                    }
                });
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
                showGridMessage(_thisGrid, '');
              
                var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
                var $panelFilterRow = $panelView.find('.datagrid-filter-row');
            
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
                
                initDVClearColumnFilterBtn($panelView, $panelFilterRow);      
                _thisGrid.datagrid('resize');
            }
        });
        $('#mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>').datagrid({
            url: 'mdprocessflow/getWfmStatusStatusList',
            queryParams: {wfmStatusId: '<?php echo $this->wfmStatusId; ?>' }, 
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
            selectOnCheck: true,
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
                {field:'statusname',title:'Төлөвийн нэр',sortable:false,fixed:true, width: '50%', halign: 'center',align: 'left'}, 
                {field:'wfmworkflowname',title:'Бүтэцийн нэр',sortable:false,fixed:true, width: '50%', halign: 'center',align: 'left'}
            ]],
            onClickRow: function(index, row) {
                $("#currentSelectedRowIndex", "#object-value-list").val(index);
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
                
                var $this = $(this);
                
                if ($this.datagrid('getSelections').length == 1) {
                    $this.datagrid('unselectAll');
                }
                
                $this.datagrid('selectRow', index);
                
                $.contextMenu({
                    selector: "div#table_mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#table_mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    events: {
                        show: function(opt) {
                            if (!isWfmShowOnly && !isWfmLock) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    items: {
                        "04": {name: plang.get('delete_btn'), icon: "trash", callback: function(key, options) {
                            removeWfmStatusStatusPermission_<?php echo $this->wfmStatusId ?>(key);
                        }}
                    }
                });
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
                showGridMessage(_thisGrid, '');
              
                var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
                var $panelFilterRow = $panelView.find('.datagrid-filter-row');
            
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
                
                initDVClearColumnFilterBtn($panelView, $panelFilterRow);      
                _thisGrid.datagrid('resize');
            }
        });
        $('#mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>').datagrid('enableFilter', [{field: 'IS_EDIT', type: 'label'}]);
        
        $('.mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>').datagrid({
            url: 'mdprocessflow/getWfmStatusLinkList',
            queryParams: {wfmStatusId: '<?php echo $this->wfmStatusId; ?>' }, 
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
                {field:'CRITERIA',title:'<?php echo $this->lang->line('criteria') ?>',sortable:true,fixed:true, width: '40%', halign: 'center',align: 'left',},
                {field:'DESCRIPTION',title:'<?php echo $this->lang->line('description') ?>',sortable:true,fixed:true, width: '40%', halign: 'center',align: 'left',},
            ]],
            onClickRow: function(index, row) {
                $("#currentSelectedRowIndex", "#object-value-list").val(index);
            },
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
                
                $.contextMenu({
                    selector: "div#table_mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, div#table_mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?> .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                    events: {
                        show: function(opt) {
                            if (!isWfmShowOnly && !isWfmLock) {
                                return true;
                            } else {
                                return false;
                            }
                        }
                    },
                    items: {
                        "05": {name: "Засах", icon: "edit", callback: function(key, options) {
                            editWfmStatusLinkData_<?php echo $this->wfmStatusId ?>(options.$trigger, key);
                        }},
                        "04": {name: "Устгах", icon: "trash", callback: function(key, options) {
                            removeWfmStatusLinkData_<?php echo $this->wfmStatusId ?>(options.$trigger, key);
                        }}
                    }
                });
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
                _thisGrid.datagrid('resize');
            }
        });              
        
        $('#wfmStatusCfgUserProcess_<?php echo $this->wfmStatusId ?>').on("focus", 'input#userQuickCode', function(e) {
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
        $('#wfmStatusCfgAssignmentProcess_<?php echo $this->wfmStatusId ?>').on("focus", 'input#userQuickCode', function(e) {
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
                    var userId = [];
                    userId.push({id: data.ID});
                    userAssignmentSelectabledGridForMain('', '', '', userId);

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
        $('#wfmStatusCfgRoleProcess_<?php echo $this->wfmStatusId ?>').on("focus", 'input#roleQuickCode', function(e) {
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
        $('#wfmStatusCfgStatuses_<?php echo $this->wfmStatusId ?>').on("focus", 'input#statusQuickCode', function(e) {
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
                        url: 'mdprocessflow/filterStatusInfo',
                        dataType: "json",
                        data: {
                            q: request.term
                        },
                        success: function(data) {
                            if (data.items) {
                                response($.map(data.items, function(item) {
                                    return {
                                        name: item.WFMWORKFLOWNAME,
                                        label: item.STATUSNAME,
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
                    addStatusDtlWithAccountValue(data);

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
        
        $('#tabid_wfmStatusCfgUserProcess_<?php echo $this->wfmStatusId ?>').click(function () {
            $('#mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
            $('#mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>').datagrid('resize');
        });
        $('#tabid_wfmStatusCfgRoleProcess_<?php echo $this->wfmStatusId ?>').click(function () {
            $('#mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
            $('#mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>').datagrid('resize');
        });
        $('#tabid_wfmStatusCfgStatusProcess_<?php echo $this->wfmStatusId ?>').click(function () {
            $('#mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
            $('#mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>').datagrid('resize');
        });
        $('#tabid_wfmStatusCfgAssignmentProcess_<?php echo $this->wfmStatusId ?>').click(function () {
            $('.mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
            $('.mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?>').datagrid('resize');
        });
        $('#tabid_wfmStatusLink_<?php echo $this->wfmStatusId ?>').click(function () {
            $('.mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>').datagrid('reload');
            $('.mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>').datagrid('resize');
        });
        
        $('.addWfmStatusLink_<?php echo $this->wfmStatusId ?>').click(function () {
            var html = '<table class="table table-sm table-no-bordered" style="table-layout: fixed !important">'
                            + '<tbody>'
                                + '<tr style="border-bottom: 1px solid #CCC;">'
                                    + '<td>'
                                        + '<label class="text font-weight-bold" style="color: #FF5722;"><?php echo $this->lang->line('criteria') ?></label>'
                                    + '</td>'
                                + '</tr>'
                                + '<tr>'
                                    + '<td class="middle" style="width: 100%" colspan="">'
                                        + '<div data-section-path="linkCriteria">'
                                            + '<textArea type="text" id="linkCriteria" name="linkCriteria" placeholder="<?php echo $this->lang->line('criteria') ?>" class="form-control form-control-sm" required="required"></textArea>'
                                        + '</div>'
                                    + '</td>'
                                + '</tr>'
                                + '<tr style="border-bottom: 1px solid #CCC;">'
                                    + '<td >'
                                        + '<label class="text font-weight-bold" style="color: #FF5722;"><?php echo $this->lang->line('description') ?></label>'
                                    + '</td>'
                                + '</tr>'
                                + '<tr>'
                                    + '<td class="middle" style="width: 100%" colspan="">'
                                        + '<div data-section-path="linkDescription">'
                                            + '<textArea type="text" id="linkDescription" name="linkDescription" placeholder="<?php echo $this->lang->line('description') ?>" class="form-control form-control-sm" required="required"></textArea>'
                                        + '</div>'
                                    + '</td>'
                                + '</tr>'
                            + '</tbody>'
                        + '</table>';
            var $dialogName = 'dialog-wfmstatus-link-<?php echo $this->wfmStatusId ?>';
            if (!$($dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo("body");
            }    
            var $dialog = $("#" + $dialogName);
            $dialog.empty().append(html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: "Тохиргоо",
                width: 350,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },                        
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm blue', click: function() {
                        $.ajax({
                            type: 'post',
                            url: 'mdprocessflow/saveMetaStatusLinkData',
                            data: {
                                linkCriteria: $('#linkCriteria').val(), 
                                linkDescription: $('#linkDescription').val(), 
                                wfmStatusId: '<?php echo $this->wfmStatusId; ?>'
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                Core.blockUI({target: "#workFlowEditor", animate: true});
                            },
                            success: function (data) {
                                if (data.status === 'success') {
                                    $('.mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>').datagrid('reload');
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
                        $dialog.dialog('close');
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');    
        });
    });
    
    function userRoleDataSelectableGrid(roleId) {

        var $dialogName = 'dialog-commonmetadata-userrole';
        if (!$("#" + $dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo('body');
        }
        var $dialog = $('#' + $dialogName);

        $dialog.empty().append('<div class="row">'+
                '<div class="col-md-12 jeasyuiTheme3" id="table_mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>">'+
                  '<table id="mdWfmStatus_userrolelist_<?php echo $this->wfmStatusId ?>"></table>'+
                '</div>'+
            '</div>');
    
        $dialog.dialog({
            cache: false,
            resizable: false,
            bgiframe: true,
            autoOpen: false,
            title: 'Хэрэглэгчийн жагсаалт',
            position: { my: "top", at: "top+50" },
            width: 800,
            height: "auto",
            modal: true,
            open: function() {
                disableScrolling();
            },
            close: function() {
                enableScrolling();
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {
                    text: plang.get('close_btn'),
                    class: 'btn blue-hoki btn-sm',
                    click: function() {
                        $dialog.dialog('close');
                    }
                }
            ]
        });
        $dialog.dialog('open');
        Core.unblockUI();

        $('#mdWfmStatus_userrolelist_<?php echo $this->wfmStatusId ?>').datagrid({
            url: 'mdprocessflow/getWfmStatusUserListByRole',
            queryParams: {roleId: roleId }, 
            resizeHandle: 'right',
            fitColumns: true,
            autoRowHeight: true,
            striped: false,
            method: 'post',
            nowrap: true,
            pagination: false,
            rownumbers: true,
            singleSelect: false,
            ctrlSelect: false,
            checkOnSelect: false,
            selectOnCheck: false,
            pagePosition: 'bottom',
            pageNumber: 1,
            pageSize: 100,
            pageList: [10, 30,40,50,100,200],
            multiSort: false,
            remoteSort: true,
            showHeader: true,
            showFooter: false,
            scrollbarSize: 18,            
            remoteFilter: true,
            filterDelay: 10000000000,
            frozenColumns: [],
            columns: [[
                {field:'USERNAME',title:'Нэр',sortable:true,fixed:true, width: '50%', halign: 'center',align: 'left'},
                {field:'DEPARTMENT_NAME',title:'Хэлтэс',sortable:true,fixed:true, width: '48%', halign: 'center',align: 'left'} 
            ]],
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
                showGridMessage(_thisGrid, '');

                var $panelView = _thisGrid.datagrid('getPanel').children('div.datagrid-view');
                var $panelFilterRow = $panelView.find('.datagrid-filter-row');

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

                initDVClearColumnFilterBtn($panelView, $panelFilterRow);      
                _thisGrid.datagrid('resize');
            }
        });       
    }      
    
    function addUserDtlWithAccountValue (data) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addStatusUserPermission',
            data: {userId: data.ID, wfmStatusId: '<?php echo $this->wfmStatusId ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (data) {
                if (data.status === 'success') {
                    $('#mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
                }
                /*
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                */
                Core.unblockUI();
            },
            error: function () {
            }
        });   
    }
    
    function addRoleDtlWithAccountValue (data) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addStatusRolePermission',
            data: {roleId: data.ID, wfmStatusId: '<?php echo $this->wfmStatusId ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (data) {
                if (data.status === 'success') {
                    $('#mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
                }
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                Core.unblockUI();
            }
        });   
    }
    
    function addStatusDtlWithAccountValue (data) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addStatusStatusPermission',
            data: {rows: [data], wfmStatusId: '<?php echo $this->wfmStatusId ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({animate: true});
            },
            success: function (data) {
                if (data.status === 'success') {
                    $('#mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
                }
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                Core.unblockUI();
            }
        });   
    }
    
    function userSelectabledGridForMain(metaDataCode, chooseType, elem, rows) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addStatusUserPermission',
            data: {rows: rows, wfmStatusId: '<?php echo $this->wfmStatusId ?>'},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    target: "#workFlowEditor",
                    animate: true
                });
            },
            success: function (data) {
                if (data.status === 'success') {
                    $('#mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
                }
                /*
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
                */
                Core.unblockUI('#workFlowEditor');
            },
            error: function () {
            }
        });  
    }
    
    function userAssignmentSelectabledGridForMain (metaDataCode, chooseType, elem, rows) {
        var $dialogName = 'dialog-wfmstatus-assignment';
        if (!$($dialogName).length) {
            $('<div id="' + $dialogName + '"></div>').appendTo("body");
        }    
        var htmlForm = '<label class="float-left">Хугацаа:</label> <input type="text" id="due_period" name="due_period" style="width: 150px; margin-left: 90px;" class="form-control form-control-sm float-left"/><div class="clearfix w-100"></div>';
        htmlForm += '<label class="float-left">Гарын үсэгтэй эсэх:</label> <input type="checkbox" id="is_need_sign" name="is_need_sign" style="margin-left: 22px;margin-top: -2px;" class="form-control-sm float-left"/>';
        $("#" + $dialogName).empty().html(htmlForm);
        $("#" + $dialogName).dialog({
            appendTo: "body",
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: "Create Assignment",
            width: 350,
            height: 'auto',
            modal: true,
            close: function(){
                $("#" + $dialogName).empty().dialog('destroy').remove();
            },                        
            buttons: [
                {text: plang.get('save_btn'), class: 'btn btn-sm blue', click: function() {
                    var due_period = $("#due_period", "#" + $dialogName).val();
                    var is_need_sign = $("#is_need_sign", "#" + $dialogName).is(':checked') ? '1' : '0';
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/addUserAssignment',
                        data: {
                            userId: rows, 
                            due_period: due_period, 
                            is_need_sign: is_need_sign, 
                            wfmStatusId: '<?php echo $this->wfmStatusId ?>'
                        },
                        dataType: 'json',
                        beforeSend: function () {
                            Core.blockUI({animate: true});
                        },
                        success: function (data) {
                            if (data.status === 'success') {
                                $('.mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
                            }
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                            Core.unblockUI();
                        },
                        error: function () {
                        }
                    });  
                    $("#" + $dialogName).dialog('close');
                }},
                {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                    $("#" + $dialogName).dialog('close');
                }}
            ]
        });
        $("#" + $dialogName).dialog('open');        
    }
    
    function roleSelectabledGridForMain(metaDataCode, chooseType, elem, rows) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addStatusRolePermission',
            data: {rows: rows, wfmStatusId: '<?php echo $this->wfmStatusId ?>'},
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
                    $('#mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
                }
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            },
            error: function () {}
        });  
    }
    
    function statusSelectabledGridForMain(metaDataCode, chooseType, elem, rows) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/addStatusStatusPermission',
            data: {rows: rows, wfmStatusId: '<?php echo $this->wfmStatusId ?>'},
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
                    $('#mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
                }
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false
                });
            },
            error: function () {}
        });  
    }
    
    function removeWfmStatusAssignmentPermission_<?php echo $this->wfmStatusId ?> (key) {
        var datagrid = $('.mdWfmStatus_assignmentlist_<?php echo $this->wfmStatusId ?>');
        deleteWfmStatusPermission_<?php echo $this->wfmStatusId ?>(datagrid);
    }
    
    function removeWfmStatusUserPermission_<?php echo $this->wfmStatusId ?> (key) {
        var datagrid = $('#mdWfmStatus_userlist_<?php echo $this->wfmStatusId ?>');
        deleteWfmStatusPermission_<?php echo $this->wfmStatusId ?>(datagrid);
    }
    
    function removeWfmStatusRolePermission_<?php echo $this->wfmStatusId ?>(key) {
        var datagrid = $('#mdWfmStatus_rolelist_<?php echo $this->wfmStatusId ?>');
        deleteWfmStatusPermission_<?php echo $this->wfmStatusId ?>(datagrid);
    }
    
    function removeWfmStatusStatusPermission_<?php echo $this->wfmStatusId ?>(key) {
        var datagrid = $('#mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>');
        deleteWfmStatusPermission_<?php echo $this->wfmStatusId ?>(datagrid, 'statusdelete');
    }
    
    function deleteWfmStatusPermission_<?php echo $this->wfmStatusId ?>($datagrid, type) {
        var dialogName = '#deleteConfirm_wfm';
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        var $dialog = $(dialogName);
        $dialog.html('Та устгахдаа итгэлтэй байна уу?');
        $dialog.dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Сануулах',
            width: '350',
            height: 'auto',
            modal: true,
            close: function () {
                $dialog.empty().dialog('destroy').remove();
            },
            buttons: [
                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {      
                    
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/deleteWfmStatusPermission',
                        dataType: 'json',
                        data: {
                            statusPermissionId: '', 
                            rows: $datagrid.datagrid('getSelections'), 
                            type: type
                        },
                        beforeSend: function () {
                            Core.blockUI({
                                message: plang.get('msg_saving_block'),
                                boxed: true
                            });
                        },
                        success: function (data) {
                            PNotify.removeAll();
                            new PNotify({
                                title: data.status,
                                text: data.message,
                                type: data.status,
                                sticker: false
                            });
                            if (data.status === 'success') {
                                if (type === 'statusdelete') {
                                     $('#mdWfmStatus_statuslist_<?php echo $this->wfmStatusId ?>').datagrid('reload');
                                } else {
                                    wfmStatusSideBarReload(tempWfmStatusId);
                                }
                                $dialog.dialog('close');
                            } 
                            Core.unblockUI();
                        }
                    });
                }},
                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                    $dialog.dialog('close');
                }}
            ]
        });
        $dialog.dialog('open');
    }
    
    function clickFunction_<?php echo $this->wfmStatusId ?>(element, rowId, rowType) {
        $.ajax({
            type: 'post',
            url: 'mdprocessflow/iseditStatusUserPermission',
            data: {permissionId: rowId, isEdit: rowType},
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({
                    target: "#workFlowEditor",
                    animate: true
                });
            },
            success: function (data) {
                if (data.status === 'success') {
                    switch(rowType) {
                        case 1:
                            $(element).parent().html('<i class="fa fa-eye" onclick="clickFunction_<?php echo $this->wfmStatusId ?>(this, '+ rowId +', 2)"></i>'); 
                            break;
                        case 2:
                            $(element).parent().html('<i class="fa fa-pencil" onclick="clickFunction_<?php echo $this->wfmStatusId ?>(this, '+ rowId +', 0)"></i>'); 
                            break;
                        default:
                            $(element).parent().html('<i class="fa fa-eye-slash" onclick="clickFunction_<?php echo $this->wfmStatusId ?>(this, '+ rowId +', 1)"></i>'); 
                    }
                }
                Core.unblockUI('#workFlowEditor');
            },
            error: function () {
            }
        });  
    }
    function noAction_<?php echo $this->wfmStatusId ?>(element, rowId, rowType) {
        return;
    }
    function editWfmStatusLinkData_<?php echo $this->wfmStatusId ?> (trigger, key) {
        var _selectedIndex = $(trigger).attr('datagrid-row-index')
        var rows = $('.mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>').datagrid('getRows');
        selectedRow = rows[_selectedIndex];
        editWfmStatusPermission_<?php echo $this->wfmStatusId ?>(selectedRow);
    }
    
    function removeWfmStatusLinkData_<?php echo $this->wfmStatusId ?> (trigger, key) {
        var _selectedIndex = $(trigger).attr('datagrid-row-index')
        var rows = $('.mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>').datagrid('getRows');
        selectedRow = rows[_selectedIndex];
        deleteWfmStatusLink_<?php echo $this->wfmStatusId ?>(selectedRow.ID);
    }
    
    function editWfmStatusPermission_<?php echo $this->wfmStatusId ?> (selectedRow) {
        var html = '<table class="table table-sm table-no-bordered" style="table-layout: fixed !important">'
                + '<tbody>'
                    + '<tr style="border-bottom: 1px solid #CCC;">'
                        + '<td>'
                            + '<label class="text font-weight-bold" style="color: #FF5722;"><?php echo $this->lang->line('criteria') ?></label>'
                        + '</td>'
                    + '</tr>'
                    + '<tr>'
                        + '<td class="middle" style="width: 100%" colspan="">'
                            + '<div data-section-path="linkCriteria">'
                                + '<textArea type="text" id="linkCriteria" name="linkCriteria" placeholder="<?php echo $this->lang->line('criteria') ?>" class="form-control form-control-sm" required="required">'+selectedRow.CRITERIA+'</textArea>'
                            + '</div>'
                        + '</td>'
                    + '</tr>'
                    + '<tr style="border-bottom: 1px solid #CCC;">'
                        + '<td >'
                            + '<label class="text font-weight-bold" style="color: #FF5722;"><?php echo $this->lang->line('description') ?></label>'
                        + '</td>'
                    + '</tr>'
                    + '<tr>'
                        + '<td class="middle" style="width: 100%" colspan="">'
                            + '<div data-section-path="linkDescription">'
                                + '<textArea type="text" id="linkDescription" name="linkDescription" placeholder="<?php echo $this->lang->line('description') ?>" class="form-control form-control-sm" required="required">'+selectedRow.DESCRIPTION+'</textArea>'
                            + '</div>'
                        + '</td>'
                    + '</tr>'
                + '</tbody>'
            + '</table>';
    
            var $dialogName = 'dialog-wfmstatus-link-<?php echo $this->wfmStatusId ?>';
            if (!$($dialogName).length) {
                $('<div id="' + $dialogName + '"></div>').appendTo("body");
            }    
            var $dialog = $("#" + $dialogName);
            $dialog.empty().append(html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: "Тохиргоо",
                width: 350,
                height: 'auto',
                modal: true,
                close: function() {
                    $dialog.empty().dialog('destroy').remove();
                },                        
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn btn-sm blue', click: function() {
                        var due_period = $("#due_period", "#" + $dialogName).val();
                        var is_need_sign = $("#is_need_sign", "#" + $dialogName).is(':checked') ? '1' : '0';
                        $.ajax({
                            type: 'post',
                            url: 'mdprocessflow/saveMetaStatusLinkData',
                            data: {
                                linkCriteria: $('#linkCriteria').val(), 
                                linkDescription: $('#linkDescription').val(), 
                                wfmStatusId: '<?php echo $this->wfmStatusId; ?>', 
                                id: selectedRow.ID
                            },
                            dataType: 'json',
                            beforeSend: function () {
                                Core.blockUI({target: "#workFlowEditor", animate: true});
                            },
                            success: function (data) {
                                if (data.status === 'success') {
                                    $('.mdWfmStatus_linkList_<?php echo $this->wfmStatusId ?>').datagrid('reload');
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
                        $dialog.dialog('close');
                    }},
                    {text: plang.get('close_btn'), class: 'btn btn-sm blue-hoki', click: function() {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');    
    }
    
    function deleteWfmStatusLink_<?php echo $this->wfmStatusId ?> (statusLinkId) {
        var dialogName = '#deleteConfirm_' + statusLinkId;
        if (!$(dialogName).length) {
            $('<div id="' + dialogName.replace('#', '') + '"></div>').appendTo('body');
        }
        $(dialogName).html('Та устгахдаа итгэлтэй байна уу?');
        $(dialogName).dialog({
            cache: false,
            resizable: true,
            bgiframe: true,
            autoOpen: false,
            title: 'Сануулах',
            width: '350',
            height: 'auto',
            modal: true,
            buttons: [
                {text: 'Тийм', class: 'btn green-meadow btn-sm', click: function () {                            
                    $.ajax({
                        type: 'post',
                        url: 'mdprocessflow/deleteWfmStatusLink',
                        dataType: 'json',
                        data: {statusLinkId: statusLinkId},
                        beforeSend: function () {
                            Core.blockUI({
                                message: plang.get('msg_saving_block'),
                                boxed: true
                            });
                        },
                        success: function (data) {
                            if (data.status === 'success') {
                                new PNotify({
                                    title: 'Success',
                                    text: data.message,
                                    type: 'success',
                                    sticker: false
                                });
                                wfmStatusSideBarReload(tempWfmStatusId);
                                $(dialogName).empty().dialog('destroy').remove();
                            } 
                            else {
                                PNotify.removeAll();
                                new PNotify({
                                    title: data.status,
                                    text: data.message,
                                    type: data.status,
                                    sticker: false
                                });
                            }
                            Core.unblockUI();
                        }
                    });
                }},
                {text: 'Үгүй', class: 'btn blue-madison btn-sm', click: function () {
                    $(dialogName).empty().dialog('destroy').remove();
                }}
            ]
        });
        $(dialogName).dialog('open');
    }
</script>