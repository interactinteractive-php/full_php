<div class="row">
    <div class="col-md-3">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#common-folder-tab-filder" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00193'); ?></a>
                </li>
                <li class="nav-item">
                    <a href="#common-folder-tab-folder" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('META_00041'); ?></a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active in" id="common-folder-tab-filder">
                    <form role="form" id="common-metadata-search-form" method="post">
                        <div class="form-body col">
                            <?php echo $this->searchForm; ?> 
                        </div>    
                        <div class="form-actions">
                            <?php echo Form::button(array('class' => 'btn blue btn-sm', 'onclick' => 'commonFolderGridSearch();', 'value' => '<i class="fa fa-search"></i> ' . $this->lang->line('search_btn'))); ?>
                            <?php echo Form::button(array('class' => 'btn grey-cascade btn-sm', 'onclick' => 'commonFolderGridReset();', 'value' => $this->lang->line('clear_btn'))); ?>
                        </div>
                    </form>    
                </div>
                <div class="tab-pane in" id="common-folder-tab-folder">
                    <div id="common-metadata-folder-view" class="tree-demo">
                    </div>
                </div>
            </div>
        </div>    
    </div>
    <div class="col-md-9">
        <div class="tabbable-line">
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a href="#common-folder-tab-order" class="nav-link active" data-toggle="tab"><?php echo $this->lang->line('META_00062'); ?></a>
                </li>
                <li class="nav-item">
                    <a href="#common-folder-tab-basket" data-toggle="tab" class="nav-link"><?php echo $this->lang->line('basket'); ?> (<span id="commonMetaSelectedCount">0</span>)</a>
                </li>
            </ul>
            <div class="tab-content">
                <div class="tab-pane active in" id="common-folder-tab-order">
                    <table id="commonFolderGrid" style="width:100%;height:380px"></table>
                </div>
                <div class="tab-pane in" id="common-folder-tab-basket">
                    <table id="commonBasketFolderGrid"></table>
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
#common-folder-tab-order *::-moz-selection, #common-folder-tab-basket *::-moz-selection { background:transparent; }
#common-folder-tab-order *::selection, #common-folder-tab-basket *::selection { background:transparent; }
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
                    return { 'parent' : node.id };
                }
            }
        },
        "types" : {
            "default" : {
                "icon" : "fa fa-folder text-orange-400 fa-lg"
            }
        },
        "plugins": ["types", "cookies"]
    }).bind("select_node.jstree", function (e, data){
        commonFolderFolderFilter(data.node.id);
    });
    
    $("#common-metadata-search-form").on("keydown", 'input', function(e){
        if (e.which === 13) {
            commonFolderGridSearch();
        }
    });
    
    $('#commonFolderGrid').datagrid({
        url:'mdfolder/commonFolderGrid',
        <?php echo $this->searchParams; ?>
        rownumbers:true,
        singleSelect:<?php echo $this->singleSelect; ?>,
        ctrlSelect:true,
        pagination:true,
        pageSize:30,
        fitColumns:true,
        nowrap:false,
        frozenColumns:[[
            {field:'ck', checkbox:true}, 
            {field:'FOLDER_CODE',title:"<?php echo $this->lang->line('META_00075'); ?>",sortable:true,width:180},
            {field:'FOLDER_NAME',title:"<?php echo $this->lang->line('META_00125');?>", sortable:true,width:300}
        ]],
        columns:[[
            {field:'CREATED_DATE',title:"<?php echo $this->lang->line('META_00140'); ?>", sortable:true,width:124}, 
            {field:'CREATED_PERSON_NAME',title:"<?php echo $this->lang->line('META_00063'); ?>",sortable:true,width:110}
        ]],
        onDblClickRow:function(index, row){
            dblClickCommonFolderGrid(index, row);
        },
        onRowContextMenu:function(e, index, row){
            e.preventDefault();
            $(this).datagrid('selectRow', index);
            $.contextMenu({
                selector: "#common-folder-tab-order .datagrid .datagrid-view .datagrid-view1 .datagrid-body .datagrid-row, #common-folder-tab-order .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                callback: function(key, opt) {
                    if (key === 'basket') {
                        basketCommonFolderGrid();
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
    $('#commonBasketFolderGrid').datagrid({
        url:'',
        rownumbers:true,
        singleSelect:true,
        pagination:false,
        remoteSort:false,
        width:795,
        height:380,
        fitColumns:true,
        showFooter:false,
        frozenColumns:[[
            {field:'action', title:'', sortable:false, width:40, align:'center'}
        ]],
        columns:[[
            {field:'FOLDER_CODE',title:"<?php echo $this->lang->line('META_00075'); ?>",sortable:true,width:180},
            {field:'FOLDER_NAME',title:"<?php echo $this->lang->line('META_00125'); ?>",sortable:true,width:300},
            {field:'CREATED_DATE',title:"<?php echo $this->lang->line('META_00140'); ?>",sortable:true,width:124},
            {field:'CREATED_PERSON_NAME',title:"<?php echo $this->lang->line('META_00063'); ?>",sortable:true,width:110}
        ]]
    });
    $('#commonBasketFolderGrid').datagrid('loadData', []);
    $("a[href=#common-folder-tab-basket]").on("click", function(){      
        $('#commonBasketFolderGrid').datagrid('resize');
    });
});

function commonFolderGridSearch(){
    $('#commonFolderGrid').datagrid('load', {
        searchData: $("#common-metadata-search-form").serialize(),
        <?php echo $this->defaultCriteria; ?>
    });
}
function commonFolderGridReset(){
    $("#common-metadata-search-form").find("input[type=text], select").val("");
    $("#common-metadata-search-form").find("select.select2").select2("val", "");
    $('#commonFolderGrid').datagrid('load',{});
}
    
function commonFolderFolderFilter(folderId){
    $('a[href="#common-folder-tab-order"]').tab('show');
    $("#common-metadata-search-form").find("input[type=text], select").val("");
    $("#common-metadata-search-form").find("select.select2").select2("val", "");
    $('#commonFolderGrid').datagrid('load', {
        folderId: folderId,
        <?php echo $this->defaultCriteria; ?>
    });
    $("#common-metadata-search-form #folderId").val(folderId);
}
function basketCommonFolderGrid(){
    var rows = $('#commonFolderGrid').datagrid('getSelections');
    <?php
    if ($this->chooseType == 'single') {
        echo 'if (rows.length > 0) {';
        echo "$('#commonBasketFolderGrid').datagrid('loadData', []);";
        echo '}';
    }
    ?>
    for (var i = 0; i < rows.length; i++) {
        var row = rows[i];
        var isAddRow = true;
        var subrows = $('#commonBasketFolderGrid').datagrid('getRows');
        for (var j = 0; j < subrows.length; j++) {
            var subrow = subrows[j];
            if (subrow.META_DATA_ID === row.META_DATA_ID) {
                isAddRow = false;
            }
        }
        if (isAddRow) {
            $('#commonBasketFolderGrid').datagrid('appendRow', {
                FOLDER_ID: row.FOLDER_ID,
                FOLDER_CODE: row.FOLDER_CODE,
                FOLDER_NAME: row.FOLDER_NAME,
                CREATED_DATE: row.CREATED_DATE, 
                CREATED_PERSON_NAME: row.CREATED_PERSON_NAME, 
                action: '<a href="javascript:;" onclick="deleteCommonMetaDataBasket(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>'
            });
        }
    }
    $("body").find("#commonMetaSelectedCount").text($('#commonBasketFolderGrid').datagrid('getData').total);
}
function dblClickCommonFolderGrid(index, row){
    <?php
    if ($this->chooseType == 'single') {
        echo "$('#commonBasketFolderGrid').datagrid('loadData', []);";
    }
    ?>
    var isAddRow = true;
    var rows = $('#commonBasketFolderGrid').datagrid('getRows');
    for (var j = 0; j < rows.length; j++) {
        var subrow = rows[j];
        if (subrow.FOLDER_ID === row.FOLDER_ID) {
            isAddRow = false;
        }
    }
    if (isAddRow) {
        $('#commonBasketFolderGrid').datagrid('appendRow', {
            FOLDER_ID: row.FOLDER_ID,
            FOLDER_CODE: row.FOLDER_CODE,
            FOLDER_NAME: row.FOLDER_NAME,
            CREATED_DATE: row.CREATED_DATE, 
            CREATED_PERSON_NAME: row.CREATED_PERSON_NAME, 
            action: '<a href="javascript:;" onclick="deleteCommonMetaDataBasket(this);" class="btn btn-xs red" title="<?php echo $this->lang->line('META_00002'); ?>"><i class="fa fa-trash"></i></a>'
        });
    }
    $("body").find("#commonMetaSelectedCount").text($('#commonBasketFolderGrid').datagrid('getData').total);
}
function deleteCommonMetaDataBasket(target){
    $('#commonBasketFolderGrid').datagrid('deleteRow', getRowIndex(target));
    $("body").find("#commonMetaSelectedCount").text($('#commonBasketFolderGrid').datagrid('getData').total);
}
</script>