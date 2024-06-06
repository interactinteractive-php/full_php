<?php if(!defined('_VALID_PHP')) exit('Direct access to this location is not allowed.'); ?>

<?php echo Form::create(array('class'=>'form-horizontal form-middle mb0', 'id'=>'mdUserGrid-form', 'method'=>'post')); ?>
<div class="row-fluid">
    <div class="tabbable tabbable-custom tabbable-no-border">
        <ul class="nav nav-tabs">
            <li class="nav-item"><a href="#mdUserTab1" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('choose_item'); ?></a></li>
            <li class="nav-item"><a href="#mdUserTab2" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('basket'); ?> (<span id="mdUserSelectedCount">0</span>)</a></li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane mt10 active" id="mdUserTab1">
                <div class="span6">
                    <div class="control-group">
                        <?php echo Form::label(array('text' => $this->lang->line('lname'), 'for'=>'lastname_s', 'class' => 'col-form-label', 'style'=>'width:50px')); ?>
                        <div class="controls" style="margin-left: 70px;">
                            <?php echo Form::text(array('name' => 'lastname_s', 'id' => 'lastname_s', 'class' => 'span12 m-wrap')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <?php echo Form::label(array('text' => $this->lang->line('fname'), 'for'=>'firstname_s', 'class' => 'col-form-label', 'style'=>'width:50px')); ?>
                        <div class="controls" style="margin-left: 70px;">
                            <?php echo Form::text(array('name' => 'firstname_s', 'id' => 'firstname_s', 'class' => 'span12 m-wrap')); ?>
                        </div>
                    </div>
                </div>
                <div class="span6">
                    <div class="control-group">
                        <?php echo Form::label(array('text' => $this->lang->line('emp_register'), 'for'=>'register_s', 'class' => 'col-form-label', 'style'=>'width:100px')); ?>
                        <div class="controls" style="margin-left: 110px;">
                            <?php echo Form::text(array('name' => 'register_s', 'id' => 'register_s', 'class' => 'span12 m-wrap')); ?>
                        </div>
                    </div>
                    <div class="control-group">
                        <?php echo Form::label(array('text' => $this->lang->line('user_name'), 'for'=>'username_s', 'class' => 'col-form-label', 'style'=>'width:100px')); ?>
                        <div class="controls" style="margin-left: 110px;">
                            <?php echo Form::text(array('name' => 'username_s', 'id' => 'username_s', 'class' => 'span12 m-wrap')); ?>
                        </div>
                    </div>
                </div>
                <div class="row-fluid">
                    <div class="span12">
                        <?php echo Form::button(array('class'=>'btn mini float-right','onclick'=>'mdUserDataGridReset();','value'=>$this->lang->line('clear_btn'))); ?>
                        <?php echo Form::button(array('class'=>'btn blue mini float-right','onclick'=>'mdUserDataGridSearch();','value'=>$this->lang->line('search_btn'))); ?>
                    </div>    
                </div>
                
                <div class="clearfix w-100"></div>
                
                <table id="mdUserDataGrid"></table>
            </div>
            <div class="tab-pane mt10" id="mdUserTab2">
                <table id="mdUserBasketDataGrid"></table>
            </div>
        </div>
    </div>
</div>    
<?php echo Form::hidden(array('id'=>'chooseMode','value'=>$this->chooseMode)); ?>
<?php echo Form::hidden(array('id'=>'appendId','value'=>$this->appendId)); ?>
<?php echo Form::hidden(array('id'=>'selectedElementId','value'=>$this->selectedElementId)); ?>
<?php echo Form::hidden(array('id'=>'objectId','value'=>$this->objectId)); ?>
<?php echo Form::close(); ?>

<style type="text/css">
#mdUserTab2 div.datagrid-view{
    height: 220px !important;
}    
#mdUserTab2 div.datagrid-view div.datagrid-view1 {
    width: 26px !important;
}
#mdUserTab2 div.datagrid-view div.datagrid-view1 tr.datagrid-row {
    height: 27px !important;
}
#mdUserTab2 div.datagrid-view div.datagrid-view1 div.datagrid-header {
    height: 28px !important;
}
#mdUserTab2 div.datagrid-view div.datagrid-view1 table.datagrid-htable {
    height: 28px !important;
}
</style>

<script type="text/javascript">
$(function(){
   $('#mdUserDataGrid').datagrid({
        url:'mduser/userDataGrid',
        rownumbers:true,
        singleSelect:<?php echo (($this->chooseMode == 'multi') ? 'false' : 'true'); ?>,
        pagination:true,
        pageSize:20,
        width:724,
        height:250,
        fitColumn:true,
        frozenColumns:[[
            {field:'ck',checkbox:true}
        ]],
        columns:[[
            {field:'LAST_NAME',title:'<?php echo $this->lang->line('lname'); ?>',sortable:true,width:150},
            {field:'FIRST_NAME',title:'<?php echo $this->lang->line('fname'); ?>',sortable:true,width:150},
            {field:'USERNAME',title:'<?php echo $this->lang->line('user_name'); ?>',sortable:true,width:150},
            {field:'STATE_REG_NUMBER',title:'<?php echo $this->lang->line('emp_register'); ?>',sortable:true,width:197}
        ]],
        onLoadSuccess:function(){
           showGridMessage($('#mdUserDataGrid'));
        }
    });
    $('#mdUserBasketDataGrid').datagrid({
        url:'',
        rownumbers:true,
        singleSelect:true,
        pagination:false,
        remoteSort:false,
        width:724,
        height:250,
        columns:[[
            {field:'LAST_NAME',title:'<?php echo $this->lang->line('lname'); ?>',sortable:true,width:150},
            {field:'FIRST_NAME',title:'<?php echo $this->lang->line('fname'); ?>',sortable:true,width:150},
            {field:'USERNAME',title:'<?php echo $this->lang->line('user_name'); ?>',sortable:true,width:150},
            {field:'STATE_REG_NUMBER',title:'<?php echo $this->lang->line('emp_register'); ?>',sortable:true,width:170},
            {field:'action',title:'',sortable:true,width:40,align:'center'}
        ]]
    });
    $('#mdUserBasketDataGrid').datagrid('loadData', []);
    
    $("#mdUserGrid-form").on('keyup', "input[type=text]", function(e){
        if (e.keyCode === 13) {
            mdUserDataGridSearch();
        }
    });
});
function mdUserDataGridSearch(){
    $('#mdUserDataGrid').datagrid('load',{
        lastname: $("#mdUserGrid-form").find('#lastname_s').val(),
        firstname: $("#mdUserGrid-form").find('#firstname_s').val(),
        register: $("#mdUserGrid-form").find('#register_s').val(),
        username: $("#mdUserGrid-form").find('#username_s').val()
    });
}
function mdUserDataGridReset(){
    $("#mdUserGrid-form").find("input").val("");
    $('#mdUserDataGrid').datagrid('load',{
        lastname: $("#mdUserGrid-form").find('#lastname_s').val(),
        firstname: $("#mdUserGrid-form").find('#firstname_s').val(),
        register: $("#mdUserGrid-form").find('#register_s').val(),
        username: $("#mdUserGrid-form").find('#username_s').val()
    });
}
</script>

