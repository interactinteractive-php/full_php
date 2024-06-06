<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<div class="row">
    <div class="col-md-12">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#mdUserTab1" class="nav-link active" data-toggle="tab">Жагсаалт</a>
                </li>
                <li class="nav-item">
                    <a href="#mdUserTab2" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('basket'); ?> (<span id="mdUserSelectedCount">0</span>)</a>
                </li>
            </ul>
            <div class="tab-content pb0">
                <div class="tab-pane active in" id="mdUserTab1">
                    <table id="mdUserDataGrid"></table>
                </div>
                <div class="tab-pane in" id="mdUserTab2">
                    <table id="mdUserBasketDataGrid"></table>
                </div>
            </div>
        </div>    
    </div>
</div>

<script type="text/javascript">
$(function(){
   $('#mdUserDataGrid').datagrid({
        url:'mduser/userDataGrid',
        rownumbers:true,
        singleSelect:<?php echo (($this->chooseMode == 'multi') ? 'false' : 'true'); ?>,
        pagination:true,
        pageSize:20,
        width:890,
        height:311,
        remoteFilter: true,
        filterDelay: 10000000000,
        fitColumns:true,
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'LAST_NAME',title:'<?php echo $this->lang->line('lname'); ?>',sortable:true,width:140},
            {field:'FIRST_NAME',title:'<?php echo $this->lang->line('fname'); ?>',sortable:true,width:140},
            {field:'USERNAME',title:'<?php echo $this->lang->line('user_name'); ?>',sortable:true,width:130},
            {field:'STATE_REG_NUMBER',title:'<?php echo $this->lang->line('emp_register'); ?>',sortable:true,width:100}, 
            {field:'DEPARTMENT_NAME',title:'<?php echo $this->lang->line('Department'); ?>',sortable:true,width:150}
        ]],
        onDblClickRow:function(index, row){
            dblClickMdUserDataGridReset(index, row);
        },
        onRowContextMenu:function(e, index, row){
            e.preventDefault();
            $(this).datagrid('selectRow', index);
            $.contextMenu({
                selector: "#mdUserTab1 .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                callback: function(key, opt) {
                    if (key === 'basket') {
                        basketMdUserV3();
                    }
                },
                items: {
                    "basket": {name: "Сагсанд нэмэх", icon: "plus-circle"}
                }
            });
        },
        onLoadSuccess:function(){
           showGridMessage($('#mdUserDataGrid'));
        }
    });
    
    $('#mdUserDataGrid').datagrid('enableFilter');
    
    $('#mdUserBasketDataGrid').datagrid({
        url:'',
        rownumbers:true,
        singleSelect:true,
        pagination:false,
        remoteSort:false,
        width:890,
        height:311,
        fitColumns:true,
        columns:[[
            {field:'LAST_NAME',title:'<?php echo $this->lang->line('lname'); ?>',sortable:true,width:140},
            {field:'FIRST_NAME',title:'<?php echo $this->lang->line('fname'); ?>',sortable:true,width:140},
            {field:'USERNAME',title:'<?php echo $this->lang->line('user_name'); ?>',sortable:true,width:130},
            {field:'STATE_REG_NUMBER',title:'<?php echo $this->lang->line('emp_register'); ?>',sortable:true,width:100},
            {field:'DEPARTMENT_NAME',title:'<?php echo $this->lang->line('Department'); ?>',sortable:true,width:150}, 
            {field:'action',title:'',sortable:true,width:40,align:'center'}
        ]]
    });
    $('#mdUserBasketDataGrid').datagrid('loadData', []);
    
    $("#mdUserGrid-form").on("keydown", 'input', function(e){
        if (e.which === 13) {
            mdUserDataGridSearch();
        }
    });
    $("a[href=#mdUserTab2]").on("click", function(){      
        $('#mdUserBasketDataGrid').datagrid('resize');
    });
});
function mdUserDataGridSearch(){
    var $form = $("#mdUserGrid-form"); 
    $('#mdUserDataGrid').datagrid('load',{
        lastname: $form.find('#lastname_s').val(),
        firstname: $form.find('#firstname_s').val(),
        register: $form.find('#register_s').val(),
        username: $form.find('#username_s').val()
    });
}
function mdUserDataGridReset(){
    var $form = $("#mdUserGrid-form"); 
    $form.find("input").val('');
    $('#mdUserDataGrid').datagrid('load',{
        lastname: $form.find('#lastname_s').val(),
        firstname: $form.find('#firstname_s').val(),
        register: $form.find('#register_s').val(),
        username: $form.find('#username_s').val()
    });
}
function dblClickMdUserDataGridReset(index, row){
    <?php
    if ($this->chooseMode == 'single') {
        echo "$('#mdUserBasketDataGrid').datagrid('loadData', []);";
    }
    ?>
    var isAddRow = true;
    var rows = $('#mdUserBasketDataGrid').datagrid('getRows');
    for (var j = 0; j < rows.length; j++) {
        var subrow = rows[j];
        if (subrow.USER_ID === row.USER_ID) {
            isAddRow = false;
        }
    }
    if (isAddRow) {
        $('#mdUserBasketDataGrid').datagrid('appendRow',{
            USER_ID: row.USER_ID,
            LAST_NAME: row.LAST_NAME,
            FIRST_NAME: row.FIRST_NAME, 
            USERNAME: row.USERNAME, 
            STATE_REG_NUMBER: row.STATE_REG_NUMBER,
            DEPARTMENT_NAME: row.DEPARTMENT_NAME,
            action: '<a href="javascript:;" onclick="deleteRowBasketMdUser(this);" class="btn btn-xs red" title="Устгах"><i class="fa fa-trash"></i></a>'
        });
    }
    $("body").find("#mdUserSelectedCount").text($('#mdUserBasketDataGrid').datagrid('getData').total);
}
function deleteRowBasketMdUser(target){
    $('#mdUserBasketDataGrid').datagrid('deleteRow', getRowIndex(target));
    $("body").find("#mdUserSelectedCount").text($('#mdUserBasketDataGrid').datagrid('getData').total);
}
function basketMdUserV3(){
    var rows = $('#mdUserDataGrid').datagrid('getSelections');
    var chooseMode = $("body").find("#mdUserGrid-form").find("input#chooseMode").val();
    if (chooseMode !== 'multi') {
        $('#mdUserBasketDataGrid').datagrid('loadData', {"total":0,"rows":[]});
    }
    for (var i=0; i<rows.length; i++) {
        var row = rows[i];
        var isAddRow = true;
        var subrows = $('#mdUserBasketDataGrid').datagrid('getRows');
        for (var j=0; j<subrows.length; j++) {
            var subrow = subrows[j];
            if (subrow.USER_ID === row.USER_ID) {
                isAddRow = false;
            }
        }
        if (isAddRow) {
            $('#mdUserBasketDataGrid').datagrid('appendRow', {
                USER_ID: row.USER_ID,
                LAST_NAME: row.LAST_NAME,
                FIRST_NAME: row.FIRST_NAME, 
                USERNAME: row.USERNAME, 
                STATE_REG_NUMBER: row.STATE_REG_NUMBER,
                DEPARTMENT_NAME: row.DEPARTMENT_NAME,
                action: '<a href="javascript:;" onclick="deleteRowBasketMdUser(this);" class="btn btn-xs red" title="Устгах"><i class="fa fa-trash"></i></a>'
            });
        }
    }
    
    $("body").find("#mdUserSelectedCount").text($('#mdUserBasketDataGrid').datagrid('getData').total);
}
</script>

