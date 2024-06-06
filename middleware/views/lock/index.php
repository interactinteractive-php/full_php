<div class="col-md-12">
    <div class="card light shadow bordered bg-white mb0">
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title">
                <i class="fa fa-refresh"></i>
                <span class="caption-subject font-weight-bold uppercase card-subject-blue"><?php echo $this->title; ?></span>
            </div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">

            <div class="row">
                <div class="col-md-2 pr0">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="dv-process-buttons btn-group btn-group-devided">
                                    <a class="btn btn-success btn-circle btn-sm" onclick="lockCategoryAdd();" href="javascript:;"><i class="icon-plus3 font-size-12"></i> Категори нэмэх</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="lock-tree-view"></div>
                </div>
                <div class="col-md-10 jeasyuiTheme3">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-10">
                                <div class="dv-process-buttons btn-group btn-group-devided">
                                    <a class="btn btn-success btn-circle btn-sm" onclick="lockTempAdd();" href="javascript:;"><i class="icon-plus3 font-size-12"></i> Нэмэх</a>
                                    <a class="btn btn-primary btn-circle btn-sm" onclick="lockEditMetaRow();" href="javascript:;"><i class="fa fa-edit"></i> Категори солих</a>
                                    <a class="btn btn-danger btn-circle btn-sm" onclick="lockRowsDelete();" href="javascript:;"><i class="fa fa-trash-o"></i> Устгах</a>
                                    <a class="btn btn-warning btn-circle btn-sm" onclick="lockMetaRow();" href="javascript:;"><i class="fa fa-lock"></i> Түгжих</a>
                                    <a class="btn btn-info btn-circle btn-sm" onclick="unlockMetaRow();" href="javascript:;"><i class="fa fa-unlock"></i> Түгжээг авах</a>
                                    <a class="btn purple btn-circle btn-sm" onclick="shareLockMetaRow();" href="javascript:;"><i class="fa fa-user"></i> Эрх өгөх</a>
                                    <a class="btn default btn-circle btn-sm" onclick="lockRequest();" href="javascript:;"><i class="fa fa-comments"></i> Хүсэлтүүд <span id="request_count" class="badge badge-success">0</span></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="lock-datagrid"></table>
                </div>
            </div>    
            
        </div>
    </div>
</div>    
<input type="hidden" id="lockCategoryId">
<div class="clearfix w-100"></div>

<style type="text/css">
.jstree {
    overflow: auto; 
    padding-bottom: 10px;
}    
.lock-history-tbl {
    overflow: auto;
    max-height: 500px;
}
.lock-history-tbl thead th {
    position: sticky; 
    top: 0;
    background-color: #f8f8f8;
}
</style>

<script type="text/javascript">
var $lockDatagrid = $('#lock-datagrid');

