<?php 
if ($this->isAjax) {
?>
<div class="row">
    <div class="col-md-12 jeasyuiTheme3" id="md-language">
        <table id="languageDataGrid" style="height: 400px"></table>
    </div>
</div>
<?php 
} else {
?>
<div class="col-md-12 jeasyuiTheme3" id="md-language">
    <div class="card light shadow">	
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="card-title"><i class="fa fa-cogs"></i> <?php echo $this->title; ?></div>
            <div class="header-elements">
                <div class="list-icons">
                    <a class="list-icons-item" data-action="collapse"></a>
                    <a class="list-icons-item" data-action="fullscreen"></a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <table id="languageDataGrid"></table>
        </div>
    </div>
</div>        
<?php 
}
?>

<script type="text/javascript">
var mdLanguageWindowId = "#md-language";
var languageDataGrid = $('#languageDataGrid', mdLanguageWindowId);

$(function() {
    languageDataGrid.datagrid({
        url: 'mdlanguage/languageDataGrid',
        queryParams: {code: '<?php echo $this->code; ?>'},
        rownumbers: true,
        singleSelect: true,
        pagination: true,
        pageSize: 20,
        striped: false,
        remoteFilter: true,
        filterDelay: 10000000000,
        fitColumns: true,
        frozenColumns: [[            
            {field: 'CODE', title: 'Код', align: 'left', sortable: true, fit: true}
        ]],        
        columns: [[
            {field: 'MONGOLIAN', title: 'Монгол', align: 'left', sortable: true, fit: true}, 
            {field: 'ENGLISH', title: 'English', align: 'left', sortable: true, fit: true}, 
            {field: 'RUSSIAN', title: 'Russian', align: 'left', sortable: true, fit: true},
            {field: 'TYPE_NAME', title: 'Төрөл', align: 'left', sortable: true, fit: true},
            {field: 'GROUP_NAME', title: 'Бүлэг', align: 'left', sortable: true, fit: true}
        ]],
        /*onClickCell: onClickCell,*/
        onDblClickRow:function(index, row) {
            _parentGlobeCode.find("input[type=text]:visible").val(row.CODE).trigger('change');
            $("#dialog-globecode-list").dialog('close');
        },
        onBeforeLoad: function(p){
            Core.blockUI({animate: true});
        },
        onLoadSuccess: function(){
            showGridMessage(languageDataGrid);
            Core.unblockUI();
        }
    });
    languageDataGrid.datagrid('enableFilter');
    
    /*$(window).bind('resize', function(){
        languageDataGrid.datagrid('resize'); 
    });*/
});
</script>