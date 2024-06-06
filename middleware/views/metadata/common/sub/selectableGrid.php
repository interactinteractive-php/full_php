<div class="row">
    <div class="col-md-3">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#common-metadata-tab-filder" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00193'); ?></a>
                </li>
                <li class="nav-item">
                    <a href="#common-metadata-tab-folder" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00041'); ?></a>
                </li>
            </ul>
            <div class="tab-content pb0">
                <div class="tab-pane active in" id="common-metadata-tab-filder">
                    <form role="form" id="common-metadata-search-form" method="post">
                        <div class="form-body col">
                            <?php echo $this->searchForm; ?> 
                        </div>    
                        <div class="form-actions">
                            <?php 
                            echo Form::button(array('class' => 'btn blue btn-sm mr5', 'onclick' => 'commonMetaDataGridSearch();', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); 
                            echo Form::button(array('class' => 'btn grey-cascade btn-sm', 'onclick' => 'commonMetaDataGridReset();', 'value' => $this->lang->line('clear_btn'))); 
                            ?>
                        </div>
                    </form>    
                </div>
                <div class="tab-pane in" id="common-metadata-tab-folder">
                    <div id="common-metadata-folder-view" class="tree-demo">
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <div class="col-md-9 pl0">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#common-metadata-tab-order" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00062'); ?></a>
                </li>
                <li class="nav-item">
                    <a href="#common-metadata-tab-basket" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('basket'); ?> (<span id="commonMetaSelectedCount">0</span>)</a>
                </li>
            </ul>
            <div class="tab-content pb0">
                <div class="tab-pane active in" id="common-metadata-tab-order">
                    <table id="commonMetaDataGrid" style="width: 897px; height: 380px"></table>
                </div>
                <div class="tab-pane in" id="common-metadata-tab-basket">
                    <table id="commonBasketMetaDataGrid"></table>
                </div>
            </div>
        </div>    
    </div>
</div>

<style type="text/css">
#common-metadata-folder-view {
    overflow: auto;
    height: 380px !important;
}   
#common-metadata-search-form .form-group {
    margin-bottom: 5px !important;
}
#common-metadata-search-form label {
    font-size: 12px !important;
}
#common-metadata-search-form .form-actions {
    margin-top: 20px !important;
}
#common-metadata-search-form .form-body {
    overflow: auto;
    max-height: 300px !important;
}
/*#common-metadata-tab-order *::-moz-selection, #common-metadata-tab-basket *::-moz-selection { background:transparent; }
#common-metadata-tab-order *::selection, #common-metadata-tab-basket *::selection { background:transparent; }*/
</style>