$(function () {       
    
    lockTreeCategory();
    
    $lockDatagrid.attr('height', ($(window).height() - $lockDatagrid.offset().top - 20) + 'px');
    
    $lockDatagrid.datagrid({
        view: horizonscrollview, 
        url: 'mdlock/lockDataGrid',
        rownumbers: true,
        singleSelect: false,
        ctrlSelect: true, 
        checkOnSelect: true, 
        selectOnCheck: true, 
        pagination: true,
        pageSize: 50,
        multiSort: false,
        remoteSort: true,
        remoteFilter: true,
        filterDelay: 10000000000,
        resizeHandle: 'right',
        fitColumns: true,
        autoRowHeight: true,
        striped: false,
        pageList: [20, 30, 50, 100, 200, 300, 400, 500], 
        frozenColumns: [[
            {field: 'ck', checkbox: true}
        ]],
        columns: [[
            {field: 'META_DATA_CODE', title: 'Процессийн код', halign: 'center', sortable: true, width: 230},
            {field: 'META_DATA_NAME', title: 'Процессийн нэр', halign: 'center', sortable: true, width: 300},
            {field: 'LABEL_NAME', title: 'Нэршил', halign: 'center', sortable: true, width: 350},
            {field: 'META_TYPE_NAME', title: 'Төрөл', sortable: true, halign: 'center', align: 'center', width: 100}, 
            {field: 'DESCRIPTION', title: 'Тайлбар', sortable: true, halign: 'center', align: 'center', width: 100, fit: true}, 
            {field: 'IS_LOCKED', title: 'Төлөв', sortable: true, align: 'center', width: 70, formatter: function(val,row){
                if (val == '1') {
                    return '<a href="javascript:;" class="badge label-sm badge-danger" onclick="lockHistory(\''+row.META_DATA_ID+'\');">Locked</a>';
                } else {
                    return '<a href="javascript:;" class="badge label-sm badge-success" onclick="lockHistory(\''+row.META_DATA_ID+'\');">Unlocked</a>';
                }
            }}
        ]],
        onRowContextMenu: function (e, index) {
            e.preventDefault();
            $lockDatagrid.datagrid('selectRow', index);
                        
            $.contextMenu({
                selector: '.datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row',
                callback: function (key, opt) {
                    if (key === 'delete') {
                        lockRowsDelete();
                    } else if (key === 'lock') {
                        lockMetaRow();
                    } else if (key === 'unlock') {
                        unlockMetaRow();
                    } else if (key === 'user') {
                        shareLockMetaRow();
                    } else if (key === 'category') {
                        lockEditMetaRow();
                    }
                },
                items: {
                    "lock": {name: 'Түгжих', icon: 'lock'}, 
                    "unlock": {name: 'Түгжээг авах', icon: 'unlock'}, 
                    "user": {name: 'Эрх өгөх', icon: 'user'}, 
                    "category": {name: 'Категори солих', icon: 'edit'}, 
                    "delete": {name: 'Устгах', icon: 'trash'}
                }
            });
        },
        rowStyler: function(index, row){
            if (row.fixed === '1') {
                return 'background-color: #1ab386;';
            }
        },  
        onLoadSuccess: function(data){
            
            if (data.status === 'error') {
                PNotify.removeAll();
                new PNotify({
                    title: 'Error',
                    text: data.message,
                    type: 'error',
                    sticker: false
                });
            }
            
            showGridMessage($lockDatagrid);
            
            $lockDatagrid.datagrid('resize');   
        }
    });
    
    $lockDatagrid.datagrid('enableFilter', [
        {
            field: 'IS_LOCKED',
            type: 'combobox',
            options:{
                panelHeight:'auto',
                data:[{value:'',text:'All'},{value:'1',text:'Locked'},{value:'0',text:'Unlocked'}],
                onChange:function(value){
                    if (value == ''){
                        $lockDatagrid.datagrid('removeFilterRule', 'IS_LOCKED');
                    } else {
                        $lockDatagrid.datagrid('addFilterRule', {
                            field: 'IS_LOCKED',
                            op: 'equal',
                            value: value
                        });
                    }
                    $lockDatagrid.datagrid('doFilter');
                }
            }
        }
    ]);
    
    $(window).bind('resize', function () {
        $lockDatagrid.datagrid('resize');
    });

    $('#lockDatagrid-form').on('keydown', 'input', function (e) {
        if (e.which === 13) {
            lockDatagridSearch();
        }
    });
    
    $.contextMenu({
        selector: '#lock-tree-view a.jstree-anchor:not([data-id="99999999"])',
        callback: function(key, opt) {
            if (key === 'edit') {
                lockCategoryEdit(opt.$trigger.attr('data-id'));
            } else if (key === 'delete') {
                lockCategoryDelete(opt.$trigger.attr('data-id'));
            }
        },
        items: {
            "edit": {name: plang.get('edit_btn'), icon: "edit"},
            "delete": {name: 'Устгах', icon: "trash"}
        }
    });
});

function lockTreeCategory() {
    $.ajax({
        type: 'post',
        url: 'mdlock/treeview', 
        success: function (data) {
            $('#lock-tree-view').empty().append(data);
        }
    });
}

function lockTreeCategoryFilter(catId) {
    $lockDatagrid.datagrid('load', {
        categoryId: catId 
    });
    $('#lockCategoryId').val(catId);
}

