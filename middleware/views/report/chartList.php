<div class="col-md-12" id="whPriceKey">
    <div class="card light shadow">	
        <div class="card-header card-header-no-padding header-elements-inline">
            <div class="caption font-weight-bold"><i class="fa fa-list"></i> <?php echo $this->title; ?></div>
            <div class="tools">
                <a href="javascript:;" class="collapse"></a>
                <a href="javascript:;" class="fullscreen"></a>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12 jeasyuiTheme3" style="min-height: 591px;">
                    <div class="table-toolbar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="btn-group">
                                    <?php
                                    echo Html::anchor(
                                            'javascript:;', '<i class="fa fa-edit"></i> ' . $this->lang->line('edit_btn'), array(
                                        'class' => 'btn blue btn-sm',
                                        'onclick' => 'rmChartEdit(this);'
                                            ), $this->isEdit
                                    );
                                    echo Html::anchor(
                                            'javascript:;', '<i class="fa fa-trash"></i> ' . $this->lang->line('delete_btn'), array(
                                        'class' => 'btn red btn-sm',
                                        'onclick' => 'rmChartDelete(this);'
                                            ), $this->isDelete
                                    );
                                    ?>  
                                </div>
                            </div>
                        </div>
                    </div>
                    <table id="reportDataGrid"></table>
                </div>
            </div>   
        </div>
    </div>   
</div>

<style type="text/css">
    .jstree {
        overflow-x: auto; 
        overflow-y: hidden;
    }
    #priceKeySearchForm .form-group {
        margin-bottom: 5px !important;
    }
    #priceKeySearchForm label {
        font-size: 12px !important;
    }
    #priceKeySearchForm .form-actions {
        margin-top: 20px !important;
    }
</style>

<script type="text/javascript">
    $(function () {
        $('#reportDataGrid').datagrid({
            url: 'Rmreport/chartDataGrid',
            rownumbers: true,
            singleSelect: true,
            pagination: true,
            pageSize: 20,
            remoteFilter: true,
            filterDelay: 10000000000,
            fit: true,
            fitColumn: true,
            striped: true,
            nowrap: false,
            pagePosition: 'both',
            frozenColumns: [[
                    {field: 'CHART_NAME', title: 'Чартны нэр', halign: 'center', sortable: true}
                ]],
            columns: [[
                    {field: 'REPORT_MODEL_NAME', title: 'Ашигласан тайлан', halign: 'center', sortable: true},
                    {field: 'DATA_MART_NAME', title: 'Ашигласан дата март', halign: 'center', sortable: true}
                ]],
            onRowContextMenu: function (e, index, row) {
                e.preventDefault();
                $(this).datagrid('selectRow', index);
            },
            onLoadSuccess: function () {
                Core.initInputType();
            }
        });
    });


    function rmChartEdit(elem) {
        var row = $('#reportDataGrid').datagrid('getSelected');
        if (row) {
            if (row.CHART_ID) {
                console.log(row.CHART_ID);
                location.href = "rmreport/chart/?id="+row.CHART_ID;
            }
        } else {
            alert("Та жагсаалтаас сонгоно уу!");
        }
    }

    function rmChartDelete(elem) {
        var row = $('#reportDataGrid').datagrid('getSelected');
        if (row) {
            if (row.CHART_ID) {

            }
        } else {
            alert("Та жагсаалтаас сонгоно уу!");
        }
    }


</script>