<script type="text/javascript">
var commonMetaSelectableTreeView = $('#common-metadata-folder-view');
$(function(){
    commonMetaSelectableTreeView.jstree({
        "core" : {
            "themes" : {
                "responsive": true
            }, 
            "check_callback" : true,
            "data" : {
                "url" : function(node) {
                    return 'mdmetadata/childFolderSystem';
                },
                "data" : function(node) {
                    return { 'parent' : node.id, 'isExternalServer': '<?php echo $this->isExternalServer; ?>' };
                }
            }
        },
        "types" : {
            "default" : {
                "icon" : "icon-folder2 text-orange-300"
            }
        },
        "plugins": ["types", "cookies"]
    }).bind("select_node.jstree", function (e, data){
        commonMetaDataFolderFilter(data.node.id);
    });
    
    $("#common-metadata-search-form").on("keydown", 'input', function(e){
        if (e.which === 13) {
            commonMetaDataGridSearch();
        }
    });
    
    $('#commonMetaDataGrid').datagrid({
        url: 'mdmetadata/commonMetaDataGrid',
        <?php echo $this->searchParams; ?>
        rownumbers: true,
        singleSelect: <?php echo $this->singleSelect; ?>,
        ctrlSelect: true,
        pagination: true,
        pageSize: 30,
        fitColumn: true,
        nowrap: false,
        remoteFilter: true,
        filterDelay: 10000000000, 
        frozenColumns:[[
            {field:'ck', checkbox:true}, 
            {field:'META_DATA_CODE',title:"<?php echo $this->lang->line('META_00075') ?>",sortable:true,width:185},
            {field:'META_DATA_NAME',title:"<?php echo $this->lang->line('META_00125') ?>",sortable:true,width:215}
        ]],
        columns:[[
            {field:'META_TYPE_NAME',title:"<?php echo $this->lang->line('META_00145') ?>",sortable:true,width:105},
            {field:'META_DATA_ID',title:"ID",sortable:true,width:120}, 
            <?php if (isset($this->isComplexProcess)) { ?>
            {field:'IS_COMPLEX_PROCESS',title:'Нийлмэл',sortable:true,width:60,align:'center',formatter: gridBooleanField}, 
            <?php } ?>    
            {field:'CREATED_PERSON_NAME',title:"<?php echo $this->lang->line('META_00063'); ?>",sortable:true,width:110}, 
            {field:'CREATED_DATE',title:"<?php echo $this->lang->line('META_00140') ?>",sortable:true,width:124}
        ]],
        onDblClickRow:function(index, row){
            dblClickCommonMetaDataGrid(index, row);
        },
        onRowContextMenu:function(e, index, row){
            e.preventDefault();
            $(this).datagrid('selectRow', index);
            $.contextMenu({
                selector: "#common-metadata-tab-order .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, #common-metadata-tab-order .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                callback: function(key, opt) {
                    if (key === 'basket') {
                        basketCommonMetaDataGrid();
                    }
                },
                items: {
                    "basket": {name: "<?php echo $this->lang->line('META_00042'); ?>", icon: "plus-circle"}
                }
            });
        },
        onLoadSuccess:function(){
            showGridMessage($(this));
        }
    });
    $('#commonMetaDataGrid').datagrid('enableFilter');
    $('#commonBasketMetaDataGrid').datagrid({
        url:'',
        rownumbers:true,
        singleSelect:true,
        pagination:false,
        remoteSort:false,
        width:897,
        height:380,
        fitColumn:true,
        showFooter:false,
        frozenColumns:[[
            {field:'action', title:'', sortable:false, width:40, align:'center'}, 
            {field:'META_DATA_CODE',title:"<?php echo $this->lang->line('META_00075') ?>",sortable:true,width:185},
            {field:'META_DATA_NAME',title:"<?php echo $this->lang->line('META_00125') ?>",sortable:true,width:200}
        ]],
        columns:[[
            {field:'META_TYPE_NAME',title:"<?php echo $this->lang->line('META_00145') ?>",sortable:true,width:105},
            {field:'META_DATA_ID',title:"ID",sortable:true,width:120}, 
            <?php if (isset($this->isComplexProcess)) { ?>
            {field:'IS_COMPLEX_PROCESS',title:'Нийлмэл',sortable:true,width:60,align:'center',formatter: gridBooleanField}, 
            <?php } ?>  
            {field:'CREATED_PERSON_NAME',title:"<?php echo $this->lang->line('META_00063') ?>",sortable:true,width:110}, 
            {field:'CREATED_DATE',title:"<?php echo $this->lang->line('META_00140') ?>",sortable:true,width:124}
        ]]
    });
    $('#commonBasketMetaDataGrid').datagrid('loadData', <?php echo isset($this->selectedRows) ? json_encode($this->selectedRows) : '[]'; ?>);
    $("a[href=#common-metadata-tab-basket]").on("shown.bs.tab", function(){      
        $('#commonBasketMetaDataGrid').datagrid('resize');
        $('#commonBasketMetaDataGrid').datagrid('fixRowHeight');
    });
    
    setCountCommonMetaDataBasket();
});

function commonMetaDataGridSearch(){
    $('#commonMetaDataGrid').datagrid('load', {
        searchData: $("#common-metadata-search-form").serialize(),
        isExternalServer: '<?php echo $this->isExternalServer; ?>',
        <?php echo $this->defaultCriteria; ?>
    });
}
function commonMetaDataGridReset(){
    $("#common-metadata-search-form").find("input[type=text], select").val("");
    $("#common-metadata-search-form").find("select.select2").select2("val", "");
    $('#commonMetaDataGrid').datagrid('load',{});
}
    