function lockTempAdd() {
    var $dialogName = 'dialog-locktemp-add';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdlock/lockTempAdd', 
        data: {categoryId: $('#lockCategoryId').val()}, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 800,
                height: 'auto',
                modal: true,
                position: {my: 'top', at: 'top+50'}, 
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                        
                        $('#temp-lock-form').validate({errorPlacement: function () {}});
                        
                        if ($('#temp-lock-form').valid()) {
        
                            $('#temp-lock-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mdlock/lockTempSave',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Saving...', boxed: true});
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
                                        $lockDatagrid.datagrid('reload');
                                        $dialog.dialog('close');  
                                    } 
                                    Core.unblockUI();
                                }
                            });
                        }
                    }}, 
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function(){
        Core.initSelect2($dialog);
        Core.initUniform($dialog);
    });
}

function lockEditMetaRow() {
    
    var rows = $lockDatagrid.datagrid('getSelections');
    
    if (rows.length === 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    var $dialogName = 'dialog-lock-edit';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdlock/lockRowEdit', 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                        
                        $('#cat-edit-form').validate({errorPlacement: function () {}});
                        
                        if ($('#cat-edit-form').valid()) {
        
                            $('#cat-edit-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mdlock/lockEditMetaRowSave',
                                dataType: 'json',
                                beforeSubmit: function (formData, jqForm, options) {
                                    formData.push(
                                        {name: 'selectedRows', value: JSON.stringify(rows)}
                                    );
                                },
                                beforeSend: function () {
                                    Core.blockUI({message: 'Saving...', boxed: true});
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
                                        $dialog.dialog('close');  
                                    } 
                                    Core.unblockUI();
                                }
                            });
                        }
                    }}, 
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function(){
        Core.initSelect2($dialog);
    });
}

