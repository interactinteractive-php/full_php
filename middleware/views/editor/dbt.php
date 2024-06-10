<div class="col-md-12">
    <div class="bg-white">	
        <div class="card-header card-header-no-padding bg-white header-elements-inline">
            <div class="card-title"><?php echo $this->title; ?></div>
        </div>
        <div class="card-body form">
            <div class="row mt-2">
                <div class="col-md-12">
                    <a href="javascript:;" class="btn btn-sm green-meadow mr6" onclick="dbtRun(this);"><i class="far fa-play"></i> Ажиллуулах</a>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <textarea id="dbt_editor" class="form-control ace-textarea" spellcheck="false" style="width: 100%;"></textarea>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12 jeasyuiTheme3">
                    <table id="dbt-datagrid"></table>
                </div>
            </div>   
        </div>
    </div>   
</div> 
<style type="text/css">
    .CodeMirror .cm-error {
        background-color: transparent !important;
        color: #82b1ff !important;
    }
</style>

<script type="text/javascript">
var dbt_editor = CodeMirror.fromTextArea(document.getElementById('dbt_editor'), {
    mode: 'text/x-plsql',
    styleActiveLine: true,
    lineNumbers: true,
    lineWrapping: true,
    matchBrackets: true,
    autoCloseBrackets: true,
    autofocus: true, 
    indentUnit: 2,
    tabSize: 2, 
    theme: 'material', 
    extraKeys: {
        "Alt-F": "findPersistent", 
        "F11": function(cm) {
            cm.setOption("fullScreen", !cm.getOption("fullScreen"));
        },
        "Esc": function(cm) {
            if (cm.getOption("fullScreen")) {
                cm.setOption("fullScreen", false);
            } 
        }
    }
});    
var $dbtDatagrid = $('#dbt-datagrid');

$(function() {
    
    $dbtDatagrid.datagrid({
        url: 'mdeditor/getDbtDataGrid',
        rownumbers: true,
        singleSelect: true,
        checkOnSelect: true,
        selectOnCheck: true, 
        pagination: true,
        pageSize: 50,
        height: 400,
        striped: false,
        remoteFilter: true,
        filterDelay: 10000000000,
        fitColumns: false,
        columns: [[
            {field: 'ck', checkbox:true}
        ]],
        onLoadSuccess: function(){
            showGridMessage(configMainDataGrid);
        }
    });
});

function dbtRun(elem) {
    PNotify.removeAll();
    
    dbt_editor.save();
    sqlQuerySql = (dbt_editor.getValue()).trim();
    
    if (sqlQuerySql !== null && sqlQuerySql !== '') {
        
        var dbs = encodeURIComponent(window.btoa(sqlQuerySql));
        
        $.ajax({
            type: 'post',
            url: 'mdeditor/getDbtColumns',
            data: {dbs: dbs}, 
            dataType: 'json',
            beforeSend: function () {
                Core.blockUI({message: 'Loading...', boxed: true});
            },
            success: function (data) {
                if (data.status == 'success') {
                    
                    var setColumns = [], getColumns = data.columns;
                    
                    setColumns.push({field: 'ck', checkbox:true});
                    
                    for (var c in getColumns) {
                        setColumns.push({field: c, title: c,width: 130});
                    }
                    
                    $dbtDatagrid.datagrid({
                        queryParams: {dbs: dbs}, 
                        columns: [setColumns]
                    });
                    
                } else {
                    new PNotify({
                        title: data.status,
                        text: data.message,
                        type: data.status,
                        sticker: false, 
                        addclass: 'pnotify-center'
                    });
                }
                
                Core.unblockUI();
            }
        });
    }
}
</script>