function commonMetaDataFolderFilter(folderId){
    $('a[href="#common-metadata-tab-order"]').tab('show');
    $("#common-metadata-search-form").find("input[type=text], select").val("");
    $("#common-metadata-search-form").find("select.select2").select2("val", "");
    $('#commonMetaDataGrid').datagrid('load', {
        folderId: folderId,
        isExternalServer: '<?php echo $this->isExternalServer; ?>',
        <?php echo $this->defaultCriteria; ?>
    });
    $("#common-metadata-search-form #folderId").val(folderId);
}
function basketCommonMetaDataGrid(){
    var rows = $('#commonMetaDataGrid').datagrid('getSelections');
    <?php
    if ($this->chooseType == 'single') {
        echo 'if (rows.length > 0) {';
        echo "$('#commonBasketMetaDataGrid').datagrid('loadData', []);";
        echo '}';
    }
    ?>
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var isAddRow = true;
        var subrows = $('#commonBasketMetaDataGrid').datagrid('getRows');
        for (var j = 0; j < subrows.length; j++) {
            var subrow = subrows[j];
            if (subrow.META_DATA_ID === row.META_DATA_ID) {
                isAddRow = false;
            }
        }
        if (isAddRow) {
            $('#commonBasketMetaDataGrid').datagrid('appendRow', {
                META_DATA_ID: row.META_DATA_ID,
                META_DATA_CODE: row.META_DATA_CODE,
                META_DATA_NAME: row.META_DATA_NAME,
                META_TYPE_ID: row.META_TYPE_ID,
                META_TYPE_NAME: row.META_TYPE_NAME,
                META_TYPE_CODE: row.META_TYPE_CODE,
                META_ICON_NAME: row.META_ICON_NAME,
                GROUP_TYPE: row.GROUP_TYPE,
                MODEL_ID: row.MODEL_ID,
                CREATED_DATE: row.CREATED_DATE,
                CREATED_PERSON_NAME: row.CREATED_PERSON_NAME, 
                <?php if (isset($this->isComplexProcess)) { ?>
                IS_COMPLEX_PROCESS: row.IS_COMPLEX_PROCESS, 
                <?php } ?> 
                action: '<a href="javascript:;" onclick="deleteCommonMetaDataBasket(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>'
            });
        }
    }
    setCountCommonMetaDataBasket();
}
function dblClickCommonMetaDataGrid(index, row){``
    <?php
    if ($this->chooseType == 'single') {
        echo "$('#commonBasketMetaDataGrid').datagrid('loadData', []);";
    }
    ?>
    var isAddRow = true;
    var rows = $('#commonBasketMetaDataGrid').datagrid('getRows');
    for (var j = 0; j < rows.length; j++) {
        var subrow = rows[j];
        if (subrow.META_DATA_ID === row.META_DATA_ID) {
            isAddRow = false;
        }
    }
    if (isAddRow) {
        $('#commonBasketMetaDataGrid').datagrid('appendRow', {
            META_DATA_ID: row.META_DATA_ID,
            META_DATA_CODE: row.META_DATA_CODE,
            META_DATA_NAME: row.META_DATA_NAME,
            META_TYPE_ID: row.META_TYPE_ID,
            META_TYPE_CODE: row.META_TYPE_CODE,
            META_TYPE_NAME: row.META_TYPE_NAME,
            META_ICON_NAME: row.META_ICON_NAME,
            GROUP_TYPE: row.GROUP_TYPE,
            MODEL_ID: row.MODEL_ID,
            CREATED_DATE: row.CREATED_DATE, 
            CREATED_PERSON_NAME: row.CREATED_PERSON_NAME, 
            <?php if (isset($this->isComplexProcess)) { ?>
            IS_COMPLEX_PROCESS: row.IS_COMPLEX_PROCESS, 
            <?php } ?> 
            action: '<a href="javascript:;" onclick="deleteCommonMetaDataBasket(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>'
        });
    }
    setCountCommonMetaDataBasket();
    
    <?php
    if ($this->chooseType == 'single') { ?>
        if($('#dialog-commonmetadata').length)
            $('#dialog-commonmetadata').closest("div.ui-dialog").children("div.ui-dialog-buttonpane").find("button.datagrid-common-choose-btn").click();
    <?php
    }
    ?>        
}
function deleteCommonMetaDataBasket(target){
    $('#commonBasketMetaDataGrid').datagrid('deleteRow', getRowIndex(target));
    setCountCommonMetaDataBasket();
}
function setCountCommonMetaDataBasket() {
    $("#commonMetaSelectedCount").text($('#commonBasketMetaDataGrid').datagrid('getData').total);
}
</script>