function lockRowsDelete() {
    var rows = $lockDatagrid.datagrid('getSelections');
    
    if (rows.length === 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    var $dialogName = 'dialog-delete-confirm';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdlock/deleteLockConfirm',
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({animate: true});
        },
        success: function(data){
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 350,
                height: "auto",
                modal: true,
                close: function(){
                    $("#" + $dialogName).empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.yes_btn, class: 'btn green-meadow btn-sm', click: function(){
                            
                        $('#delete-lock-form').validate({errorPlacement: function () {}});
                        
                        if ($('#delete-lock-form').valid()) {
                            $('#delete-lock-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mdlock/deleteLock',
                                dataType: 'json',
                                beforeSubmit: function (formData, jqForm, options) {
                                    formData.push(
                                        {name: 'selectedRows', value: JSON.stringify(rows)}
                                    );
                                },
                                beforeSend: function(){
                                    Core.blockUI({message: 'Deleting...', boxed: true});
                                },
                                success: function(data){

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                        
                                    if (data.status === 'success') {
                                        $lockDatagrid.datagrid('reload');
                                        $dialog.dialog('close');
                                    } 

                                    Core.unblockUI();
                                },
                                error: function(){
                                    alert("Error");
                                }
                            });
                        }
                    }},
                    {text: data.no_btn, class: 'btn blue-madison btn-sm', click: function(){
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function(){
            alert("Error");
        }
    });
}
function lockMetaRow() {
    var rows = $lockDatagrid.datagrid('getSelections');
    
    if (rows.length === 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    var $dialogName = 'dialog-meta-lock';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdlock/lockConfirm',
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({animate: true});
        },
        success: function(data){
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: "auto",
                modal: true,
                close: function(){
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.lock_btn, class: 'btn green-meadow btn-sm', click: function(){
                            
                        $('#meta-lock-form').validate({errorPlacement: function () {}});
                        
                        if ($('#meta-lock-form').valid()) {
                            $('#meta-lock-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mdlock/saveLock',
                                dataType: 'json',
                                beforeSubmit: function (formData, jqForm, options) {
                                    formData.push(
                                        {name: 'selectedRows', value: JSON.stringify(rows)}
                                    );
                                },
                                beforeSend: function(){
                                    Core.blockUI({message: 'Locking...', boxed: true});
                                },
                                success: function(data){

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                        
                                    if (data.status === 'success') {
                                        $lockDatagrid.datagrid('reload');
                                        $dialog.dialog('close');
                                    }

                                    Core.unblockUI();
                                },
                                error: function(){
                                    alert("Error");
                                }
                            });
                        }
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function(){
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function(){
            alert("Error");
        }
    });
}
function unlockMetaRow() {
    var rows = $lockDatagrid.datagrid('getSelections');
    
    if (rows.length === 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    var $dialogName = 'dialog-meta-unlock';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdlock/unlockConfirm',
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({animate: true});
        },
        success: function(data){
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: "auto",
                modal: true,
                close: function(){
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.lock_btn, class: 'btn green-meadow btn-sm', click: function(){
                            
                        $('#meta-unlock-form').validate({errorPlacement: function () {}});
                        
                        if ($('#meta-unlock-form').valid()) {
                            $('#meta-unlock-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mdlock/saveUnlock',
                                dataType: 'json',
                                beforeSubmit: function (formData, jqForm, options) {
                                    formData.push(
                                        {name: 'selectedRows', value: JSON.stringify(rows)}
                                    );
                                },
                                beforeSend: function(){
                                    Core.blockUI({message: 'Unlocking...', boxed: true});
                                },
                                success: function(data){

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                        
                                    if (data.status === 'success') {
                                        $lockDatagrid.datagrid('reload');
                                        $dialog.dialog('close');
                                    } 

                                    Core.unblockUI();
                                },
                                error: function(){
                                    alert("Error");
                                }
                            });
                        }
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function(){
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function(){
            alert("Error");
        }
    });
}
function shareLockMetaRow() {
    var rows = $lockDatagrid.datagrid('getSelections');
    
    if (rows.length === 0) {
        alert(plang.get('msg_pls_list_select'));
        return;
    }
    
    var $dialogName = 'dialog-meta-sharelock';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    var $dialog = $("#" + $dialogName);

    $.ajax({
        type: 'post',
        url: 'mdlock/shareLockForm',
        dataType: 'json',
        beforeSend: function(){
            Core.blockUI({animate: true});
        },
        success: function(data){
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: false,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 530,
                height: "auto",
                modal: true,
                open: function () {
                    $(this).keypress(function (e) {
                        if (e.keyCode == $.ui.keyCode.ENTER) {
                            $(this).parent().find(".ui-dialog-buttonpane button:first").trigger("click");
                        }
                    });
                },
                close: function(){
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function(){
                            
                        $('#metaShareLockForm').validate({errorPlacement: function () {}});
                        
                        if ($('#metaShareLockForm').valid()) {
                            $('#metaShareLockForm', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mdlock/shareLockSave',
                                dataType: 'json',
                                beforeSubmit: function(formData, jqForm, options){
                                    formData.push(
                                        {name: 'selectedRows', value: JSON.stringify(rows)}
                                    );
                                },
                                beforeSend: function(){
                                    Core.blockUI({message: 'Sharing...', boxed: true});
                                },
                                success: function(data){

                                    PNotify.removeAll();
                                    new PNotify({
                                        title: data.status,
                                        text: data.message,
                                        type: data.status,
                                        sticker: false
                                    });
                                        
                                    if (data.status === 'success') {
                                        $lockDatagrid.datagrid('reload');
                                        $dialog.dialog('close');
                                    } 

                                    Core.unblockUI();
                                },
                                error: function(){
                                    alert("Error");
                                }
                            });
                        }
                    }},
                    {text: data.close_btn, class: 'btn blue-madison btn-sm', click: function(){
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            Core.unblockUI();
        },
        error: function(){
            alert("Error");
        }
    }).done(function(){
        Core.initSelect2($dialog);
        Core.initDateTimeInput($dialog);
    });
}
function lockCategoryAdd() {
    var $dialogName = 'dialog-lockcat-add';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdlock/lockCategoryAdd', 
        data: {categoryId: $('#lockCategoryId').val()}, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: data.save_btn, class: 'btn green-meadow btn-sm', click: function () {
                        
                        $('#cat-lock-form').validate({errorPlacement: function () {}});
                        
                        if ($('#cat-lock-form').valid()) {
        
                            $('#cat-lock-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mdlock/lockCategorySave',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Saving...', boxed: true});
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
                                        lockTreeCategory();
                                        $dialog.dialog('close');  
                                    } 
                                    Core.unblockUI();
                                }
                            });
                        }
                    }}, 
                    {text: data.close_btn, class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function(){
        Core.initSelect2($dialog);
    });
}
function lockCategoryEdit(id) {
    var $dialogName = 'dialog-lockcat-edit';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdlock/lockCategoryEdit', 
        data: {categoryId: id}, 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({
                message: 'Loading...', 
                boxed: true 
            });
        },
        success: function (data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 500,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('save_btn'), class: 'btn green-meadow btn-sm', click: function () {
                        
                        $('#cat-lock-form').validate({errorPlacement: function () {}});
                        
                        if ($('#cat-lock-form').valid()) {
        
                            $('#cat-lock-form', '#' + $dialogName).ajaxSubmit({
                                type: 'post',
                                url: 'mdlock/lockCategoryEditSave',
                                dataType: 'json',
                                beforeSend: function () {
                                    Core.blockUI({message: 'Saving...', boxed: true});
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
                                        lockTreeCategory();
                                        $dialog.dialog('close');  
                                    } 
                                    Core.unblockUI();
                                }
                            });
                        }
                    }}, 
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    }).done(function(){
        Core.initSelect2($dialog);
    });
}
function lockCategoryDelete(id) {
    var $dialogName = 'dialog-lockcat-delete';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdcommon/deleteConfirm', 
        dataType: 'json',
        beforeSend: function () {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function (data) {
            $dialog.empty().append(data.Html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.Title,
                width: 300,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('yes_btn'), class: 'btn green-meadow btn-sm', click: function () {
        
                        $.ajax({
                            type: 'post',
                            url: 'mdlock/lockCategoryDelete',
                            data: {id: id}, 
                            dataType: 'json',
                            beforeSend: function () {
                                Core.blockUI({message: 'Deleting...', boxed: true});
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
                                    lockTreeCategory();
                                    $dialog.dialog('close');
                                } 
                                
                                Core.unblockUI();
                            }
                        });
                    }}, 
                    {text: plang.get('no_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
}
function lockRequest() {
    var $dialogName = 'dialog-lock-request';
    if (!$("#" + $dialogName).length) {
        $('<div id="' + $dialogName + '"></div>').appendTo('body');
    }
    
    var $dialog = $("#" + $dialogName);
    
    $.ajax({
        type: 'post',
        url: 'mdlock/lockRequests', 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            $dialog.empty().append(data.html);
            $dialog.dialog({
                cache: false,
                resizable: true,
                bgiframe: true,
                autoOpen: false,
                title: data.title,
                width: 1050,
                height: 'auto',
                modal: true,
                close: function () {
                    $dialog.empty().dialog('destroy').remove();
                },
                buttons: [
                    {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                        $dialog.dialog('close');
                    }}
                ]
            });
            $dialog.dialog('open');
            
            Core.unblockUI();
        },
        error: function () {
            alert("Error");
        }
    });
}
function requestCount() {
    $.ajax({
        type: 'post',
        url: 'mdlock/requestCount', 
        success: function(data) {
            $('#request_count').text(data);
        }
    });
}
function lockHistory(metaId) {
    $.ajax({
        type: 'post',
        url: 'mdlock/lockHistory', 
        data: {metaId: metaId}, 
        dataType: 'json',
        beforeSend: function() {
            Core.blockUI({message: 'Loading...', boxed: true});
        },
        success: function(data) {
            
            if (data.status != 'success') {
                
                PNotify.removeAll();
                new PNotify({
                    title: data.status,
                    text: data.message,
                    type: data.status,
                    sticker: false 
                });
                Core.unblockUI();
                                
            } else {
                
                var html = [];
                
                html.push('<div class="col-md-12 xs-form">');
                
                    html.push('<div class="form-group row">');
                        html.push('<label class="col-form-label col-md-2 text-right">Үзүүлэлт:</label>');
                        html.push('<div class="col-md-10">'+data.metaRow.META_DATA_CODE+' - '+data.metaRow.META_DATA_NAME+' ('+data.metaRow.META_TYPE_NAME+')</div>');
                    html.push('</div>');
                    html.push('<div class="form-group row">');
                        html.push('<label class="col-form-label col-md-2 text-right">Эхний түгжээ:</label>');
                        html.push('<div class="col-md-10">'+data.firstLockRow.PERSON_NAME+' ('+data.firstLockRow.EMAIL+') - /Огноо: '+data.firstLockRow.CREATED_DATE+'/</div>');
                    html.push('</div>');
                    
                    html.push('<div class="lock-history-tbl mt20" style="overflow: auto;">');
                    html.push('<table class="table table-hover">');
                    html.push('<thead>');
                        html.push('<tr>');
                            html.push('<th class="font-weight-bold" style="width: 30px">#</th>');
                            html.push('<th class="font-weight-bold">Хэрэглэгчийн нэр</th>');
                            html.push('<th class="font-weight-bold">Тайлбар</th>');
                            html.push('<th class="font-weight-bold">Үйлдэл</th>');
                            html.push('<th class="font-weight-bold">Дуусах огноо</th>');
                            html.push('<th class="font-weight-bold">Үүсгэсэн огноо</th>');
                        html.push('</tr>');
                    html.push('</thead>');
                    html.push('<tbody>');
                    
                    if (Object.keys(data.historyData).length) {
                        
                        var historyData = data.historyData, n = 1;
                        
                        for (var i in historyData) {
                            
                            var modifierName = '';
                            
                            if (historyData[i]['USERNAME']) {
                                modifierName = historyData[i]['USERNAME'] + '-д эрх олгов. ';
                            }
                            
                            html.push('<tr>');
                                html.push('<td>'+n+'</td>');
                                html.push('<td>'+historyData[i]['PERSON_NAME']+'</td>');
                                html.push('<td>'+modifierName+dvFieldValueShow(historyData[i]['DESCRIPTION'])+'</td>');
                                html.push('<td>'+dvFieldValueShow(logTypeToLabel(historyData[i]['LOG_TYPE']))+'</td>');
                                html.push('<td>'+dvFieldValueShow(historyData[i]['END_TIME'])+'</td>');
                                html.push('<td>'+dvFieldValueShow(historyData[i]['CREATED_DATE'])+'</td>');
                            html.push('</tr>');
                            n++;
                        }
                    }    
                
                    html.push('</tbody>');
                html.push('</table>');
                html.push('</div>');
                
                html.push('</div>');
                
                var $dialogName = 'dialog-lock-history';
                if (!$("#" + $dialogName).length) {
                    $('<div id="' + $dialogName + '"></div>').appendTo('body');
                }
                var $dialog = $("#" + $dialogName);

                $dialog.empty().append(html.join(''));
                $dialog.dialog({
                    cache: false,
                    resizable: true,
                    bgiframe: true,
                    autoOpen: false,
                    title: 'Түүх',
                    width: 1050,
                    height: 'auto',
                    modal: true, 
                    open: function() {
                        Core.unblockUI();
                    },
                    close: function() {
                        $dialog.empty().dialog('destroy').remove();
                    },
                    buttons: [
                        {text: plang.get('close_btn'), class: 'btn blue-hoki btn-sm', click: function () {
                            $dialog.dialog('close');
                        }}
                    ]
                });
                $dialog.dialog('open');
            }
        },
        error: function() { alert('Error'); }
    });
}
function logTypeToLabel(type) {
    if (type == 'locked') {
        return 'Түгжсэн';
    } else if (type == 'sharelock') {
        return 'Эрх өгсөн';
    } else if (type == 'unlocked') {
        return 'Түгжээг болиулсан';
    }
}

requestCount();
setInterval(requestCount, 10000);
</script>