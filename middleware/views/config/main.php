<div class="col-md-12" id="md-config">
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
            <div class="tabbable-line tab-not-padding-top">
                <ul class="nav nav-tabs">
                    <li class="nav-item">
                        <a href="#config_tab_1" class="nav-link active" data-toggle="tab">Тохиргооны түлхүүр</a>
                    </li>
                    <li class="nav-item">
                        <a href="#config_tab_2" data-toggle="tab" class="nav-link">Тохируулсан утгууд</a>
                    </li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="config_tab_1">
                        <div class="row">
                            <div class="col-md-12 jeasyuiTheme3">
                                <table id="configMainDataGrid"></table>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="config_tab_2"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
var mdConfigWindowId = "#md-config";

$(function() {
    var configMainDataGrid = $('#configMainDataGrid', mdConfigWindowId);
    var gridHeight = elemHeight(mdConfigWindowId, 120, 0);

    configMainDataGrid.datagrid({
        url: 'mdconfig/configMainDataGrid',
        rownumbers: true,
        singleSelect: true,
        checkOnSelect: true,
        selectOnCheck: true, 
        pagination: true,
        pageSize: 20,
        height: gridHeight,
        striped: false,
        remoteFilter: true,
        filterDelay: 10000000000,
        fitColumns: true,
        columns: [[
            {field: 'ck', checkbox:true},    
            {field: 'CODE', title: 'Түлхүүр', align: 'left', sortable: true, fit: true},
            {field: 'DESCRIPTION', title: 'Тайлбар', align: 'left', sortable: true, fit: true},
            {field: 'ACTION', title: '', align: 'center', sortable: false, formatter: configValueRedirectLink,fit: true}
        ]],
        onBeforeSelect: function(index, row) {
            var getSelected = configMainDataGrid.datagrid('getSelected');
            var selectedIndex = configMainDataGrid.datagrid('getRowIndex', getSelected);
            
            if (selectedIndex !== index) {
                var rows = configMainDataGrid.datagrid('getRows');
                var rowsLength = rows.length;
                var rowR = [];

                for (var i = 0; i < rowsLength; i++) {
                     
                    rowR = rows[i];
                    delete rowR.isSelected;

                    configMainDataGrid.datagrid('updateRow', {
                        index: i,
                        row: rowR
                    });
                }
            }
            
            if (!row.hasOwnProperty('isSelected')) {
                configMainDataGrid.datagrid('updateRow', {
                    index: index,
                    row: {isSelected: 1}
                });
            } 
        },
        onSelect: function(index, row) {
            
            if (row.hasOwnProperty('isSelected') && row.isSelected == 1) {
                configMainDataGrid.datagrid('updateRow', {
                    index: index,
                    row: {isSelected: 0}
                });
            } else {
                configMainDataGrid.datagrid('unselectRow', index);
                configMainDataGrid.datagrid('uncheckRow', index);

                configMainDataGrid.datagrid('updateRow', {
                    index: index,
                    row: {isSelected: 1}
                });

                setTimeout(function(){
                    var $checkbox = configMainDataGrid.datagrid('getPanel').find('div.datagrid-view > .datagrid-view2 > .datagrid-body').find('tr[datagrid-row-index="'+index+'"] > td[field="ck"]');
                    if ($checkbox.length) {
                        $checkbox.find('input[type="checkbox"]').prop('checked', false);
                    }
                }, 20);
            }
        },
        onRowContextMenu:function(e, index, row){
            e.preventDefault();
            configMainDataGrid.datagrid('selectRow', index);
            $.contextMenu({
                selector: "#config_tab_1 .datagrid .datagrid-view .datagrid-view2 .datagrid-body .datagrid-row",
                callback: function(key, opt) {
                    if (key === 'setvalue') {
                        setConfigValueLoad(row.ID);
                    }
                },
                items: {
                    "setvalue": {name: "Тохируулсан утгууд", icon: "cogs"}
                }
            });
        },
        onLoadSuccess: function(){
            showGridMessage(configMainDataGrid);
        }
    });
    
    configMainDataGrid.datagrid('enableFilter', [{field: 'ACTION', type: 'label'}]);
    
    //$('a[data-toggle="tab"]').on('shown.bs.tab', function(e){});
    $("ul.nav-tabs a[data-toggle=tab]", mdConfigWindowId).on("click", function(){
        var _this = $(this);
        var _href = _this.attr("href");
        if (_href === '#config_tab_1') {
            //configMainDataGrid.datagrid('resize'); 
        }
        if (_href === '#config_tab_2') {
            var configValueLength = $.trim($("#config_tab_2", mdConfigWindowId).html()).length;
            if (configValueLength === 0) {
                configValueGridRender('');
            }
        }
    });
    
    $(window).bind('resize', function(){
        configMainDataGrid.datagrid('resize'); 
    });
});

function configValueRedirectLink(v, r, i){
    return '<a href="javascript:;" class="btn btn-xs green-meadow" onclick="setConfigValueLoad(\''+r.ID+'\');"><i class="fa fa-cogs"></i></a>';
}
function setConfigValueLoad(id){
    $('ul.nav-tabs a[href="#config_tab_2"]', mdConfigWindowId).tab('show');
    configValueGridRender('configId='+id);
}
function configValueGridRender(params){
    $.ajax({
        type: 'post',
        url: 'mdconfig/configValueGridRender',
        data: {params: params},
        beforeSend: function(){
            Core.blockUI({
                animate: true
            });
        },
        success: function(data){
            $('#config_tab_2', mdConfigWindowId).empty().append(data);
            Core.unblockUI();
        }
    }).done(function(){
        Core.initAjax($('#config_tab_2', mdConfigWindowId));
    });
}
</script>